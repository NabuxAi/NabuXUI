{{--
    Polaris Contextual Save Bar over a live product form: the first keystroke
    makes the form dirty and slides the dark bar in from the top edge with a
    real dirty status; Save walks through saving → saved-tick → hide, Discard
    reverts the fields to their snapshot. The variants row freezes the three
    bar states side by side.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp
<style>
    [x-cloak] { display: none !important; }
    .plsb-root {
        --plsb-surface: #FFFFFF; --plsb-raised: #F6F6F6; --plsb-text: #303030; --plsb-subdued: #616161;
        --plsb-border: #E3E3E3; --plsb-strong: #8A8A8A; --plsb-hover: color-mix(in srgb, var(--plsb-text) 4%, var(--plsb-surface));
        --plsb-green: #008060; --plsb-on-green: #FFFFFF; --plsb-green-hover: #004C3F;
        --plsb-bar: #1A1A1A; --plsb-on-bar: #F1F1F1; --plsb-bar-sub: #B5B5B5;
        --plsb-focus: #005BD3;
        font-family: Inter, -apple-system, "Segoe UI", Roboto, system-ui, sans-serif;
        display: grid; gap: 1.5rem; justify-items: center;
    }
    html[data-theme="dark"] .plsb-root {
        --plsb-surface: #202020; --plsb-raised: #2B2B2B; --plsb-text: #F1F1F1; --plsb-subdued: #B5B5B5;
        --plsb-border: #454545; --plsb-strong: #8A8A8A;
        --plsb-green: #00A97F; --plsb-on-green: #08211A; --plsb-green-hover: #00BA93;
        --plsb-bar: #111111; --plsb-on-bar: #F1F1F1; --plsb-bar-sub: #B5B5B5;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .plsb-root {
            --plsb-surface: #202020; --plsb-raised: #2B2B2B; --plsb-text: #F1F1F1; --plsb-subdued: #B5B5B5;
            --plsb-border: #454545; --plsb-strong: #8A8A8A;
            --plsb-green: #00A97F; --plsb-on-green: #08211A; --plsb-green-hover: #00BA93;
            --plsb-bar: #111111; --plsb-on-bar: #F1F1F1; --plsb-bar-sub: #B5B5B5;
        }
    }
    .plsb-stage { position: relative; overflow: clip; inline-size: min(100%, 30rem); padding: 4.2rem 1.25rem 1.4rem; border: 1px solid var(--plsb-border); border-radius: 12px; background: var(--plsb-surface); box-shadow: 0 1px 4px rgba(0, 0, 0, .07); }
    .plsb-bar { position: absolute; inset-inline: 0; inset-block-start: 0; z-index: 8; display: flex; flex-wrap: wrap; align-items: center; gap: .5rem .8rem; min-block-size: 3.2rem; padding: .55rem 1.1rem; background: var(--plsb-bar); color: var(--plsb-on-bar); box-shadow: 0 10px 26px -12px rgba(0, 0, 0, .55); }
    .plsb-status { display: inline-flex; align-items: center; gap: .5rem; font: 500 .82rem/1.3 Inter, system-ui, sans-serif; color: var(--plsb-on-bar); }
    .plsb-dot { inline-size: .5rem; aspect-ratio: 1; border-radius: 50%; background: #FFC96B; }
    .plsb-status[data-state="saved"] { color: #35C29A; }
    .plsb-spin { inline-size: .95rem; aspect-ratio: 1; border-radius: 50%; border: 2px solid color-mix(in srgb, var(--plsb-on-bar) 30%, transparent); border-block-start-color: var(--plsb-on-bar); animation: plsb-turn .7s linear infinite; }
    @keyframes plsb-turn { to { rotate: 360deg; } }
    .plsb-actions { display: flex; gap: .5rem; margin-inline-start: auto; }
    .plsb-discard { block-size: 2rem; padding-inline: .7rem; border: none; border-radius: 8px; background: transparent; color: var(--plsb-on-bar); cursor: pointer; font: 500 .8rem/1 Inter, system-ui, sans-serif; }
    .plsb-discard:hover { background: color-mix(in srgb, var(--plsb-on-bar) 12%, transparent); }
    .plsb-save { display: inline-flex; align-items: center; gap: .45rem; block-size: 2rem; padding-inline: .95rem; border: none; border-radius: 8px; background: var(--plsb-green); color: var(--plsb-on-green); cursor: pointer; font: 500 .8rem/1 Inter, system-ui, sans-serif; }
    .plsb-save:hover:not(:disabled) { background: var(--plsb-green-hover); }
    .plsb-save:disabled { opacity: .6; cursor: wait; }
    .plsb-discard:focus-visible, .plsb-save:focus-visible, .plsb-field input:focus-visible { outline: 2px solid var(--plsb-focus); outline-offset: 2px; }
    .plsb-form { display: grid; gap: .9rem; }
    .plsb-field { display: grid; gap: .3rem; }
    .plsb-field label { font: 500 .76rem/1.2 Inter, system-ui, sans-serif; color: var(--plsb-text); }
    .plsb-field input { block-size: 2.3rem; padding-inline: .75rem; border: 1px solid var(--plsb-strong); border-radius: 8px; background: var(--plsb-surface); color: var(--plsb-text); font: 400 .85rem/1 Inter, system-ui, sans-serif; }
    .plsb-field input::placeholder { color: color-mix(in srgb, var(--plsb-subdued) 80%, transparent); }
    .plsb-inline { display: flex; align-items: center; gap: .7rem; }
    .plsb-switch { position: relative; inline-size: 2.4rem; block-size: 1.35rem; border: none; border-radius: 999px; background: var(--plsb-strong); cursor: pointer; transition: background .15s; }
    .plsb-switch::after { content: ''; position: absolute; inset-block-start: .14rem; inset-inline-start: .14rem; inline-size: 1.07rem; aspect-ratio: 1; border-radius: 50%; background: #fff; box-shadow: 0 1px 3px rgba(0, 0, 0, .3); transition: translate .18s cubic-bezier(.2, 0, 0, 1); }
    html[dir="rtl"] .plsb-switch::after { inset-inline-start: auto; inset-inline-end: .14rem; }
    .plsb-switch[aria-checked="true"] { background: var(--plsb-green); }
    html[dir="rtl"] .plsb-switch[aria-checked="true"]::after { translate: -1.06rem 0; }
    html:not([dir="rtl"]) .plsb-switch[aria-checked="true"]::after { translate: 1.06rem 0; }
    .plsb-switch:focus-visible { outline: 2px solid var(--plsb-focus); outline-offset: 2px; }
    .plsb-small { font: 400 .78rem/1.5 Inter, system-ui, sans-serif; color: var(--plsb-subdued); }
    .plsb-dirtybadge { display: inline-flex; align-items: center; gap: .35rem; margin-inline-start: auto; font: 500 .72rem/1 Inter, system-ui, sans-serif; color: var(--plsb-subdued); }
    .plsb-enter { transition: translate .28s cubic-bezier(.2, 0, 0, 1), opacity .28s; }
    .plsb-enter-start { translate: 0 -100%; opacity: 0; }
    .plsb-enter-end { translate: 0 0; opacity: 1; }
    .plsb-variants { inline-size: min(100%, 42rem); display: flex; flex-wrap: wrap; gap: 1.25rem 2rem; justify-content: center; align-items: stretch; }
    .plsb-vcell { display: grid; gap: .5rem; justify-items: center; }
    .plsb-vcell > small { font: 500 .72rem/1 Inter, system-ui, sans-serif; color: var(--nx-text-muted); }
    .plsb-mini { position: relative; inline-size: 13.5rem; block-size: 3.2rem; border-radius: 10px; background: var(--plsb-bar); color: var(--plsb-on-bar); display: flex; align-items: center; gap: .5rem; padding: .55rem .8rem; font: 500 .76rem/1 Inter, system-ui, sans-serif; box-shadow: 0 8px 20px -10px rgba(0, 0, 0, .5); }
    :where(.nx-js) .pg:has(.plsb-root) .nx-data-table[data-nx-reveal] tbody tr {
        opacity: 1; translate: none;
    }
    @media (max-width: 480px) {
        .pg:has(.plsb-root) .nx-data-table :is(th, td) {
            white-space: normal; padding-inline: .5rem; overflow-wrap: break-word;
        }
        .pg:has(.plsb-root) pre code { white-space: pre-wrap; overflow-wrap: anywhere; }
    }
    @media (prefers-reduced-motion: reduce) {
        .plsb-root * { animation-duration: .01ms !important; transition-duration: .01ms !important; }
    }
</style>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Touch the form — the bar slides in', 'فرم را لمس کن — نوار می‌لغزد بیرون') }}</h3>
        <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
            {{ $say('Type in any field: the dark bar drops from the top edge with the dirty status. «Save» walks through saving and a green tick before it hides; «Discard» puts every field back.', 'در هر فیلدی تایپ کنید: نوار تیره با وضعیت ذخیره‌نشده از لبهٔ بالا می‌افتد. «ذخیره» حالت ذخیره و تیک سبز را تجربه می‌کند و بعد جمع می‌شود؛ «دور انداختن» همهٔ فیلدها را برمی‌گرداند.') }}
        </p>
    </div>

    <div class="plsb-root" style="inline-size: 100%"
        x-data="{
            name: '{{ $say('Soy candle, minimal', 'شمع سویا مینیمال') }}',
            price: '{{ $say('240000', '۲۴۰۰۰۰') }}',
            active: true,
            snap: null, saving: false, saved: false,
            init() { this.snap = { name: this.name, price: this.price, active: this.active } },
            get dirty() { return this.name !== this.snap.name || this.price !== this.snap.price || this.active !== this.snap.active },
            get open() { return this.dirty || this.saving || this.saved },
            save() {
                this.saving = true;
                setTimeout(() => {
                    this.saving = false; this.saved = true;
                    this.snap = { name: this.name, price: this.price, active: this.active };
                    setTimeout(() => this.saved = false, 900);
                }, 900);
            },
            discard() { this.name = this.snap.name; this.price = this.snap.price; this.active = this.snap.active },
        }">
        <div class="plsb-stage">
            <div class="plsb-bar" x-show="open" x-cloak x-transition:enter="plsb-enter" x-transition:enter-start="plsb-enter-start" x-transition:enter-end="plsb-enter-end"
                x-bind:data-dirty="dirty" role="status" x-bind:aria-label="saving ? '{{ $say('Saving changes', 'در حال ذخیرهٔ تغییرات') }}' : '{{ $say('Unsaved changes', 'تغییرات ذخیره‌نشده') }}'">
                <span class="plsb-status" x-bind:data-state="saved ? 'saved' : 'live'">
                    <template x-if="saving"><span class="plsb-spin" aria-hidden="true"></span></template>
                    <template x-if="saved"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="m8.4 12.3 2.5 2.5 4.7-5.2"/></svg></template>
                    <template x-if="!saving && !saved"><span class="plsb-dot" aria-hidden="true"></span></template>
                    <span x-text="saved ? '{{ $say('Saved', 'ذخیره شد') }}' : saving ? '{{ $say('Saving…', 'در حال ذخیره…') }}' : '{{ $say('Unsaved changes', 'تغییرات ذخیره‌نشده') }}'"></span>
                </span>
                <span class="plsb-actions">
                    <button type="button" class="plsb-discard" x-on:click="discard()" x-bind:disabled="saving">{{ $say('Discard', 'دور انداختن') }}</button>
                    <button type="button" class="plsb-save" x-on:click="save()" x-bind:disabled="saving || !dirty">
                        <span x-text="'{{ $say('Save', 'ذخیره') }}'"></span>
                    </button>
                </span>
            </div>

            <div class="plsb-form">
                <div class="plsb-field">
                    <label for="plsb-name">{{ $say('Product title', 'عنوان محصول') }}</label>
                    <input id="plsb-name" type="text" x-model="name" autocomplete="off">
                </div>
                <div class="plsb-field">
                    <label for="plsb-price">{{ $say('Price (toman)', 'قیمت (تومان)') }}</label>
                    <input id="plsb-price" type="text" inputmode="numeric" x-model="price" autocomplete="off">
                </div>
                <div class="plsb-inline">
                    <button type="button" class="plsb-switch" role="switch" x-bind:aria-checked="active ? 'true' : 'false'" x-on:click="active = !active" aria-label="{{ $say('Visible on storefront', 'نمایان در ویترین') }}"></button>
                    <span class="plsb-small">{{ $say('Visible on the storefront', 'نمایان در ویترین فروشگاه') }}</span>
                    <span class="plsb-dirtybadge" x-show="dirty" x-cloak>
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                        {{ $say('edited', 'ویرایش‌شده') }}
                    </span>
                </div>
                <p class="plsb-small" style="margin: 0">{{ $say('The snapshot Discard restores to is whatever the bar last saved.', 'عکسی که «دور انداختن» به آن برمی‌گرداند، همان است که نوار آخرین بار ذخیره کرده.') }}</p>
            </div>
        </div>
    </div>
</section>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('The three states of the bar', 'سه حالت نوار') }}</h3>
        <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
            {{ $say('Dirty carries the amber dot, saving spins, and saved wears the green tick — the same bar, frozen mid-breath.', 'حالت کثیف نقطهٔ کهربایی دارد، ذخیره می‌چرخد و ذخیره‌شده تیک سبز می‌پوشد — همان نوار، در وسطِ نفس.') }}
        </p>
    </div>
    <div class="plsb-root plsb-variants">
        <div class="plsb-vcell">
            <div class="plsb-mini"><span class="plsb-dot" style="background: #FFC96B" aria-hidden="true"></span>{{ $say('Unsaved changes', 'تغییرات ذخیره‌نشده') }}</div>
            <small>dirty</small>
        </div>
        <div class="plsb-vcell">
            <div class="plsb-mini"><span class="plsb-spin" aria-hidden="true"></span>{{ $say('Saving…', 'در حال ذخیره…') }}</div>
            <small>saving</small>
        </div>
        <div class="plsb-vcell">
            <div class="plsb-mini"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#35C29A" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="m8.4 12.3 2.5 2.5 4.7-5.2"/></svg><span style="color: #35C29A">{{ $say('Saved', 'ذخیره شد') }}</span></div>
            <small>saved</small>
        </div>
    </div>
</section>
