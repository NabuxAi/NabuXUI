{{--
    Carbon's selectable tiles at a checkout: a single-select radio group for
    the support plan and a multi-select checkbox group for add-ons whose total
    re-totals live in Persian digits; the variants row compares radio, checkbox,
    clickable and icon-carrying tiles.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;
@endphp
<style>
    .cbtl-root {
        --cbtl-accent: #0f62fe; --cbtl-accent-hover: #0353e9;
        --cbtl-text: #161616; --cbtl-text-secondary: #525252;
        --cbtl-border: #e0e0e0; --cbtl-border-strong: #8d8d8d;
        --cbtl-layer: #f4f4f4; --cbtl-layer-hover: #e8e8e8;
        --cbtl-font: 'IBM Plex Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        font-family: var(--cbtl-font);
        display: grid; gap: 2rem; justify-items: center;
    }
    html[data-theme="dark"] .cbtl-root {
        --cbtl-accent: #4589ff; --cbtl-accent-hover: #78a9ff;
        --cbtl-text: #f4f4f4; --cbtl-text-secondary: #c6c6c6;
        --cbtl-border: #393939; --cbtl-border-strong: #8d8d8d;
        --cbtl-layer: #262626; --cbtl-layer-hover: #333333;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .cbtl-root {
            --cbtl-accent: #4589ff; --cbtl-accent-hover: #78a9ff;
            --cbtl-text: #f4f4f4; --cbtl-text-secondary: #c6c6c6;
            --cbtl-border: #393939; --cbtl-border-strong: #8d8d8d;
            --cbtl-layer: #262626; --cbtl-layer-hover: #333333;
        }
    }
    .cbtl-grid { display: grid; gap: 1rem; grid-template-columns: repeat(3, minmax(10rem, 1fr)); inline-size: min(100%, 44rem); }
    .cbtl, .cbtl-link { position: relative; display: grid; align-content: start; gap: .25rem; padding: 1rem;
                        border: 1px solid var(--cbtl-border); background: var(--cbtl-layer); color: var(--cbtl-text);
                        font: 400 .875rem/1.4 var(--cbtl-font); transition: background-color .11s ease-in, border-color .11s ease-in; }
    .cbtl { cursor: pointer; }
    .cbtl:hover, .cbtl-link:hover { background: var(--cbtl-layer-hover); }
    .cbtl:focus-within, .cbtl-link:focus-visible { outline: 2px solid var(--cbtl-accent); outline-offset: 1px; }
    .cbtl input { position: absolute; inline-size: 1px; block-size: 1px; margin: -1px; opacity: 0; pointer-events: none; }
    .cbtl:has(input:checked) { border-color: var(--cbtl-accent); box-shadow: inset 0 0 0 1px var(--cbtl-accent); }
    .cbtl-check { position: absolute; inset-block-start: 0; inset-inline-end: 0; display: grid; place-items: center;
                  inline-size: 1.375rem; block-size: 1.375rem; background: var(--cbtl-accent); color: #fff;
                  visibility: hidden; }
    .cbtl:has(input:checked) .cbtl-check { visibility: visible; }
    .cbtl b { font-weight: 600; }
    .cbtl small { font-size: .75rem; color: var(--cbtl-text-secondary); }
    .cbtl-price { font: 600 1.125rem/1.2 var(--cbtl-font); }
    .cbtl-link { justify-items: start; text-decoration: none; }
    .cbtl-link svg { inline-size: 1.25rem; block-size: 1.25rem; color: var(--cbtl-accent); }
    .cbtl-total { display: flex; flex-wrap: wrap; align-items: baseline; gap: .5rem 1rem; inline-size: min(100%, 44rem);
                  margin-block-start: .25rem; padding: .75rem 1rem; background: var(--cbtl-layer);
                  border-block-start: 2px solid var(--cbtl-accent); }
    .cbtl-total b { font: 600 1.25rem/1.3 var(--cbtl-font); }
    .cbtl-legend { margin: 0 0 .75rem; font-size: .875rem; font-weight: 600; letter-spacing: .32px; color: var(--cbtl-text); }
    .cbtl-spec { display: grid; gap: 1.5rem; justify-items: center; inline-size: 100%; }
    .cbtl-spec-row { display: flex; flex-wrap: wrap; gap: 1.25rem; justify-content: center; align-items: stretch; }
    .cbtl-spec-cell { display: grid; gap: .5rem; }
    .cbtl-spec-cell > small { font-size: .72rem; color: var(--nx-text-muted); }
    .cbtl-spec .cbtl, .cbtl-spec .cbtl-link { inline-size: 11rem; }
    /* The shared "Important props" table below the stage reveals its rows on
       scroll (opacity: 0 until [data-nx-revealed]); a full-page capture never
       scrolls. Pin this page's table rows visible — scoped through
       :has(.cbtl-root), so it never reaches another demo page. */
    :where(.nx-js) .pg:has(.cbtl-root) .nx-data-table[data-nx-reveal] tbody tr { opacity: 1; translate: none; }
    @media (max-width: 640px) {
        .cbtl-grid { grid-template-columns: 1fr; }
        .pg:has(.cbtl-root) .nx-data-table :is(th, td) { white-space: normal; padding-inline: .5rem; overflow-wrap: break-word; }
    }
    @media (prefers-reduced-motion: reduce) {
        .cbtl-root * { transition-duration: .01ms !important; animation-duration: .01ms !important; }
    }
</style>

<div class="cbtl-root"
    x-data="{
        plan: 'growth',
        plans: {
            start:  { name: '{{ $say('Starter', 'پایه') }}',   price: 0 },
            growth: { name: '{{ $say('Growth', 'رشد') }}',     price: 240 },
            scale:  { name: '{{ $say('Scale', 'مقیاس') }}',    price: 590 },
        },
        addons: { backup: false, cdn: false, monitor: true },
        addonPrices: { backup: 60, cdn: 45, monitor: 35 },
        money(v) { return v.toLocaleString('fa-IR') },
        get total() { return this.plans[this.plan].price + Object.entries(this.addons).reduce((s, [k, on]) => s + (on ? this.addonPrices[k] : 0), 0) },
    }">
    <section class="pg-box" style="justify-items: center">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The tile is the checkbox', 'کاشی، خودِ چک‌باکس است') }}</h3>
            <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
                {{ $say('Click anywhere on a tile — the hidden input follows, the border inverts to carbon blue and the corner check snaps on. The plan group is single-select; add-ons stack.', 'هر جای کاشی کلیک کنید — ورودیِ پنهان همراه می‌شود، حاشیه به آبی کربن وارون می‌شود و تیکِ گوشه می‌نشیند. گروه پلن تک‌انتخابی است؛ افزودنی‌ها روی هم سوار می‌شوند.') }}
            </p>
        </div>

        <div style="inline-size: min(100%, 44rem)">
            <p class="cbtl-legend">{{ $say('Support plan', 'پلن پشتیبانی') }}</p>
            <div class="cbtl-grid" role="radiogroup" aria-label="{{ $say('Support plan', 'پلن پشتیبانی') }}">
                <label class="cbtl">
                    <input type="radio" name="cbtl-plan" value="start" x-model="plan">
                    <span class="cbtl-check" aria-hidden="true"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12l6 6L20 6"/></svg></span>
                    <b>{{ $say('Starter', 'پایه') }}</b>
                    <small>{{ $say('Community support · best effort', 'پشتیبانی اجتماعی · در حد تلاش') }}</small>
                    <span class="cbtl-price" x-text="plans.start.price ? money(plans.start.price) : 0">۰</span>
                </label>
                <label class="cbtl">
                    <input type="radio" name="cbtl-plan" value="growth" x-model="plan" checked>
                    <span class="cbtl-check" aria-hidden="true"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12l6 6L20 6"/></svg></span>
                    <b>{{ $say('Growth', 'رشد') }}</b>
                    <small>{{ $say('4-hour response · monthly report', 'پاسخ ۴ ساعته · گزارش ماهانه') }}</small>
                    <span class="cbtl-price" x-text="money(plans.growth.price)">۲۴۰</span>
                </label>
                <label class="cbtl">
                    <input type="radio" name="cbtl-plan" value="scale" x-model="plan">
                    <span class="cbtl-check" aria-hidden="true"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12l6 6L20 6"/></svg></span>
                    <b>{{ $say('Scale', 'مقیاس') }}</b>
                    <small>{{ $say('1-hour response · dedicated engineer', 'پاسخ ۱ ساعته · مهندس اختصاصی') }}</small>
                    <span class="cbtl-price" x-text="money(plans.scale.price)">۵۹۰</span>
                </label>
            </div>
        </div>

        <div style="inline-size: min(100%, 44rem)">
            <p class="cbtl-legend">{{ $say('Add-ons', 'افزودنی‌ها') }}</p>
            <div class="cbtl-grid" role="group" aria-label="{{ $say('Add-ons', 'افزودنی‌ها') }}">
                <label class="cbtl">
                    <input type="checkbox" value="backup" x-model="addons.backup">
                    <span class="cbtl-check" aria-hidden="true"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12l6 6L20 6"/></svg></span>
                    <b>{{ $say('Daily backup', 'پشتیبان‌گیری روزانه') }}</b>
                    <small>{{ $say('Keep 30 days of snapshots', 'نگه‌داشت ۳۰ روز اسنپ‌شات') }}</small>
                    <span class="cbtl-price">+<span x-text="money(addonPrices.backup)">۶۰</span></span>
                </label>
                <label class="cbtl">
                    <input type="checkbox" value="cdn" x-model="addons.cdn">
                    <span class="cbtl-check" aria-hidden="true"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12l6 6L20 6"/></svg></span>
                    <b>{{ $say('CDN', 'شبکهٔ توزیع محتوا') }}</b>
                    <small>{{ $say('12 edge locations', '۱۲ نقطهٔ لبه') }}</small>
                    <span class="cbtl-price">+<span x-text="money(addonPrices.cdn)">۴۵</span></span>
                </label>
                <label class="cbtl">
                    <input type="checkbox" value="monitor" x-model="addons.monitor">
                    <span class="cbtl-check" aria-hidden="true"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12l6 6L20 6"/></svg></span>
                    <b>{{ $say('Uptime monitor', 'پایش در دسترس‌بودن') }}</b>
                    <small>{{ $say('Checks every 30 seconds', 'بررسی هر ۳۰ ثانیه') }}</small>
                    <span class="cbtl-price">+<span x-text="money(addonPrices.monitor)">۳۵</span></span>
                </label>
            </div>
            <div class="cbtl-total" role="status">
                <span>{{ $say('Monthly total', 'جمع ماهانه') }}</span>
                <b x-text="money(total) + ' {{ $say('toman', 'تومان') }}'">۳۴۰ تومان</b>
                <small style="margin-inline-start: auto; color: var(--cbtl-text-secondary)">
                    {{ $say('VAT at ۱۰٪ is added at checkout.', 'ارزش افزودهٔ ۱۰٪ در پرداخت اضافه می‌شود.') }}
                </small>
            </div>
        </div>
    </section>
</div>

<section class="pg-box" style="justify-items: center">
    <div class="cbtl-root" style="inline-size: 100%">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('The whole family', 'همهٔ خانواده') }}</h3>
            <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
                {{ $say('Radio, checkbox, clickable and icon-carrying tiles — one flat frame, one corner check, one blue border.', 'رادیویی، چک‌باکسی، کلیک‌پذیر و آیکن‌دار — یک قاب تخت، یک تیکِ گوشه، یک حاشیهٔ آبی.') }}
            </p>
        </div>
        <div class="cbtl-spec">
        <div class="cbtl-spec-row">
            <div class="cbtl-spec-cell">
                <label class="cbtl">
                    <input type="radio" name="cbtl-v" checked>
                    <span class="cbtl-check" aria-hidden="true"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12l6 6L20 6"/></svg></span>
                    <b>{{ $say('Radio tile', 'کاشی رادیویی') }}</b>
                    <small>selection · single</small>
                </label>
                <small>selectable · radio</small>
            </div>
            <div class="cbtl-spec-cell">
                <label class="cbtl">
                    <input type="checkbox">
                    <span class="cbtl-check" aria-hidden="true"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12l6 6L20 6"/></svg></span>
                    <b>{{ $say('Checkbox tile', 'کاشی چک‌باکسی') }}</b>
                    <small>selection · multi</small>
                </label>
                <small>selectable · checkbox</small>
            </div>
            <div class="cbtl-spec-cell">
                <a class="cbtl-link" href="/components/carbon/selectable-tile">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 17L17 7"/><path d="M9 7h8v8"/></svg>
                    <b>{{ $say('Clickable tile', 'کاشی کلیک‌پذیر') }}</b>
                    <small>whole surface is the link</small>
                </a>
                <small>clickable</small>
            </div>
            <div class="cbtl-spec-cell">
                <label class="cbtl">
                    <input type="checkbox">
                    <span class="cbtl-check" aria-hidden="true"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12l6 6L20 6"/></svg></span>
                    <b>{{ $say('With an icon', 'با آیکن') }}</b>
                    <svg style="inline-size: 1.5rem; block-size: 1.5rem; color: var(--cbtl-accent)" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3v18"/><path d="M5 8l7-5 7 5"/><path d="M5 16l7 5 7-5"/></svg>
                </label>
                <small>icon</small>
            </div>
        </div>
        </div>
    </div>
</section>
