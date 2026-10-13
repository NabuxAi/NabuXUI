{{--
    /admin/errors/{404,500,maintenance} — the panel's standalone minimal error
    page: no admin shell, one centred column on the plain skeleton. The
    numbered flavours carry the big code in gradient display type (rolled
    through the locale's digits by the component) above the empty-state
    block — its floating plate doubles as the mark, so maintenance leans on
    the plate alone. Tokens only, both themes, logical properties so RTL
    mirrors by itself; the reveal degrades to a fade under reduced motion
    through the --nx-motion multiplier. The h1 stays in the outline (hidden
    visually) because the empty-state renders its title as a <p>.
--}}
<style>
    .ep-page { min-height: 100dvh; display: grid; place-items: center; padding: clamp(1rem, 4vw, 2rem); }
    .ep-stack { display: grid; gap: var(--nx-space-2); justify-items: center; text-align: center;
        max-inline-size: 28rem; }
    .ep-code { margin: 0; font: 800 var(--nx-text-display) / 1 var(--nx-font-display);
        letter-spacing: var(--nx-tracking-tight); background: var(--nx-gradient-text);
        -webkit-background-clip: text; background-clip: text; color: transparent; }
    .ep-stack { transition-property: opacity, translate; transition-duration: var(--nx-dur-base);
        transition-timing-function: var(--nx-ease-out); }
    :where(.nx-js) .ep-stack[data-nx-reveal]:not([data-nx-revealed]) { opacity: 0; translate: 0 calc(16px * var(--nx-motion)); }
</style>
<div class="ep-page">
    <main class="ep-stack" data-nx-reveal x-data x-nx-reveal>
        <h1 class="nx-visually-hidden">{{ $title }}</h1>
        @if ($display)
            <p class="ep-code" aria-hidden="true">{{ $display }}</p>
        @endif
        <x-nx::empty-state :icon="$icon" size="lg" :title="$title" :description="$description">
            <x-slot:actions>
                <x-nx::button variant="primary" icon="grid" href="{{ route('admin.dashboard') }}">{{ __('admin.errors_back_panel') }}</x-nx::button>
                <x-nx::button variant="secondary" icon="external-link" href="{{ url('/') }}">{{ __('admin.stub_secondary') }}</x-nx::button>
            </x-slot:actions>
        </x-nx::empty-state>
    </main>
</div>
