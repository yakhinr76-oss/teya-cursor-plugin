#!/usr/bin/env bash
#
# zhiraf_copy_lint.sh - Linter for visible copy leaks in zhiraf-ufa theme
#
# Checks translated and user-visible strings (__(), esc_html__(), esc_attr__(),
# esc_html_e(), esc_attr_e(), _e(), _x()) for forbidden internal tokens:
# - показ / показов / показов/мес
# - slug
# - URL-map
# - секция / секции
# - Wordstat
#
# Exit code: 0 if clean, 1 if forbidden patterns found.

set -euo pipefail

THEME_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/../deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa" && pwd)"

if [ ! -d "$THEME_DIR" ]; then
    echo "Error: Theme directory not found: $THEME_DIR" >&2
    exit 1
fi

echo "=== Zhiraf Visible Copy Linter ==="
echo "Scanning theme files in: $THEME_DIR"

FORBIDDEN_REGEX='(__|esc_html__|esc_attr__|esc_html_e|esc_attr_e|_e|_x)[[:space:]]*\([^)]*(показ|Wordstat|URL-map|секци|slug)[^)]*\)'

# We will run ripgrep / grep over php files
ERRORS=0

while IFS= read -r file; do
    # Search for forbidden pattern in visible strings
    if grep -EIn '(показ|Wordstat|URL-map|секци)' "$file" | grep -E '(__|esc_html__|esc_attr__|esc_html_e|esc_attr_e|_e|_x)' > /tmp/zhiraf_lint_matches.txt; then
        echo "❌ FORBIDDEN COPY LEAK FOUND in $file:"
        cat /tmp/zhiraf_lint_matches.txt
        ERRORS=$((ERRORS + 1))
    fi
    # Also check for 'slug' inside user-facing translation functions (not array keys or comments)
    if grep -EIn '(__|esc_html__|esc_attr__|esc_html_e|esc_attr_e|_e|_x)[[:space:]]*\([[:space:]]*['\''"][^'\''"]*slug[^'\''"]*['\''"]' "$file" > /tmp/zhiraf_lint_slug.txt; then
        echo "❌ FORBIDDEN TOKEN 'slug' in translation string in $file:"
        cat /tmp/zhiraf_lint_slug.txt
        ERRORS=$((ERRORS + 1))
    fi
done < <(find "$THEME_DIR" -type f -name "*.php")

rm -f /tmp/zhiraf_lint_matches.txt /tmp/zhiraf_lint_slug.txt

if [ "$ERRORS" -gt 0 ]; then
    echo ""
    echo "❌ Copy lint FAILED: $ERRORS file(s) with forbidden visible copy patterns."
    exit 1
else
    echo "✅ Copy lint PASSED: zero visible SEO leaks found across all theme templates."
    exit 0
fi
