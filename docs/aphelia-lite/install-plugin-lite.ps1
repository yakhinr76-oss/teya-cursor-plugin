# Aphelia Lite installer — Windows, GPU 6–8 GB (GTX 1660S class).
# Skips ACE-Step and Qwen GPU deps; uses cloud TTS (Yandex / ElevenLabs) via voice_cloud.py.
#
#   git clone https://github.com/Horosheff/aphelia
#   cd aphelia
#   .\install-plugin-lite.ps1
#
# Then: copy shared\aphelia.env.example -> aphelia-memory\aphelia.env.local, fill API keys, Reload Cursor.

param(
  [switch]$WithAceStep,
  [switch]$WithQwen
)

$ErrorActionPreference = "Stop"
$here = Split-Path -Parent $MyInvocation.MyCommand.Path
$dest = Join-Path $env:USERPROFILE ".cursor\plugins\local\aphelia"

Write-Host "Aphelia Lite install -> $dest" -ForegroundColor Cyan
Write-Host "  ACE-Step: $(if ($WithAceStep) { 'yes' } else { 'skip (Mixkit BGM)' })" -ForegroundColor DarkGray
Write-Host "  Qwen TTS: $(if ($WithQwen) { 'yes' } else { 'skip (cloud TTS)' })" -ForegroundColor DarkGray

if (-not (Test-Path $dest)) {
  New-Item -ItemType Directory -Force -Path $dest | Out-Null
}
Get-ChildItem $dest -Force -ErrorAction SilentlyContinue | Where-Object { $_.Name -ne "vendor" } | Remove-Item -Recurse -Force

$items = @(".cursor-plugin", "assets", "rules", "agents", "skills", "commands", "scripts", "shared", "templates", "voices", "README.md", "LICENSE", "install-plugin.ps1", "install-plugin-lite.ps1", "INSTALL-6GB.md", ".gitignore")
foreach ($item in $items) {
  $src = Join-Path $here $item
  if (-not (Test-Path $src)) { continue }
  if ((Get-Item $src).PSIsContainer) {
    robocopy $src (Join-Path $dest $item) /E /XD node_modules public out __pycache__ .pytest_cache /XF render-*.mp4 data.ts /NFL /NDL /NJH /NJS /NP | Out-Null
  } else {
    Copy-Item $src (Join-Path $dest $item) -Force
  }
}

Write-Host "npm install (Remotion)..." -ForegroundColor Cyan
Push-Location (Join-Path $dest "templates\remotion")
npm install --silent
$dataTs = Join-Path (Get-Location) "src\data.ts"
if (-not (Test-Path $dataTs)) {
  $placeholder = @'
/* eslint-disable */
import type { Timeline } from "./timeline-types";
export const timeline: Timeline = {
  id: "placeholder", fps: 30, width: 1080, height: 1920, duration: 1,
  style: { bg: "#FFFFFF", ink: "#111111", accent: "#C8FF3D", danger: "#FF3B30", muted: "#6B7280", font_head: "Inter", font_hand: "Neucha" },
  captions_cfg: {}, words: [], bgm: null, sfx: [], scenes: [],
};
'@
  [System.IO.File]::WriteAllText($dataTs, $placeholder, (New-Object System.Text.UTF8Encoding $false))
}
Pop-Location

Write-Host "npm install (Playwright)..." -ForegroundColor Cyan
Push-Location (Join-Path $dest "scripts\node")
npm install --silent
npx playwright install chromium | Out-Null
Pop-Location

if ($WithAceStep) {
  Write-Host "ACE-Step (optional, needs 12+ GB VRAM for music gen)..." -ForegroundColor Yellow
  $ace = Join-Path $dest "vendor\ace-step"
  if (Get-Command uv -ErrorAction SilentlyContinue) {
    if (-not (Test-Path (Join-Path $ace "pyproject.toml"))) {
      New-Item -ItemType Directory -Force -Path (Split-Path $ace) | Out-Null
      git clone --depth 1 https://github.com/ACE-Step/ACE-Step-1.5.git $ace
    }
    Push-Location $ace
    uv sync
    Pop-Location
  } else {
    Write-Host "  uv not found — skip ACE-Step" -ForegroundColor Yellow
  }
}

Write-Host "Python deps (Lite)..." -ForegroundColor Cyan
$pyPkgs = @("openai-whisper", "Pillow", "rembg", "onnxruntime")
if ($WithQwen) { $pyPkgs += @("qwen-tts", "ruaccent", "torch", "torchaudio", "soundfile") }
foreach ($pkg in $pyPkgs) {
  pip install $pkg --quiet 2>$null
}
python -c "import whisper, PIL, rembg; print('python lite deps OK')"

# Register Task subagents + slash commands
$agentSrc = Join-Path $here "agents"
$cmdSrc = Join-Path $here "commands"
$taskUser = Join-Path $env:USERPROFILE ".cursor\agents"
$cmdUser = Join-Path $env:USERPROFILE ".cursor\commands"
$taskProj = Join-Path $here ".cursor\agents"
$cmdProj = Join-Path $here ".cursor\commands"
New-Item -ItemType Directory -Force -Path $taskUser, $cmdUser, $taskProj, $cmdProj | Out-Null
Copy-Item -Path (Join-Path $agentSrc "aphelia*.md") -Destination $taskUser -Force
Copy-Item -Path (Join-Path $cmdSrc "aphelia*.md") -Destination $cmdUser -Force
Copy-Item -Path (Join-Path $agentSrc "aphelia*.md") -Destination $taskProj -Force
Copy-Item -Path (Join-Path $cmdSrc "aphelia*.md") -Destination $cmdProj -Force

$mem = Join-Path (Get-Location).Path "aphelia-memory"
if (-not (Test-Path $mem)) { New-Item -ItemType Directory -Path $mem | Out-Null }
$envExample = Join-Path $dest "shared\aphelia.env.example"
$envLocal = Join-Path $mem "aphelia.env.local"
if (-not (Test-Path $envLocal) -and (Test-Path $envExample)) {
  Copy-Item $envExample $envLocal
  Write-Host "Created aphelia-memory\aphelia.env.local — fill Yandex/ElevenLabs keys" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "Done. Reload Cursor (Developer: Reload Window)." -ForegroundColor Green
Write-Host "1. Edit aphelia-memory\aphelia.env.local (YANDEX_API_KEY or ELEVENLABS_API_KEY)" -ForegroundColor DarkGray
Write-Host "2. Open project folder in Cursor" -ForegroundColor DarkGray
Write-Host "3. /aphelia-new <topic or URL> --duration 75" -ForegroundColor DarkGray
Write-Host "4. Voice agent: python scripts/voice_cloud.py narrate --project aphelia-memory/runs/<slug>" -ForegroundColor DarkGray
Write-Host "5. Storyboard: BGM from library (not bgm-generated) — see INSTALL-6GB.md" -ForegroundColor DarkGray
