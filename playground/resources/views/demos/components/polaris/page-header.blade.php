{{--
    Polaris Page Header as the opening frame of the orders page: breadcrumb
    trail, oversized title with a live count badge, a green primary action
    that really answers, secondary actions and a working kebab menu; the
    variants row shows the back-button, avatar and inline-pagination heads.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $newFlash = e(json_encode($say('The new-order form opened', 'فرم سفارش تازه باز شد')));
@endphp
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
    [x-cloak] { display: none !important; }
    .plph-root {
        --plph-surface: #FFFFFF; --plph-raised: #F6F6F6; --plph-text: #303030; --plph-subdued: #616161;
        --plph-border: #E3E3E3; --plph-strong: #8A8A8A; --plph-hover: color-mix(in srgb, var(--plph-text) 4%, var(--plph-surface));
        --plph-green: #008060; --plph-on-green: #FFFFFF; --plph-green-hover: #004C3F;
        --plph-tint: color-mix(in srgb, var(--plph-green) 9%, var(--plph-surface));
        --plph-focus: #005BD3;
        font-family: 'Inter', 'Vazirmatn', sans-serif;
        display: grid; gap: 1.5rem; justify-items: center;
    }
    html[data-theme="dark"] .plph-root {
        --plph-surface: #202020; --plph-raised: #2B2B2B; --plph-text: #F1F1F1; --plph-subdued: #B5B5B5;
        --plph-border: #454545; --plph-strong: #8A8A8A;
        --plph-green: #00A97F; --plph-on-green: #08211A; --plph-green-hover: #00BA93;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .plph-root {
            --plph-surface: #202020; --plph-raised: #2B2B2B; --plph-text: #F1F1F1; --plph-subdued: #B5B5B5;
            --plph-border: #454545; --plph-strong: #8A8A8A;
            --plph-green: #00A97F; --plph-on-green: #08211A; --plph-green-hover: #00BA93;
        }
    }
    .plph-frame { position: relative; inline-size: min(100%, 40rem); display: grid; gap: .3rem; padding: 1rem 1.1rem 1.1rem; border: 1px solid var(--plph-border); border-radius: 12px; background: var(--plph-surface); box-shadow: 0 1px 4px rgba(0, 0, 0, .07); }
    .plph-crumbs { display: flex; align-items: center; gap: .35rem; font: 400 .76rem/1 Inter, system-ui, sans-serif; color: var(--plph-subdued); }
    .plph-crumbs a { color: inherit; text-decoration: none; border-radius: 5px; padding-inline: .1rem; }
    .plph-crumbs a:hover { color: var(--plph-text); text-decoration: underline; }
    .plph-crumbs svg { color: var(--plph-strong); }
    html[dir="rtl"] .plph-crumbs svg { scale: -1 1; }
    .plph-row { display: flex; flex-wrap: wrap; align-items: center; gap: .6rem .8rem; margin-block-start: .2rem; }
    .plph-title { margin: 0; display: inline-flex; align-items: center; gap: .55rem; font: 600 1.4rem/1.2 Inter, system-ui, sans-serif; color: var(--plph-text); }
    .plph-count { display: inline-grid; place-items: center; min-inline-size: 1.5rem; padding-inline: .35rem; block-size: 1.35rem; border-radius: 999px; background: var(--plph-tint); color: var(--plph-green); font: 600 .74rem/1 Inter, system-ui, sans-serif; font-variant-numeric: tabular-nums; }
    .plph-sub { margin: 0; font: 400 .8rem/1.5 Inter, system-ui, sans-serif; color: var(--plph-subdued); }
    .plph-actions { display: flex; flex-wrap: wrap; align-items: center; gap: .5rem; margin-inline-start: auto; }
    .plph-btn { display: inline-flex; align-items: center; gap: .4rem; block-size: 2.1rem; padding-inline: .9rem; border-radius: 8px; cursor: pointer; font: 500 .8rem/1 Inter, system-ui, sans-serif; }
    .plph-btn[data-primary] { border: none; background: var(--plph-green); color: var(--plph-on-green); }
    .plph-btn[data-primary]:hover { background: var(--plph-green-hover); }
    .plph-btn[data-secondary] { border: 1px solid var(--plph-strong); background: var(--plph-surface); color: var(--plph-text); }
    .plph-btn[data-secondary]:hover { background: var(--plph-hover); }
    .plph-btn:focus-visible { outline: 2px solid var(--plph-focus); outline-offset: 2px; }
    .plph-kebab { flex: none; display: grid; place-items: center; inline-size: 2.1rem; aspect-ratio: 1; border: 1px solid var(--plph-strong); border-radius: 8px; background: var(--plph-surface); color: var(--plph-text); cursor: pointer; }
    .plph-kebab:hover { background: var(--plph-hover); }
    .plph-kebab:focus-visible { outline: 2px solid var(--plph-focus); outline-offset: 2px; }
    .plph-menu { position: absolute; inset-inline-end: 1rem; inset-block-start: 3.4rem; z-index: 7; min-inline-size: 10.5rem; padding: .35rem; border: 1px solid var(--plph-border); border-radius: 10px; background: var(--plph-surface); box-shadow: 0 12px 30px -12px rgba(0, 0, 0, .3); }
    .plph-menu button { display: flex; inline-size: 100%; align-items: center; gap: .5rem; padding: .5rem .6rem; border: none; border-radius: 8px; background: transparent; color: var(--plph-text); cursor: pointer; font: 400 .8rem/1.2 Inter, system-ui, sans-serif; text-align: start; }
    .plph-menu button:hover { background: var(--plph-hover); }
    .plph-flash { margin: .3rem 0 0; font: 400 .78rem/1.4 Inter, system-ui, sans-serif; color: var(--plph-green); }
    .plph-back { display: grid; place-items: center; inline-size: 1.9rem; aspect-ratio: 1; border: 1px solid var(--plph-strong); border-radius: 50%; background: var(--plph-surface); color: var(--plph-text); cursor: pointer; }
    .plph-back:hover { background: var(--plph-hover); }
    html[dir="rtl"] .plph-back svg { scale: -1 1; }
    .plph-back:focus-visible, .plph-pg:focus-visible { outline: 2px solid var(--plph-focus); outline-offset: 2px; }
    .plph-ava { flex: none; display: grid; place-items: center; inline-size: 2.3rem; aspect-ratio: 1; border-radius: 50%; background: linear-gradient(140deg, #34d399, #008060); color: #fff; font: 600 .95rem/1 Inter, system-ui, sans-serif; }
    .plph-pg { display: inline-grid; place-items: center; inline-size: 1.8rem; aspect-ratio: 1; border: 1px solid var(--plph-strong); border-radius: 8px; background: var(--plph-surface); color: var(--plph-text); cursor: pointer; }
    .plph-pg:hover:not(:disabled) { background: var(--plph-hover); }
    .plph-pg:disabled { opacity: .4; cursor: not-allowed; }
    html[dir="rtl"] .plph-pg svg { scale: -1 1; }
    .plph-pos { font: 500 .78rem/1 Inter, system-ui, sans-serif; color: var(--plph-subdued); font-variant-numeric: tabular-nums; min-inline-size: 4.2rem; text-align: center; }
    .plph-variants { inline-size: min(100%, 40rem); display: grid; gap: 1rem; justify-items: center; }
    .plph-vcell { inline-size: 100%; display: grid; gap: .5rem; justify-items: center; }
    .plph-vcell > small { font: 500 .72rem/1 Inter, system-ui, sans-serif; color: var(--nx-text-muted); }
    :where(.nx-js) .pg:has(.plph-root) .nx-data-table[data-nx-reveal] tbody tr {
        opacity: 1; translate: none;
    }
    @media (max-width: 480px) {
        .pg:has(.plph-root) .nx-data-table :is(th, td) {
            white-space: normal; padding-inline: .5rem; overflow-wrap: break-word;
        }
        .pg:has(.plph-root) pre code { white-space: pre-wrap; overflow-wrap: anywhere; }
        .plph-actions { margin-inline-start: 0; }
    }
    @media (prefers-reduced-motion: reduce) {
        .plph-root * { transition-duration: .01ms !important; }
    }
</style>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The door of every admin page', 'درِ ورودی هر صفحهٔ ادمین') }}</h3>
        <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
            {{ $say('Breadcrumbs, an oversized title with a live count, the green primary action and secondary actions in one row. The primary action really answers, and the kebab menu opens for real.', 'بردکرامب، تایتل درشت با شمارش زنده، اکشن اولیهٔ سبز و اکشن‌های ثانویه در یک ردیف. اکشن اولیه واقعاً پاسخ می‌دهد و منوی سه‌نقطه واقعاً باز می‌شود.') }}
        </p>
    </div>

    <div class="plph-root" style="inline-size: 100%"
        x-data="{
            fa: {{ $fa ? 'true' : 'false' }},
            count: 32, menu: false, flash: '',
            newFlash: {!! $newFlash !!},
            fd(x) { return this.fa ? String(x).replace(/[0-9]/g, d => '۰۱۲۳۴۵۶۷۸۹'[+d]) : String(x) },
            create() { this.count++; this.flash = this.newFlash + ' — ' + this.fd(this.count); clearTimeout(this.t); this.t = setTimeout(() => this.flash = '', 2400) },
        }">
        <div class="plph-frame">
            <nav class="plph-crumbs" aria-label="{{ $say('Breadcrumbs', 'بردکرامب') }}">
                <a href="#" onclick="return false">{{ $say('Home', 'خانه') }}</a>
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 5 7 7-7 7"/></svg>
                <a href="#" onclick="return false">{{ $say('Shop', 'فروشگاه') }}</a>
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 5 7 7-7 7"/></svg>
                <span aria-current="page">{{ $say('Orders', 'سفارش‌ها') }}</span>
            </nav>
            <div class="plph-row">
                <h1 class="plph-title">{{ $say('Orders', 'سفارش‌ها') }} <span class="plph-count" x-text="fd(count)">{{ $say('32', '۳۲') }}</span></h1>
                <div class="plph-actions">
                    <button type="button" class="plph-btn" data-secondary>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3v12m0 0 4-4m-4 4-4-4"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2"/></svg>
                        {{ $say('Export', 'صادرات') }}
                    </button>
                    <button type="button" class="plph-btn" data-primary x-on:click="create()">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                        {{ $say('Create order', 'ایجاد سفارش') }}
                    </button>
                    <button type="button" class="plph-kebab" x-on:click="menu = !menu" x-bind:aria-expanded="menu ? 'true' : 'false'" aria-label="{{ $say('More actions', 'اقدام‌های بیشتر') }}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><circle cx="12" cy="5" r="1.7"/><circle cx="12" cy="12" r="1.7"/><circle cx="12" cy="19" r="1.7"/></svg>
                    </button>
                </div>
            </div>
            <p class="plph-sub" x-text="fd(count) + ' {{ $say('open orders · 5 awaiting payment · 3 fulfilling', 'سفارش باز · ۵ در انتظار پرداخت · ۳ در حال ارسال') }}'"></p>
            <div class="plph-menu" x-show="menu" x-cloak x-on:click.outside="menu = false" role="menu">
                <button type="button" role="menuitem" x-on:click="menu = false">{{ $say('View all orders', 'مشاهدهٔ همهٔ سفارش‌ها') }}</button>
                <button type="button" role="menuitem" x-on:click="menu = false">{{ $say('Draft orders', 'سفارش‌های پیش‌نویس') }}</button>
                <button type="button" role="menuitem" x-on:click="menu = false">{{ $say('Abandoned checkouts', 'سبدهای رهاشده') }}</button>
            </div>
            <p class="plph-flash" x-show="flash" x-cloak aria-live="polite" x-text="flash"></p>
        </div>
    </div>
</section>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('Back, avatar and in-header pagination', 'بازگشت، آواتار و صفحه‌بندی درون‌سربرگی') }}</h3>
        <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
            {{ $say('Deep pages swap the trail for a back button; resource headers carry the shop avatar; record headers step through «32 of 128» — and here the arrows really step.', 'صفحه‌های عمیق بردکرامب را با دکمهٔ بازگشت عوض می‌کنند؛ سربرگ منبع، آواتار فروشگاه دارد؛ سربرگ رکورد در «۳۲ از ۱۲۸» قدم می‌زند — و اینجا فلش‌ها واقعاً قدم برمی‌دارند.') }}
        </p>
    </div>
    <div class="plph-root plph-variants"
        x-data="{
            fa: {{ $fa ? 'true' : 'false' }}, pos: 32,
            fd(x) { return this.fa ? String(x).replace(/[0-9]/g, d => '۰۱۲۳۴۵۶۷۸۹'[+d]) : String(x) },
        }">
        <div class="plph-vcell">
            <div class="plph-frame" style="padding-block: .8rem">
                <div class="plph-row">
                    <button type="button" class="plph-back" aria-label="{{ $say('Back to orders', 'بازگشت به سفارش‌ها') }}"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5m6-6-6 6 6 6"/></svg></button>
                    <h1 class="plph-title" style="font-size: 1.15rem">{{ $say('Order #1001', 'سفارش #۱۰۰۱') }}</h1>
                    <span class="plph-actions">
                        <button type="button" class="plph-btn" data-secondary>{{ $say('Print', 'چاپ') }}</button>
                        <button type="button" class="plph-btn" data-primary>{{ $say('Fulfill', 'بسته‌بندی') }}</button>
                    </span>
                </div>
            </div>
            <small>back</small>
        </div>
        <div class="plph-vcell">
            <div class="plph-frame" style="padding-block: .8rem">
                <div class="plph-row">
                    <span class="plph-ava" aria-hidden="true">{{ $say('N', 'ن') }}</span>
                    <div style="min-inline-size: 0">
                        <h1 class="plph-title" style="font-size: 1.05rem">{{ $say('Nabu Home & Living', 'نابو خانه و زندگی') }}</h1>
                        <p class="plph-sub" style="margin: 0">{{ $say('myshop.nabu.example', 'فروشگاه نمونهٔ نابو') }}</p>
                    </div>
                    <span class="plph-actions">
                        <button type="button" class="plph-btn" data-secondary>{{ $say('View storefront', 'مشاهدهٔ ویترین') }}</button>
                    </span>
                </div>
            </div>
            <small>avatar</small>
        </div>
        <div class="plph-vcell">
            <div class="plph-frame" style="padding-block: .8rem">
                <div class="plph-row">
                    <h1 class="plph-title" style="font-size: 1.15rem">{{ $say('Customers', 'مشتریان') }}</h1>
                    <span class="plph-actions" style="align-items: center">
                        <span class="plph-pos" x-text="fd(pos) + ' {{ $say('of', 'از') }} ' + fd(128)">{{ $say('32 of 128', '۳۲ از ۱۲۸') }}</span>
                        <button type="button" class="plph-pg" x-on:click="pos = Math.max(1, pos - 1)" x-bind:disabled="pos <= 1" aria-label="{{ $say('Previous', 'قبلی') }}"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 5l-7 7 7 7"/></svg></button>
                        <button type="button" class="plph-pg" x-on:click="pos = Math.min(128, pos + 1)" x-bind:disabled="pos >= 128" aria-label="{{ $say('Next', 'بعدی') }}"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 5l7 7-7 7"/></svg></button>
                    </span>
                </div>
            </div>
            <small>pagination</small>
        </div>
    </div>
</section>
