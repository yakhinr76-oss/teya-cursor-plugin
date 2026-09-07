# Aphelia на GTX 1660 Super 6 GB — Lite установка

## 1. Требования

- Windows 10/11
- **Node.js 20+** — https://nodejs.org/
- **Python 3.12+** — https://python.org (галочка «Add to PATH»)
- **ffmpeg** — `winget install Gyan.FFmpeg` или https://ffmpeg.org
- **Git** — https://git-scm.com
- API-ключ **Яндекс SpeechKit** или **ElevenLabs**

## 2. Установка плагина

```powershell
git clone https://github.com/Horosheff/aphelia
cd aphelia
git checkout cursor/install-lite-7675   # ветка с Lite-установщиком (пока не в main)
.\install-plugin-lite.ps1
```

Полная установка (12+ GB VRAM): `.\install-plugin.ps1`

## 3. Секреты TTS

```powershell
# создаётся автоматически при install-plugin-lite.ps1
notepad aphelia-memory\aphelia.env.local
```

Минимум для Яндекса:

```env
APHELIA_TTS_ENGINE=yandex
YANDEX_API_KEY=AQVN...
YANDEX_FOLDER_ID=b1g...
YANDEX_VOICE=filipp
YANDEX_SPEED=1.08
APHELIA_SKIP_ACE_STEP=1
```

Ключи: https://console.cloud.yandex.ru/ → SpeechKit → API-ключ + Folder ID.

## 4. Cursor

1. **Developer: Reload Window**
2. Customize → Plugins → **Aphelia** должен быть виден
3. Открой папку проекта (где `aphelia-memory/`)

## 5. Первый ролик

```
/aphelia-new Тестовая тема про нейросети --duration 60
```

Когда Директор дойдёт до voice (или вручную после `script.txt`):

```powershell
cd %USERPROFILE%\.cursor\plugins\local\aphelia
python scripts\voice_cloud.py narrate --project C:\path\to\aphelia-memory\runs\<slug> --engine yandex
```

## 6. Музыка без ACE-Step

На 6 GB **не запускай** `bgm-generated`. В storyboard / music-brief укажи трек из библиотеки SFX/BGM или попроси storyboarder:

> BGM: `templates/audio/bgm/<name>.mp3` из Mixkit, не ACE-Step

Если render падает на ACE-Step — в `timeline.json` замени `bgm.src` на существующий mp3 в `assets/audio/`.

## 7. Cutout без VRAM-войны

```powershell
$env:REMBG_SESSION="u2net"
# rembg на CPU (медленнее, но не бьётся с TTS):
python scripts\cutout.py --project aphelia-memory\runs\<slug>
```

Или `--no-rembg` если только ink-mask достаточно.

## 8. Render

```powershell
python scripts\render.py --project aphelia-memory\runs\<slug> --concurrency 2
```

## 9. Проверка

```powershell
python scripts\qa.py --project aphelia-memory\runs\<slug>
```

Результат: `aphelia-memory\runs\<slug>\out\<slug>.mp4`

## Troubleshooting

| Ошибка | Решение |
|--------|---------|
| Task не видит `aphelia-*` | перезапуск Cursor; проверь `%USERPROFILE%\.cursor\agents\aphelia-*.md` |
| Yandex HTTP 401 | неверный API key или folderId |
| ACE-Step OOM | `APHELIA_SKIP_ACE_STEP=1`, BGM из библиотеки |
| `qwen_tts` import | используй `voice_cloud.py`, не `voice.py` |
| SoX / torchaudio warning | игнорировать если JSON в конце stdout OK |
