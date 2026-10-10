{{--
    Polaris Banners as a merchant's morning report: four dismissible tones —
    informational, success, warning, critical — each with its status icon,
    heading, body and one action. Dismissing really removes a banner (with a
    soft leave transition) and the restore link brings the whole set back.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
    [x-cloak] { display: none !important; }
    .plbn-root {
        --plbn-surface: #FFFFFF; --plbn-raised: #F6F6F6; --plbn-text: #303030; --plbn-subdued: #616161;
        --plbn-border: #E3E3E3; --plbn-strong: #8A8A8A;
        --plbn-green: #008060; --plbn-on-green: #FFFFFF; --plbn-green-hover: #004C3F;
        --plbn-info: #2C6ECB; --plbn-critical: #D72C0D; --plbn-warn: #8A6116; --plbn-focus: #005BD3;
        font-family: 'Inter', 'Vazirmatn', sans-serif;
        display: grid; gap: 1.5rem; justify-items: center;
    }
    html[data-theme="dark"] .plbn-root {
        --plbn-surface: #202020; --plbn-raised: #2B2B2B; --plbn-text: #F1F1F1; --plbn-subdued: #B5B5B5;
        --plbn-border: #454545; --plbn-strong: #8A8A8A;
        --plbn-green: #00A97F; --plbn-on-green: #08211A; --plbn-green-hover: #00BA93;
        --plbn-info: #6EA6FF; --plbn-critical: #FF8D75; --plbn-warn: #FFC96B;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .plbn-root {
            --plbn-surface: #202020; --plbn-raised: #2B2B2B; --plbn-text: #F1F1F1; --plbn-subdued: #B5B5B5;
            --plbn-border: #454545; --plbn-strong: #8A8A8A;
            --plbn-green: #00A97F; --plbn-on-green: #08211A; --plbn-green-hover: #00BA93;
            --plbn-info: #6EA6FF; --plbn-critical: #FF8D75; --plbn-warn: #FFC96B;
        }
    }
    .plbn-stack { inline-size: min(100%, 34rem); display: grid; gap: .7rem; }
    .plbn { display: flex; align-items: flex-start; gap: .75rem; padding: .85rem .95rem; border-radius: 10px; border: 1px solid color-mix(in srgb, var(--plbn-tone) 32%, var(--plbn-border)); background: color-mix(in srgb, var(--plbn-tone) 7%, var(--plbn-surface)); }
    .plbn[data-tone="info"] { --plbn-tone: var(--plbn-info); }
    .plbn[data-tone="success"] { --plbn-tone: var(--plbn-green); }
    .plbn[data-tone="warning"] { --plbn-tone: var(--plbn-warn); }
    .plbn[data-tone="critical"] { --plbn-tone: var(--plbn-critical); }
    .plbn-icon { flex: none; margin-block-start: .1rem; color: var(--plbn-tone); }
    .plbn-body { min-inline-size: 0; }
    .plbn-body b { display: block; font: 600 .85rem/1.4 Inter, system-ui, sans-serif; color: var(--plbn-text); }
    .plbn-body p { margin: .15rem 0 0; font: 400 .8rem/1.55 Inter, system-ui, sans-serif; color: var(--plbn-subdued); }
    .plbn-act { display: inline-flex; align-items: center; gap: .3rem; margin-block-start: .45rem; border: none; background: transparent; color: var(--plbn-tone); cursor: pointer; padding: 0; font: 500 .78rem/1 Inter, system-ui, sans-serif; }
    .plbn-act:hover { text-decoration: underline; }
    .plbn-x { flex: none; display: grid; place-items: center; inline-size: 1.7rem; aspect-ratio: 1; margin-inline-start: auto; border: none; border-radius: 7px; background: transparent; color: var(--plbn-subdued); cursor: pointer; }
    .plbn-x:hover { background: color-mix(in srgb, var(--plbn-tone) 14%, transparent); color: var(--plbn-text); }
    .plbn-act:focus-visible, .plbn-x:focus-visible, .plbn-restore:focus-visible { outline: 2px solid var(--plbn-focus); outline-offset: 2px; }
    .plbn-restore { border: none; background: transparent; color: var(--plbn-green); cursor: pointer; font: 500 .8rem/1 Inter, system-ui, sans-serif; }
    .plbn-restore:hover { text-decoration: underline; }
    .plbn-variants { inline-size: min(100%, 40rem); display: grid; gap: .7rem; justify-items: center; }
    .plbn-vcell { display: grid; gap: .5rem; justify-items: center; }
    .plbn-vcell > small { font: 500 .72rem/1 Inter, system-ui, sans-serif; color: var(--nx-text-muted); }
    :where(.nx-js) .pg:has(.plbn-root) .nx-data-table[data-nx-reveal] tbody tr {
        opacity: 1; translate: none;
    }
    @media (max-width: 480px) {
        .pg:has(.plbn-root) .nx-data-table :is(th, td) {
            white-space: normal; padding-inline: .5rem; overflow-wrap: break-word;
        }
        .pg:has(.plbn-root) pre code { white-space: pre-wrap; overflow-wrap: anywhere; }
    }
    @media (prefers-reduced-motion: reduce) {
        .plbn-root * { animation-duration: .01ms !important; transition-duration: .01ms !important; }
    }
</style>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Morning report, four voices', 'گزارش صبح، چهار لحن') }}</h3>
        <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
            {{ $say('Each banner is the official Polaris tone — informational, success, warning, critical — with its icon, heading and one action. Dismiss one and it really leaves; the restore link brings them all back.', 'هر بنر یک تُن رسمی پولاریس است — اطلاع، موفق، هشدار، بحرانی — با آیکن، عنوان و یک کنش. یکی را ببندید تا واقعاً برود؛ لینک بازگردانی همه را برمی‌گرداند.') }}
        </p>
    </div>

    <div class="plbn-root" style="inline-size: 100%"
        x-data="{
            b: { info: true, success: true, warn: true, crit: true },
            get anyClosed() { return !this.b.info || !this.b.success || !this.b.warn || !this.b.crit },
            restore() { this.b = { info: true, success: true, warn: true, crit: true } },
        }">
        <div class="plbn-stack">
            <div class="plbn" data-tone="info" x-show="b.info" x-transition.opacity.duration.250ms role="status">
                <svg class="plbn-icon" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 11v5M12 8h.01"/></svg>
                <div class="plbn-body">
                    <b>{{ $say('A collaboration request arrived', 'یک درخواست همکاری رسید') }}</b>
                    <p>{{ $say('A boutique from Isfahan asked to carry your candle line in its spring shelf.', 'یک بوتیک در اصفهان خواسته خط شمع‌های شما را در قفسهٔ بهارش داشته باشد.') }}</p>
                    <button type="button" class="plbn-act">{{ $say('View request', 'مشاهدهٔ درخواست') }} <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg></button>
                </div>
                <button type="button" class="plbn-x" x-on:click="b.info = false" aria-label="{{ $say('Dismiss banner', 'بستن بنر') }}">✕</button>
            </div>

            <div class="plbn" data-tone="success" x-show="b.success" x-transition.opacity.duration.250ms role="status">
                <svg class="plbn-icon" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="m8.4 12.3 2.5 2.5 4.7-5.2"/></svg>
                <div class="plbn-body">
                    <b>{{ $say('Notification template saved', 'قالب اطلاع‌رسانی ذخیره شد') }}</b>
                    <p>{{ $say('From today, customers receive the order-confirmation email the moment they pay.', 'از امروز مشتریان در همان لحظهٔ پرداخت، ایمیل تأیید سفارش می‌گیرند.') }}</p>
                </div>
                <button type="button" class="plbn-x" x-on:click="b.success = false" aria-label="{{ $say('Dismiss banner', 'بستن بنر') }}">✕</button>
            </div>

            <div class="plbn" data-tone="warning" x-show="b.warn" x-transition.opacity.duration.250ms role="status">
                <svg class="plbn-icon" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 4 2.9 19.4a1.2 1.2 0 0 0 1 1.8h16.2a1.2 1.2 0 0 0 1-1.8L12 4z"/><path d="M12 10v4M12 17.4h.01"/></svg>
                <div class="plbn-body">
                    <b>{{ $say('Two products are running low', 'دو محصول رو به پایان‌اند') }}</b>
                    <p>{{ $say('Minimal soy candle and single-origin coffee each have fewer than 10 units left.', 'شمع سویا مینیمال و قهوهٔ تک‌خاستگاه هرکدام کمتر از ۱۰ عدد موجودی دارند.') }}</p>
                    <button type="button" class="plbn-act">{{ $say('Restock inventory', 'بازپروری موجودی') }} <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg></button>
                </div>
                <button type="button" class="plbn-x" x-on:click="b.warn = false" aria-label="{{ $say('Dismiss banner', 'بستن بنر') }}">✕</button>
            </div>

            <div class="plbn" data-tone="critical" x-show="b.crit" x-transition.opacity.duration.250ms role="alert">
                <svg class="plbn-icon" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16.4h.01"/></svg>
                <div class="plbn-body">
                    <b>{{ $say('Payment gateway is not responding', 'درگاه پرداخت پاسخ نمی‌دهد') }}</b>
                    <p>{{ $say('Three failed checkout attempts have been recorded since 9:12.', 'از ساعت ۹:۱۲ سه تلاش ناموفق پرداخت ثبت شده است.') }}</p>
                    <button type="button" class="plbn-act">{{ $say('Reconnect now', 'اتصال مجدد') }} <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12a9 9 0 1 1-2.6-6.3M21 4v5h-5"/></svg></button>
                </div>
                <button type="button" class="plbn-x" x-on:click="b.crit = false" aria-label="{{ $say('Dismiss banner', 'بستن بنر') }}">✕</button>
            </div>

            <p style="margin: 0; text-align: center" x-show="anyClosed" x-cloak>
                <button type="button" class="plbn-restore" x-on:click="restore()">{{ $say('Restore dismissed banners', 'بازگردانی بنرهای بسته‌شده') }}</button>
            </p>
        </div>
    </div>
</section>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('Compact tones', 'تُن‌های جمع‌ونرم') }}</h3>
        <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
            {{ $say('Without the body text the banner collapses to one line — same tones, same icons, quiet enough for inline placement.', 'بدون بدنه، بنر به یک خط جمع می‌شود — همان تُن‌ها و آیکن‌ها، آن‌قدر آرام که داخل متن بنشیند.') }}
        </p>
    </div>
    <div class="plbn-root plbn-variants">
        <div class="plbn-vcell">
            <div class="plbn" data-tone="success"><svg class="plbn-icon" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="m8.4 12.3 2.5 2.5 4.7-5.2"/></svg><div class="plbn-body"><b>{{ $say('Tracking number sent to the customer', 'کد رهگیری به مشتری رفت') }}</b></div></div>
            <small>success</small>
        </div>
        <div class="plbn-vcell">
            <div class="plbn" data-tone="info"><svg class="plbn-icon" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 11v5M12 8h.01"/></svg><div class="plbn-body"><b>{{ $say('Scheduled maintenance tonight, 2–3 a.m.', 'تعمیر برنامه‌ریزی‌شده امشب ۲ تا ۳ بامداد') }}</b></div></div>
            <small>informational</small>
        </div>
        <div class="plbn-vcell">
            <div class="plbn" data-tone="critical"><svg class="plbn-icon" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16.4h.01"/></svg><div class="plbn-body"><b>{{ $say('Shipment to Shiraz was returned to sender', 'مرسولهٔ شیراز به فرستنده برگشت') }}</b></div></div>
            <small>critical</small>
        </div>
    </div>
</section>
