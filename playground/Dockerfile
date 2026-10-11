# Build context is the repository root (see docker-compose.yml): the playground's
# composer.json path-repository points at "..", so the whole repo must be baked
# into the image for vendor/nabuxai/nabuxui to resolve.
FROM node:22-alpine AS assets
# Declared for Coolify's build-time env scan; values unused at build time.
ARG COOLIFY_FQDN
ARG APP_PORT
ARG VITE_PORT
ARG APP_NAME
ARG APP_ENV
ARG APP_DEBUG
ARG APP_KEY
ARG APP_URL
WORKDIR /app/playground
COPY playground/package.json playground/package-lock.json ./
RUN npm ci --no-audit --no-fund
COPY playground/ .
RUN npm run build

# The five SPA flavors (React showcase + admin, Vue, Svelte, Inertia) built
# from the pnpm workspace at the repo root. Apps' vite builds consume the
# workspace packages' dist (apps/*/vite.config.ts aliases packages/*/src
# for the dev server only), and `--filter <app>...` runs the build script
# in each app together with its workspace dependencies, topologically —
# packages first, apps after. Every app builds with vite base './' and a
# hash router, so the dist folders drop straight into the public tree at
# the same layout bin/sync-flavors.sh mirrors locally. pnpm itself comes
# from corepack, pinned by the root package.json's packageManager field
# (pnpm@8.7.5; pnpm-lock.yaml is lockfileVersion 6.0).
FROM node:22-alpine AS flavors
# Declared for Coolify's build-time env scan; values unused at build time.
ARG COOLIFY_FQDN
ARG APP_PORT
ARG VITE_PORT
ARG APP_NAME
ARG APP_ENV
ARG APP_DEBUG
ARG APP_KEY
ARG APP_URL
# corepack 0.20+ asks before its first download otherwise.
ENV COREPACK_ENABLE_DOWNLOAD_PROMPT=0
RUN corepack enable pnpm
WORKDIR /app
COPY pnpm-workspace.yaml package.json pnpm-lock.yaml tsconfig.base.json ./
COPY packages packages
COPY apps apps
# No install lock needed here: this is the only pnpm consumer in the image.
RUN pnpm install --no-frozen-lockfile \
    && pnpm --filter showcase... --filter admin... --filter admin-vue... --filter admin-svelte... --filter inertia... build

FROM php:8.4-cli
# Declared for Coolify's build-time env scan; values unused at build time.
ARG COOLIFY_FQDN
ARG APP_PORT
ARG VITE_PORT
ARG APP_NAME
ARG APP_ENV
ARG APP_DEBUG
ARG APP_KEY
ARG APP_URL

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip libicu-dev libzip-dev \
    && docker-php-ext-install intl zip \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app/playground

COPY --from=assets /app/playground/public/build ./public/build
COPY . /app

# The SPA flavors, copied after `COPY .` so the stage-built outputs always
# win over anything the build context might carry. The layout mirrors
# bin/sync-flavors.sh: showcase -> public/react, admin -> public/react/admin,
# and the Vue/Svelte panels under their own prefixes.
COPY --from=flavors /app/apps/showcase/dist ./public/react
COPY --from=flavors /app/apps/admin/dist ./public/react/admin
COPY --from=flavors /app/apps/admin-vue/dist ./public/vue
COPY --from=flavors /app/apps/admin-svelte/dist ./public/svelte
COPY --from=flavors /app/apps/inertia/dist ./public/inertia

# Same adapter pass bin/sync-flavors.sh applies locally (keep the two in
# sync): the apps build with vite base './', so the shells reference
# "./assets/…" — correct for sub-path URLs, but the advertised root URLs
# carry no trailing slash and a browser would resolve "./assets/x.js" on
# /react against "/" and on /react/admin against /react/assets. Rewriting
# the references to root-absolute "/<prefix>/…" fixes every URL shape.
# The dev-lab copy (_lab) stays out of the served tree, and /inertia gets
# a PHP directory index instead of the dist's standalone dev shell: its
# controllers answer /inertia, and `php artisan serve` only reaches the app
# for a bare directory request when an index.php sits inside (the
# inertia-root Blade view resolves the hashed assets from
# public/inertia/.vite/manifest.json).
RUN sed -i -E 's#(src|href)="\./#\1="/react/#g' public/react/index.html \
    && sed -i -E 's#(src|href)="\./#\1="/react/admin/#g' public/react/admin/index.html \
    && sed -i -E 's#(src|href)="\./#\1="/vue/#g' public/vue/index.html \
    && sed -i -E 's#(src|href)="\./#\1="/svelte/#g' public/svelte/index.html \
    && rm -rf public/react/_lab \
    && rm -f public/inertia/index.html \
    && printf '%s\n' \
        '<?php' \
        '' \
        '/**' \
        ' * The directory index for the copied Inertia build (public/inertia) — see' \
        ' * bin/sync-flavors.sh for the rationale: /inertia is answered by the Inertia' \
        ' * controllers through the inertia-root Blade view; this index only exists so a' \
        ' * bare directory request reaches the front controller under `php artisan serve`.' \
        ' */' \
        '' \
        "require __DIR__.'/../index.php';" \
        > public/inertia/index.php

# Git doesn't track the storage/framework subdirectories (only .gitignore
# placeholders), so create them before artisan boots during composer install.
RUN mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/testing storage/framework/views storage/logs bootstrap/cache \
    && composer install --no-interaction --no-progress --prefer-dist --optimize-autoloader

EXPOSE 8000

ENTRYPOINT ["sh", "/app/playground/docker/boot.sh"]
# --no-reload: without it, artisan serve strips container env vars (APP_KEY,
# APP_ENV, ...) from the web workers whenever a .env file exists.
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000", "--no-reload"]
