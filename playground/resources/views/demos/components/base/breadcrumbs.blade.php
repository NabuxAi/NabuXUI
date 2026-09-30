{{--
    Breadcrumbs on real ground: a docs manager deep in a folder tree and a
    settings page one level down. Separators mirror themselves in RTL.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Deep in the contract folder', 'عمیق در پوشهٔ قراردادها') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Every crumb before the last is a working link back up the tree; the current page states itself with aria-current.', 'هر بردکرامب جز آخری لینکِ کارکردن به بالای درخت است؛ صفحهٔ فعلی با aria-current خودش را معرفی می‌کند.') }}
        </p>
    </div>
    <x-nx::breadcrumbs :items="[
        ['label' => $say('Documents', 'اسناد'), 'href' => '/components'],
        ['label' => $say('Contracts', 'قراردادها'), 'href' => '/components'],
        ['label' => '1404', 'href' => '/components'],
        ['label' => 'Nabu — '.$say('master services', 'خدمات اصلی')],
    ]" />
    <div class="pg-row" style="justify-content: space-between; padding: .9rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg)">
        <span class="pg-row" style="gap: .6rem">
            <x-nx::icon name="file" />
            <strong dir="ltr" style="font: 500 var(--nx-text-sm) var(--nx-font-mono)">nabu-msa-1404.pdf</strong>
        </span>
        <span class="pg-row">
            <span style="color: var(--nx-text-subtle); font-size: var(--nx-text-sm)">{{ NabuXUI::formatNumber(2.4, 1) }} MB · {{ $say('signed', 'امضاشده') }}</span>
            <x-nx::button size="xs" variant="ghost" icon="copy" icon-only :aria-label="$say('Copy link', 'کپی لینک')" wire:click="ping('{{ $say('Link copied', 'لینک کپی شد') }}')" />
        </span>
    </div>
</section>

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('One level down, enough', 'یک سطح پایین، همین بس') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">{{ $say('Settings pages rarely go deeper than two crumbs — resist inventing a third.', 'صفحه‌های تنظیمات به‌ندرت از دو بردکرامب عمیق‌تر می‌شوند — سومی را از سر نسازید.') }}</p>
        <x-nx::breadcrumbs :items="[
            ['label' => $say('Settings', 'تنظیمات'), 'href' => '/components'],
            ['label' => $say('Notifications', 'اعلان‌ها')],
        ]" />
        <x-nx::switch :label="$say('Weekly digest', 'خلاصهٔ هفتگی')" wire:model="state.digest" />
    </section>

    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Where it sits in the page', 'جایش در صفحه') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">{{ $say('Between the page header and the content — never inside the h1 row.', 'بین هدر صفحه و محتوا — هیچ‌وقت داخل ردیف h1.') }}</p>
        <div style="display: grid; gap: .75rem; padding: 1rem; border: 1px dashed var(--nx-border-strong); border-radius: var(--nx-radius-lg)">
            <span style="font: 700 var(--nx-text-lg) var(--nx-font-display)">{{ $say('Team spaces', 'ورک‌اسپیس‌های تیم') }}</span>
            <x-nx::breadcrumbs :items="[
                ['label' => $say('Workspace', 'ورک‌اسپیس'), 'href' => '/components'],
                ['label' => $say('Spaces', 'فضاها'), 'href' => '/components'],
                ['label' => 'growth'],
            ]" />
            <p style="margin: 0; color: var(--nx-text-muted); font-size: var(--nx-text-sm)">{{ NabuXUI::formatNumber(12).' '.$say('members landed here from search this week.', 'عضو این هفته از جست‌وجو به این‌جا آمدند.') }}</p>
        </div>
    </section>
</div>
