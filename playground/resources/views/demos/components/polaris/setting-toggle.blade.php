{{--
    Polaris Setting Toggle as the store's settings form: whole cards whose
    description and helper copy rewrite themselves as the switch flips, one
    locked (disabled) card, and a variants row with the classic text-button
    control and the four raw switch states.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $lbl = [
        'smsOn' => $say('Customers receive an SMS with the tracking link the moment an order ships — 124 orders today.', 'مشتریان به‌محض ارسال سفارش، پیامک لینک رهگیری می‌گیرند — امروز برای ۱۲۴ سفارش.'),
        'smsOff' => $say('Off — customers only get emails, and only when they ask for them.', 'خاموش — مشتریان فقط ایمیل می‌گیرند و آن هم وقتی خودشان خواسته باشند.'),
        'shipOn' => $say('Orders over 500,000 tomans ship free; the badge shows on 68% of carts.', 'سفارش‌های بالای ۵۰۰ هزار تومان ارسال رایگان می‌گیرند؛ نشان روی ۶۸٪ سبدها دیده می‌شود.'),
        'shipOff' => $say('Shipping is always billed separately from the order total.', 'هزینهٔ ارسال همیشه جدا از مبلغ سفارش محاسبه می‌شود.'),
    ];
    $btn = ['goOn' => $say('Turn on', 'روشن کن'), 'goOff' => $say('Turn off', 'خاموش کن')];
    $lblJson = e(json_encode($lbl));
    $btnJson = e(json_encode($btn));
@endphp
<style>
    [x-cloak] { display: none !important; }
    .plst-root {
        --plst-surface: #FFFFFF; --plst-raised: #F6F6F6; --plst-text: #303030; --plst-subdued: #616161;
        --plst-border: #E3E3E3; --plst-strong: #8A8A8A; --plst-hover: color-mix(in srgb, var(--plst-text) 4%, var(--plst-surface));
        --plst-green: #008060; --plst-on-green: #FFFFFF; --plst-green-hover: #004C3F;
        --plst-tint: color-mix(in srgb, var(--plst-green) 9%, var(--plst-surface));
        --plst-focus: #005BD3;
        font-family: Inter, -apple-system, "Segoe UI", Roboto, system-ui, sans-serif;
        display: grid; gap: 1.5rem; justify-items: center;
    }
    html[data-theme="dark"] .plst-root {
        --plst-surface: #202020; --plst-raised: #2B2B2B; --plst-text: #F1F1F1; --plst-subdued: #B5B5B5;
        --plst-border: #454545; --plst-strong: #8A8A8A;
        --plst-green: #00A97F; --plst-on-green: #08211A; --plst-green-hover: #00BA93;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .plst-root {
            --plst-surface: #202020; --plst-raised: #2B2B2B; --plst-text: #F1F1F1; --plst-subdued: #B5B5B5;
            --plst-border: #454545; --plst-strong: #8A8A8A;
            --plst-green: #00A97F; --plst-on-green: #08211A; --plst-green-hover: #00BA93;
        }
    }
    .plst-card { inline-size: min(100%, 34rem); display: grid; gap: .55rem; }
    .plst-row { display: flex; align-items: center; gap: 1rem; padding: 1rem 1.1rem; border: 1px solid var(--plst-border); border-radius: 12px; background: var(--plst-surface); box-shadow: 0 1px 4px rgba(0, 0, 0, .07); }
    .plst-row[data-locked] { background: var(--plst-raised); box-shadow: none; }
    .plst-body { min-inline-size: 0; }
    .plst-body b { display: block; font: 600 .88rem/1.4 Inter, system-ui, sans-serif; color: var(--plst-text); }
    .plst-desc { margin: .15rem 0 0; font: 400 .8rem/1.55 Inter, system-ui, sans-serif; color: var(--plst-subdued); }
    .plst-row[data-locked] :is(b, .plst-desc) { color: color-mix(in srgb, var(--plst-text) 55%, var(--plst-surface)); }
    .plst-note { display: inline-flex; align-items: center; gap: .35rem; margin-block-start: .4rem; padding: .2rem .55rem; border-radius: 999px; background: var(--plst-tint); color: var(--plst-green); font: 500 .72rem/1.4 Inter, system-ui, sans-serif; }
    .plst-row[data-locked] .plst-note { background: var(--plst-hover); color: var(--plst-subdued); }
    .plst-switch { flex: none; position: relative; inline-size: 2.4rem; block-size: 1.35rem; border: none; border-radius: 999px; background: var(--plst-strong); cursor: pointer; transition: background .15s; }
    .plst-switch::after { content: ''; position: absolute; inset-block-start: .14rem; inset-inline-start: .14rem; inline-size: 1.07rem; aspect-ratio: 1; border-radius: 50%; background: #fff; box-shadow: 0 1px 3px rgba(0, 0, 0, .3); transition: translate .18s cubic-bezier(.2, 0, 0, 1); }
    html[dir="rtl"] .plst-switch::after { inset-inline-start: auto; inset-inline-end: .14rem; }
    .plst-switch[aria-checked="true"] { background: var(--plst-green); }
    html[dir="rtl"] .plst-switch[aria-checked="true"]::after { translate: -1.06rem 0; }
    html:not([dir="rtl"]) .plst-switch[aria-checked="true"]::after { translate: 1.06rem 0; }
    .plst-switch:hover[aria-checked="false"] { background: color-mix(in srgb, var(--plst-strong) 82%, var(--plst-text)); }
    .plst-switch:disabled { opacity: .38; cursor: not-allowed; }
    .plst-switch:focus-visible { outline: 2px solid var(--plst-focus); outline-offset: 2px; }
    .plst-link { flex: none; block-size: 2rem; padding-inline: .8rem; border: 1px solid transparent; border-radius: 8px; background: transparent; color: var(--plst-green); cursor: pointer; font: 500 .8rem/1 Inter, system-ui, sans-serif; }
    .plst-link:hover { border-color: var(--plst-border); background: var(--plst-hover); }
    .plst-link:focus-visible { outline: 2px solid var(--plst-focus); outline-offset: 2px; }
    .plst-flash { margin: 0; font: 400 .82rem/1.5 Inter, system-ui, sans-serif; color: var(--plst-green); }
    .plst-variants { inline-size: min(100%, 42rem); display: grid; gap: 1rem; justify-items: center; }
    .plst-vcell { display: flex; flex-wrap: wrap; gap: .6rem; justify-content: center; align-items: center; }
    .plst-vcell > small { inline-size: 100%; text-align: center; font: 500 .72rem/1 Inter, system-ui, sans-serif; color: var(--nx-text-muted); }
    .plst-mini { inline-size: 2.4rem; block-size: 1.35rem; border: none; border-radius: 999px; position: relative; pointer-events: none; }
    .plst-mini::after { content: ''; position: absolute; inset-block-start: .14rem; inset-inline-start: .14rem; inline-size: 1.07rem; aspect-ratio: 1; border-radius: 50%; background: #fff; box-shadow: 0 1px 3px rgba(0, 0, 0, .3); }
    .plst-mini[data-on] { background: var(--plst-green); }
    .plst-mini:not([data-on]) { background: var(--plst-strong); }
    html[dir="rtl"] .plst-mini[data-on]::after { inset-inline-start: auto; inset-inline-end: .14rem; }
    html:not([dir="rtl"]) .plst-mini[data-on]::after { inset-inline-start: 1.19rem; }
    .plst-mini:disabled { opacity: .38; }
    :where(.nx-js) .pg:has(.plst-root) .nx-data-table[data-nx-reveal] tbody tr {
        opacity: 1; translate: none;
    }
    @media (max-width: 480px) {
        .pg:has(.plst-root) .nx-data-table :is(th, td) {
            white-space: normal; padding-inline: .5rem; overflow-wrap: break-word;
        }
        .pg:has(.plst-root) pre code { white-space: pre-wrap; overflow-wrap: anywhere; }
    }
    @media (prefers-reduced-motion: reduce) {
        .plst-root * { transition-duration: .01ms !important; }
    }
</style>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Flip the switch, rewrite the card', 'کلید را بزن، متن کارت را عوض کن') }}</h3>
        <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
            {{ $say('Each card owns one setting: flipping the switch rewrites the description and helper copy with it. The locked card shows how Polaris marks what the plan does not include.', 'هر کارت یک تنظیم دارد: با زدن کلید، توضیح و متن راهنمای همان کارت بازنویسی می‌شود. کارت قفل‌شده نشان می‌دهد پولاریس چه‌چیز بیرون از پلن را چطور علامت می‌زند.') }}
        </p>
    </div>

    <div class="plst-root" style="inline-size: 100%"
        x-data="{
            fa: {{ $fa ? 'true' : 'false' }},
            lbl: {!! $lblJson !!},
            sms: true, ship: false, digest: true,
            fd(x) { return this.fa ? String(x).replace(/[0-9]/g, d => '۰۱۲۳۴۵۶۷۸۹'[+d]) : String(x) },
        }">
        <div class="plst-card">
            <div class="plst-row">
                <span class="plst-body">
                    <b>{{ $say('Order tracking by SMS', 'پیگیری سفارش با پیامک') }}</b>
                    <p class="plst-desc" x-text="sms ? lbl.smsOn : lbl.smsOff">{{ $say('Customers receive an SMS with the tracking link the moment an order ships — 124 orders today.', 'مشتریان به‌محض ارسال سفارش، پیامک لینک رهگیری می‌گیرند — امروز برای ۱۲۴ سفارش.') }}</p>
                </span>
                <button type="button" class="plst-switch" role="switch" x-bind:aria-checked="sms ? 'true' : 'false'" x-on:click="sms = !sms" aria-label="{{ $say('Order tracking by SMS', 'پیگیری سفارش با پیامک') }}"></button>
            </div>

            <div class="plst-row">
                <span class="plst-body">
                    <b>{{ $say('Free-shipping threshold', 'سقف ارسال رایگان') }}</b>
                    <p class="plst-desc" x-text="ship ? lbl.shipOn : lbl.shipOff">{{ $say('Shipping is always billed separately from the order total.', 'هزینهٔ ارسال همیشه جدا از مبلغ سفارش محاسبه می‌شود.') }}</p>
                </span>
                <button type="button" class="plst-switch" role="switch" x-bind:aria-checked="ship ? 'true' : 'false'" x-on:click="ship = !ship" aria-label="{{ $say('Free-shipping threshold', 'سقف ارسال رایگان') }}"></button>
            </div>

            <div class="plst-row" data-locked>
                <span class="plst-body">
                    <b>{{ $say('Automatic refunds', 'بازپرداخت خودکار') }}</b>
                    <p class="plst-desc">{{ $say('Refund approved orders without a manual gateway step.', 'سفارش‌های واجد شرایط را بدون گام دستی درگاه بازپرداخت کنید.') }}</p>
                    <span class="plst-note">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>
                        {{ $say('Not available on the Basic plan', 'در پلن پایه در دسترس نیست') }}
                    </span>
                </span>
                <button type="button" class="plst-switch" role="switch" aria-checked="false" disabled aria-label="{{ $say('Automatic refunds (locked)', 'بازپرداخت خودکار (قفل)') }}"></button>
            </div>
        </div>
    </div>
</section>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('The classic text control & raw states', 'کنترل متنی کلاسیک و حالت‌های خام') }}</h3>
        <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
            {{ $say('Older Polaris settings end the card with a plain text button that names the action it performs; the switch itself comes in four raw states.', 'ستینگ‌های قدیمی‌تر پولاریس کارت را با یک دکمهٔ متنی ساده می‌بندند که نام کنش بعدی را می‌گوید؛ خود سوییچ هم چهار حالت خام دارد.') }}
        </p>
    </div>
    <div class="plst-root plst-variants">
        <div class="plst-row" style="inline-size: min(100%, 30rem)"
            x-data="{ on: true, lbl: {!! $btnJson !!} }">
            <span class="plst-body">
                <b>{{ $say('Daily sales digest', 'خلاصهٔ روزانهٔ فروش') }}</b>
                <p class="plst-desc" x-text="on ? '{{ $say('Every evening at 6, a summary of today lands in your inbox.', 'هر شب ساعت ۶، خلاصهٔ امروز در صندوق شما می‌نشیند.') }}' : '{{ $say('No summary is sent; reports stay in Analytics.', 'خلاصه‌ای فرستاده نمی‌شود؛ گزارش‌ها در تحلیل‌ها می‌مانند.') }}'"></p>
            </span>
            <button type="button" class="plst-link" x-on:click="on = !on" x-text="on ? lbl.goOff : lbl.goOn">خاموش کن</button>
        </div>
        <div class="plst-vcell">
            <span class="plst-mini" data-on aria-hidden="true"></span>
            <span class="plst-mini" aria-hidden="true"></span>
            <span class="plst-mini" data-on disabled aria-hidden="true"></span>
            <span class="plst-mini" disabled aria-hidden="true"></span>
            <small>on · off · disabled on · disabled off</small>
        </div>
    </div>
</section>
