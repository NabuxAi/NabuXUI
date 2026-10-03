{{--
    Bottom sheets the way phone apps use them: a ride app's "choose a ride"
    sheet that opens at 40% (drag it to 90% to see every option; a quick
    flick down dismisses it), and a store's filter sheet whose apply button
    talks to the server. Drag the handle or the header.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $n = fn ($v) => \NabuXUI\NabuXUI::formatNumber($v);

    $rides = [
        ['id' => 'eco', 'name' => $say('Economy', 'اقتصادی'), 'eta' => 3, 'price' => 182000, 'seats' => 4],
        ['id' => 'plus', 'name' => $say('Comfort', 'راحت'), 'eta' => 5, 'price' => 236000, 'seats' => 4],
        ['id' => 'van', 'name' => $say('Van', 'ون'), 'eta' => 9, 'price' => 312000, 'seats' => 6],
        ['id' => 'bike', 'name' => $say('Motorbike courier', 'پیک موتوری'), 'eta' => 2, 'price' => 95000, 'seats' => 1],
        ['id' => 'women', 'name' => $say('Women drivers', 'رانندهٔ بانو'), 'eta' => 7, 'price' => 198000, 'seats' => 4],
    ];
@endphp
<style>
    .bs-map { position: relative; display: grid; place-items: center; gap: 1rem; min-block-size: 16rem; padding: 1.5rem; border-radius: var(--nx-radius-2xl); background:
        radial-gradient(circle at 30% 40%, var(--nx-accent-soft), transparent 40%),
        repeating-linear-gradient(90deg, var(--nx-surface-2) 0 1px, transparent 1px 48px),
        repeating-linear-gradient(0deg, var(--nx-surface-2) 0 1px, transparent 1px 48px), var(--nx-bg); border: 1px solid var(--nx-border); text-align: center; }
    .bs-ride { display: grid; grid-template-columns: auto 1fr auto; align-items: center; gap: .875rem; inline-size: 100%; padding: .875rem 1rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg); background: var(--nx-surface); color: var(--nx-text); font: inherit; text-align: start; cursor: pointer; transition: border-color var(--nx-dur-fast) var(--nx-ease-out), scale var(--nx-dur-fast) var(--nx-ease-out); }
    .bs-ride:hover { border-color: var(--nx-accent-border); }
    .bs-ride:active { scale: calc(1 - 0.04 * var(--nx-motion)); }
    .bs-ride:focus-visible { outline: 2px solid var(--nx-ring); outline-offset: 2px; }
    .bs-ride .nx-icon { inline-size: 1.5rem; block-size: 1.5rem; color: var(--nx-accent-text); }
    .bs-ride small { display: block; color: var(--nx-text-muted); }
    .bs-list { display: grid; gap: .625rem; margin: 0; padding: 0; list-style: none; }
</style>

<section class="bs-map" aria-labelledby="bs-ride-title">
    <div style="display: grid; gap: .5rem">
        <h3 class="pg-title" id="bs-ride-title" style="margin: 0">{{ $say('Where to?', 'کجا می‌روید؟') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">{{ $say('Azadi Square → Tajrish', 'میدان آزادی ← تجریش') }}</p>
    </div>
    <x-nx::button variant="primary" icon="zap" x-data x-on:click="$dispatch('nx-open-sheet', 'bs-rides')">{{ $say('Choose a ride', 'انتخاب سرویس') }}</x-nx::button>
</section>

<x-nx::bottom-sheet id="bs-rides" :title="$say('Choose a ride', 'انتخاب سرویس')" :snaps="[0.4, 0.9]">
    <ul class="bs-list">
        @foreach ($rides as $ride)
            <li>
                <button type="button" class="bs-ride" x-data x-on:click="$wire.ping(@js($say('Requested: ', 'درخواست شد: ').$ride['name'])); $dispatch('nx-close-sheet')">
                    {{ \NabuXUI\NabuXUI::icon($ride['id'] === 'bike' ? 'zap' : 'users') }}
                    <span><strong>{{ $ride['name'] }}</strong><small>{{ $say($ride['eta'].' min away · '.$ride['seats'].' seats', $n($ride['eta']).' دقیقه تا شما · '.$n($ride['seats']).' نفر') }}</small></span>
                    <strong>{{ $n($ride['price']) }} {{ $say('IRR', 'ریال') }}</strong>
                </button>
            </li>
        @endforeach
    </ul>
</x-nx::bottom-sheet>

<section class="pg-box" style="gap: .75rem" aria-labelledby="bs-filter-title">
    <div class="pg-row" style="justify-content: space-between">
        <h3 class="pg-title" id="bs-filter-title" style="margin: 0">{{ $say('Running shoes', 'کفش دویدن') }} <small style="color: var(--nx-text-muted); font-weight: 400">· {{ $n(214) }} {{ $say('items', 'کالا') }}</small></h3>
        <x-nx::button size="sm" icon="sliders" x-data x-on:click="$dispatch('nx-open-sheet', 'bs-filters')">{{ $say('Filters', 'فیلترها') }}</x-nx::button>
    </div>
    <p style="margin: 0; color: var(--nx-text-muted); font-size: var(--nx-text-sm)">{{ $say('Focus the handle and use ↑/↓ to move between heights; Escape or the backdrop closes it.', 'روی دستگیره فوکوس کنید و با ↑/↓ ارتفاع را عوض کنید؛ Escape یا پس‌زمینه می‌بندد.') }}</p>
</section>

<x-nx::bottom-sheet id="bs-filters" :title="$say('Filters', 'فیلترها')" :snaps="[0.55, 0.9]">
    <div style="display: grid; gap: 1.25rem">
        <x-nx::field :label="$say('Size', 'سایز')">
            <x-nx::segmented :options="['40' => $n(40), '41' => $n(41), '42' => $n(42), '43' => $n(43), '44' => $n(44)]" value="42" />
        </x-nx::field>
        <x-nx::checkbox :label="$say('In stock only', 'فقط موجود')" checked />
        <x-nx::checkbox :label="$say('Free delivery', 'ارسال رایگان')" />
        <x-nx::switch :label="$say('On sale', 'حراج')" />
        <x-nx::button variant="primary" block wire:click="save(@js($say('Filters applied', 'فیلترها اعمال شد')))">{{ $say('Show 214 shoes', 'نمایش ۲۱۴ کفش') }}</x-nx::button>
    </div>
</x-nx::bottom-sheet>
