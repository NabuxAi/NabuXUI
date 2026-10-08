{{--
    An iOS sheet with medium and large detents over a photo-detail page: the
    grabber drags, letting go mid-way springs to the nearest detent (position
    plus flick velocity), the parent page recedes, rounds and scales back, and
    the scrim tap or Escape closes it.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<style>
    .iosheet-root {
        --ios-blue: #007AFF; --ios-green: #34C759; --ios-red: #FF3B30; --ios-orange: #FF9500;
        --ios-teal: #5AC8FA; --ios-indigo: #5856D6; --ios-gray: #8E8E93;
        --ios-label: #000000; --ios-label-2: rgba(60, 60, 67, .6); --ios-label-3: rgba(60, 60, 67, .3);
        --ios-fill: rgba(120, 120, 128, .2); --ios-sep: rgba(60, 60, 67, .29);
        --ios-bg: #F2F2F7; --ios-card: #FFFFFF; --ios-gray5: #E5E5EA;
        font-family: -apple-system, system-ui, 'Segoe UI', sans-serif; color: var(--ios-label);
    }
    html[data-theme="dark"] .iosheet-root {
        --ios-blue: #0A84FF; --ios-green: #30D158; --ios-red: #FF453A; --ios-orange: #FF9F0A;
        --ios-teal: #64D2FF; --ios-indigo: #5E5CE6; --ios-gray: #8E8E93;
        --ios-label: #FFFFFF; --ios-label-2: rgba(235, 235, 245, .6); --ios-label-3: rgba(235, 235, 245, .3);
        --ios-fill: rgba(120, 120, 128, .36); --ios-sep: rgba(84, 84, 88, .6);
        --ios-bg: #000000; --ios-card: #1C1C1E; --ios-gray5: #2C2C2E;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .iosheet-root {
            --ios-blue: #0A84FF; --ios-green: #30D158; --ios-red: #FF453A; --ios-orange: #FF9F0A;
            --ios-teal: #64D2FF; --ios-indigo: #5E5CE6; --ios-gray: #8E8E93;
            --ios-label: #FFFFFF; --ios-label-2: rgba(235, 235, 245, .6); --ios-label-3: rgba(235, 235, 245, .3);
            --ios-fill: rgba(120, 120, 128, .36); --ios-sep: rgba(84, 84, 88, .6);
            --ios-bg: #000000; --ios-card: #1C1C1E; --ios-gray5: #2C2C2E;
        }
    }
    .iosheet-root :focus-visible { outline: 2px solid var(--ios-blue); outline-offset: 2px; }

    .iosheet-stage { position: relative; overflow: clip; inline-size: min(100%, 22rem); block-size: 26rem; margin-inline: auto;
                     border-radius: 20px; background: var(--ios-bg); isolation: isolate;
                     box-shadow: 0 18px 44px rgba(0, 0, 0, .18), inset 0 0 0 1px rgba(0, 0, 0, .06); }
    .iosheet-page { position: absolute; inset: 0; padding: 1rem .9rem 0; overflow-y: auto; scrollbar-width: none; background: var(--ios-bg);
                    transition: scale .5s cubic-bezier(.32, .72, .28, 1), border-radius .5s cubic-bezier(.32, .72, .28, 1); }
    .iosheet-page::-webkit-scrollbar { display: none; }
    .iosheet-page.back { scale: .92; border-radius: 1.4rem; }
    .iosheet-page h4 { margin: 0 0 .15rem; font: 700 1.35rem/-apple-system, system-ui, sans-serif; }
    .iosheet-page .sub { margin: 0 0 .8rem; font-size: .82rem; color: var(--ios-label-2); }
    .iosheet-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: .5rem; }
    .iosheet-grid figure { margin: 0; }
    .iosheet-grid .ph { aspect-ratio: 1; border-radius: 10px; }
    .iosheet-grid figcaption { margin-top: .25rem; font-size: .68rem; color: var(--ios-label-2); text-align: center; }
    .iosheet-grid button.ph { display: block; inline-size: 100%; transition: scale .15s ease; }
    .iosheet-grid button.ph:active { scale: .94; }

    .iosheet-scrim { position: absolute; inset: 0; z-index: 1; background: rgba(0, 0, 0, .4); opacity: 0; pointer-events: none;
                     transition: opacity .4s ease; }
    .iosheet-scrim.on { opacity: 1; pointer-events: auto; }

    .iosheet-card { position: absolute; inset-inline: 0; inset-block-end: 0; z-index: 2; display: flex; flex-direction: column;
                    block-size: 97%; border-radius: 12px 12px 0 0; background: var(--ios-card);
                    box-shadow: 0 -8px 40px rgba(0, 0, 0, .25); overflow: clip;
                    transition: translate .5s cubic-bezier(.32, .72, .28, 1.05); }
    .iosheet-card.drag { transition: none; }
    .iosheet-head { flex: none; padding: 6px 1rem 8px; touch-action: none; cursor: grab; user-select: none; }
    .iosheet-card.drag .iosheet-head { cursor: grabbing; }
    .iosheet-grab { display: block; inline-size: 36px; block-size: 5px; margin-inline: auto; border-radius: 999px;
                    background: linear-gradient(90deg, #a1a1a8, #68686e); }
    .iosheet-card .body { flex: 1; overflow-y: auto; scrollbar-width: thin; padding: .4rem 1rem 1.2rem; }
    .iosheet-photo { aspect-ratio: 4/3; border-radius: 10px; background:
                     radial-gradient(42% 30% at 62% 34%, #fff7d6 0%, transparent 60%),
                     linear-gradient(180deg, #FF9F0A, #FF3B30 52%, #5856D6); }
    .iosheet-card h4 { margin: .8rem 0 .1rem; font: 600 1.05rem/-apple-system, system-ui, sans-serif; }
    .iosheet-card .meta { margin: 0 0 .8rem; font-size: .8rem; color: var(--ios-label-2); }
    .iosheet-actions { display: flex; gap: .5rem; margin-block-end: .9rem; }
    .iosheet-actions span { flex: 1; display: grid; place-items: center; block-size: 34px; border-radius: 8px;
                            background: var(--ios-fill); font-size: .78rem; font-weight: 500; }
    .iosheet-exif { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: .5rem; }
    .iosheet-exif div { padding: .5rem .7rem; border-radius: 10px; background: var(--ios-gray5); }
    .iosheet-exif b { display: block; font-size: .82rem; font-weight: 600; }
    .iosheet-exif span { font-size: .7rem; color: var(--ios-label-2); }

    @media (prefers-reduced-motion: reduce) {
        .iosheet-card, .iosheet-page, .iosheet-scrim, .iosheet-grid button.ph { transition-duration: 1ms; }
    }

    /* The «Important props» table that the demo page renders after this partial
       fades its rows in only when a scroll observer marks the table revealed.
       The section sits below the fold, so in full-page captures the observer
       never fires and the body stays invisible (on this Livewire page .nx-live
       also switches off the 2.5s CSS failsafe). The rule ships only with this
       partial, so it is page-scoped: show the rows unconditionally. */
    .nx-data-table[data-nx-reveal]:not([data-nx-revealed]) tbody tr { opacity: 1; translate: none; }
</style>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A sheet that parks at two heights', 'شیت‌ای که در دو ارتفاع پارک می‌شود') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Open the photo details: the parent page steps back, rounds and scales to 92% while the sheet rises to the medium or large detent. Drag the grabber (or the header) — letting go mid-way springs to the nearest detent using position and flick velocity. Scrim tap or Escape dismisses; for keyboards, the grabber itself is a button that swaps detents.', 'جزئیات عکس را باز کنید: صفحهٔ پدر عقب می‌رود، گرد و به ۹۲٪ کوچک می‌شود و شیت به توقف‌گاه متوسط یا بلند بالا می‌آید. دستگیره (یا سرِ شیت) را بکشید — رها کردن وسط راه با توجه به جای دست و سرعتِ فلیک به نزدیک‌ترین توقف‌گاه فنر می‌خورد. لمس اسکریم یا Escape می‌بندد؛ برای کیبورد، خود دستگیره دکمه‌ای است که توقف‌گاه را عوض می‌کند.') }}
        </p>
    </div>

    <div class="iosheet-root" x-data="{
            open: false, detent: 'medium', dragging: false, y: 0, y0: 0, base: 0, v: 0, lastY: 0, lastT: 0,
            sheetH() { return this.$refs.sheet ? this.$refs.sheet.clientHeight : 1 },
            show(d) { this.detent = d; this.y = 0; this.open = true; this.$nextTick(() => this.$refs.grab.focus()) },
            close() { this.open = false; this.dragging = false; this.y = 0 },
            grabToggle() { this.detent = this.detent === 'medium' ? 'large' : 'medium' },
            off() { return this.sheetH() * .485 },
            ds(e) { if (!this.open) return; this.dragging = true; this.y0 = e.clientY; this.lastY = e.clientY; this.lastT = performance.now();
                    this.v = 0; this.base = this.detent === 'medium' ? this.off() : 0; e.preventDefault() },
            dm(e) { if (!this.dragging) return; const now = performance.now(); const dt = Math.max(1, now - this.lastT);
                    this.v = (e.clientY - this.lastY) / dt; this.lastY = e.clientY; this.lastT = now;
                    this.y = Math.min(this.off(), Math.max(0, this.base + (e.clientY - this.y0))) },
            du() { if (!this.dragging) return; this.dragging = false; const max = this.off();
                   const projected = this.y + this.v * 140;
                   this.detent = (this.v > .5 || projected > max * .55) ? 'medium' : 'large'; this.y = 0 },
            pose() { if (!this.open) return 'translate: 0 105%';
                     const offPx = this.dragging ? this.y : (this.detent === 'medium' ? this.off() : 0);
                     return 'translate: 0 ' + offPx + 'px' },
        }"
         x-on:keydown.escape.window="close()">
        <div class="iosheet-stage" x-ref="stage">
            <div class="iosheet-page" :class="{ back: open }">
                <h4>{{ $say('My Albums', 'آلبوم‌های من') }}</h4>
                <p class="sub">{{ $say('Tehran, autumn 1404 · 24 photos', 'تهران، پاییز ۱۴۰۴ · ۲۴ عکس') }}</p>
                <div class="iosheet-grid">
                    <figure>
                        <button type="button" class="ph" style="aspect-ratio: 1; border-radius: 10px; background: radial-gradient(42% 30% at 62% 34%, #fff7d6 0%, transparent 60%), linear-gradient(180deg, #FF9F0A, #FF3B30 52%, #5856D6)"
                                x-on:click="show('medium')" aria-label="{{ $say('Open Tabiat Bridge sunset', 'باز کردن غروب پل طبیعت') }}"></button>
                        <figcaption>{{ $say('Tabiat Bridge', 'پل طبیعت') }}</figcaption>
                    </figure>
                    <figure>
                        <span class="ph" style="display: block; background: linear-gradient(160deg, #30D158, #0A2A6B)" aria-hidden="true"></span>
                        <figcaption>{{ $say('Mellat Park', 'پارک ملت') }}</figcaption>
                    </figure>
                    <figure>
                        <span class="ph" style="display: block; background: linear-gradient(160deg, #64D2FF, #5856D6)" aria-hidden="true"></span>
                        <figcaption>{{ $say('Milad Tower', 'برج میلاد') }}</figcaption>
                    </figure>
                    <figure>
                        <span class="ph" style="display: block; background: linear-gradient(160deg, #FF3B75, #FF9F0A)" aria-hidden="true"></span>
                        <figcaption>{{ $say('Valiasr Street', 'خیابان ولی‌عصر') }}</figcaption>
                    </figure>
                    <figure>
                        <span class="ph" style="display: block; background: linear-gradient(160deg, #FF9500, #5856D6)" aria-hidden="true"></span>
                        <figcaption>{{ $say('Bazaar rooftop', 'پشت‌بام بازار') }}</figcaption>
                    </figure>
                    <figure>
                        <span class="ph" style="display: block; background: linear-gradient(160deg, #5AC8FA, #007AFF)" aria-hidden="true"></span>
                        <figcaption>{{ $say('Ab-o-Atash Park', 'پارک آب و آتش') }}</figcaption>
                    </figure>
                </div>
            </div>

            <button type="button" class="iosheet-scrim" :class="{ on: open }" x-on:click="close()"
                    aria-label="{{ $say('Close sheet', 'بستن شیت') }}" :tabindex="open ? 0 : -1"></button>

            <div class="iosheet-card" x-ref="sheet" :class="{ drag: dragging }" :style="pose()" role="dialog"
                 aria-label="{{ $say('Photo details', 'جزئیات عکس') }}" :aria-hidden="!open">
                <div class="iosheet-head" x-on:pointerdown="ds($event)" x-on:pointermove.window="dm($event)"
                     x-on:pointerup.window="du()" x-on:pointercancel.window="du()">
                    <button type="button" x-ref="grab" class="iosheet-grab" x-on:click="grabToggle()"
                            aria-label="{{ $say('Switch detent', 'تغییر توقف‌گاه') }}"></button>
                </div>
                <div class="body">
                    <div class="iosheet-photo" role="img" aria-label="{{ $say('Sunset over Tabiat Bridge', 'غروب روی پل طبیعت') }}"></div>
                    <h4>{{ $say('Sunset at Tabiat Bridge', 'غروب پل طبیعت') }}</h4>
                    <p class="meta">{{ $say('Tehran · 20 Mehr 1404 · 18:12', 'تهران · ۲۰ مهر ۱۴۰۴ · ۱۸:۱۲') }}</p>
                    <div class="iosheet-actions" aria-hidden="true">
                        <span>{{ $say('Share', 'اشتراک‌گذاری') }}</span>
                        <span>{{ $say('Edit', 'ویرایش') }}</span>
                        <span>{{ $say('Favourite', 'علاقه‌مندی') }}</span>
                    </div>
                    <div class="iosheet-exif">
                        <div><b>iPhone 15 Pro</b><span>{{ $say('Camera', 'دوربین') }}</span></div>
                        <div><b>ƒ/1.78</b><span>{{ $say('Aperture', 'دیافراگم') }}</span></div>
                        <div><b>24 mm</b><span>{{ $say('Focal length', 'فاصلهٔ کانونی') }}</span></div>
                        <div><b>ISO 64</b><span>{{ $say('Sensitivity', 'حساسیت') }}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
