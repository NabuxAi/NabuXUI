# Build context is the repository root (see docker-compose.yml): the playground's
# composer.json path-repository points at "..", so the whole repo must be baked
# into the image for vendor/nabuxai/nabuxui to resolve.
FROM node:22-alpine AS assets
WORKDIR /app/playground
COPY playground/package.json playground/package-lock.json ./
RUN npm ci --no-audit --no-fund
COPY playground/ .
RUN npm run build

FROM php:8.4-cli

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app/playground

COPY --from=assets /app/playground/public/build ./public/build
COPY . /app

# Git doesn't track the storage/framework subdirectories (only .gitignore
# placeholders), so create them before artisan boots during composer install.
RUN mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/testing storage/framework/views storage/logs bootstrap/cache \
    && composer install --no-interaction --no-progress --prefer-dist --optimize-autoloader

EXPOSE 8000

ENTRYPOINT ["sh", "/app/playground/docker/boot.sh"]
# --no-reload: without it, artisan serve strips container env vars (APP_KEY,
# APP_ENV, ...) from the web workers whenever a .env file exists.
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000", "--no-reload"]
