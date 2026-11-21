"""Cloud TTS for Aphelia Lite (no local Qwen GPU).

Outputs the same artifacts as voice.py narrate:
  assets/vo.mp3, vo.wav, vo-words.json, vo-duration.txt, voice-report.json

Usage:
  python scripts/voice_cloud.py narrate --project aphelia-memory/runs/<slug>
  python scripts/voice_cloud.py narrate --project ... --engine yandex
"""

from __future__ import annotations

import argparse
import json
import os
import urllib.error
import urllib.parse
import urllib.request
from pathlib import Path

from common import PLUGIN_ROOT, say, write_json

# Reuse post-processing + Whisper align from voice.py (no Qwen import at module load).
from voice import align, split_sentences, tighten


def load_env() -> dict[str, str]:
    paths = [
        Path(os.environ.get("APHELIA_ENV", "")),
        PLUGIN_ROOT / "shared" / "aphelia.env.local",
        Path.cwd() / "aphelia-memory" / "aphelia.env.local",
    ]
    out: dict[str, str] = {}
    for p in paths:
        if not p or not p.exists():
            continue
        for line in p.read_text(encoding="utf-8").splitlines():
            line = line.strip()
            if not line or line.startswith("#") or "=" not in line:
                continue
            k, v = line.split("=", 1)
            out[k.strip()] = v.strip()
    for k, v in os.environ.items():
        if k.startswith(("APHELIA_", "YANDEX_", "ELEVENLABS_")):
            out[k] = v
    return out


def yandex_tts(text: str, env: dict[str, str], out_mp3: Path) -> None:
    key = env.get("YANDEX_API_KEY", "")
    if not key:
        raise SystemExit("YANDEX_API_KEY missing — set in aphelia-memory/aphelia.env.local")
    data = urllib.parse.urlencode(
        {
            "text": text,
            "lang": "ru-RU",
            "voice": env.get("YANDEX_VOICE", "filipp"),
            "format": "mp3",
            "speed": env.get("YANDEX_SPEED", "1.08"),
            "folderId": env.get("YANDEX_FOLDER_ID", ""),
        }
    ).encode("utf-8")
    req = urllib.request.Request(
        "https://tts.api.cloud.yandex.net/speech/v1/tts:synthesize",
        data=data,
        headers={"Authorization": f"Api-Key {key}"},
        method="POST",
    )
    try:
        with urllib.request.urlopen(req, timeout=120) as resp:
            out_mp3.write_bytes(resp.read())
    except urllib.error.HTTPError as e:
        body = e.read().decode("utf-8", errors="replace")
        raise SystemExit(f"Yandex TTS HTTP {e.code}: {body}") from e


def elevenlabs_tts(text: str, env: dict[str, str], out_mp3: Path) -> None:
    key = env.get("ELEVENLABS_API_KEY", "")
    voice_id = env.get("ELEVENLABS_VOICE_ID", "")
    if not key or not voice_id:
        raise SystemExit("ELEVENLABS_API_KEY and ELEVENLABS_VOICE_ID required")
    model = env.get("ELEVENLABS_MODEL_ID", "eleven_multilingual_v2")
    payload = json.dumps(
        {
            "text": text,
            "model_id": model,
            "voice_settings": {"stability": 0.45, "similarity_boost": 0.8, "style": 0.35, "use_speaker_boost": True},
        }
    ).encode("utf-8")
    url = f"https://api.elevenlabs.io/v1/text-to-speech/{voice_id}"
    req = urllib.request.Request(
        url,
        data=payload,
        headers={"xi-api-key": key, "Content-Type": "application/json", "Accept": "audio/mpeg"},
        method="POST",
    )
    try:
        with urllib.request.urlopen(req, timeout=180) as resp:
            out_mp3.write_bytes(resp.read())
    except urllib.error.HTTPError as e:
        body = e.read().decode("utf-8", errors="replace")
        raise SystemExit(f"ElevenLabs HTTP {e.code}: {body}") from e


def narrate(project: Path, engine: str, tempo: float, script_name: str) -> None:
    env = load_env()
    engine = engine or env.get("APHELIA_TTS_ENGINE", "yandex")
    assets = project / "assets"
    assets.mkdir(parents=True, exist_ok=True)
    text = (project / script_name).read_text(encoding="utf-8").strip()
    sentences = split_sentences(text)
    raw_mp3 = assets / "vo-api.mp3"

    if engine == "yandex":
        yandex_tts(text, env, raw_mp3)
    elif engine == "elevenlabs":
        elevenlabs_tts(text, env, raw_mp3)
    else:
        raise SystemExit(f"Unknown engine '{engine}'. Use yandex or elevenlabs.")

    # vo-api.mp3 → wav/mp3 with Aphelia loudnorm/tempo chain
    import shutil
    import subprocess

    raw_wav = assets / "vo-raw.wav"
    subprocess.run(
        ["ffmpeg", "-y", "-hide_banner", "-loglevel", "error", "-i", str(raw_mp3), "-ar", "48000", str(raw_wav)],
        check=True,
    )
    duration = tighten(raw_wav, assets / "vo.wav", assets / "vo.mp3", tempo)
    words = align(assets / "vo.wav", assets / "vo-words.json")
    (assets / "vo-duration.txt").write_text(f"{duration:.3f}", encoding="ascii")

    report = {
        "engine": engine,
        "sentences": len(sentences),
        "duration": round(duration, 2),
        "tempo": tempo,
        "words": len(words["words"]),
        "model": f"cloud:{engine}",
    }
    write_json(project / "voice-report.json", report)
    say({"duration": report["duration"], "words": report["words"], "sentences": report["sentences"], "engine": engine, "report": str(project / "voice-report.json")})


def main() -> None:
    ap = argparse.ArgumentParser(description=__doc__, formatter_class=argparse.RawDescriptionHelpFormatter)
    sub = ap.add_subparsers(dest="cmd", required=True)
    n = sub.add_parser("narrate", help="script.txt -> vo.mp3 via cloud TTS + Whisper align")
    n.add_argument("--project", required=True)
    n.add_argument("--script", default="script.txt")
    n.add_argument("--engine", choices=["yandex", "elevenlabs"], default="")
    n.add_argument("--tempo", type=float, default=1.06)
    args = ap.parse_args()
    if args.cmd == "narrate":
        narrate(Path(args.project), args.engine, args.tempo, args.script)


if __name__ == "__main__":
    main()
