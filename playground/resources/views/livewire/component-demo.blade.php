{{--
    /components/{group}/{slug} — the single-component demo template: bilingual
    title and intro, the "real scenarios" stage that includes the component's
    partial (demos/components/{group}/{slug}), the important props, a copyable
    snippet, and the previous/next demo of the same group. The pattern page
    for every group author: fill the manifest, add the partial, done.
--}}
@php
    use App\Support\DemoCatalog;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $locale = app()->getLocale();

    $title = DemoCatalog::pick($demo['title'] ?? '', $locale);
    $oneLiner = DemoCatalog::pick($demo['oneLiner'] ?? '', $locale);
    $groupLabel = DemoCatalog::pick(DemoCatalog::groups()[$group]['label'] ?? [], $locale);
    $needsJs = (bool) ($demo['js'] ?? false);
    $docs = $demo['docs'] ?? null;
    $code = $demo['code'] ?? null;

    $propRows = [];
    foreach (($demo['props'] ?? []) as $index => $prop) {
        $propRows[] = [
            'id' => (string) $index,
            'name' => (string) ($prop['name'] ?? ''),
            'type' => (string) ($prop['type'] ?? ''),
            'default' => (string) ($prop['default'] ?? '—'),
            'note' => DemoCatalog::pick($prop['note'] ?? '', $locale),
        ];
    }
    $prevTitle = $prev ? DemoCatalog::pick(DemoCatalog::find($prev['group'], $prev['slug'])['title'] ?? '', $locale) : null;
    $nextTitle = $next ? DemoCatalog::pick(DemoCatalog::find($next['group'], $next['slug'])['title'] ?? '', $locale) : null;
@endphp
<style>
    /* Scoped to the ids this very template emits: below 640px the props-table
       cells may wrap and the snippet pre soft-wraps, so the 4th column and the
       code stay visible at the 375px edge (the core keeps nowrap by default). */
    @media (max-width: 639.98px) {
        [aria-labelledby="demo-props-title"] .nx-data-table :is(th, td) { white-space: normal; padding-inline: .5rem; }
        [aria-labelledby="demo-snippet-title"] pre { white-space: pre-wrap; overflow-wrap: anywhere; }
    }
</style>
<div>
    <main class="nx-page" wire:transition.navigate="nx-page">
        <div class="pg">
            <nav class="pg-row" style="justify-content: space-between" aria-label="{{ $say('Demo', 'دمو') }}">
                <x-nx::button size="sm" variant="ghost" icon="arrow-left" href="/components" wire:navigate>
                    {{ $say('All demos', 'همهٔ دموها') }}
                </x-nx::button>
                <x-nx::language-menu :value="$locale" wire:model.live="locale" :label="$say('Language', 'زبان')" :languages="[
                    ['id' => 'fa', 'name' => 'فارسی', 'short' => 'FA'],
                    ['id' => 'en', 'name' => 'English', 'short' => 'EN'],
                ]" />
            </nav>

            <header style="display: grid; gap: .75rem">
                <div class="pg-row" style="gap: .5rem">
                    <x-nx::badge tone="neutral">{{ $groupLabel }}</x-nx::badge>
                    @if ($needsJs)
                        <x-nx::badge tone="info">{{ $say('JS behaviour', 'رفتار JS') }}</x-nx::badge>
                    @else
                        <x-nx::badge tone="success">{{ $say('Pure CSS', 'خالص CSS') }}</x-nx::badge>
                    @endif
                </div>
                <h1 style="margin: 0; font: 700 var(--nx-text-3xl) / 1.2 var(--nx-font-display); letter-spacing: var(--nx-tracking-tight)">
                    {{ $title }}
                </h1>
                <p style="margin: 0; max-inline-size: 46rem; color: var(--nx-text-muted)">{{ $oneLiner }}</p>
                @if (is_string($docs) && str_starts_with($docs, 'http'))
                    <p style="margin: 0"><a href="{{ $docs }}" target="_blank" rel="noopener" style="color: var(--nx-accent-text)">{{ $say('Documentation', 'مستندات') }}</a></p>
                @endif
            </header>

            <section class="pg-box" style="gap: 1.25rem" aria-labelledby="demo-scenarios-title">
                <div>
                    <h2 class="pg-title" id="demo-scenarios-title">{{ $say('Real scenarios', 'سناریوهای واقعی') }}</h2>
                    <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                        {{ $say('The component as it is used in a product — not a specimen grid.', 'کامپوننت همان‌طور که در محصول به‌کار می‌رود — نه یک گرید نمونه‌وار.') }}
                    </p>
                </div>
                @if ($hasScenarios)
                    @include(DemoCatalog::viewName($group, $slug))
                @else
                    <x-nx::empty-state icon="wand" :title="$say('Demo in the making', 'دمو در راه است')"
                        :description="$say('This manifest entry shipped before its scenarios. The partial demos/components/'.$group.'/'.$slug.'.blade.php will land here.', 'این مدخل منیفست زودتر از سناریوهایش رسیده. پارشال demos/components/'.$group.'/'.$slug.'.blade.php این‌جا نمایش داده می‌شود.')"
                        :action="$say('Back to the catalog', 'بازگشت به کاتالوگ')" action-href="/components" />
                @endif
            </section>

            @if (count($propRows) > 0)
                <section aria-labelledby="demo-props-title">
                    <h2 class="pg-title" id="demo-props-title" style="margin-block-end: 1rem">{{ $say('Important props', 'پراپ‌های مهم') }}</h2>
                    <x-nx::data-table caption="{{ $say('Important props', 'پراپ‌های مهم') }}" :rows="$propRows" :columns="[
                        ['key' => 'name', 'label' => $say('Prop', 'پراپ')],
                        ['key' => 'type', 'label' => $say('Type', 'نوع')],
                        ['key' => 'default', 'label' => $say('Default', 'پیش‌فرض')],
                        ['key' => 'note', 'label' => $say('What it does', 'چه می‌کند')],
                    ]" />
                </section>
            @endif

            @if ($code)
                <section class="pg-box" aria-labelledby="demo-snippet-title" style="gap: .75rem">
                    <div class="pg-row" style="justify-content: space-between">
                        <h2 class="pg-title" id="demo-snippet-title" style="margin: 0">{{ $say('Snippet', 'تکه‌کد') }}</h2>
                        <x-nx::copy-button :value="$code" variant="secondary" size="sm">{{ $say('Copy', 'کپی') }}</x-nx::copy-button>
                    </div>
                    <pre dir="ltr" style="margin: 0; padding: 1rem 1.25rem; overflow-x: auto; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg); background: var(--nx-surface-2); font: 500 var(--nx-text-sm) / 1.7 var(--nx-font-mono)"><code>{{ $code }}</code></pre>
                </section>
            @endif

            <nav class="pg-row" style="justify-content: space-between" aria-label="{{ $say('More demos', 'دموهای بیشتر') }}">
                @if ($prev)
                    <x-nx::button variant="ghost" icon="arrow-left" href="/components/{{ $prev['group'] }}/{{ $prev['slug'] }}" wire:navigate>{{ $prevTitle }}</x-nx::button>
                @else
                    <x-nx::button variant="ghost" icon="arrow-left" disabled aria-disabled="true">{{ $say('First in this group', 'نخستین دمو در این گروه') }}</x-nx::button>
                @endif
                @if ($next)
                    <x-nx::button variant="ghost" icon-end="arrow-right" href="/components/{{ $next['group'] }}/{{ $next['slug'] }}" wire:navigate>{{ $nextTitle }}</x-nx::button>
                @else
                    <x-nx::button variant="ghost" icon-end="arrow-right" disabled aria-disabled="true">{{ $say('Last in this group', 'آخرین دمو در این گروه') }}</x-nx::button>
                @endif
            </nav>
        </div>
    </main>
</div>
