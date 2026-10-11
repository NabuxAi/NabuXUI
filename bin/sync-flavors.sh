#!/usr/bin/env bash
#
# bin/sync-flavors — mirror every built SPA flavor from apps/*/dist into the
# Laravel playground's public tree, one address per framework:
#
#   apps/showcase/dist     -> playground/public/react        (/react)
#   apps/admin/dist        -> playground/public/react/admin  (/react/admin)
#   apps/admin-vue/dist    -> playground/public/vue          (/vue)
#   apps/admin-svelte/dist -> playground/public/svelte       (/svelte)
#   apps/inertia/dist      -> playground/public/inertia      (/inertia)
#
# The apps keep their build output in place (apps/<app>/dist); this script
# only mirrors it, so re-run it after rebuilding any app. rsync --delete
# keeps the public copies exact. The react sync excludes "admin" so it can
# never wipe the nested /react/admin copy, whichever order runs in. The
# inertia copy skips index.html (the standalone dev shell) and gains a
# directory index.php instead: /inertia is answered by the Inertia
# controllers through the inertia-root Blade view, and under
# `php artisan serve` a bare directory request only reaches the app when
# an index.php sits inside it (see apps/inertia/public-index.php).
#
# Nothing here builds anything. If a dist is missing, build it first:
#   pnpm install --filter <app>... --no-frozen-lockfile
#   pnpm --filter <app> build
# (the apps' vite builds consume packages/*/dist, so build those too —
# `pnpm --filter <app>... build` does the whole chain topologically).

set -euo pipefail

repo="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
public="$repo/playground/public"

sync_flavor() {
    local src="$1" dst="$2"
    shift 2

    if [ ! -f "$src/index.html" ] && [ ! -f "$src/.vite/manifest.json" ]; then
        echo "sync-flavors: missing build output in $src — build the app first" >&2
        exit 1
    fi

    mkdir -p "$dst"
    rsync -a --delete "$@" "$src/" "$dst/"
    echo "synced $src -> $dst"
}

sync_flavor "$repo/apps/showcase/dist"     "$public/react"        --exclude=admin
sync_flavor "$repo/apps/admin/dist"        "$public/react/admin"
sync_flavor "$repo/apps/admin-vue/dist"    "$public/vue"
sync_flavor "$repo/apps/admin-svelte/dist" "$public/svelte"
sync_flavor "$repo/apps/inertia/dist"      "$public/inertia"      --exclude=index.html

# /inertia must boot the app (its controllers answer), so the directory
# needs a PHP index — not the dist's standalone dev shell.
cp "$repo/apps/inertia/public-index.php" "$public/inertia/index.php"

echo "sync-flavors: all five flavors copied."
