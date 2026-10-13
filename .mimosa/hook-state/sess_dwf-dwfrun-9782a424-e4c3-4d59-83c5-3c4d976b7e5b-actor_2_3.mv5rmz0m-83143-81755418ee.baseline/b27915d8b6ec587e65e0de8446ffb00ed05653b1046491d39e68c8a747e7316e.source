{{--
    The checkout flow's real scenario: a Nordvana store checkout — a cart with
    live quantity steppers and removal, an address form that really validates
    on Continue, a card form masked while typing (fours, MM/YY) with a live
    card preview and the honest declined state, a sticky order summary that
    recounts shipping and VAT, and a confirmation with a generated order
    number and a delivery date. Alpine state only, no wire:.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;
@endphp
<style>
    .flck-root {
        --flck-accent: #4f46e5; --flck-accent-soft: #eef0fe; --flck-on-accent: #fff;
        --flck-text: #191b2a; --flck-muted: #666b83;
        --flck-border: #e4e6ef; --flck-surface: #fff; --flck-layer: #f5f6fb;
        --flck-danger: #c62a2f; --flck-danger-soft: #fdecec; --flck-danger-border: #f5b9bb;
        --flck-ok: #0f8a43; --flck-ok-soft: #e7f6ec;
        display: grid; gap: 1.25rem;
    }
    html[data-theme="dark"] .flck-root {
        --flck-accent: #8e94f8; --flck-accent-soft: #262a4d; --flck-on-accent: #14152b;
        --flck-text: #ecedf6; --flck-muted: #a6abc4;
        --flck-border: #34364e; --flck-surface: #1b1c2e; --flck-layer: #222439;
        --flck-danger: #f0868b; --flck-danger-soft: #3a2226; --flck-danger-border: #5c3138;
        --flck-ok: #5bd08d; --flck-ok-soft: #1d3a2a;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .flck-root {
            --flck-accent: #8e94f8; --flck-accent-soft: #262a4d; --flck-on-accent: #14152b;
            --flck-text: #ecedf6; --flck-muted: #a6abc4;
            --flck-border: #34364e; --flck-surface: #1b1c2e; --flck-layer: #222439;
            --flck-danger: #f0868b; --flck-danger-soft: #3a2226; --flck-danger-border: #5c3138;
            --flck-ok: #5bd08d; --flck-ok-soft: #1d3a2a;
        }
    }
    .flck-grid { display: grid; grid-template-columns: minmax(0, 1fr) 15.5rem; gap: 1.25rem; align-items: start;
                 inline-size: min(100%, 48rem); margin-inline: auto; }
    .flck-crumb { display: flex; align-items: center; gap: .5rem; margin: 0; padding: 0; list-style: none;
                  font-size: .78rem; color: var(--flck-muted); }
    .flck-crumb li { display: inline-flex; align-items: center; gap: .5rem; }
    .flck-crumb li + li::before { content: '·'; color: var(--flck-border); }
    .flck-crumb li[data-now] { color: var(--flck-accent); font-weight: 700; }
    .flck-pane { display: grid; gap: 1.1rem; padding: 1.4rem; border: 1px solid var(--flck-border);
                 border-radius: 1.4rem; background: var(--flck-surface); color: var(--flck-text);
                 animation: flck-in .3s cubic-bezier(.2, 0, 0, 1); }
    @keyframes flck-in { from { opacity: 0; translate: 0 10px; } }
    .flck-pane h4 { margin: 0; font: 700 var(--nx-text-lg, 1.05rem) / 1.4 var(--nx-font-display, inherit); }
    .flck-pane > p.lead { margin: 0; font-size: .85rem; color: var(--flck-muted); line-height: 1.7; }
    .flck-row { display: grid; grid-template-columns: minmax(0, 1fr) auto auto; align-items: center; gap: .75rem;
                padding-block: .65rem; border-block-start: 1px solid var(--flck-border); font-size: .88rem; }
    .flck-row:first-of-type { border-block-start: 0; padding-block-start: 0; }
    .flck-row small { display: block; color: var(--flck-muted); font-size: .74rem; }
    .flck-qty { display: inline-flex; align-items: center; gap: .15rem; border: 1px solid var(--flck-border);
                border-radius: 999px; }
    .flck-qty button { display: grid; place-items: center; inline-size: 1.7rem; block-size: 1.7rem; border: none;
                       background: transparent; color: var(--flck-text); font-size: .95rem; cursor: pointer; border-radius: 999px; }
    .flck-qty button:hover { background: var(--flck-layer); }
    .flck-qty b { min-inline-size: 1.4rem; text-align: center; font-size: .84rem; }
    .flck-row > .flck-price { font-weight: 700; font-variant-numeric: tabular-nums; }
    .flck-x { display: grid; place-items: center; inline-size: 1.7rem; aspect-ratio: 1; border: none;
              background: transparent; color: var(--flck-muted); cursor: pointer; border-radius: 999px; }
    .flck-x:hover { background: var(--flck-danger-soft); color: var(--flck-danger); }
    .flck-x svg { inline-size: .95rem; }
    .flck-empty { display: grid; justify-items: center; gap: .6rem; padding-block: 1.5rem; text-align: center;
                  color: var(--flck-muted); font-size: .86rem; }
    .flck-fields { display: grid; gap: .85rem; }
    .flck-duo { display: grid; grid-template-columns: 1fr 1fr; gap: .85rem; }
    .flck-field { display: grid; gap: .3rem; font-size: .78rem; font-weight: 600; color: var(--flck-muted); }
    .flck-field input, .flck-field select { min-block-size: 2.6rem; padding-inline: .8rem; border: 1px solid var(--flck-border);
                     border-radius: .7rem; background: var(--flck-surface); color: var(--flck-text); font: inherit; font-weight: 400; }
    .flck-field input:focus-visible, .flck-field select:focus-visible { outline: 2px solid var(--flck-accent); outline-offset: 1px; }
    .flck-field input[data-invalid], .flck-field select[data-invalid] { border-color: var(--flck-danger);
                                                                        box-shadow: inset 0 0 0 1px var(--flck-danger); }
    .flck-err { display: flex; align-items: center; gap: .3rem; color: var(--flck-danger); font-size: .73rem; font-weight: 500; }
    .flck-err svg { inline-size: .85rem; flex: none; }
    .flck-card { position: relative; display: grid; gap: .9rem; padding: 1.1rem 1.2rem; border-radius: 1.1rem;
                 color: #fff; background: linear-gradient(130deg, #3f3a8f, #6d4fd0 55%, #8f5edb);
                 overflow: clip; max-inline-size: 24rem; }
    .flck-card::after { content: ''; position: absolute; inline-size: 12rem; aspect-ratio: 1; border-radius: 50%;
                        background: rgb(255 255 255 / .12); inset-block-end: -6rem; inset-inline-end: -4rem; }
    .flck-card-chip { inline-size: 2.1rem; block-size: 1.5rem; border-radius: .35rem;
                      background: linear-gradient(140deg, #f4d47c, #c9a13f); }
    .flck-card-num { font: 600 1.02rem / 1 ui-monospace, 'SFMono-Regular', Menlo, monospace; letter-spacing: .12em;
                     min-block-size: 1.2em; }
    .flck-card-foot { display: flex; justify-content: space-between; gap: 1rem; font-size: .72rem; text-transform: uppercase;
                      letter-spacing: .06em; }
    .flck-card-foot span { max-inline-size: 60%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .flck-decline { display: flex; gap: .55rem; align-items: flex-start; margin: 0; padding: .75rem .9rem;
                    border: 1px solid var(--flck-danger-border); border-radius: .8rem; background: var(--flck-danger-soft);
                    color: var(--flck-danger); font-size: .82rem; line-height: 1.6; }
    .flck-decline svg { flex: none; inline-size: 1rem; margin-block-start: .1rem; }
    .flck-nav { display: flex; align-items: center; gap: .75rem; }
    .flck-btn { display: inline-flex; align-items: center; justify-content: center; gap: .5rem; min-block-size: 2.7rem;
                padding-inline: 1.3rem; border: none; border-radius: 999px; background: var(--flck-layer);
                color: var(--flck-text); font: 600 .86rem / 1 inherit; cursor: pointer;
                transition: background-color .18s ease, opacity .18s ease; }
    .flck-btn:hover:not(:disabled) { background: var(--flck-border); }
    .flck-btn:disabled { opacity: .45; cursor: not-allowed; }
    .flck-btn[data-kind="go"] { margin-inline-start: auto; background: var(--flck-accent); color: var(--flck-on-accent); }
    .flck-btn[data-kind="go"]:hover:not(:disabled) { filter: brightness(1.08); }
    .flck-btn:focus-visible { outline: 2px solid var(--flck-accent); outline-offset: 2px; }
    .flck-btn svg { inline-size: 1rem; }
    html[dir="rtl"] .flck-btn svg[data-arrow] { rotate: 180deg; }
    .flck-spin { inline-size: 1rem; aspect-ratio: 1; border-radius: 50%; border: 2px solid rgb(255 255 255 / .4);
                 border-block-start-color: #fff; animation: flck-spin .7s linear infinite; }
    @keyframes flck-spin { to { rotate: 360deg; } }
    .flck-aside { position: sticky; inset-block-start: 1rem; display: grid; gap: .55rem; padding: 1.2rem;
                  border: 1px solid var(--flck-border); border-radius: 1.4rem; background: var(--flck-layer);
                  color: var(--flck-text); font-size: .82rem; }
    .flck-aside h5 { margin: 0 0 .35rem; font: 700 .82rem / 1.4 var(--nx-font-display, inherit); }
    .flck-aside > div { display: flex; justify-content: space-between; gap: 1rem; }
    .flck-aside > div span:first-child { color: var(--flck-muted); }
    .flck-aside .flck-free { color: var(--flck-ok); font-weight: 700; }
    .flck-aside .flck-tot { border-block-start: 2px solid var(--flck-accent); margin-block-start: .35rem;
                            padding-block-start: .6rem; font-weight: 700; font-size: .92rem; }
    .flck-aside .flck-tot span:last-child { font-variant-numeric: tabular-nums; }
    .flck-ship-hint { margin: .3rem 0 0; font-size: .72rem; color: var(--flck-muted); line-height: 1.6; }
    .flck-done { justify-items: center; text-align: center; gap: .8rem; }
    .flck-done-ring { display: grid; place-items: center; inline-size: 4.2rem; aspect-ratio: 1; border-radius: 50%;
                      background: var(--flck-ok-soft); }
    .flck-done-ring svg { inline-size: 2.2rem; color: var(--flck-ok); }
    .flck-done-ring path { stroke-dasharray: 30; stroke-dashoffset: 30; animation: flck-draw .5s ease-out .1s forwards; }
    .flck-done-ring circle { stroke-dasharray: 76; stroke-dashoffset: 76; animation: flck-draw .6s ease-out forwards; }
    @keyframes flck-draw { to { stroke-dashoffset: 0; } }
    .flck-order { display: inline-flex; align-items: center; gap: .4rem; padding: .35rem .8rem; border-radius: .6rem;
                  background: var(--flck-layer); font: 700 .85rem / 1.4 ui-monospace, Menlo, monospace;
                  font-variant-numeric: tabular-nums; }
    .flck-hint { margin: 0; text-align: center; font-size: .82rem; color: var(--nx-text-muted); max-inline-size: 60ch; }
    @media (max-width: 720px) {
        .flck-grid { grid-template-columns: 1fr; }
        .flck-aside { position: static; order: -1; }
    }
    @media (max-width: 480px) {
        .flck-pane { padding: 1.05rem; border-radius: 1.1rem; }
        .flck-row { grid-template-columns: minmax(0, 1fr) auto; }
        .flck-row .flck-price { grid-column: 2; grid-row: 1; }
        .flck-x { grid-column: 2; grid-row: 2; justify-self: end; }
        .flck-nav { flex-wrap: wrap; }
        .flck-btn { flex: 1 1 auto; }
    }
    @media (prefers-reduced-motion: reduce) {
        .flck-root * { transition-duration: .01ms !important; animation-duration: .01ms !important;
                       animation-iteration-count: 1 !important; }
    }
</style>

<div class="flck-root"
    x-data="{
        fa: {{ $fa ? 'true' : 'false' }},
        step: 0,
        initial: {{ json_encode([
            ['id' => 'lamp', 'name' => $say('Aster desk lamp', 'چراغ رومیزی آستر'), 'meta' => $say('Warm dim · graphite', 'نور گرمِ کم‌شدنی · گرافیتی'), 'price' => 89, 'qty' => 1],
            ['id' => 'keys', 'name' => $say('Nimbus Keys K2 keyboard', 'کیبورد نیمبوس K2'), 'meta' => $say('Tactile · wireless', 'تکتیکال · بی‌سیم'), 'price' => 149, 'qty' => 1],
            ['id' => 'stand', 'name' => $say('Vela laptop stand', 'پایهٔ لپ‌تاپ ولا'), 'meta' => $say('Aluminium · folding', 'آلومینیومیِ تاشو'), 'price' => 59, 'qty' => 2],
        ]) }},
        items: null,
        form: { name: '', street: '', city: '', postcode: '', country: '{{ $say('DE', 'DE') }}' },
        errors: {},
        card: { number: '', exp: '', cvc: '' },
        cardErrors: {},
        declined: false,
        processing: false,
        order: null,
        delivery: '',
        init() { this.items = this.initial.map((i) => ({ ...i })); },
        n(v) { const s = String(v); return this.fa ? s.replace(/\d/g, (d) => '۰۱۲۳۴۵۶۷۸۹'[d]) : s; },
        maskNumber() {
            const d = this.card.number.replace(/\D/g, '').slice(0, 16);
            this.card.number = d.replace(/(.{4})/g, '$1 ').trim();
            if (this.declined) this.declined = false;
        },
        maskExp() {
            const d = this.card.exp.replace(/\D/g, '').slice(0, 4);
            this.card.exp = d.length > 2 ? d.slice(0, 2) + '/' + d.slice(2) : d;
        },
        maskCvc() { this.card.cvc = this.card.cvc.replace(/\D/g, '').slice(0, 4); },
        bump(id, delta) {
            this.items = this.items.map((i) => i.id === id ? { ...i, qty: Math.max(1, i.qty + delta) } : i);
        },
        drop(id) { this.items = this.items.filter((i) => i.id !== id); },
        get subtotal() { return this.items.reduce((s, i) => s + i.price * i.qty, 0); },
        get shipping() { return this.subtotal >= 150 ? 0 : 12; },
        get vat() { return Math.round(this.subtotal * 0.19); },
        get total() { return this.subtotal + this.shipping + this.vat; },
        get cities() { return this.form.city.length > 1; },
        validateAddress() {
            this.errors = {};
            if (this.form.name.trim().length < 2) this.errors.name = '{{ $say('Full name is required', 'نام کامل لازم است') }}';
            if (this.form.street.trim().length < 3) this.errors.street = '{{ $say('Street and number, please', 'خیابان و پلاک، لطفاً') }}';
            if (this.form.city.trim().length < 2) this.errors.city = '{{ $say('Which city ships it to?', 'به کدام شهر بفرستیم؟') }}';
            if (!/^[A-Za-z0-9][A-Za-z0-9 -]{1,8}[A-Za-z0-9]$/.test(this.form.postcode.trim())) this.errors.postcode = '{{ $say('Postcode looks off (3–10 characters)', 'کدپستی درست به نظر نمی‌رسد (۳ تا ۱۰ نویسه)') }}';
            return Object.keys(this.errors).length === 0;
        },
        toPayment() { if (this.validateAddress()) this.step = 2; },
        pay() {
            this.cardErrors = {};
            const digits = this.card.number.replace(/\s/g, '');
            if (digits.length !== 16) this.cardErrors.number = '{{ $say('16 digits, grouped in fours', '۱۶ رقم، چهارتایی گروه‌شده') }}';
            if (!/^\d{2}\/\d{2}$/.test(this.card.exp)) this.cardErrors.exp = '{{ $say('MM/YY', 'ماه/سال') }}';
            if (this.card.cvc.length < 3) this.cardErrors.cvc = '{{ $say('3–4 digits on the back', '۳ تا ۴ رقم پشت کارت') }}';
            if (Object.keys(this.cardErrors).length) return;
            this.processing = true;
            setTimeout(() => {
                this.processing = false;
                if (digits === '4000000000000002') { this.declined = true; return; }
                this.order = 'NBX-' + Math.floor(10000 + Math.random() * 90000);
                this.delivery = new Intl.DateTimeFormat(this.fa ? 'fa-IR' : 'en-IE',
                    { day: 'numeric', month: 'long' }).format(new Date(Date.now() + 5 * 86400000));
                this.step = 3;
            }, 900);
        },
        reset() {
            this.items = this.initial.map((i) => ({ ...i }));
            this.form = { name: '', street: '', city: '', postcode: '', country: this.form.country };
            this.card = { number: '', exp: '', cvc: '' };
            this.errors = {}; this.cardErrors = {}; this.declined = false;
            this.order = null; this.step = 0;
        },
    }">
    <section class="pg-box" style="gap: 1.25rem; justify-items: center">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A checkout that behaves like one', 'پرداختی که واقعاً مثل پرداخت رفتار می‌کند') }}</h3>
            <p style="margin: 0; max-inline-size: 56ch; color: var(--nx-text-muted)">
                {{ $say('Cart, address, card, confirmation — every step guards itself: the address form validates on Continue and parks the error under its field, the card groups in fours and slashes its own expiry while the preview below follows, the classic 4000…0002 decline test card trips the honest error state, and the sticky summary recounts shipping and VAT with every click. Everything runs on local state; nothing leaves the frame.', 'سبد، آدرس، کارت، تأیید — هر قدم خودش را نگهبان خودش دارد: فرم آدرس هنگام «ادامه» اعتبارسنجی می‌کند و خطا زیر فیلد خودش می‌نشیند، کارت چهارتایی گروه می‌شود و تاریخش خودش اسلش می‌خورد و پیش‌نمایشِ زیرش دنبال می‌کند، کارت آزمایشی ۴۰۰۰…۰۰۰۲ حالت خطای صادق را روشن می‌کند، و خلاصهٔ چسبیده با هر کلیک ارسال و مالیات را دوباره می‌شمارد. همه‌چیز با وضعیت محلی است؛ هیچ‌چیز از قاب بیرون نمی‌رود.') }}
            </p>
        </div>

        <div class="flck-grid">
            <div>
                <ol class="flck-crumb" style="margin-block-end: .9rem">
                    <li :data-now="step === 0 ? '' : null">{{ $say('Cart', 'سبد') }}</li>
                    <li :data-now="step === 1 ? '' : null">{{ $say('Address', 'آدرس') }}</li>
                    <li :data-now="step === 2 ? '' : null">{{ $say('Payment', 'پرداخت') }}</li>
                    <li :data-now="step === 3 ? '' : null">{{ $say('Done', 'تأیید') }}</li>
                </ol>

                <section class="flck-pane" x-show="step === 0">
                    <h4>{{ $say('Your cart', 'سبد شما') }}</h4>
                    <template x-if="items.length">
                        <div style="display: grid">
                            <template x-for="item in items" :key="item.id">
                                <div class="flck-row">
                                    <div>
                                        <b x-text="item.name"></b>
                                        <small x-text="item.meta + ' · ' + n(item.price) + ' €'"></small>
                                    </div>
                                    <span class="flck-qty">
                                        <button type="button" aria-label="{{ $say('Decrease quantity', 'کاهش تعداد') }}" x-on:click="bump(item.id, -1)">−</button>
                                        <b x-text="n(item.qty)"></b>
                                        <button type="button" aria-label="{{ $say('Increase quantity', 'افزایش تعداد') }}" x-on:click="bump(item.id, 1)">+</button>
                                    </span>
                                    <span class="flck-price" x-text="n(item.price * item.qty) + ' €'"></span>
                                    <button type="button" class="flck-x" aria-label="{{ $say('Remove item', 'حذف قلم') }}" x-on:click="drop(item.id)">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 7h16M10 11v6m4-6v6M6 7l1 13h10l1-13M9 7V4h6v3"/></svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </template>
                    <div class="flck-empty" x-show="!items.length" x-cloak>
                        <p style="margin: 0">{{ $say('The cart is empty — everything you removed is one click away.', 'سبد خالی است — هرچه حذف کردید یک کلیک با شما فاصله دارد.') }}</p>
                        <button type="button" class="flck-btn" x-on:click="reset()">{{ $say('Restore the demo cart', 'بازگرداندن سبدِ دمو') }}</button>
                    </div>
                    <div class="flck-nav">
                        <span style="font-size: .78rem; color: var(--flck-muted)" x-show="items.length"
                            x-text="(subtotal >= 150 ? '{{ $say('Shipping is on us', 'ارسال مهمان ما') }}' : '{{ $say('Add', 'افزودن') }}' + ' ' + n(150 - subtotal) + ' € {{ $say('for free shipping', 'تا ارسال رایگان شود') }}') + ' · ' + n(items.reduce((s, i) => s + i.qty, 0)) + ' {{ $say('items', 'قلم') }}'"></span>
                        <button type="button" class="flck-btn" data-kind="go" :disabled="!items.length" x-on:click="step = 1">
                            {{ $say('Continue to address', 'ادامه به آدرس') }}
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-arrow aria-hidden="true"><path d="M5 12h14m-6-6l6 6-6 6"/></svg>
                        </button>
                    </div>
                </section>

                <section class="flck-pane" x-show="step === 1" x-cloak>
                    <h4>{{ $say('Shipping address', 'آدرس ارسال') }}</h4>
                    <p class="lead">{{ $say('Dublin, Frankfurt, Singapore — wherever the desk is, this form checks itself before moving on.', 'دوبلین، فرانکفورت، سنگاپور — میز هرجا باشد، این فرم پیش از رفتن خودش را می‌سنجد.') }}</p>
                    <div class="flck-fields">
                        <label class="flck-field">
                            <span>{{ $say('Full name', 'نام کامل') }}</span>
                            <input type="text" x-model="form.name" autocomplete="name" :data-invalid="errors.name ? '' : null"
                                placeholder="{{ $say('Maya Fischer', 'مایا فیشر') }}">
                            <span class="flck-err" x-show="errors.name" x-cloak>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 8v5m0 3v.01"/></svg>
                                <span x-text="errors.name"></span>
                            </span>
                        </label>
                        <label class="flck-field">
                            <span>{{ $say('Street and number', 'خیابان و پلاک') }}</span>
                            <input type="text" x-model="form.street" autocomplete="street-address" :data-invalid="errors.street ? '' : null"
                                placeholder="{{ $say('14 Hanover Quay', 'هانوور کی ۱۴') }}">
                            <span class="flck-err" x-show="errors.street" x-cloak>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 8v5m0 3v.01"/></svg>
                                <span x-text="errors.street"></span>
                            </span>
                        </label>
                        <div class="flck-duo">
                            <label class="flck-field">
                                <span>{{ $say('City', 'شهر') }}</span>
                                <input type="text" x-model="form.city" list="flck-cities" :data-invalid="errors.city ? '' : null"
                                    placeholder="{{ $say('Dublin', 'دوبلین') }}">
                                <datalist id="flck-cities">
                                    <option value="Dublin"></option>
                                    <option value="Frankfurt"></option>
                                    <option value="Singapore"></option>
                                    <option value="Amsterdam"></option>
                                </datalist>
                                <span class="flck-err" x-show="errors.city" x-cloak>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 8v5m0 3v.01"/></svg>
                                    <span x-text="errors.city"></span>
                                </span>
                            </label>
                            <label class="flck-field">
                                <span>{{ $say('Postcode', 'کدپستی') }}</span>
                                <input type="text" x-model="form.postcode" :data-invalid="errors.postcode ? '' : null" dir="ltr"
                                    placeholder="{{ $say('D02 X285', 'D02 X285') }}">
                                <span class="flck-err" x-show="errors.postcode" x-cloak>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 8v5m0 3v.01"/></svg>
                                    <span x-text="errors.postcode"></span>
                                </span>
                            </label>
                        </div>
                        <label class="flck-field">
                            <span>{{ $say('Country', 'کشور') }}</span>
                            <select x-model="form.country">
                                <option value="DE">{{ $say('Germany', 'آلمان') }}</option>
                                <option value="IE">{{ $say('Ireland', 'ایرلند') }}</option>
                                <option value="SG">{{ $say('Singapore', 'سنگاپور') }}</option>
                                <option value="NL">{{ $say('Netherlands', 'هلند') }}</option>
                            </select>
                        </label>
                    </div>
                    <div class="flck-nav">
                        <button type="button" class="flck-btn" x-on:click="step = 0">{{ $say('Back to cart', 'بازگشت به سبد') }}</button>
                        <button type="button" class="flck-btn" data-kind="go" x-on:click="toPayment()">
                            {{ $say('Continue to payment', 'ادامه به پرداخت') }}
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-arrow aria-hidden="true"><path d="M5 12h14m-6-6l6 6-6 6"/></svg>
                        </button>
                    </div>
                </section>

                <section class="flck-pane" x-show="step === 2" x-cloak>
                    <h4>{{ $say('Payment', 'پرداخت') }}</h4>
                    <p class="lead">{{ $say('Type and watch the mask work: fours for the number, MM/YY for the expiry. Try 4000 0000 0000 0002 to see a decline handled honestly.', 'تایپ کنید و ماسک را دیدن کنید: شماره چهارتایی و تاریخ ماه/سال. برای دیدن ردشدنِ صادقانه، 4000 0000 0000 0002 را امتحان کنید.') }}</p>

                    <div class="flck-card" dir="ltr" aria-hidden="true">
                        <span class="flck-card-chip"></span>
                        <span class="flck-card-num" x-text="card.number || '•••• •••• •••• ••••'"></span>
                        <div class="flck-card-foot">
                            <span x-text="form.name.toUpperCase() || 'CARDHOLDER'"></span>
                            <span x-text="card.exp || 'MM/YY'"></span>
                        </div>
                    </div>

                    <div class="flck-fields">
                        <label class="flck-field">
                            <span>{{ $say('Card number', 'شمارهٔ کارت') }}</span>
                            <input type="text" inputmode="numeric" x-model="card.number" x-on:input="maskNumber()" :data-invalid="cardErrors.number ? '' : null"
                                dir="ltr" placeholder="4000 0000 0000 0002" style="font-family: ui-monospace, Menlo, monospace; letter-spacing: .08em">
                            <span class="flck-err" x-show="cardErrors.number" x-cloak>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 8v5m0 3v.01"/></svg>
                                <span x-text="cardErrors.number"></span>
                            </span>
                        </label>
                        <div class="flck-duo">
                            <label class="flck-field">
                                <span>{{ $say('Expiry', 'تاریخ انقضا') }}</span>
                                <input type="text" inputmode="numeric" x-model="card.exp" x-on:input="maskExp()" :data-invalid="cardErrors.exp ? '' : null"
                                    dir="ltr" placeholder="MM/YY">
                                <span class="flck-err" x-show="cardErrors.exp" x-cloak>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 8v5m0 3v.01"/></svg>
                                    <span x-text="cardErrors.exp"></span>
                                </span>
                            </label>
                            <label class="flck-field">
                                <span>CVC</span>
                                <input type="text" inputmode="numeric" x-model="card.cvc" x-on:input="maskCvc()" :data-invalid="cardErrors.cvc ? '' : null"
                                    dir="ltr" placeholder="123">
                                <span class="flck-err" x-show="cardErrors.cvc" x-cloak>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 8v5m0 3v.01"/></svg>
                                    <span x-text="cardErrors.cvc"></span>
                                </span>
                            </label>
                        </div>
                    </div>

                    <p class="flck-decline" role="alert" x-show="declined" x-cloak>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10.3 4.3 2.9 17a2 2 0 0 0 1.7 3h14.8a2 2 0 0 0 1.7-3L13.7 4.3a2 2 0 0 0-3.4 0Z"/><path d="M12 9v4m0 3v.01"/></svg>
                        <span>{{ $say('<b>Card declined.</b> The bank said no to 4000…0002 — nothing was charged. Edit the number to any other 16 digits and try again, or switch to another card.', '<b>کارت رد شد.</b> بانک به ۴۰۰۰…۰۰۰۲ نه گفت — هیچ مبلغی کسر نشد. شماره را به هر ۱۶ رقم دیگری تغییر دهید و دوباره امتحان کنید، یا کارت دیگری بیندازید.') }}</span>
                    </p>

                    <div class="flck-nav">
                        <button type="button" class="flck-btn" x-on:click="step = 1">{{ $say('Back to address', 'بازگشت به آدرس') }}</button>
                        <button type="button" class="flck-btn" data-kind="go" x-on:click="pay()" :disabled="processing"
                            x-text="processing ? '{{ $say('Processing…', 'در حال پردازش…') }}' : (declined ? '{{ $say('Try again', 'تلاش دوباره') }}' : '{{ $say('Pay', 'پرداخت') }}' + ' ' + n(total) + ' €')"></button>
                        <span class="flck-spin" x-show="processing" x-cloak aria-hidden="true"></span>
                    </div>
                </section>

                <section class="flck-pane flck-done" x-show="step === 3" x-cloak>
                    <span class="flck-done-ring" aria-hidden="true">
                        <svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="24" cy="24" r="15.9" stroke-width="2"/>
                            <path d="M15.5 24.5l5.5 5.5 11.5-12"/>
                        </svg>
                    </span>
                    <h4>{{ $say('Order confirmed', 'سفارش تأیید شد') }}</h4>
                    <p style="margin: 0; color: var(--flck-muted); font-size: .88rem">
                        {{ $say('Receipt is on its way to your inbox.', 'رسید در راه صندوق ایمیل شماست.') }}
                    </p>
                    <span class="flck-order">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="inline-size: .95rem" aria-hidden="true"><rect x="3" y="6" width="18" height="13" rx="2.5"/><path d="M3 10.5h18"/></svg>
                        <span x-text="order"></span>
                    </span>
                    <p style="margin: 0; font-size: .84rem" x-html="(form.name ? form.name + ' · ' : '') + form.city + ' · ' + delivery + '<br>' + '{{ $say('Card ending', 'کارت پایان‌یافته به') }}' + ' ' + n(card.number.replace(/\s/g, '').slice(-4))"></p>
                    <button type="button" class="flck-btn" data-kind="go" x-on:click="reset()">{{ $say('Back to the store', 'بازگشت به فروشگاه') }}</button>
                </section>
            </div>

            <aside class="flck-aside" aria-label="{{ $say('Order summary', 'خلاصهٔ سفارش') }}">
                <h5>{{ $say('Order summary', 'خلاصهٔ سفارش') }}</h5>
                <template x-if="items.length">
                    <div style="display: contents">
                        <template x-for="item in items" :key="item.id">
                            <div>
                                <span x-text="n(item.qty) + ' × ' + item.name"></span>
                                <span x-text="n(item.price * item.qty) + ' €'"></span>
                            </div>
                        </template>
                    </div>
                </template>
                <div x-show="!items.length" x-cloak><span>{{ $say('No items', 'بی‌قلم') }}</span><span>0 €</span></div>
                <div>
                    <span>{{ $say('Shipping', 'ارسال') }}</span>
                    <span x-show="shipping > 0" x-text="n(shipping) + ' €'"></span>
                    <span class="flck-free" x-show="shipping === 0" x-cloak>{{ $say('Free', 'رایگان') }}</span>
                </div>
                <div>
                    <span>{{ $say('VAT', 'مالیات') }} ({{ $num('19') }}{{ $say('%', '٪') }})</span>
                    <span x-text="n(vat) + ' €'"></span>
                </div>
                <div class="flck-tot">
                    <span>{{ $say('Total', 'جمع') }}</span>
                    <span x-text="n(total) + ' €'"></span>
                </div>
                <p class="flck-ship-hint">
                    {{ $say('Shipping is free from €150 — steppers above recompute everything instantly.', 'ارسال از ۱۵۰ یورو رایگان است — پله‌های بالا همه‌چیز را همان لحظه دوباره می‌شمارند.') }}
                </p>
            </aside>
        </div>

        <p class="flck-hint">{{ $say('The whole journey is local: validation, masks, decline, order number and delivery date are computed in the frame — swap the Alpine store for your backend when you take it to production.', 'کل سفر محلی است: اعتبارسنجی، ماسک‌ها، ردشدن، شمارهٔ سفارش و تاریخ تحویل داخل همین قاب حساب می‌شوند — برای پروداکشن، فروشگاهِ Alpine را با بک‌اند خودتان عوض کنید.') }}</p>
    </section>
</div>
