{{--
    The icon where it earns its keep: a document manager's file list (the type
    glyph is decorative beside its text, the sync status is an img with a
    label), a project-icon picker over the whole core set — native radios, so
    the keyboard works — and the two rules people ask about: directional
    arrows mirror themselves on RTL pages, and size/colour ride the text.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    // Scenario 1 — the document manager's recent files
    $statusWord = [
        'synced' => $say('Synced', 'هم‌گام‌شده'),
        'failed' => $say('Upload failed', 'بارگذاری ناموفق'),
    ];
    $files = [
        ['icon' => 'folder', 'name' => $say('Invoices 1404', 'فاکتورهای ۱۴۰۴'), 'meta' => $say('24 files', '۲۴ پرونده'), 'status' => null,
            'action' => ['icon' => 'chevron-right', 'msg' => $say('Opened Invoices 1404', 'پوشهٔ فاکتورهای ۱۴۰۴ باز شد')]],
        ['icon' => 'file', 'name' => $say('Contract — Nabu.pdf', 'قرارداد — نابو.pdf'), 'meta' => $say('2.1 MB · yesterday', '۲٫۱ مگابایت · دیروز'), 'status' => 'synced',
            'action' => ['icon' => 'external-link', 'msg' => $say('Contract — Nabu.pdf opened', 'قرارداد — نابو.pdf باز شد')]],
        ['icon' => 'image', 'name' => $say('Store banner.png', 'بنر فروشگاه.png'), 'meta' => $say('840 KB · 2 days ago', '۸۴۰ کیلوبایت · ۲ روز پیش'), 'status' => 'synced',
            'action' => ['icon' => 'external-link', 'msg' => $say('Store banner.png opened', 'بنر فروشگاه.png باز شد')]],
        ['icon' => 'music', 'name' => $say('Podcast intro.mp3', 'تیتراژ پادکست.mp3'), 'meta' => $say('4.7 MB · 3 days ago', '۴٫۷ مگابایت · ۳ روز پیش'), 'status' => 'failed',
            'action' => ['icon' => 'upload', 'msg' => $say('Retrying Podcast intro.mp3…', 'تلاش دوباره برای تیتراژ پادکست…')]],
        ['icon' => 'paperclip', 'name' => $say('Voice note from support', 'یادداشت صوتی پشتیبانی'), 'meta' => $say('1.2 MB · last week', '۱٫۲ مگابایت · هفتهٔ پیش'), 'status' => 'synced',
            'action' => ['icon' => 'external-link', 'msg' => $say('Voice note opened', 'یادداشت صوتی باز شد')]],
    ];

    // Scenario 2 — the project icon picker, over the whole core set
    $icons = NabuXUI::icons();
    $chosen = $state['projectIcon'] ?? 'sparkles';
    $savedMsg = $say('Project icon saved', 'آیکون پروژه ذخیره شد').': '.$chosen;

    // Scenario 3 — the size ladder and the tint row
    $steps = [
        ['sm', 'var(--nx-text-sm)'], ['md', 'var(--nx-text-md)'], ['lg', 'var(--nx-text-lg)'],
        ['xl', 'var(--nx-text-xl)'], ['2xl', 'var(--nx-text-2xl)'],
    ];
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The document manager’s file list', 'فهرست پرونده‌های مدیریت اسناد') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The type glyph sits beside its text, so it is decorative (aria-hidden) and follows the row’s size and colour. The sync status carries meaning on its own — there it takes a label and renders as role=img, and the word stays next to it so colour is never the only cue. The folder’s “go deeper” chevron is directional: it flips by itself on this RTL page.', 'نماد نوع کنار متنش می‌نشیند، پس تزئینی است (aria-hidden) و از اندازه و رنگ ردیف پیروی می‌کند. وضعیت هم‌گام‌سازی خودش پیام را می‌رساند — همان‌جا برچسب می‌گیرد و به role=img تبدیل می‌شود، و واژه‌اش هم کنارش می‌ماند تا رنگ تنها نشانه نباشد. شِورانِ «ورود به پوشه» جهت‌دار است: روی همین صفحهٔ راست‌به‌چپ خودش برمی‌گردد.') }}
        </p>
    </div>
    <ul style="list-style: none; margin: 0; padding: 0; display: grid; gap: .5rem">
        @foreach ($files as $file)
            <li class="pg-row" style="justify-content: space-between; padding: .6rem .9rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg)">
                <span class="pg-row" style="gap: .75rem">
                    <span style="font-size: var(--nx-text-lg); color: var(--nx-text-muted)" aria-hidden="true"><x-nx::icon :name="$file['icon']" /></span>
                    <span style="display: grid">
                        <strong style="font-weight: 600">{{ $file['name'] }}</strong>
                        <span style="color: var(--nx-text-subtle); font-size: var(--nx-text-sm)">{{ $file['meta'] }}</span>
                    </span>
                </span>
                <span class="pg-row" style="gap: .75rem">
                    @if ($file['status'])
                        <span class="pg-row" style="gap: .35rem; color: {{ $file['status'] === 'failed' ? 'var(--nx-danger)' : 'var(--nx-success)' }}">
                            <x-nx::icon :name="$file['status'] === 'failed' ? 'alert-triangle' : 'check-circle'" :label="$statusWord[$file['status']]" />
                            <span style="font-size: var(--nx-text-sm)">{{ $statusWord[$file['status']] }}</span>
                        </span>
                    @endif
                    <x-nx::button size="sm" variant="ghost" :icon="$file['action']['icon']" icon-only
                        :aria-label="$file['action']['msg']" wire:click="ping('{{ $file['action']['msg'] }}')" />
                </span>
            </li>
        @endforeach
    </ul>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Picking the project’s icon', 'انتخاب آیکون پروژه') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The whole core set — '.NabuXUI::formatNumber(count($icons)).' icons — as a real picker: native radios under each tile, so arrow keys and Space work and the checked tile is announced. The name under every icon is its API name, ready to copy into code.', 'کل مجموعهٔ هسته — '.NabuXUI::formatNumber(count($icons)).' آیکون — به‌شکل یک انتخاب‌گر واقعی: زیر هر کاشی یک رادیوی بومی است، پس فلش‌ها و Space کار می‌کنند و کاشی انتخاب‌شده اعلام می‌شود. نام زیر هر آیکون همان نام API است؛ آمادهٔ کپی در کد.') }}
        </p>
    </div>
    <fieldset class="pg-iconpick" style="border: 0; margin: 0; padding: 0">
        <legend class="nx-visually-hidden">{{ $say('Project icon', 'آیکون پروژه') }}</legend>
        <div class="pg-iconpick-scroll">
            @foreach ($icons as $iconName => $icon)
                <label class="pg-iconpick-tile" for="pg-iconpick-{{ $iconName }}">
                    <span class="pg-iconpick-check" aria-hidden="true"><x-nx::icon name="check" /></span>
                    <span style="font-size: var(--nx-text-lg)" aria-hidden="true"><x-nx::icon :name="$iconName" /></span>
                    <span class="pg-iconpick-name" dir="ltr">{{ $iconName }}</span>
                    <input type="radio" id="pg-iconpick-{{ $iconName }}" name="project-icon" value="{{ $iconName }}"
                        :checked="$chosen === $iconName" wire:click="$set('state.projectIcon', '{{ $iconName }}')" />
                </label>
            @endforeach
        </div>
    </fieldset>
    <div class="pg-row" style="justify-content: space-between">
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('Current pick:', 'انتخاب فعلی:') }}
            <strong style="color: var(--nx-text)"><code dir="ltr" style="font: 500 var(--nx-text-sm) / 1 var(--nx-font-mono)">{{ $chosen }}</code></strong>
        </p>
        <x-nx::button variant="primary" icon="check" wire:click="save('{{ $savedMsg }}')">{{ $say('Save icon', 'ذخیرهٔ آیکون') }}</x-nx::button>
    </div>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The two rules everyone asks about', 'دو قاعده‌ای که همه می‌پرسند') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Arrows and chevrons that point along the reading direction mirror themselves on RTL pages — “next” keeps pointing at the inline end without any extra class. And an icon has no size or colour of its own: it rides the text (1.15em) and takes currentColor, so both come from the parent.', 'فلش‌ها و شِوران‌هایی که در راستای خواندن اشاره می‌کنند در صفحه‌های راست‌به‌چپ خودشان آینه می‌شوند — «بعدی» بدون هیچ کلاس اضافه‌ای به انتهای سطر اشاره می‌کند. آیکون هم اندازه و رنگِ خودش را ندارد: با متن می‌آید (1.15em) و currentColor می‌گیرد، پس هر دو از والد می‌رسند.') }}
        </p>
    </div>
    <div class="pg-grid">
        <div style="display: grid; gap: .875rem">
            <strong style="font-weight: 600">{{ $say('Directional, self-mirroring', 'جهت‌دار، خودآینه') }}</strong>
            <ul style="list-style: none; margin: 0; padding: 0; display: flex; flex-wrap: wrap; gap: .6rem">
                @foreach (['arrow-left', 'arrow-right', 'chevron-left', 'chevron-right'] as $dirIcon)
                    <li style="display: grid; gap: .35rem; justify-items: center; min-inline-size: 6rem; padding: .6rem .75rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg)">
                        <span style="font-size: var(--nx-text-xl)" aria-hidden="true"><x-nx::icon :name="$dirIcon" /></span>
                        <code dir="ltr" style="font: 500 var(--nx-text-xs) / 1.2 var(--nx-font-mono); color: var(--nx-text-muted)">{{ $dirIcon }}</code>
                    </li>
                @endforeach
            </ul>
            <p style="margin: 0; color: var(--nx-text-subtle); font-size: var(--nx-text-sm)">
                {{ $say('On this page dir is '.($fa ? 'rtl' : 'ltr').', so arrow-right points at the inline end — flip the language and watch them turn.', 'در این صفحه dir برابر '.($fa ? 'rtl' : 'ltr').' است، پس arrow-right به انتهای سطر اشاره می‌کند — زبان را عوض کنید و بگردند.') }}
            </p>
        </div>
        <div style="display: grid; gap: .875rem">
            <strong style="font-weight: 600">{{ $say('Sized by the text, tinted by the text', 'هم‌مقیاس با متن، هم‌رنگ با متن') }}</strong>
            <div style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: flex-end">
                @foreach ($steps as [$step, $textToken])
                    <span style="display: grid; gap: .4rem; justify-items: center">
                        <span style="font-size: {{ $textToken }}" aria-hidden="true"><x-nx::icon name="sparkles" /></span>
                        <code dir="ltr" style="font: 500 var(--nx-text-xs) / 1.2 var(--nx-font-mono); color: var(--nx-text-muted)">{{ $textToken }}</code>
                    </span>
                @endforeach
            </div>
            <div class="pg-row" style="gap: 1.25rem; font-size: var(--nx-text-xl)">
                <span style="color: var(--nx-gold)"><x-nx::icon name="star" :label="$say('Gold star', 'ستارهٔ طلایی')" /></span>
                <span style="color: var(--nx-accent-text)" aria-hidden="true"><x-nx::icon name="zap" /></span>
                <span style="color: var(--nx-success)" aria-hidden="true"><x-nx::icon name="check-circle" /></span>
                <span style="color: var(--nx-danger)" aria-hidden="true"><x-nx::icon name="heart" /></span>
            </div>
            <p style="margin: 0; color: var(--nx-text-subtle); font-size: var(--nx-text-sm)">
                {{ $say('star, play and stop are drawn filled; the rest are stroked. Without the component the helper prints the same svg — inline like this: ', 'استاره، پخش و توقف توپُر ترسیم می‌شوند؛ بقیه خطی. بدون کامپوننت هم هلپر همان svg را چاپ می‌کند — همین‌طور درون متن: ') }}{{ NabuXUI::icon('shield') }}
            </p>
        </div>
    </div>
</section>

<style>
    .pg-iconpick-scroll { display: grid; grid-template-columns: repeat(auto-fill, minmax(4.75rem, 1fr)); gap: .375rem; max-block-size: 17.5rem; overflow-y: auto; padding: .5rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg); background: var(--nx-surface-2); }
    .pg-iconpick-tile { position: relative; display: grid; gap: .3rem; justify-items: center; padding: .7rem .25rem .5rem; border: 1px solid transparent; border-radius: var(--nx-radius-md); color: var(--nx-text-muted); cursor: pointer; transition-property: border-color, background-color, color; transition-duration: var(--nx-dur-fast); transition-timing-function: var(--nx-ease-out); }
    .pg-iconpick-tile:hover { border-color: var(--nx-border); color: var(--nx-text); }
    @media (hover: hover) and (pointer: fine) {
        .pg-iconpick-tile:hover { translate: 0 calc(-2px * var(--nx-motion)); transition-property: border-color, background-color, color, translate; }
    }
    .pg-iconpick-tile input { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; margin: 0; opacity: 0; cursor: pointer; }
    .pg-iconpick-tile:has(input:checked) { border-color: var(--nx-accent-border); background: var(--nx-accent-soft); color: var(--nx-accent-text); }
    .pg-iconpick-tile:has(input:focus-visible) { outline: 2px solid var(--nx-ring); outline-offset: 2px; }
    .pg-iconpick-name { max-inline-size: 100%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font: 500 .625rem / 1.2 var(--nx-font-mono); }
    .pg-iconpick-check { position: absolute; inset-block-start: .3rem; inset-inline-end: .3rem; color: var(--nx-accent-text); opacity: 0; scale: .5 .5; transition-property: opacity, scale; transition-duration: var(--nx-dur-fast); transition-timing-function: var(--nx-ease-out); }
    .pg-iconpick-tile:has(input:checked) .pg-iconpick-check { opacity: 1; scale: 1 1; }
</style>
