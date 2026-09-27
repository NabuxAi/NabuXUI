#!/usr/bin/env sh
set -e

cd /app/playground

if [ ! -f .env ]; then
    cp .env.example .env
fi

grep -q '^APP_KEY=base64' .env || php artisan key:generate --force

if [ ! -d vendor ]; then
    composer install --no-interaction --prefer-dist
fi

touch database/database.sqlite
php artisan migrate --graceful --force

exec "$@"
