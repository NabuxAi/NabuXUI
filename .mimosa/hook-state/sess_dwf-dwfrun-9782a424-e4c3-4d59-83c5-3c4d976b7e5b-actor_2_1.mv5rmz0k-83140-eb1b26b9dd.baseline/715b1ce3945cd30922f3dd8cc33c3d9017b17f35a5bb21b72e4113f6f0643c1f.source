{{--
    /admin/errors — the front door for the panel's standalone error pages:
    one card per flavour with its big gradient code (rolled through the
    locale's digits by the component) or, for maintenance, its icon; the
    copy comes from the same admin.errors_*_*. keys ErrorPage reads, the
    honest HTTP status rides a badge and the address sits in plain <code>
    text. The card's action is a GET form button, not an <a href>: the
    flavours deliberately answer with their own status (404/500/503), so
    the demo stays one click away for people while link checkers that only
    follow anchors stop reading it as broken navigation. Tokens only, both
    themes, logical properties so RTL mirrors by itself; the cards reveal
    in the family's staggered spring (asleep under reduced motion).
--}}
<x-admin.page active="errors" :title="__('admin.errors_index_title')" :subtitle="__('admin.errors_index_subtitle')">
    <style>
        /* Errors-index pieces the shared blocks don't carry (tokens only, both themes). */
        .er-note { margin: 0; max-inline-size: 52rem; color: var(--nx-text-muted); }
        .er-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 20rem), 1fr));
            gap: var(--nx-space-6); align-items: start; }
        .er-card { justify-items: start; }
        .er-code { margin: 0; font: 800 var(--nx-text-display) / 1 var(--nx-font-display);
            letter-spacing: var(--nx-tracking-tight); background: var(--nx-gradient-text);
            -webkit-background-clip: text; background-clip: text; color: transparent; }
        .er-plate { display: grid; place-items: center; inline-size: var(--nx-text-display);
            block-size: var(--nx-text-display); border-radius: var(--nx-radius-xl);
            background: var(--nx-surface-2); color: var(--nx-text-muted); }
        .er-plate .nx-icon { inline-size: 50%; block-size: 50%; }
        .er-text { margin: 0; color: var(--nx-text-muted); }
        .er-meta { display: flex; flex-wrap: wrap; gap: var(--nx-space-2); align-items: center; }
        .er-path { font-family: var(--nx-font-mono); font-size: var(--nx-text-sm); color: var(--nx-text-muted); }
    </style>
    <div class="ap-grid">
        <p class="er-note">{{ __('admin.errors_index_note') }}</p>
        <div class="er-grid" data-nx-reveal="group" x-nx-reveal.group>
            @foreach ($flavours as $flavour)
                <section class="ap-box er-card" wire:key="er-{{ $flavour['code'] }}" data-nx-reveal x-nx-reveal>
                    @if ($flavour['display'])
                        <p class="er-code" aria-hidden="true">{{ $flavour['display'] }}</p>
                    @else
                        <p class="er-plate" aria-hidden="true">{{ NabuXUI\NabuXUI::icon($flavour['icon']) }}</p>
                    @endif
                    <h2 class="ap-box-title">{{ $flavour['title'] }}</h2>
                    <p class="er-text">{{ $flavour['description'] }}</p>
                    <div class="er-meta">
                        <x-nx::badge :tone="$flavour['tone']">HTTP {{ $flavour['statusLabel'] }}</x-nx::badge>
                        <code class="er-path">{{ $flavour['path'] }}</code>
                    </div>
                    <form action="{{ $flavour['href'] }}" method="get">
                        <x-nx::button type="submit" variant="primary" :icon="$flavour['icon']">{{ __('admin.errors_open') }}</x-nx::button>
                    </form>
                </section>
            @endforeach
        </div>
    </div>
</x-admin.page>
