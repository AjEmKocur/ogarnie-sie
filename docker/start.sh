#!/usr/bin/env sh
set -e

cd /var/www/html

if [ -z "${APP_KEY:-}" ]; then
  echo "APP_KEY is missing. Set APP_KEY in Render environment variables."
  exit 1
fi

# Force production asset mode (in case a stale hot file exists).
rm -f public/hot

# Clear stale caches between deployments.
php artisan optimize:clear

# Ensure public/storage symlink exists (required for uploaded gallery images).
php artisan storage:link --force || true

if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
  php artisan migrate --force
fi

exec php artisan serve --host=0.0.0.0 --port="${PORT:-10000}"
