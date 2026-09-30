{{--
    The promo bar as a shop announcement: a rotated sticker, the discount in
    Persian digits, an arrow link to a real page, and a dismissal that folds
    the strip away (grid 1fr → 0fr) and is remembered for the session. The
    second bar never remembers — perfect for pages that must always announce.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The autumn sale strip', 'نوار حراج پاییزه') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Close it: the strip folds away in one motion and stays gone for this session (persist is the default). The reset button clears that memory.', 'بستهش کنید: نوار با یک حرکت جمع می‌شود و تا پایان این نشست غیب می‌ماند (پیش‌فرض persist است). دکمهٔ بازنشانی این حافظه را پاک می‌کند.') }}
        </p>
    </div>
    <div style="display: grid; gap: .75rem">
        <x-nx::promo-bar id="menus-autumn" badge="{{ $say('NEW', 'جدید') }}" href="/components"
            :link-label="$say('See the deals', 'دیدن تخفیف‌ها')"
            x-on:nx-dismiss="ping(@js($say('Dismissed — reload to see it remembered gone', 'بسته شد — بارگذاری دوباره را ببینید: به‌یاد مانده')))">
            {{ $say('Autumn sale — up to '.NabuXUI::formatNumber(70).'% off every template, this week only', 'حراج پاییزه — تا '.NabuXUI::formatNumber(70).'٪ تخفیف روی همهٔ قالب‌ها، فقط همین هفته') }}
        </x-nx::promo-bar>

        <div class="pg-row">
            <x-nx::button size="sm" variant="ghost" icon="sparkles"
                x-data
                @click="sessionStorage.removeItem('nabuxui.promo.menus-autumn'); $wire.ping(@js($say('Forgotten — reload to see it back', 'فراموش شد — بارگذاری دوباره، نوار برمی‌گردد')))">
                {{ $say('Reset the dismissal', 'بازنشانی حافظهٔ بستن') }}
            </x-nx::button>
        </div>
    </div>
</section>

<section class="pg-box" style="gap: 1rem">
    <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The one that always comes back', 'نواره‌ای که همیشه برمی‌گردد') }}</h3>
    <p style="margin: 0; color: var(--nx-text-muted)">
        {{ $say('persist="false" drops the memory — right for maintenance banners that must survive every reload. badge={false} removes the sticker.', 'با persist="false" حافظه کنار می‌رود — برای بنرهای نگهداری که باید در هر بارگذاری حاضر باشند. badge={false} استیکر را حذف می‌کند.') }}
    </p>
    <x-nx::promo-bar id="menus-maintenance" :persist="false" :badge="false" href="/components" :link-label="$say('Status page', 'صفحهٔ وضعیت')"
        x-on:nx-dismiss="ping(@js($say('It folds away — and returns on reload', 'جمع می‌شود — و با بارگذاری دوباره برمی‌گردد')))">
        {{ $say('Maintenance window tonight, 02:00–02:30 — brief read-only minutes', 'پنجرهٔ نگهداری امشب، ۰۲:۰۰ تا ۰۲:۳۰ — چند دقیقه فقط-خواندنی') }}
    </x-nx::promo-bar>
</section>
