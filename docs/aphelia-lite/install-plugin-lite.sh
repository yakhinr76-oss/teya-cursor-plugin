#!/usr/bin/env bash
# Aphelia Lite install (Linux/macOS). Windows: use install-plugin-lite.ps1
set -euo pipefail
HERE="$(cd "$(dirname "$0")" && pwd)"
DEST="${HOME}/.cursor/plugins/local/aphelia"
echo "Installing Aphelia Lite -> $DEST"
mkdir -p "$DEST"
for item in .cursor-plugin assets rules agents skills commands scripts shared templates voices README.md LICENSE install-plugin.ps1 install-plugin-lite.ps1 INSTALL-6GB.md .gitignore; do
  [[ -e "$HERE/$item" ]] && cp -a "$HERE/$item" "$DEST/"
done

pushd "$DEST/templates/remotion" >/dev/null
npm install --silent
popd >/dev/null
pushd "$DEST/scripts/node" >/dev/null
npm install --silent
npx playwright install chromium
popd >/dev/null

pip3 install -q openai-whisper Pillow rembg onnxruntime
python3 -c "import whisper, PIL, rembg; print('python lite deps OK')"

mkdir -p "$HOME/.cursor/agents" "$HOME/.cursor/commands" "$HERE/.cursor/agents" "$HERE/.cursor/commands"
cp "$HERE/agents/aphelia"*.md "$HOME/.cursor/agents/"
cp "$HERE/commands/aphelia"*.md "$HOME/.cursor/commands/"
cp "$HERE/agents/aphelia"*.md "$HERE/.cursor/agents/"
cp "$HERE/commands/aphelia"*.md "$HERE/.cursor/commands/"

MEM="$(pwd)/aphelia-memory"
mkdir -p "$MEM"
[[ -f "$MEM/aphelia.env.local" ]] || cp "$DEST/shared/aphelia.env.example" "$MEM/aphelia.env.local"

echo "Done. Reload Cursor. Fill $MEM/aphelia.env.local"
