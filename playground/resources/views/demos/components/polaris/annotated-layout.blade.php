{{--
    Polaris Annotated Layout as the notifications settings page: a 1:2 grid
    where the annotation column (title, explanation, help link) sits beside
    sectioned field cards; every control is live and flashes a saved chip,
    and under 768px the columns stack with the annotation on top. The
    variants row shows the plain full-width card without an annotation.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp
<style>
    [x-cloak] { display: none !important; }
    .plal-root {
        --plal-surface: #FFFFFF; --plal-raised: #F6F6F6; --plal-text: #303030; --plal-subdued: #616161;
        --plal-border: #E3E3E3; --plal-strong: #8A8A8A; --plal-hover: color-mix(in srgb, var(--plal-text) 4%, var(--plal-surface));
        --plal-green: #008060; --plal-on-green: #FFFFFF; --plal-green-hover: #004C3F;
        --plal-tint: color-mix(in srgb, var(--plal-green) 9%, var(--plal-surface));
        --plal-focus: #005BD3;
        font-family: Inter, -apple-system, "Segoe UI", Roboto, system-ui, sans-serif;
        display: grid; gap: 1.5rem; justify-items: center;
    }
    html[data-theme="dark"] .plal-root {
        --plal-surface: #202020; --plal-raised: #2B2B2B; --plal-text: #F1F1F1; --plal-subdued: #B5B5B5;
        --plal-border: #454545; --plal-strong: #8A8A8A;
        --plal-green: #00A97F; --plal-on-green: #08211A; --plal-green-hover: #00BA93;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .plal-root {
            --plal-surface: #202020; --plal-raised: #2B2B2B; --plal-text: #F1F1F1; --plal-subdued: #B5B5B5;
            --plal-border: #454545; --plal-strong: #8A8A8A;
            --plal-green: #00A97F; --plal-on-green: #08211A; --plal-green-hover: #00BA93;
        }
    }
    .plal-page { inline-size: min(100%, 40rem); display: grid; gap: 1.25rem; }
    .plal-intro h1 { margin: 0; font: 600 1.25rem/1.25 Inter, system-ui, sans-serif; color: var(--plal-text); }
    .plal-intro p { margin: .25rem 0 0; font: 400 .82rem/1.55 Inter, system-ui, sans-serif; color: var(--plal-subdued); }
    .plal-grid { display: grid; grid-template-columns: minmax(9rem, 1fr) 2fr; gap: 1.5rem; align-items: start; }
    .plal-note { display: grid; gap: .45rem; }
    .plal-note b { font: 600 .9rem/1.35 Inter, system-ui, sans-serif; color: var(--plal-text); }
    .plal-note p { margin: 0; font: 400 .8rem/1.6 Inter, system-ui, sans-serif; color: var(--plal-subdued); }
    .plal-note a { display: inline-flex; align-items: center; gap: .3rem; inline-size: fit-content; color: var(--plal-green); font: 500 .78rem/1 Inter, system-ui, sans-serif; text-decoration: none; border-radius: 5px; }
    .plal-note a:hover { text-decoration: underline; }
    .plal-note a:focus-visible { outline: 2px solid var(--plal-focus); outline-offset: 2px; }
    .plal-card { display: grid; gap: 0; border: 1px solid var(--plal-border); border-radius: 12px; background: var(--plal-surface); box-shadow: 0 1px 4px rgba(0, 0, 0, .07); }
    .plal-sec { display: grid; gap: .55rem; padding: 1rem 1.1rem; }
    .plal-sec + .plal-sec { border-block-start: 1px solid var(--plal-border); }
    .plal-check { display: flex; align-items: flex-start; gap: .6rem; cursor: pointer; }
    .plal-check input { flex: none; inline-size: 1.05rem; aspect-ratio: 1; margin-block-start: .1rem; accent-color: var(--plal-green); cursor: pointer; }
    .plal-check span b { display: block; font: 500 .84rem/1.4 Inter, system-ui, sans-serif; color: var(--plal-text); }
    .plal-check span small { display: block; font: 400 .76rem/1.5 Inter, system-ui, sans-serif; color: var(--plal-subdued); }
    .plal-field { display: grid; gap: .3rem; }
    .plal-field label { font: 500 .76rem/1.2 Inter, system-ui, sans-serif; color: var(--plal-text); }
    .plal-field :is(select, input) { block-size: 2.3rem; padding-inline: .7rem; border: 1px solid var(--plal-strong); border-radius: 8px; background: var(--plal-surface); color: var(--plal-text); font: 400 .82rem/1 Inter, system-ui, sans-serif; }
    .plal-field small { font: 400 .72rem/1.5 Inter, system-ui, sans-serif; color: var(--plal-subdued); }
    .plal-radios { display: flex; flex-wrap: wrap; gap: .35rem 1.2rem; }
    .plal-radio { display: inline-flex; align-items: center; gap: .45rem; font: 400 .82rem/1 Inter, system-ui, sans-serif; color: var(--plal-text); cursor: pointer; }
    .plal-radio input { inline-size: 1rem; aspect-ratio: 1; accent-color: var(--plal-green); cursor: pointer; }
    .plal-check input:focus-visible, .plal-field :is(select, input):focus-visible, .plal-radio input:focus-visible { outline: 2px solid var(--plal-focus); outline-offset: 1px; }
    .plal-foot { display: flex; align-items: center; gap: .5rem; padding: .8rem 1.1rem; border-block-start: 1px solid var(--plal-border); background: var(--plal-raised); border-end-start-radius: 12px; border-end-end-radius: 12px; }
    .plal-foot small { font: 400 .74rem/1.4 Inter, system-ui, sans-serif; color: var(--plal-subdued); }
    .plal-save { margin-inline-start: auto; block-size: 2rem; padding-inline: .9rem; border: none; border-radius: 8px; background: var(--plal-green); color: var(--plal-on-green); cursor: pointer; font: 500 .8rem/1 Inter, system-ui, sans-serif; }
    .plal-save:hover { background: var(--plal-green-hover); }
    .plal-save:focus-visible { outline: 2px solid var(--plal-focus); outline-offset: 2px; }
    .plal-flash { display: inline-flex; align-items: center; gap: .4rem; padding: .3rem .7rem; border-radius: 999px; background: var(--plal-tint); color: var(--plal-green); font: 500 .76rem/1 Inter, system-ui, sans-serif; }
    .plal-variants { inline-size: min(100%, 40rem); display: grid; gap: .5rem; justify-items: center; }
    .plal-variants > small { font: 500 .72rem/1 Inter, system-ui, sans-serif; color: var(--nx-text-muted); }
    @media (max-width: 768px) {
        .plal-grid { grid-template-columns: 1fr; gap: .9rem; }
    }
    :where(.nx-js) .pg:has(.plal-root) .nx-data-table[data-nx-reveal] tbody tr {
        opacity: 1; translate: none;
    }
    @media (max-width: 480px) {
        .pg:has(.plal-root) .nx-data-table :is(th, td) {
            white-space: normal; padding-inline: .5rem; overflow-wrap: break-word;
        }
        .pg:has(.plal-root) pre code { white-space: pre-wrap; overflow-wrap: anywhere; }
    }
    @media (prefers-reduced-motion: reduce) {
        .plal-root * { transition-duration: .01ms !important; }
    }
</style>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Explain on one side, edit on the other', 'توضیح یک‌سو، ویرایش سوی دیگر') }}</h3>
        <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
            {{ $say('The official settings-page skeleton: the annotation column explains each section and links to the docs, while the card column holds the live fields. Any change flashes the saved chip; under 768px the columns stack.', 'اسکلت رسمی صفحهٔ تنظیمات: ستون توضیح هر بخش را شرح می‌دهد و به مستندات لینک می‌دهد، ستون کارت فیلدهای زنده را نگه می‌دارد. هر تغییری چیپ ذخیره را روشن می‌کند و زیر ۷۶۸ پیکسل ستون‌ها روی هم می‌افتند.') }}
        </p>
    </div>

    <div class="plal-root" style="inline-size: 100%"
        x-data="{
            fa: {{ $fa ? 'true' : 'false' }}, flash: false,
            confirm: true, sender: 'shop', digest: 'am', workdays: true,
            get anyChange() { return true },
            touch() { this.flash = true; clearTimeout(this.t); this.t = setTimeout(() => this.flash = false, 2200) },
        }">
        <div class="plal-page">
            <div class="plal-intro">
                <h1>{{ $say('Notifications', 'اطلاع‌رسانی‌ها') }}</h1>
                <p>{{ $say('Decide what your customers hear after each order — and from which voice.', 'تصمیم بدهید مشتریان پس از هر سفارش چه بشنوند — و از چه زبانی.') }}</p>
            </div>

            <div class="plal-grid">
                <div class="plal-note">
                    <b>{{ $say('Order confirmation email', 'ایمیل تأیید سفارش') }}</b>
                    <p>{{ $say('The receipt customers forward to their accountant. Keep it quiet and factual.', 'رسیدی که مشتری برای حسابدارش می‌فرستد. آرام و دقیق نگه‌اش دارید.') }}</p>
                    <a href="#" onclick="return false">{{ $say('Learn more', 'بیشتر بدانید') }} <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 13v6a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h6"/><path d="M15 3h6v6"/><path d="M10 14 21 3"/></svg></a>
                </div>
                <div class="plal-card">
                    <div class="plal-sec">
                        <label class="plal-check">
                            <input type="checkbox" x-model="confirm" x-on:change="touch()">
                            <span>
                                <b>{{ $say('Send the confirmation email', 'ارسال ایمیل تأیید') }}</b>
                                <small>{{ $say('Right after payment, before fulfillment begins.', 'بلافاصله پس از پرداخت و پیش از آغاز بسته‌بندی.') }}</small>
                            </span>
                        </label>
                    </div>
                    <div class="plal-sec">
                        <div class="plal-field">
                            <label for="plal-sender">{{ $say('Sender', 'فرستنده') }}</label>
                            <select id="plal-sender" x-model="sender" x-on:change="touch()">
                                <option value="shop">{{ $say('The shop, from its own address', 'خود فروشگاه، از نشانی خودش') }}</option>
                                <option value="support">{{ $say('Support desk', 'میز پشتیبانی') }}</option>
                                <option value="noreply">{{ $say('No-reply robot', 'ربات بدون پاسخ') }}</option>
                            </select>
                            <small>{{ $say('A human sender lifts open rates by about a tenth.', 'فرستندهٔ انسانی نرخ باز شدن را حدود یک‌دهم بالا می‌برد.') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="plal-grid">
                <div class="plal-note">
                    <b>{{ $say('Daily digest', 'خلاصهٔ روزانه') }}</b>
                    <p>{{ $say('One email a day instead of twenty — the number of orders, the money, the two things to fix.', 'روزی یک ایمیل به‌جای بیست ایمیل — تعداد سفارش‌ها، پول، و دو چیزی که باید درست شود.') }}</p>
                    <a href="#" onclick="return false">{{ $say('Learn more', 'بیشتر بدانید') }} <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 13v6a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h6"/><path d="M15 3h6v6"/><path d="M10 14 21 3"/></svg></a>
                </div>
                <div class="plal-card">
                    <div class="plal-sec">
                        <div class="plal-field">
                            <label id="plal-time">{{ $say('Delivery time', 'زمان ارسال') }}</label>
                            <div class="plal-radios" role="radiogroup" aria-labelledby="plal-time">
                                <label class="plal-radio"><input type="radio" name="plal-digest" value="am" x-model="digest" x-on:change="touch()"> {{ $say('8:00 in the morning', '۸ صبح') }}</label>
                                <label class="plal-radio"><input type="radio" name="plal-digest" value="pm" x-model="digest" x-on:change="touch()"> {{ $say('6:00 in the evening', '۶ عصر') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="plal-sec">
                        <label class="plal-check">
                            <input type="checkbox" x-model="workdays" x-on:change="touch()">
                            <span>
                                <b>{{ $say('Workdays only', 'فقط روزهای کاری') }}</b>
                                <small>{{ $say('Saturday to Wednesday — nobody reads reports on the weekend.', 'شنبه تا سه‌شنبه — کسی آخر هفته گزارش نمی‌خواند.') }}</small>
                            </span>
                        </label>
                    </div>
                    <div class="plal-foot">
                        <small x-show="!flash">{{ $say('Changes apply to the next order immediately.', 'تغییرها از سفارش بعدی فوری اعمال می‌شوند.') }}</small>
                        <span class="plal-flash" x-show="flash" x-cloak aria-live="polite">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 12.5 4.5 4.5L19 7.5"/></svg>
                            {{ $say('Settings saved', 'تنظیمات ذخیره شد') }}
                        </span>
                        <button type="button" class="plal-save">{{ $say('Save', 'ذخیره') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('The plain variant', 'گونهٔ ساده') }}</h3>
        <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
            {{ $say('Without the annotation the card runs full width — the section header moves inside, and the footer actions stay put.', 'بدون ستون توضیح، کارت تمام‌عرض می‌شود — سربرگ بخش داخل کارت می‌نشیند و کنش‌های پایانی سر جایشان می‌مانند.') }}
        </p>
    </div>
    <div class="plal-root plal-variants">
        <div class="plal-card" style="inline-size: min(100%, 40rem)">
            <div class="plal-sec" style="gap: .7rem">
                <b style="font: 600 .9rem/1.3 Inter, system-ui, sans-serif; color: var(--plal-text)">{{ $say('Packing-station note', 'یادداشت ایستگاه بسته‌بندی') }}</b>
                <div class="plal-field">
                    <label for="plal-packnote">{{ $say('Printed on every packing slip', 'روی هر رسید بسته‌بندی چاپ می‌شود') }}</label>
                    <input id="plal-packnote" type="text" value="{{ $say('Fragile — the candle goes on top.', 'شکستنی است — شمع روی قرار می‌گیرد.') }}">
                    <small>{{ $say('Keep it under one line; the slip is 8 cm wide.', 'زیر یک خط نگهش دارید؛ رسید ۸ سانتی‌متر عرض دارد.') }}</small>
                </div>
            </div>
            <div class="plal-foot">
                <button type="button" class="plal-save" style="margin-inline-start: 0">{{ $say('Save', 'ذخیره') }}</button>
            </div>
        </div>
        <small>full width · no annotation</small>
    </div>
</section>
