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

if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
  php artisan migrate --force
fi

exec php -S "0.0.0.0:${PORT:-10000}" -t public public/index.php
