#!/usr/bin/env bash
set -euo pipefail

# Script to add, commit and push specific view changes, then clear Laravel caches.
# Usage: ./scripts/push_changes.sh "Commit message"

# Determine repo root (script is expected at repo_root/scripts)
REPO_ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$REPO_ROOT"

MSG="${1:-}"
if [ -z "$MSG" ]; then
  MSG="Update views: change store name and responsive tweaks"
fi

FILES=(
  "resources/views/landing.blade.php"
  "resources/views/product/show.blade.php"
  "resources/views/thanks.blade.php"
)

echo "Repo: $REPO_ROOT"

echo "Staging files: ${FILES[*]}"
git add "${FILES[@]}"

# If nothing to commit, exit gracefully
if git diff --cached --quiet; then
  echo "No staged changes to commit."
else
  git commit -m "$MSG"
  echo "Pushing to origin/main..."
  git push origin main
fi

# Optional maintenance: composer dump-autoload if composer exists
if command -v composer >/dev/null 2>&1; then
  echo "Running composer dump-autoload..."
  composer dump-autoload --no-interaction --optimize || true
fi

# Clear Laravel caches if artisan available
if command -v php >/dev/null 2>&1 && [ -f artisan ]; then
  echo "Clearing Laravel caches..."
  php artisan view:clear || true
  php artisan cache:clear || true
  php artisan config:clear || true
  php artisan route:clear || true
fi

echo "Done."
