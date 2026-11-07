#!/usr/bin/env python3
"""Download user-selected Pexels stills into theme assets (hub + scenarios + hero)."""
from __future__ import annotations

import json
import os
import subprocess
import urllib.request

HERE = os.path.dirname(os.path.abspath(__file__))
IMG_DIR = os.path.join(HERE, "..", "wp-theme", "zhiraf-ufa", "assets", "images")
MANIFEST = os.path.join(HERE, "user-v3-applied-picks.json")
UA = "ZhirafUfaTheme/1.0 (+pexels license; contact: zhiraf-ufa)"


def pexels_url(photo_id: int, width: int = 2000) -> str:
    return (
        f"https://images.pexels.com/photos/{photo_id}/pexels-photo-{photo_id}.jpeg"
        f"?auto=compress&cs=tinysrgb&w={width}"
    )


def download(photo_id: int, dest: str, width: int = 2000) -> None:
    os.makedirs(os.path.dirname(dest), exist_ok=True)
    req = urllib.request.Request(pexels_url(photo_id, width), headers={"User-Agent": UA})
    with urllib.request.urlopen(req, timeout=120) as resp:
        data = resp.read()
    with open(dest, "wb") as f:
        f.write(data)
    print("saved", dest, len(data) // 1024, "KB")


def maybe_webp(jpg: str) -> None:
    webp = os.path.splitext(jpg)[0] + ".webp"
    if subprocess.call(["which", "cwebp"], stdout=subprocess.DEVNULL) != 0:
        return
    subprocess.check_call(["cwebp", "-q", "82", jpg, "-o", webp], stdout=subprocess.DEVNULL)
    at2x = jpg.replace(".jpg", "@2x.jpg")
    if os.path.isfile(at2x):
        subprocess.check_call(
            ["cwebp", "-q", "82", at2x, "-o", webp.replace(".webp", "@2x.webp")],
            stdout=subprocess.DEVNULL,
        )


def hero_derivatives(basename: str) -> None:
    jpg = os.path.join(IMG_DIR, f"{basename}.jpg")
    if not os.path.isfile(jpg):
        return
    at2x = os.path.join(IMG_DIR, f"{basename}@2x.jpg")
    with open(jpg, "rb") as src, open(at2x, "wb") as dst:
        dst.write(src.read())
    maybe_webp(jpg)


def main() -> None:
    picks = json.load(open(MANIFEST, encoding="utf-8"))
    for row in picks["files"]:
        dest = os.path.join(IMG_DIR, row["file"])
        download(int(row["pexels_id"]), dest, int(row.get("width") or 2000))
        if row.get("hero"):
            hero_derivatives(row["file"].removesuffix(".jpg"))

    out = os.path.join(HERE, "user-v3-applied-picks.done.json")
    json.dump({"ok": True, "count": len(picks["files"])}, open(out, "w", encoding="utf-8"), indent=2)
    print("done", len(picks["files"]), "files")


if __name__ == "__main__":
    main()
