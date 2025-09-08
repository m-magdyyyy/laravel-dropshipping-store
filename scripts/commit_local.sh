#!/usr/bin/env bash
set -euo pipefail

# Safe local commit script — stages specific view files and commits locally only (no push)
# Usage: ./scripts/commit_local.sh "Commit message"

REPO_ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$REPO_ROOT"

MSG="${1:-}"
if [ -z "$MSG" ]; then
  MSG="Local: update views (store name + responsiveness)"
fi

FILES=(
  "resources/views/landing.blade.php"
  "resources/views/product/show.blade.php"
  "resources/views/thanks.blade.php"
)

echo "Repo: $REPO_ROOT"

echo "Staging files: ${FILES[*]}"
git add "${FILES[@]}"

if git diff --cached --quiet; then
  echo "No staged changes to commit."
  exit 0
fi

git commit -m "$MSG"

echo "Committed locally (no push). To push later run: git push origin main"
