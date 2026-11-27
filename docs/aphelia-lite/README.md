# Установка Aphelia Lite (GTX 1660S 6 GB)

Файлы Lite-установки лежат в `docs/aphelia-lite/`. Скопируй их в клон [Horosheff/aphelia](https://github.com/Horosheff/aphelia) или используй после `git clone`.

## Windows — быстрый старт

```powershell
# 1. Зависимости
winget install OpenJS.NodeJS.LTS
winget install Gyan.FFmpeg
winget install Python.Python.3.12

# 2. Aphelia
git clone https://github.com/Horosheff/aphelia
cd aphelia

# 3. Lite-файлы из teya-cursor-plugin/docs/aphelia-lite/
Copy-Item ..\teya-cursor-plugin\docs\aphelia-lite\install-plugin-lite.ps1 .
Copy-Item ..\teya-cursor-plugin\docs\aphelia-lite\INSTALL-6GB.md .
Copy-Item ..\teya-cursor-plugin\docs\aphelia-lite\voice_cloud.py .\scripts\
Copy-Item ..\teya-cursor-plugin\docs\aphelia-lite\aphelia.env.example .\shared\

.\install-plugin-lite.ps1

# 4. Ключи Яндекс SpeechKit
notepad aphelia-memory\aphelia.env.local

# 5. Reload Cursor → /aphelia-new тест --duration 60
```

Подробности: `INSTALL-6GB.md` в этой папке.
