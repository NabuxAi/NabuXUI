{{--
    Shared chrome for the platform-apps demos — NOT a demo of its own. This is
    the first underscore-prefixed partial under demos/components: the catalog
    renders only {group}/{slug} views (App\Support\DemoCatalog::viewName), so
    this file is reachable only through @include from the four demo pages.

    It ships two things those pages share:
      · the .pa-* layout CSS (switcher row, 2×2 window grid, cell tags) — plain
        CSS with tokens only, no Blade interpolation inside the style block;
      · the live skin switcher: five buttons bound to the including page's
        Alpine state `uniform` (null = every window keeps its own shell; a
        platform id = all four windows take that skin in place, through each
        window's :data-platform bind and the core behaviour's observer, which
        re-syncs state and fires nx-platform-change).

    Include it inside the page's x-data="{ uniform: null, names: { … } }"
    wrapper — see notebook.blade.php for the canonical shape.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $switchPlatforms = [
        ['id' => 'windows', 'label' => ['en' => 'Windows', 'fa' => 'ویندوز']],
        ['id' => 'macos', 'label' => ['en' => 'macOS', 'fa' => 'مک‌او‌اس']],
        ['id' => 'android', 'label' => ['en' => 'Android', 'fa' => 'اندروید']],
        ['id' => 'ios', 'label' => ['en' => 'iOS', 'fa' => 'آی‌او‌اس']],
    ];
@endphp
<style>
    /* platform-apps demo chrome — shared by the four pages that include this partial. */
    .pa-head { display: flex; flex-wrap: wrap; gap: .5rem .875rem; align-items: center; justify-content: space-between; }
    .pa-switch { display: flex; flex-wrap: wrap; gap: .375rem; align-items: center; }
    .pa-switch-btn { display: inline-flex; align-items: center; gap: .375rem; block-size: 2rem; padding-inline: .8rem;
                     border: 1px solid var(--nx-border); border-radius: var(--nx-radius-full);
                     background: var(--nx-surface-2); color: var(--nx-text-muted);
                     font: 500 var(--nx-text-xs) / 1 var(--nx-font-sans); letter-spacing: var(--nx-tracking-wide);
                     cursor: pointer; transition-property: scale, background-color, color, border-color;
                     transition-duration: var(--nx-dur-fast); }
    .pa-switch-btn:hover { border-color: var(--nx-border-strong); color: var(--nx-text); }
    .pa-switch-btn:active { scale: calc(1 - 0.04 * var(--nx-motion)); }
    .pa-switch-btn:focus-visible { outline: 2px solid var(--nx-ring); outline-offset: 2px; }
    .pa-switch-btn[aria-pressed='true'] { background: var(--nx-accent-soft); border-color: var(--nx-accent-border); color: var(--nx-accent-text); }
    .pa-grid { display: grid; gap: 1.25rem; grid-template-columns: repeat(2, minmax(0, 1fr)); align-items: start; }
    .pa-cell { margin: 0; display: grid; gap: .5rem; min-inline-size: 0; }
    /* A cell whose window currently wears a phone shell keeps phone
       proportions — a mobile skin stretched over a half-page column reads as
       a landscape tablet, nothing like the platform it stands for. Selecting
       the window by its own data-platform attribute (not the cell through
       :has()) keeps the rule working in engines without :has() support, and
       the attribute selector follows the live x-bind:data-platform swap of
       the switcher just the same. */
    .pa-cell > .nx-app-window[data-platform='android'],
    .pa-cell > .nx-app-window[data-platform='ios'] {
        inline-size: min(100%, 24.5rem);
        justify-self: center;
    }
    .pa-tag { display: inline-flex; align-items: center; gap: .375rem; font: 600 var(--nx-text-xs) / 1.4 var(--nx-font-sans);
              letter-spacing: var(--nx-tracking-wide); color: var(--nx-text-subtle); }
    .pa-tag::before { content: ''; inline-size: .5rem; block-size: .5rem; border-radius: 50%;
                      background: var(--nx-accent); opacity: .7; }
    @media (max-width: 64rem) { .pa-grid { grid-template-columns: 1fr; } }
</style>
<div class="pa-head">
    <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted); max-inline-size: 34rem">
        {{ $say('Every window below is the same component — pick a skin and watch all four re-shell in place, or leave them each their own.', 'هر چهار پنجرهٔ زیر یک کامپوننت‌اند — یک پوسته را برگزینید تا همان‌جا بازپوسته شوند، یا حالت «هر پلتفرم خودش» را نگه دارید.') }}
    </p>
    <div class="pa-switch" role="group" aria-label="{{ $say('Live skin', 'پوستهٔ زنده') }}">
        <button type="button" class="pa-switch-btn" x-on:click="uniform = null" aria-pressed="true" x-bind:aria-pressed="uniform === null ? 'true' : 'false'">
            {{ $say('Each its own', 'هر پلتفرم خودش') }}
        </button>
        @foreach ($switchPlatforms as $switchPlatform)
            <button type="button" class="pa-switch-btn" x-on:click="uniform = @js($switchPlatform['id'])"
                aria-pressed="false" x-bind:aria-pressed="uniform === @js($switchPlatform['id']) ? 'true' : 'false'">
                {{ $fa ? $switchPlatform['label']['fa'] : $switchPlatform['label']['en'] }}
            </button>
        @endforeach
    </div>
</div>
