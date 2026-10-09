{{--
    Carbon's expandable tiles as a region comparison grid: three tiles whose
    chevron spins 180° and whose body unfurls inside the same flat frame via a
    grid-rows transition; open/close-all drives every tile at once, and the
    variants row shows light, forced-open and flush states.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;
@endphp
<style>
    .cbex-root {
        --cbex-accent: #0f62fe; --cbex-accent-hover: #0353e9;
        --cbex-text: #161616; --cbex-text-secondary: #525252;
        --cbex-border: #e0e0e0; --cbex-border-strong: #8d8d8d;
        --cbex-layer: #f4f4f4; --cbex-layer-hover: #e8e8e8;
        --cbex-font: 'IBM Plex Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        font-family: var(--cbex-font);
        display: grid; gap: 2rem; justify-items: center;
    }
    html[data-theme="dark"] .cbex-root {
        --cbex-accent: #4589ff; --cbex-accent-hover: #78a9ff;
        --cbex-text: #f4f4f4; --cbex-text-secondary: #c6c6c6;
        --cbex-border: #393939; --cbex-border-strong: #8d8d8d;
        --cbex-layer: #262626; --cbex-layer-hover: #333333;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .cbex-root {
            --cbex-accent: #4589ff; --cbex-accent-hover: #78a9ff;
            --cbex-text: #f4f4f4; --cbex-text-secondary: #c6c6c6;
            --cbex-border: #393939; --cbex-border-strong: #8d8d8d;
            --cbex-layer: #262626; --cbex-layer-hover: #333333;
        }
    }
    .cbex-bar { display: flex; flex-wrap: wrap; gap: .75rem; align-items: center; }
    .cbex-toggle { block-size: 2.5rem; padding-inline: 1rem; border: 1px solid var(--cbex-border-strong);
                   background: transparent; color: var(--cbex-accent); font: 400 .875rem/1 var(--cbex-font);
                   cursor: pointer; transition: background-color .11s ease-in; }
    .cbex-toggle:hover { background: color-mix(in srgb, var(--cbex-accent) 8%, transparent); }
    .cbex-toggle:focus-visible { outline: 2px solid var(--cbex-accent); outline-offset: 1px; }
    .cbex-grid { display: grid; gap: 1rem; grid-template-columns: repeat(3, minmax(11rem, 1fr)); inline-size: min(100%, 44rem); }
    .cbex { display: grid; grid-template-rows: auto 0fr; border: 1px solid var(--cbex-border); background: var(--cbex-layer);
            transition: grid-template-rows 240ms cubic-bezier(.2, 0, 0, 1), border-color 240ms cubic-bezier(.2, 0, 0, 1); }
    .cbex[data-open='true'] { grid-template-rows: auto 1fr; border-color: var(--cbex-border-strong); }
    .cbex-head { display: flex; align-items: center; gap: .5rem; padding: .875rem 1rem; border: none; background: transparent;
                 color: var(--cbex-text); font: 600 .875rem/1.3 var(--cbex-font); cursor: pointer; text-align: start; }
    .cbex-head:hover { background: var(--cbex-layer-hover); }
    .cbex-head:focus-visible { outline: 2px solid var(--cbex-accent); outline-offset: -2px; }
    .cbex-head small { font-weight: 400; color: var(--cbex-text-secondary); }
    .cbex-chevron { margin-inline-start: auto; display: grid; place-items: center; inline-size: 1rem; block-size: 1rem;
                    color: var(--cbex-text); transition: rotate 240ms cubic-bezier(.2, 0, 0, 1); }
    .cbex[data-open='true'] .cbex-chevron { rotate: 180deg; }
    .cbex-body { overflow: hidden; padding-inline: 1rem; }
    .cbex[data-open='true'] .cbex-body { padding-block-end: 1rem; }
    .cbex-body dl { display: grid; gap: .375rem; margin: 0; padding-block-start: .25rem; font-size: .75rem; }
    .cbex-body div { display: flex; justify-content: space-between; gap: 1rem; padding-block: .375rem;
                     border-block-start: 1px solid var(--cbex-border); }
    .cbex-body dt { color: var(--cbex-text-secondary); }
    .cbex-body dd { margin: 0; font-weight: 600; color: var(--cbex-text); }
    .cbex-body dd[data-good] { color: #24a148; }
    .cbex-note { margin: 0; font-size: .8125rem; color: var(--nx-text-muted); max-width: 56ch; }
    .cbex-spec { display: grid; gap: 1.5rem; justify-items: center; inline-size: 100%; }
    .cbex-spec-row { display: flex; flex-wrap: wrap; gap: 1.25rem; justify-content: center; align-items: flex-start; }
    .cbex-spec-cell { display: grid; gap: .5rem; }
    .cbex-spec-cell > small { font-size: .72rem; color: var(--nx-text-muted); }
    .cbex-spec .cbex { inline-size: min(15rem, 78vw); }
    :where(.nx-js) .pg:has(.cbex-root) .nx-data-table[data-nx-reveal] tbody tr { opacity: 1; translate: none; }
    @media (max-width: 640px) {
        .cbex-grid { grid-template-columns: 1fr; }
        .pg:has(.cbex-root) .nx-data-table :is(th, td) { white-space: normal; padding-inline: .5rem; overflow-wrap: break-word; }
    }
    @media (prefers-reduced-motion: reduce) {
        .cbex-root * { transition-duration: .01ms !important; animation-duration: .01ms !important; }
    }
</style>

<div class="cbex-root"
    x-data="{
        open: { fra: false, dub: false, sin: true },
        setAll(v) { this.open = { fra: v, dub: v, sin: v } },
        get allOpen() { return Object.values(this.open).every(Boolean) },
    }">
    <section class="pg-box" style="justify-items: center">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('An accordion that lives in the grid', 'آکاردئونی که در گرید زندگی می‌کند') }}</h3>
            <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
                {{ $say('Click anywhere above the fold — the chevron spins 180° and the detail rows unfurl inside the same flat frame, growing the tile instead of pushing the page around.', 'هر جای نیمهٔ رویین کلیک کنید — شِوران ۱۸۰ درجه می‌چرخد و ردیف‌های جزئیات داخل همان قاب تخت رونده می‌شوند؛ کاشی قد می‌کشد، نه اینکه صفحه را جابه‌جا کند.') }}
            </p>
        </div>

        <div class="cbex-bar">
            <button type="button" class="cbex-toggle" x-on:click="setAll(!allOpen)" x-text="allOpen ? '{{ $say('Close all', 'بستن همه') }}' : '{{ $say('Open all', 'باز کردن همه') }}'">باز کردن همه</button>
        </div>

        <div class="cbex-grid">
            <div class="cbex" x-bind:data-open="open.fra">
                <button type="button" class="cbex-head" x-on:click="open.fra = !open.fra" x-bind:aria-expanded="open.fra.toString()">
                    <span>{{ $say('Frankfurt', 'فرانکفورت') }} <small>eu-de</small></span>
                    <span class="cbex-chevron" aria-hidden="true"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg></span>
                </button>
                <div class="cbex-body">
                    <dl>
                        <div><dt>{{ $say('Access zones', 'ناحیه‌های دسترس') }}</dt><dd>{{ $num('3') }}</dd></div>
                        <div><dt>{{ $say('Latency to Tehran', 'تأخیر تا تهران') }}</dt><dd data-good>{{ $num('24') }}ms</dd></div>
                        <div><dt>{{ $say('Price per core', 'بها هر هسته') }}</dt><dd>{{ $num('38') }}K</dd></div>
                        <div><dt>{{ $say('Free capacity', 'ظرفیت آزاد') }}</dt><dd>{{ $num('64') }} vCPU</dd></div>
                    </dl>
                </div>
            </div>
            <div class="cbex" x-bind:data-open="open.dub">
                <button type="button" class="cbex-head" x-on:click="open.dub = !open.dub" x-bind:aria-expanded="open.dub.toString()">
                    <span>{{ $say('Dublin', 'دوبلین') }} <small>eu-ie</small></span>
                    <span class="cbex-chevron" aria-hidden="true"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg></span>
                </button>
                <div class="cbex-body">
                    <dl>
                        <div><dt>{{ $say('Access zones', 'ناحیه‌های دسترس') }}</dt><dd>{{ $num('2') }}</dd></div>
                        <div><dt>{{ $say('Latency to Tehran', 'تأخیر تا تهران') }}</dt><dd>{{ $num('31') }}ms</dd></div>
                        <div><dt>{{ $say('Price per core', 'بها هر هسته') }}</dt><dd>{{ $num('41') }}K</dd></div>
                        <div><dt>{{ $say('Free capacity', 'ظرفیت آزاد') }}</dt><dd>{{ $num('18') }} vCPU</dd></div>
                    </dl>
                </div>
            </div>
            <div class="cbex" x-bind:data-open="open.sin">
                <button type="button" class="cbex-head" x-on:click="open.sin = !open.sin" x-bind:aria-expanded="open.sin.toString()">
                    <span>{{ $say('Singapore', 'سنگاپور') }} <small>ap-se</small></span>
                    <span class="cbex-chevron" aria-hidden="true"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg></span>
                </button>
                <div class="cbex-body">
                    <dl>
                        <div><dt>{{ $say('Access zones', 'ناحیه‌های دسترس') }}</dt><dd>{{ $num('3') }}</dd></div>
                        <div><dt>{{ $say('Latency to Tehran', 'تأخیر تا تهران') }}</dt><dd>{{ $num('118') }}ms</dd></div>
                        <div><dt>{{ $say('Price per core', 'بها هر هسته') }}</dt><dd>{{ $num('35') }}K</dd></div>
                        <div><dt>{{ $say('Free capacity', 'ظرفیت آزاد') }}</dt><dd>{{ $num('96') }} vCPU</dd></div>
                    </dl>
                </div>
            </div>
        </div>
        <p class="cbex-note">
            {{ $say('The body row animates from 0fr to 1fr — no measured heights, no janky max-height tricks; it is the same grid trick Carbon’s own tile uses.', 'ردیف بدنه از 0fr به 1fr انیمیت می‌شود — نه اندازه‌گیری ارتفاع و نه ترفند max-height؛ همان ترفند گریدی که کاشی خود کربن به کار می‌برد.') }}
        </p>
    </section>
</div>

<section class="pg-box" style="justify-items: center">
    <div class="cbex-root" style="inline-size: 100%">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('The whole family', 'همهٔ خانواده') }}</h3>
            <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
                {{ $say('Closed, forced-open and flush — the same frame at every state.', 'بسته، بازِ اجباری و flush — همان قاب در هر حالت.') }}
            </p>
        </div>
        <div class="cbex-spec">
        <div class="cbex-spec-row">
            <div class="cbex-spec-cell">
                <div class="cbex" data-open="false">
                    <button type="button" class="cbex-head" x-on:click="$el.closest('.cbex').dataset.open = $el.closest('.cbex').dataset.open === 'true' ? 'false' : 'true'">
                        <span>{{ $say('Closed', 'بسته') }}</span>
                        <span class="cbex-chevron" aria-hidden="true"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg></span>
                    </button>
                    <div class="cbex-body"><dl><div><dt>ping</dt><dd>{{ $num('12') }}ms</dd></div></dl></div>
                </div>
                <small>default</small>
            </div>
            <div class="cbex-spec-cell">
                <div class="cbex" data-open="true">
                    <button type="button" class="cbex-head" x-on:click="$el.closest('.cbex').dataset.open = $el.closest('.cbex').dataset.open === 'true' ? 'false' : 'true'">
                        <span>{{ $say('Open', 'باز') }}</span>
                        <span class="cbex-chevron" aria-hidden="true"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg></span>
                    </button>
                    <div class="cbex-body"><dl><div><dt>ping</dt><dd>{{ $num('12') }}ms</dd></div></dl></div>
                </div>
                <small>expanded</small>
            </div>
            <div class="cbex-spec-cell">
                <div class="cbex" data-open="true" style="border-inline: none; border-block-end: none">
                    <button type="button" class="cbex-head">
                        <span>{{ $say('Flush edges', 'لبه‌های flush') }}</span>
                        <span class="cbex-chevron" aria-hidden="true"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg></span>
                    </button>
                    <div class="cbex-body"><dl><div><dt>ping</dt><dd>{{ $num('12') }}ms</dd></div></dl></div>
                </div>
                <small>flush</small>
            </div>
        </div>
        </div>
    </div>
</section>
