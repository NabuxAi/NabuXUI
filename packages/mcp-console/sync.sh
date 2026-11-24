#!/usr/bin/env bash
# Copy the Nabux MCP Console kit into a gateway repo.
#
#   packages/mcp-console/sync.sh /path/to/FaceBookMCP            # app/ layout (default)
#   packages/mcp-console/sync.sh /path/to/Repo --app-dir src/app  # another package dir
#   packages/mcp-console/sync.sh /path/to/Repo --check            # exit 1 if the copy is stale
#
# Writes, inside <repo>/<app-dir>:
#   nabux_console.py
#   static/console/console.css, console.js, fonts/*.woff2, fonts/OFL-*.txt
# and <repo>/tests/test_nabux_console.py (the kit's own tests).
# The vendored files are never edited in the gateway; change them here and re-run.
set -euo pipefail

KIT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
REPO="${1:-}"
[ -n "$REPO" ] || { echo "usage: $0 <repo> [--app-dir app] [--check]" >&2; exit 2; }
shift || true
APP_DIR="app"
CHECK=0
while [ $# -gt 0 ]; do
  case "$1" in
    --app-dir) APP_DIR="$2"; shift 2 ;;
    --check) CHECK=1; shift ;;
    *) echo "unknown option: $1" >&2; exit 2 ;;
  esac
done
[ -d "$REPO/$APP_DIR" ] || { echo "no $REPO/$APP_DIR directory" >&2; exit 2; }

DEST="$REPO/$APP_DIR"
FILES=(
  "nabux_console.py:$DEST/nabux_console.py"
  "static/console/console.css:$DEST/static/console/console.css"
  "static/console/console.js:$DEST/static/console/console.js"
  "static/console/fonts/inter-latin.woff2:$DEST/static/console/fonts/inter-latin.woff2"
  "static/console/fonts/vazirmatn-arabic.woff2:$DEST/static/console/fonts/vazirmatn-arabic.woff2"
  "static/console/fonts/OFL-Inter.txt:$DEST/static/console/fonts/OFL-Inter.txt"
  "static/console/fonts/OFL-Vazirmatn.txt:$DEST/static/console/fonts/OFL-Vazirmatn.txt"
  "tests/test_nabux_console.py:$REPO/tests/test_nabux_console.py"
)

stale=0
for pair in "${FILES[@]}"; do
  src="$KIT/${pair%%:*}"; dst="${pair#*:}"
  if [ "$CHECK" = 1 ]; then
    if ! cmp -s "$src" "$dst"; then echo "stale: $dst"; stale=1; fi
  else
    mkdir -p "$(dirname "$dst")"
    cp "$src" "$dst"
    echo "wrote $dst"
  fi
done
if [ "$CHECK" = 1 ]; then
  [ "$stale" = 0 ] && echo "kit is up to date in $REPO" || exit 1
else
  echo "Nabux MCP Console $(grep -m1 '^KIT_VERSION' "$KIT/nabux_console.py" | cut -d'"' -f2) synced into $REPO/$APP_DIR"
fi
