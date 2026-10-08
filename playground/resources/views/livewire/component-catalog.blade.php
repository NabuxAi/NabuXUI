{{--
    /components — the catalog of standalone component demos: live search over
    the manifests in app/Support/Demos, a group chip filter, and a card grid
    with the JS / pure-CSS badge (the FRAMEWORKS.md catalogue columns).
--}}
@php
    use App\Support\DemoCatalog;
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $chipOptions = ['all' => $say('All groups', 'همهٔ گروه‌ها')];
    $chipCounts = ['all' => NabuXUI::formatNumber($total)];
    foreach ($groups as $id => $manifest) {
        $chipOptions[$id] = DemoCatalog::pick($manifest['label']);
        $chipCounts[$id] = NabuXUI::formatNumber(count($manifest['items']));
    }
@endphp
<div>
    <main class="nx-page" wire:transition.navigate="nx-page">
        <div class="pg">
            <nav class="pg-row" style="justify-content: space-between" aria-label="{{ $say('Playground', 'پلی‌گراوند') }}">
                <div class="pg-row">
                    <x-nx::button size="sm" variant="ghost" icon="home" href="/" wire:navigate>{{ $say('Home', 'خانه') }}</x-nx::button>
                    <x-nx::button size="sm" variant="ghost" icon="grid" href="/livewire" wire:navigate>{{ $say('Showcase', 'نمایشگاه') }}</x-nx::button>
                    <x-nx::button size="sm" variant="ghost" icon="sparkles" href="/blocks/text" wire:navigate>{{ $say('Blocks', 'بلوک‌ها') }}</x-nx::button>
                </div>
                <x-nx::language-menu :value="$locale" wire:model.live="locale" :label="$say('Language', 'زبان')" :languages="[
                    ['id' => 'fa', 'name' => 'فارسی', 'short' => 'FA'],
                    ['id' => 'en', 'name' => 'English', 'short' => 'EN'],
                ]" />
            </nav>

            <header style="display: grid; gap: .5rem">
                <div class="pg-row" style="gap: .75rem">
                    <h1 style="margin: 0; font: 700 var(--nx-text-3xl) / 1.2 var(--nx-font-display); letter-spacing: var(--nx-tracking-tight)">
                        {{ $say('Component demos', 'دموهای کامپوننت‌ها') }}
                    </h1>
                    <x-nx::badge tone="neutral">{{ NabuXUI::formatNumber($total) }} {{ $say('demos', 'دمو') }}</x-nx::badge>
                </div>
                <p style="margin: 0; max-inline-size: 46rem; color: var(--nx-text-muted)">
                    {{ $say(
                        'Every component on its own page, in real scenarios — search by name, filter by group, open a demo for the props and a copyable snippet.',
                        'هر کامپوننت در صفحهٔ خودش و در سناریوهای واقعی — با نام جست‌وجو کنید، گروه را فیلتر کنید و برای پراپ‌ها و تکه‌کد، دمو را باز کنید.',
                    ) }}
                </p>
            </header>

            <div class="pg-box" style="gap: 1.25rem">
                <x-nx::input :label="$say('Search demos', 'جست‌وجوی دموها')" icon="search" size="md"
                    :placeholder="$say('button, card, table…', 'دکمه، کارت، جدول…')"
                    wire:model.live.debounce.300ms="search" />
                <x-nx::chip-filter :options="$chipOptions" :counts="$chipCounts" wire:model.live="group" :label="$say('Group', 'گروه')" />
            </div>

            @if (count($demos) === 0)
                <section class="pg-box" wire:key="demos-empty">
                    <x-nx::empty-state icon="search" :title="$say('Nothing found', 'چیزی پیدا نشد')"
                        :description="$say('No demo matches this search in this group. Clear the filters and try another name.', 'دمویی با این جست‌وجو در این گروه نیست. فیلترها را پاک کنید و نام دیگری را امتحان کنید.')">
                        <x-slot:actions>
                            <x-nx::button variant="primary" icon="x" wire:click="resetFilters">{{ $say('Clear filters', 'پاک‌کردن فیلترها') }}</x-nx::button>
                        </x-slot:actions>
                    </x-nx::empty-state>
                </section>
            @else
                {{--
                    The grid runs without the reveal stagger: the reveal marks the
                    grid client-side (data-nx-revealed) and Livewire's morph strips
                    that attribute on every filter/search update, leaving the cards
                    permanently invisible — the observer has already unobserved.
                    Cards carry stable keys so the morph patches only what changed.
                --}}
                <x-nx::grid min="17rem" :stagger="false" wire:key="demos-grid">
                    @foreach ($demos as $demo)
                        <x-nx::card interactive wire:key="demo-{{ $demo['group'] }}-{{ $demo['slug'] }}"
                            :href="'/components/'.$demo['group'].'/'.$demo['slug']" wire:navigate
                            :icon="$demo['icon'] ?? 'grid'" :title="$demo['title']" :description="$demo['oneLiner']">
                            <x-slot:footer>
                                <div class="pg-row" style="gap: .5rem">
                                    <x-nx::badge tone="neutral" size="sm">{{ DemoCatalog::pick($groups[$demo['group']]['label']) }}</x-nx::badge>
                                    @if ($demo['js'])
                                        <x-nx::badge tone="info" size="sm">{{ $say('JS behaviour', 'رفتار JS') }}</x-nx::badge>
                                    @else
                                        <x-nx::badge tone="success" size="sm">{{ $say('Pure CSS', 'خالص CSS') }}</x-nx::badge>
                                    @endif
                                </div>
                            </x-slot:footer>
                        </x-nx::card>
                    @endforeach
                </x-nx::grid>
            @endif
        </div>
    </main>
</div>
