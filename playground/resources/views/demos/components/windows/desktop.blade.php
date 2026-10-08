{{--
    The windows group's capstone: a whole Windows 11 desktop on one stage.
    Real windows (Files, Mail, Notes) that drag by their title bar, snap to
    the edges, minimise, maximise and close; a centred taskbar with a live
    clock; and a start menu of live Metro tiles that keep bringing fresh
    weather, mail counts and photos. Everything runs on inline Alpine.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<section class="pg-box">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('One whole Windows on one stage', 'یک ویندوز کامل، روی یک صحنه') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Drag the windows by their title bar, throw them at the edges to snap, and open Start — the Metro tiles keep bringing fresh weather, mail and photos.', 'پنجره‌ها را از نوار عنوان بکشید، به لبه‌ها پرت کنید تا بچسبند و منوی شروع را باز کنید — کاشی‌های مترو همیشه آب‌وهوا، نامه و عکس تازه می‌آورند.') }}
        </p>
    </div>

    <div class="fldesk-root" x-ref="stage"
        x-data="{
            fa: document.documentElement.lang === 'fa',
            z: 40, active: 'files',
            zs: { files: 41, mail: 42, notes: 43 },
            open: { files: true, mail: true, notes: false },
            min: { files: false, mail: false, notes: false },
            maxed: { files: false, mail: false, notes: false },
            snapped: { files: null, mail: null, notes: null },
            rest: {},
            pos: { files: { x: 28, y: 22 }, mail: { x: 170, y: 72 }, notes: { x: 80, y: 140 } },
            drag: null, snapZone: '',
            start: false, apps: false, sel: 0,
            note: '{{ $say('Notes:\n- call Sara about the contract\n- ship the desktop demo', 'یادداشت‌ها:\n- تماس با سارا دربارهٔ قرارداد\n- تحویل دموی میزکار') }}',
            temps: [28, 29, 27], wstep: 0, mstep: 0, mcount: 3,
            clock: '14:05',
            init() {
                this.tickClock();
                this._clock = setInterval(() => this.tickClock(), 30000);
                const stage = this.$refs.stage;
                ['files', 'mail', 'notes'].forEach(id => {
                    const el = this.$refs['w-' + id]; if (!el) return;
                    const maxX = Math.max(0, stage.clientWidth - el.offsetWidth - 8);
                    const maxY = Math.max(0, stage.clientHeight - 82);
                    this.pos[id].x = Math.min(this.pos[id].x, maxX);
                    this.pos[id].y = Math.min(this.pos[id].y, maxY);
                });
                let m = NaN;
                try { m = parseFloat(getComputedStyle(this.$el).getPropertyValue('--nx-motion')); } catch (e) {}
                const motion = Number.isFinite(m) ? m : 1;
                if (motion > 0) {
                    this._live = setInterval(() => {
                        this.wstep = (this.wstep + 1) % 3;
                        this.mstep = (this.mstep + 1) % 3;
                        this.mcount = [3, 4, 2][this.mstep];
                    }, 4000 * motion);
                }
            },
            destroy() { clearInterval(this._clock); clearInterval(this._live); },
            fd(n) { const s = String(n); return this.fa ? s.replace(/[0-9]/g, d => '۰۱۲۳۴۵۶۷۸۹'[+d]) : s; },
            tickClock() {
                try {
                    this.clock = new Intl.DateTimeFormat(this.fa ? 'fa-IR' : 'en-GB', { hour: '2-digit', minute: '2-digit', hour12: false }).format(new Date());
                } catch (e) { this.clock = ''; }
            },
            focus(id) { this.zs[id] = ++this.z; this.active = id; },
            openWin(id) { this.open[id] = true; this.min[id] = false; this.start = false; this.focus(id); },
            closeWin(id) { this.open[id] = false; this.min[id] = false; this.maxed[id] = false; this.snapped[id] = null; if (this.active === id) this.active = null; },
            minimise(id) { this.min[id] = true; if (this.active === id) this.active = null; },
            maximise(id) { this.maxed[id] = !this.maxed[id]; this.snapped[id] = null; this.focus(id); },
            tbClick(id) {
                if (!this.open[id]) { this.openWin(id); return; }
                if (this.min[id]) { this.min[id] = false; this.focus(id); return; }
                if (this.active === id) { this.minimise(id); } else { this.focus(id); }
            },
            startDrag(e, id) {
                if (e.button !== undefined && e.button !== 0) return;
                if (e.target.closest('button')) return;
                if (this.maxed[id] || this.snapped[id]) {
                    this.rest[id] = { ...this.pos[id] };
                    this.maxed[id] = false; this.snapped[id] = null;
                }
                this.focus(id); this.start = false;
                this.drag = { id, sx: e.clientX, sy: e.clientY, ox: this.pos[id].x, oy: this.pos[id].y, rect: this.$refs.stage.getBoundingClientRect() };
                try { e.currentTarget.setPointerCapture(e.pointerId); } catch (err) {}
            },
            moveDrag(e) {
                const d = this.drag; if (!d) return;
                const st = d.rect;
                const lx = c => this.fa ? (st.right - c) : (c - st.left);
                const el = this.$refs['w-' + d.id];
                const maxX = Math.max(0, st.width - el.offsetWidth);
                const maxY = Math.max(0, st.height - 82);
                const p = this.pos[d.id];
                p.x = Math.min(maxX, Math.max(0, d.ox + (lx(e.clientX) - lx(d.sx))));
                p.y = Math.min(maxY, Math.max(0, d.oy + (e.clientY - d.sy)));
                const ex = lx(e.clientX), ty = e.clientY - st.top;
                this.snapZone = ty <= 12 ? 'top' : (ex <= 12 ? 'start' : (st.width - ex <= 12 ? 'end' : ''));
            },
            endDrag() {
                const d = this.drag; if (!d) return;
                const id = d.id, z = this.snapZone;
                if (z === 'top') { this.rest[id] = { ...this.pos[id] }; this.maxed[id] = true; }
                else if (z === 'start' || z === 'end') { this.rest[id] = { ...this.pos[id] }; this.snapped[id] = z; }
                this.snapZone = ''; this.drag = null;
            },
            wstyle(id) {
                const zi = 'z-index:' + this.zs[id] + ';';
                const T = 48;
                if (this.maxed[id]) return zi + 'inset-inline-start:0;inset-block-start:0;inline-size:100%;block-size:calc(100% - ' + T + 'px);border-radius:0;';
                if (this.snapped[id] === 'start') return zi + 'inset-inline-start:0;inset-block-start:0;inline-size:50%;block-size:calc(100% - ' + T + 'px);border-radius:0;';
                if (this.snapped[id] === 'end') return zi + 'inset-inline-end:0;inset-block-start:0;inline-size:50%;block-size:calc(100% - ' + T + 'px);border-radius:0;';
                const p = this.pos[id];
                return zi + 'inset-inline-start:' + p.x + 'px;inset-block-start:' + p.y + 'px;';
            },
            bgClose(e) {
                if (!e.target.closest('.fldesk-win,.fldesk-taskbar,.fldesk-startmenu,.fldesk-startbtn,.fldesk-dicons')) {
                    this.start = false; this.active = null;
                }
            },
        }"
        x-on:click="bgClose($event)"
        x-on:keydown.escape.window="start = false"
        style="font-size: 13px">

        {{-- Snap previews --}}
        <div class="fldesk-snapzone" x-cloak x-show="snapZone" x-transition.opacity.duration.150ms :data-zone="snapZone" aria-hidden="true"></div>

        {{-- Desktop icons --}}
        <div class="fldesk-dicons">
            <button type="button" class="fldesk-dicon" x-on:click="openWin('files')">
                <i><svg viewBox="0 0 24 24" width="34" height="34" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4.5" width="18" height="12.5" rx="1.5"/><path d="M9.5 20.5h5M12 17v3.5"/></svg></i>
                <span>{{ $say('This PC', 'این کامپیوتر') }}</span>
            </button>
            <button type="button" class="fldesk-dicon" x-on:click="openWin('files')">
                <i data-bin><svg viewBox="0 0 24 24" width="34" height="34" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4.5 6.5h15M9.5 6V4.5a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1V6M7 6.5l.8 12a1.5 1.5 0 0 0 1.5 1.4h5.4a1.5 1.5 0 0 0 1.5-1.4l.8-12"/><path d="M10 10.5v6M14 10.5v6"/></svg></i>
                <span>{{ $say('Recycle Bin', 'سطل بازیافت') }}</span>
            </button>
            <button type="button" class="fldesk-dicon" x-on:click="openWin('mail')">
                <i><svg viewBox="0 0 24 24" width="34" height="34" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3.5 6.5A1.5 1.5 0 0 1 5 5h4l2 2.5h8A1.5 1.5 0 0 1 20.5 9v8.5A1.5 1.5 0 0 1 19 19H5a1.5 1.5 0 0 1-1.5-1.5z"/></svg></i>
                <span>{{ $say('Mail', 'نامه‌ها') }}</span>
            </button>
        </div>

        {{-- ============ Window: Files ============ --}}
        <article class="fldesk-win" x-ref="w-files" data-app="files" role="dialog"
            aria-label="{{ $say('Files window', 'پنجرهٔ فایل‌ها') }}"
            x-show="open.files && !min.files"
            x-on:pointerdown="focus('files')"
            :style="wstyle('files')"
            :class="active === 'files' ? 'fldesk-active' : ''"
            x-transition:enter="fldesk-win-t" x-transition:enter-start="fldesk-win-t0" x-transition:enter-end="fldesk-win-t1">
            <header class="fldesk-title"
                x-on:pointerdown="startDrag($event, 'files')"
                x-on:pointermove="moveDrag($event)"
                x-on:pointerup="endDrag($event)"
                x-on:pointercancel="endDrag($event)"
                x-on:lostpointercapture="endDrag($event)">
                <span class="fldesk-appicon" aria-hidden="true"><svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3.5 6.5A1.5 1.5 0 0 1 5 5h4l2 2.5h8A1.5 1.5 0 0 1 20.5 9v8.5A1.5 1.5 0 0 1 19 19H5a1.5 1.5 0 0 1-1.5-1.5z"/></svg></span>
                <span class="fldesk-winname">{{ $say('Files', 'فایل‌ها') }}</span>
                <div class="fldesk-cap">
                    <button type="button" aria-label="{{ $say('Minimise', 'کوچک کردن') }}" x-on:click="minimise('files')"><svg viewBox="0 0 10 10" width="10" height="10" fill="none" stroke="currentColor"><path d="M2 7.4h6"/></svg></button>
                    <button type="button" aria-label="{{ $say('Maximise', 'بزرگ‌نمایی') }}" x-bind:data-maxed="maxed.files ? 'true' : 'false'" x-on:click="maximise('files')">
                        <svg class="fldesk-maxg" viewBox="0 0 10 10" width="10" height="10" fill="none" stroke="currentColor"><rect x="2" y="2" width="6" height="6" rx="1"/></svg>
                        <svg class="fldesk-restg" viewBox="0 0 10 10" width="10" height="10" fill="none" stroke="currentColor"><rect x="2" y="3.6" width="4.4" height="4.4" rx="1"/><path d="M3.8 2h4.2v4.2"/></svg>
                    </button>
                    <button type="button" data-close aria-label="{{ $say('Close', 'بستن') }}" x-on:click="closeWin('files')"><svg viewBox="0 0 10 10" width="10" height="10" fill="none" stroke="currentColor"><path d="m2.4 2.4 5.2 5.2M7.6 2.4 2.4 7.6"/></svg></button>
                </div>
            </header>
            <div class="fldesk-body">
                <aside class="fldesk-side">
                    <button type="button" class="fldesk-sideitem" data-current><i data-pill aria-hidden="true"></i><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="m4 11 8-7 8 7M6.5 9.5V20h11V9.5"/></svg>{{ $say('Home', 'خانه') }}</button>
                    <button type="button" class="fldesk-sideitem"><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4.5" width="18" height="12.5" rx="1.5"/><path d="M9.5 20.5h5M12 17v3.5"/></svg>{{ $say('Desktop', 'دسکتاپ') }}</button>
                    <button type="button" class="fldesk-sideitem"><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 4v10M7.5 10 12 14.5 16.5 10M5 19.5h14"/></svg>{{ $say('Downloads', 'دانلودها') }}</button>
                </aside>
                <div class="fldesk-list">
                    <button type="button" class="fldesk-frow">
                        <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" style="color: #C42B1C"><path d="M6 3.5h8L18.5 8v12.5H6z"/><path d="M14 3.5V8h4.5"/></svg>
                        <span class="fldesk-fname">{{ $say('Q3-report.pdf', 'گزارش-فصل.pdf') }}</span>
                        <span class="fldesk-fmeta"><span>{{ $say('2.4 MB', '۲٫۴ م‌ب') }}</span><span class="fldesk-fdate">{{ $say('14:02', '۱۴:۰۲') }}</span></span>
                    </button>
                    <button type="button" class="fldesk-frow">
                        <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" style="color: #0072C6"><path d="M6 3.5h8L18.5 8v12.5H6z"/><path d="M14 3.5V8h4.5"/></svg>
                        <span class="fldesk-fname">{{ $say('sales-chart.png', 'نمودار-فروش.png') }}</span>
                        <span class="fldesk-fmeta"><span>{{ $say('840 KB', '۸۴۰ ک‌ب') }}</span><span class="fldesk-fdate">{{ $say('12:48', '۱۲:۴۸') }}</span></span>
                    </button>
                    <button type="button" class="fldesk-frow">
                        <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" style="color: #0F7B0F"><path d="M6 3.5h8L18.5 8v12.5H6z"/><path d="M14 3.5V8h4.5"/></svg>
                        <span class="fldesk-fname">{{ $say('budget-1405.xlsx', 'بودجه-۱۴۰۵.xlsx') }}</span>
                        <span class="fldesk-fmeta"><span>{{ $say('1.1 MB', '۱٫۱ م‌ب') }}</span><span class="fldesk-fdate">{{ $say('Yesterday', 'دیروز') }}</span></span>
                    </button>
                    <button type="button" class="fldesk-frow">
                        <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" style="color: #8a8a8a"><path d="M6 3.5h8L18.5 8v12.5H6z"/><path d="M14 3.5V8h4.5"/></svg>
                        <span class="fldesk-fname">{{ $say('notes.txt', 'یادداشت‌ها.txt') }}</span>
                        <span class="fldesk-fmeta"><span>{{ $say('12 KB', '۱۲ ک‌ب') }}</span><span class="fldesk-fdate">{{ $say('Yesterday', 'دیروز') }}</span></span>
                    </button>
                    <button type="button" class="fldesk-frow">
                        <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" style="color: #B146C2"><path d="M6 3.5h8L18.5 8v12.5H6z"/><path d="M14 3.5V8h4.5"/></svg>
                        <span class="fldesk-fname">{{ $say('design-team.jpg', 'تیم-طراحی.jpg') }}</span>
                        <span class="fldesk-fmeta"><span>{{ $say('3.6 MB', '۳٫۶ م‌ب') }}</span><span class="fldesk-fdate">{{ $say('Monday', 'دوشنبه') }}</span></span>
                    </button>
                </div>
            </div>
        </article>

        {{-- ============ Window: Mail ============ --}}
        <article class="fldesk-win" x-ref="w-mail" data-app="mail" role="dialog"
            aria-label="{{ $say('Mail window', 'پنجرهٔ نامه‌ها') }}"
            x-show="open.mail && !min.mail"
            x-on:pointerdown="focus('mail')"
            :style="wstyle('mail')"
            :class="active === 'mail' ? 'fldesk-active' : ''"
            x-transition:enter="fldesk-win-t" x-transition:enter-start="fldesk-win-t0" x-transition:enter-end="fldesk-win-t1">
            <header class="fldesk-title"
                x-on:pointerdown="startDrag($event, 'mail')"
                x-on:pointermove="moveDrag($event)"
                x-on:pointerup="endDrag($event)"
                x-on:pointercancel="endDrag($event)"
                x-on:lostpointercapture="endDrag($event)">
                <span class="fldesk-appicon" aria-hidden="true"><svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5.5" width="18" height="13" rx="1.5"/><path d="m3.5 7 8.5 6 8.5-6"/></svg></span>
                <span class="fldesk-winname">{{ $say('Mail', 'نامه‌ها') }}</span>
                <div class="fldesk-cap">
                    <button type="button" aria-label="{{ $say('Minimise', 'کوچک کردن') }}" x-on:click="minimise('mail')"><svg viewBox="0 0 10 10" width="10" height="10" fill="none" stroke="currentColor"><path d="M2 7.4h6"/></svg></button>
                    <button type="button" aria-label="{{ $say('Maximise', 'بزرگ‌نمایی') }}" x-bind:data-maxed="maxed.mail ? 'true' : 'false'" x-on:click="maximise('mail')">
                        <svg class="fldesk-maxg" viewBox="0 0 10 10" width="10" height="10" fill="none" stroke="currentColor"><rect x="2" y="2" width="6" height="6" rx="1"/></svg>
                        <svg class="fldesk-restg" viewBox="0 0 10 10" width="10" height="10" fill="none" stroke="currentColor"><rect x="2" y="3.6" width="4.4" height="4.4" rx="1"/><path d="M3.8 2h4.2v4.2"/></svg>
                    </button>
                    <button type="button" data-close aria-label="{{ $say('Close', 'بستن') }}" x-on:click="closeWin('mail')"><svg viewBox="0 0 10 10" width="10" height="10" fill="none" stroke="currentColor"><path d="m2.4 2.4 5.2 5.2M7.6 2.4 2.4 7.6"/></svg></button>
                </div>
            </header>
            <div class="fldesk-body">
                <div class="fldesk-mlist">
                    <button type="button" class="fldesk-mitem" data-current x-on:click="sel = 0">
                        <span class="fldesk-mrow"><span class="fldesk-mfrom">{{ $say('Sara Ahmadi', 'سارا احمدی') }}</span><span class="fldesk-mtime">۱۰:۲۴</span></span>
                        <span class="fldesk-mrow"><span class="fldesk-msub">{{ $say('The partnership contract', 'قرارداد همکاری') }}</span><i class="fldesk-mdot" aria-hidden="true"></i></span>
                    </button>
                    <button type="button" class="fldesk-mitem" x-on:click="sel = 1">
                        <span class="fldesk-mrow"><span class="fldesk-mfrom">{{ $say('Reza Karimi', 'رضا کریمی') }}</span><span class="fldesk-mtime">۰۹:۰۵</span></span>
                        <span class="fldesk-mrow"><span class="fldesk-msub">{{ $say('Meeting minutes', 'صورتجلسهٔ جلسه') }}</span><i class="fldesk-mdot" aria-hidden="true"></i></span>
                    </button>
                    <button type="button" class="fldesk-mitem" x-on:click="sel = 2">
                        <span class="fldesk-mrow"><span class="fldesk-mfrom">{{ $say('Mina Rezaei', 'مینا رضایی') }}</span><span class="fldesk-mtime">{{ $say('Yesterday', 'دیروز') }}</span></span>
                        <span class="fldesk-mrow"><span class="fldesk-msub">{{ $say('Design conference invite', 'دعوت به همایش طراحی') }}</span></span>
                    </button>
                    <button type="button" class="fldesk-mitem" x-on:click="sel = 3">
                        <span class="fldesk-mrow"><span class="fldesk-mfrom">{{ $say('Ali Tehrani', 'علی تهرانی') }}</span><span class="fldesk-mtime">{{ $say('Monday', 'دوشنبه') }}</span></span>
                        <span class="fldesk-mrow"><span class="fldesk-msub">{{ $say('Ticket 4821 follow-up', 'پیگیری تیکت ۴۸۲۱') }}</span></span>
                    </button>
                </div>
                <div class="fldesk-read">
                    <div x-show="sel === 0">
                        <h4>{{ $say('The partnership contract', 'قرارداد همکاری') }}</h4>
                        <p class="fldesk-readmeta">{{ $say('Sara Ahmadi · 10:24', 'سارا احمدی · ۱۰:۲۴') }}</p>
                        <p>{{ $say('Hello, the final version of the contract is attached. If the numbers on page two look good, we can sign it this week.', 'سلام، نسخهٔ نهایی قرارداد پیوست است. اگر ارقام صفحهٔ دوم مورد تأیید باشد، همین هفته می‌توانیم امضا کنیم.') }}</p>
                    </div>
                    <div x-show="sel === 1" style="display: none">
                        <h4>{{ $say('Meeting minutes', 'صورتجلسهٔ جلسه') }}</h4>
                        <p class="fldesk-readmeta">{{ $say('Reza Karimi · 09:05', 'رضا کریمی · ۰۹:۰۵') }}</p>
                        <p>{{ $say('We agreed on the window manager spec: z-order on click, edge snapping with a translucent preview, and a centred taskbar.', 'روی مشخصات مدیر پنجره توافق شد: بالا آمدن با کلیک، چسبیدن به لبه‌ها با پیش‌نمایش شفاف، و نوار وظیفهٔ وسط‌چین.') }}</p>
                    </div>
                    <div x-show="sel === 2" style="display: none">
                        <h4>{{ $say('Design conference invite', 'دعوت به همایش طراحی') }}</h4>
                        <p class="fldesk-readmeta">{{ $say('Mina Rezaei · Yesterday', 'مینا رضایی · دیروز') }}</p>
                        <p>{{ $say('The Fluent track is on Thursday morning. I saved you a seat — the Metro talk by the window team is the one to catch.', 'مسیر فلوینت پنجشنبه صبح است. جای شما را رزرو کردم — ارائهٔ متروی تیم پنجره‌ها از همه بهتر است.') }}</p>
                    </div>
                    <div x-show="sel === 3" style="display: none">
                        <h4>{{ $say('Ticket 4821 follow-up', 'پیگیری تیکت ۴۸۲۱') }}</h4>
                        <p class="fldesk-readmeta">{{ $say('Ali Tehrani · Monday', 'علی تهرانی · دوشنبه') }}</p>
                        <p>{{ $say('The drag ghost on the mobile stage is fixed — touch-action was missing on the title bar. Closing the ticket tomorrow.', 'مشکل کشیدن پنجره روی صحنهٔ موبایل درست شد — تعامل لمسی روی نوار عنوان جا افتاده بود. تیکت را فردا می‌بندم.') }}</p>
                    </div>
                </div>
            </div>
        </article>

        {{-- ============ Window: Notes ============ --}}
        <article class="fldesk-win" x-ref="w-notes" data-app="notes" role="dialog"
            aria-label="{{ $say('Notes window', 'پنجرهٔ یادداشت') }}"
            x-show="open.notes && !min.notes"
            x-on:pointerdown="focus('notes')"
            :style="wstyle('notes')"
            :class="active === 'notes' ? 'fldesk-active' : ''"
            x-transition:enter="fldesk-win-t" x-transition:enter-start="fldesk-win-t0" x-transition:enter-end="fldesk-win-t1">
            <header class="fldesk-title"
                x-on:pointerdown="startDrag($event, 'notes')"
                x-on:pointermove="moveDrag($event)"
                x-on:pointerup="endDrag($event)"
                x-on:pointercancel="endDrag($event)"
                x-on:lostpointercapture="endDrag($event)">
                <span class="fldesk-appicon" aria-hidden="true"><svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="3.5" width="14" height="17" rx="1.5"/><path d="M8.5 8h7M8.5 12h7M8.5 16h4"/></svg></span>
                <span class="fldesk-winname">{{ $say('Notes — untitled', 'یادداشت — بی‌نام') }}</span>
                <div class="fldesk-cap">
                    <button type="button" aria-label="{{ $say('Minimise', 'کوچک کردن') }}" x-on:click="minimise('notes')"><svg viewBox="0 0 10 10" width="10" height="10" fill="none" stroke="currentColor"><path d="M2 7.4h6"/></svg></button>
                    <button type="button" aria-label="{{ $say('Maximise', 'بزرگ‌نمایی') }}" x-bind:data-maxed="maxed.notes ? 'true' : 'false'" x-on:click="maximise('notes')">
                        <svg class="fldesk-maxg" viewBox="0 0 10 10" width="10" height="10" fill="none" stroke="currentColor"><rect x="2" y="2" width="6" height="6" rx="1"/></svg>
                        <svg class="fldesk-restg" viewBox="0 0 10 10" width="10" height="10" fill="none" stroke="currentColor"><rect x="2" y="3.6" width="4.4" height="4.4" rx="1"/><path d="M3.8 2h4.2v4.2"/></svg>
                    </button>
                    <button type="button" data-close aria-label="{{ $say('Close', 'بستن') }}" x-on:click="closeWin('notes')"><svg viewBox="0 0 10 10" width="10" height="10" fill="none" stroke="currentColor"><path d="m2.4 2.4 5.2 5.2M7.6 2.4 2.4 7.6"/></svg></button>
                </div>
            </header>
            <div class="fldesk-body fldesk-notebody">
                <textarea class="fldesk-notearea" x-model="note" spellcheck="false" aria-label="{{ $say('Note text', 'متن یادداشت') }}" placeholder="{{ $say('Start typing…', 'چیزی بنویسید…') }}"></textarea>
                <div class="fldesk-notestatus">
                    <span><span x-text="fd(note.length)">۰</span> {{ $say('characters', 'نویسه') }}</span>
                    <span>{{ $say('Autosaved', 'ذخیرهٔ خودکار') }}</span>
                </div>
            </div>
        </article>

        {{-- ============ Start menu ============ --}}
        <div class="fldesk-startmenu" x-cloak x-show="start" role="dialog" aria-label="{{ $say('Start menu', 'منوی شروع') }}"
            x-transition:enter="fldesk-sm-t" x-transition:enter-start="fldesk-sm-t0" x-transition:enter-end="fldesk-sm-t1"
            x-transition:leave="fldesk-sm-t" x-transition:leave-start="fldesk-sm-t1" x-transition:leave-end="fldesk-sm-t0">
            <label class="fldesk-search">
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"><circle cx="11" cy="11" r="6"/><path d="m15.5 15.5 5 5"/></svg>
                <input type="text" placeholder="{{ $say('Search', 'جست‌وجو') }}" aria-label="{{ $say('Search apps', 'جست‌وجوی برنامه‌ها') }}">
            </label>

            <div class="fldesk-sm-head">
                <span>{{ $say('Pinned', 'سنجاق‌شده') }}</span>
                <button type="button" class="fldesk-allapps" x-on:click="apps = !apps" x-bind:aria-expanded="apps ? 'true' : 'false'">
                    {{ $say('All apps', 'همه برنامه‌ها') }}
                    <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" x-bind:data-open="apps ? 'true' : 'false'"><path d="m9 5 7 7-7 7"/></svg>
                </button>
            </div>

            {{-- Metro tiles --}}
            <div class="fldesk-tiles" x-show="!apps" x-transition.opacity.duration.150ms>
                <button type="button" class="fldesk-tile" data-size="large" style="background: #0072C6" x-on:click="start = false" aria-label="{{ $say('Weather', 'آب‌وهوا') }}">
                    <span class="fldesk-tile-glyph" aria-hidden="true">
                        <svg x-show="wstep !== 1" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"><circle cx="12" cy="12" r="4"/><path d="M12 3v2M12 19v2M21 12h-2M5 12H3M18.4 5.6 17 7M7 17l-1.4 1.4M18.4 18.4 17 17M7 7 5.6 5.6"/></svg>
                        <svg x-show="wstep === 1" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" style="display: none"><path d="M7 18h9.2a3.8 3.8 0 0 0 .6-7.6A5.6 5.6 0 0 0 6 9.3 4.4 4.4 0 0 0 7 18z"/></svg>
                    </span>
                    <span class="fldesk-tile-temp" x-text="fd(temps[wstep]) + '°'">۲۸°</span>
                    <span class="fldesk-tile-name">{{ $say('Tehran · Sunny', 'تهران · آفتابی') }}</span>
                </button>

                <button type="button" class="fldesk-tile" data-size="large" style="background: #B01E00" x-on:click="openWin('files')" aria-label="{{ $say('Open Photos', 'باز کردن عکس‌ها') }}">
                    <span class="fldesk-photos" aria-hidden="true">
                        <i class="fldesk-photo" style="background: linear-gradient(135deg, #f6d365, #fda085)"></i>
                        <i class="fldesk-photo" style="background: linear-gradient(135deg, #84fab0, #8fd3f4)"></i>
                        <i class="fldesk-photo" style="background: linear-gradient(135deg, #a18cd1, #fbc2eb)"></i>
                    </span>
                    <span class="fldesk-tile-glyph" aria-hidden="true"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="6" width="18" height="14" rx="2"/><path d="m8.5 6 1.5-2.5h4L15.5 6"/><circle cx="12" cy="13" r="3.5"/></svg></span>
                    <span class="fldesk-tile-name">{{ $say('Photos', 'عکس‌ها') }}</span>
                </button>

                <button type="button" class="fldesk-tile" data-size="wide" style="background: #0063B1" x-on:click="openWin('mail')" aria-label="{{ $say('Open Mail', 'باز کردن نامه‌ها') }}">
                    <span class="fldesk-tile-glyph" aria-hidden="true"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5.5" width="18" height="13" rx="1.5"/><path d="m3.5 7 8.5 6 8.5-6"/></svg></span>
                    <span class="fldesk-tile-count" x-text="fd(mcount)">۳</span>
                    <span class="fldesk-tile-sub">{{ $say('unread', 'ناخوانده') }}</span>
                    <span class="fldesk-tile-name">{{ $say('Mail', 'نامه‌ها') }}</span>
                </button>

                <button type="button" class="fldesk-tile" data-size="wide" style="background: #008A00" x-on:click="start = false" aria-label="{{ $say('Calendar', 'تقویم') }}">
                    <span class="fldesk-tile-count" x-text="fd(16)">۱۶</span>
                    <span class="fldesk-tile-sub">{{ $say('Mehr 1405 · Thursday', 'مهر ۱۴۰۵ · پنجشنبه') }}</span>
                    <span class="fldesk-tile-name">{{ $say('Calendar', 'تقویم') }}</span>
                </button>

                <button type="button" class="fldesk-tile" style="background: #E81123" x-on:click="openWin('files')" aria-label="{{ $say('Open Files', 'باز کردن فایل‌ها') }}">
                    <span class="fldesk-tile-glyph" aria-hidden="true"><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3.5 6.5A1.5 1.5 0 0 1 5 5h4l2 2.5h8A1.5 1.5 0 0 1 20.5 9v8.5A1.5 1.5 0 0 1 19 19H5a1.5 1.5 0 0 1-1.5-1.5z"/></svg></span>
                    <span class="fldesk-tile-name">{{ $say('Files', 'فایل‌ها') }}</span>
                </button>
                <button type="button" class="fldesk-tile" style="background: #008A00" x-on:click="openWin('notes')" aria-label="{{ $say('Open Notes', 'باز کردن یادداشت') }}">
                    <span class="fldesk-tile-glyph" aria-hidden="true"><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="3.5" width="14" height="17" rx="1.5"/><path d="M8.5 8h7M8.5 12h7M8.5 16h4"/></svg></span>
                    <span class="fldesk-tile-name">{{ $say('Notes', 'یادداشت') }}</span>
                </button>
                <button type="button" class="fldesk-tile" style="background: #0072C6" x-on:click="start = false" aria-label="{{ $say('Edge browser', 'مرورگر لبه') }}">
                    <span class="fldesk-tile-glyph" aria-hidden="true"><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="8.5"/><path d="M3.5 12h17M12 3.5c2.7 2.3 4 5.2 4 8.5s-1.3 6.2-4 8.5c-2.7-2.3-4-5.2-4-8.5s1.3-6.2 4-8.5z"/></svg></span>
                    <span class="fldesk-tile-name">{{ $say('Edge', 'لبه') }}</span>
                </button>
                <button type="button" class="fldesk-tile" style="background: #4B0082" x-on:click="start = false" aria-label="{{ $say('Settings', 'تنظیمات') }}">
                    <span class="fldesk-tile-glyph" aria-hidden="true"><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"><circle cx="12" cy="12" r="3"/><path d="M12 2.8v2.6M12 18.6v2.6M21.2 12h-2.6M5.4 12H2.8M18.5 5.5l-1.9 1.9M7.4 16.6l-1.9 1.9M18.5 18.5l-1.9-1.9M7.4 7.4 5.5 5.5"/></svg></span>
                    <span class="fldesk-tile-name">{{ $say('Settings', 'تنظیمات') }}</span>
                </button>
            </div>

            {{-- All apps list --}}
            <div class="fldesk-apps" x-show="apps" x-transition.opacity.duration.150ms style="display: none">
                <button type="button" class="fldesk-approw" x-on:click="start = false"><i style="background: #4B0082"><svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="#fff" stroke-width="1.6" stroke-linecap="round"><circle cx="12" cy="12" r="3"/><path d="M12 2.8v2.6M12 18.6v2.6M21.2 12h-2.6M5.4 12H2.8M18.5 5.5l-1.9 1.9M7.4 16.6l-1.9 1.9M18.5 18.5l-1.9-1.9M7.4 7.4 5.5 5.5"/></svg></i>{{ $say('Settings', 'تنظیمات') }}</button>
                <button type="button" class="fldesk-approw" x-on:click="start = false"><i style="background: #E81123"><svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="#fff" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="6" width="18" height="14" rx="2"/><path d="m8.5 6 1.5-2.5h4L15.5 6"/><circle cx="12" cy="13" r="3.5"/></svg></i>{{ $say('Camera', 'دوربین') }}</button>
                <button type="button" class="fldesk-approw" x-on:click="openWin('files')"><i style="background: #0063B1"><svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="#fff" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3.5 6.5A1.5 1.5 0 0 1 5 5h4l2 2.5h8A1.5 1.5 0 0 1 20.5 9v8.5A1.5 1.5 0 0 1 19 19H5a1.5 1.5 0 0 1-1.5-1.5z"/></svg></i>{{ $say('Files', 'فایل‌ها') }}</button>
                <button type="button" class="fldesk-approw" x-on:click="start = false"><i style="background: #0072C6"><svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="#fff" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="8.5"/><path d="M3.5 12h17M12 3.5c2.7 2.3 4 5.2 4 8.5s-1.3 6.2-4 8.5c-2.7-2.3-4-5.2-4-8.5s1.3-6.2 4-8.5z"/></svg></i>{{ $say('Edge', 'لبه') }}</button>
                <button type="button" class="fldesk-approw" x-on:click="start = false"><i style="background: #B01E00"><svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="#fff" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18.5V6l10-2v12.5"/><circle cx="6.5" cy="18.5" r="2.5"/><circle cx="16.5" cy="16.5" r="2.5"/></svg></i>{{ $say('Music', 'موسیقی') }}</button>
                <button type="button" class="fldesk-approw" x-on:click="openWin('mail')"><i style="background: #0063B1"><svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="#fff" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5.5" width="18" height="13" rx="1.5"/><path d="m3.5 7 8.5 6 8.5-6"/></svg></i>{{ $say('Mail', 'نامه‌ها') }}</button>
                <button type="button" class="fldesk-approw" x-on:click="openWin('notes')"><i style="background: #008A00"><svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="#fff" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="3.5" width="14" height="17" rx="1.5"/><path d="M8.5 8h7M8.5 12h7M8.5 16h4"/></svg></i>{{ $say('Notes', 'یادداشت') }}</button>
            </div>

            <footer class="fldesk-sm-foot">
                <span class="fldesk-sm-user" aria-hidden="true">{{ $say('G', 'م') }}</span>
                <span>{{ $say('Guest user', 'کاربر میهمان') }}</span>
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" aria-hidden="true"><path d="M12 3.5v7"/><path d="M7 6.2a7 7 0 1 0 10 0"/></svg>
            </footer>
        </div>

        {{-- ============ Taskbar ============ --}}
        <div class="fldesk-taskbar">
            <div aria-hidden="true"></div>
            <div class="fldesk-tb-center">
                <button type="button" class="fldesk-startbtn" x-on:click="start = !start" x-bind:aria-expanded="start ? 'true' : 'false'" aria-label="{{ $say('Start', 'شروع') }}">
                    <span class="fldesk-logo" aria-hidden="true"><i></i><i></i><i></i><i></i></span>
                </button>
                <button type="button" class="fldesk-tbicon" x-on:click="tbClick('files')" x-bind:aria-pressed="open.files ? 'true' : 'false'" aria-label="{{ $say('Files', 'فایل‌ها') }}">
                    <svg viewBox="0 0 24 24" width="19" height="19" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3.5 6.5A1.5 1.5 0 0 1 5 5h4l2 2.5h8A1.5 1.5 0 0 1 20.5 9v8.5A1.5 1.5 0 0 1 19 19H5a1.5 1.5 0 0 1-1.5-1.5z"/></svg>
                    <i class="fldesk-runpill" x-show="open.files" x-bind:data-on="active === 'files' && !min.files ? 'y' : 'n'" aria-hidden="true"></i>
                </button>
                <button type="button" class="fldesk-tbicon" x-on:click="tbClick('mail')" x-bind:aria-pressed="open.mail ? 'true' : 'false'" aria-label="{{ $say('Mail', 'نامه‌ها') }}">
                    <svg viewBox="0 0 24 24" width="19" height="19" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5.5" width="18" height="13" rx="1.5"/><path d="m3.5 7 8.5 6 8.5-6"/></svg>
                    <i class="fldesk-runpill" x-show="open.mail" x-bind:data-on="active === 'mail' && !min.mail ? 'y' : 'n'" aria-hidden="true"></i>
                </button>
                <button type="button" class="fldesk-tbicon" x-on:click="tbClick('notes')" x-bind:aria-pressed="open.notes ? 'true' : 'false'" aria-label="{{ $say('Notes', 'یادداشت') }}">
                    <svg viewBox="0 0 24 24" width="19" height="19" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="3.5" width="14" height="17" rx="1.5"/><path d="M8.5 8h7M8.5 12h7M8.5 16h4"/></svg>
                    <i class="fldesk-runpill" x-show="open.notes" x-bind:data-on="active === 'notes' && !min.notes ? 'y' : 'n'" aria-hidden="true"></i>
                </button>
                <button type="button" class="fldesk-tbicon fldesk-hide-sm" aria-label="{{ $say('Edge browser', 'مرورگر لبه') }}">
                    <svg viewBox="0 0 24 24" width="19" height="19" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="8.5"/><path d="M3.5 12h17M12 3.5c2.7 2.3 4 5.2 4 8.5s-1.3 6.2-4 8.5c-2.7-2.3-4-5.2-4-8.5s1.3-6.2 4-8.5z"/></svg>
                </button>
                <button type="button" class="fldesk-tbicon fldesk-hide-sm" aria-label="{{ $say('Settings', 'تنظیمات') }}">
                    <svg viewBox="0 0 24 24" width="19" height="19" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><circle cx="12" cy="12" r="3"/><path d="M12 2.8v2.6M12 18.6v2.6M21.2 12h-2.6M5.4 12H2.8M18.5 5.5l-1.9 1.9M7.4 16.6l-1.9 1.9M18.5 18.5l-1.9-1.9M7.4 7.4 5.5 5.5"/></svg>
                </button>
            </div>
            <div class="fldesk-tb-tray">
                <button type="button" class="fldesk-traybtn" aria-label="{{ $say('Network', 'شبکه') }}"><svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"><path d="M4 9.5a12 12 0 0 1 16 0M7 13a8 8 0 0 1 10 0M9.8 16.2a4 4 0 0 1 4.4 0"/><circle cx="12" cy="19" r="1.3" fill="currentColor" stroke="none"/></svg></button>
                <button type="button" class="fldesk-traybtn" aria-label="{{ $say('Volume', 'صدا') }}"><svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M4 9.5v5h3.5L12 18.5v-13L7.5 9.5z"/><path d="M15 9a4.2 4.2 0 0 1 0 6M17.5 6.8a7.5 7.5 0 0 1 0 10.4"/></svg></button>
                <button type="button" class="fldesk-traybtn" aria-label="{{ $say('Battery', 'باتری') }}"><svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="2.5" y="8" width="17" height="8" rx="2"/><rect x="4.5" y="10" width="9" height="4" rx="1" fill="currentColor" stroke="none"/><path d="M21.5 10.8v2.4" stroke-linecap="round"/></svg></button>
                <div class="fldesk-clock" role="timer" aria-label="{{ $say('Clock', 'ساعت') }}">
                    <span x-text="clock">۱۴:۰۵</span>
                    <span class="fldesk-date">{{ $say('Thu · Oct 8', 'پنجشنبه ۱۶ مهر') }}</span>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    [x-cloak] { display: none !important; }

    /* The shared "Important props" table below the stage reveals its rows through a
       scroll observer (core nx-data-table). Full-page captures and print layouts
       never scroll, the observer never fires, and tbody paints empty. This block
       is unlayered, so it outranks the layered core rules: show the table as-is. */
    .nx-data-table[data-nx-reveal]:not([data-nx-revealed]) { opacity: 1; translate: none; }
    .nx-data-table[data-nx-reveal]:not([data-nx-revealed]) tbody tr { opacity: 1; translate: none; }

    .fldesk-root {
        /* palette — light */
        --fldesk-tb: 48px;
        --fldesk-accent: #005FB8;
        --fldesk-win: #f3f3f3;
        --fldesk-paper: #ffffff;
        --fldesk-ink: #1a1a1a;
        --fldesk-ink2: #5d5d5d;
        --fldesk-stroke: #00000026;
        --fldesk-hover: #0000000d;
        --fldesk-hover2: #00000016;
        --fldesk-tb-bg: rgba(240, 243, 249, .85);
        --fldesk-tb-hover: #00000014;
        --fldesk-menu: rgba(243, 243, 243, .88);
        --fldesk-fly-stroke: #ffffff70;
        --fldesk-hairline: #00000014;
        --fldesk-wall:
            radial-gradient(42% 34% at 66% 24%, #7aa7e800 0%, transparent 70%),
            radial-gradient(80% 60% at 68% 22%, #4a7cc9 0%, transparent 60%),
            radial-gradient(60% 70% at 20% 85%, #2a55a5 0%, transparent 65%),
            radial-gradient(120% 120% at 50% 50%, #1b3a7d 0%, #10275c 55%, #0b1a3f 100%);
        position: relative;
        isolation: isolate;
        overflow: clip;
        block-size: clamp(26rem, 60vh, 34rem);
        border-radius: 8px;
        background: var(--fldesk-wall);
        box-shadow: inset 0 0 0 1px var(--fldesk-stroke);
    }
    html[data-theme="dark"] .fldesk-root {
        --fldesk-accent: #4CC2FF;
        --fldesk-win: #262626;
        --fldesk-paper: #1d1d1d;
        --fldesk-ink: #f3f3f3;
        --fldesk-ink2: #a6a6a6;
        --fldesk-stroke: #ffffff1f;
        --fldesk-hover: #ffffff12;
        --fldesk-hover2: #ffffff1f;
        --fldesk-tb-bg: rgba(32, 32, 32, .85);
        --fldesk-tb-hover: #ffffff14;
        --fldesk-menu: rgba(40, 40, 40, .88);
        --fldesk-fly-stroke: #ffffff2e;
        --fldesk-hairline: #ffffff1a;
        --fldesk-wall:
            radial-gradient(42% 34% at 66% 24%, #6f9de012 0%, transparent 70%),
            radial-gradient(80% 60% at 68% 22%, #2c4a8a 0%, transparent 60%),
            radial-gradient(60% 70% at 20% 85%, #16305e 0%, transparent 65%),
            radial-gradient(120% 120% at 50% 50%, #0f1c38 0%, #0c1730 55%, #0a1226 100%);
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .fldesk-root {
            --fldesk-accent: #4CC2FF;
            --fldesk-win: #262626;
            --fldesk-paper: #1d1d1d;
            --fldesk-ink: #f3f3f3;
            --fldesk-ink2: #a6a6a6;
            --fldesk-stroke: #ffffff1f;
            --fldesk-hover: #ffffff12;
            --fldesk-hover2: #ffffff1f;
            --fldesk-tb-bg: rgba(32, 32, 32, .85);
            --fldesk-tb-hover: #ffffff14;
            --fldesk-menu: rgba(40, 40, 40, .88);
            --fldesk-fly-stroke: #ffffff2e;
            --fldesk-hairline: #ffffff1a;
            --fldesk-wall:
                radial-gradient(42% 34% at 66% 24%, #6f9de012 0%, transparent 70%),
                radial-gradient(80% 60% at 68% 22%, #2c4a8a 0%, transparent 60%),
                radial-gradient(60% 70% at 20% 85%, #16305e 0%, transparent 65%),
                radial-gradient(120% 120% at 50% 50%, #0f1c38 0%, #0c1730 55%, #0a1226 100%);
        }
    }

    .fldesk-root button { cursor: pointer; }
    .fldesk-root :focus-visible { outline: 2px solid var(--fldesk-accent); outline-offset: 1px; border-radius: 2px; }

    /* Desktop icons */
    .fldesk-dicons { position: absolute; z-index: 1; inset-block-start: .75rem; inset-inline-start: .75rem; display: flex; flex-direction: column; gap: .25rem; }
    .fldesk-dicon { display: grid; justify-items: center; gap: .3rem; inline-size: 4.9rem; padding: .4rem .25rem; border: 0; border-radius: 4px; background: transparent; color: #eef4ff; font: inherit; font-size: 11px; text-align: center; text-shadow: 0 1px 3px #000000a6; }
    .fldesk-dicon i { display: grid; place-items: center; filter: drop-shadow(0 2px 3px #00000059); }
    .fldesk-dicon i[data-bin] { color: #c9f3c9; }
    .fldesk-dicon:hover { background: #ffffff1f; }
    .fldesk-dicon:active { background: #ffffff38; }

    /* Windows */
    .fldesk-win { position: absolute; display: flex; flex-direction: column; min-inline-size: min(92%, 18rem); background: var(--fldesk-win); color: var(--fldesk-ink); border: 1px solid var(--fldesk-stroke); border-radius: 8px; overflow: clip; box-shadow: 0 4px 14px #00000033; }
    .fldesk-win.fldesk-active { box-shadow: 0 20px 48px #00000059; }
    .fldesk-win[data-app='files'] { inline-size: min(92%, 30rem); block-size: min(20.5rem, calc(100% - 3.5rem)); }
    .fldesk-win[data-app='mail'] { inline-size: min(92%, 32rem); block-size: min(21.5rem, calc(100% - 3.5rem)); }
    .fldesk-win[data-app='notes'] { inline-size: min(92%, 26rem); block-size: min(17rem, calc(100% - 3.5rem)); }
    .fldesk-win-t { transition: opacity .2s cubic-bezier(.2, .9, .3, 1), scale .2s cubic-bezier(.2, .9, .3, 1), translate .2s cubic-bezier(.2, .9, .3, 1); }
    .fldesk-win-t0 { opacity: 0; scale: .94; translate: 0 12px; }
    .fldesk-win-t1 { opacity: 1; scale: 1; translate: 0 0; }

    .fldesk-title { flex: none; display: flex; align-items: center; gap: .5rem; block-size: 32px; padding-inline-start: .75rem; touch-action: none; cursor: grab; user-select: none; }
    .fldesk-title:active { cursor: grabbing; }
    .fldesk-appicon { display: grid; place-items: center; color: var(--fldesk-accent); }
    .fldesk-winname { font-size: 12px; opacity: .6; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .fldesk-active .fldesk-winname { opacity: 1; }
    .fldesk-cap { margin-inline-start: auto; align-self: stretch; display: flex; }
    .fldesk-cap button { display: grid; place-items: center; inline-size: 46px; block-size: 32px; border: 0; background: transparent; color: var(--fldesk-ink); }
    .fldesk-cap button:hover { background: var(--fldesk-tb-hover); }
    .fldesk-cap button[data-close]:hover { background: #C42B1C; color: #fff; }
    .fldesk-restg { display: none; }
    .fldesk-cap [data-maxed='true'] .fldesk-restg { display: block; }
    .fldesk-cap [data-maxed='true'] .fldesk-maxg { display: none; }

    /* Window bodies */
    .fldesk-body { flex: 1; min-block-size: 0; display: flex; background: var(--fldesk-paper); font-size: 12.5px; }
    .fldesk-side { flex: none; inline-size: 7rem; padding: .375rem; border-inline-end: 1px solid var(--fldesk-stroke); background: var(--fldesk-win); display: flex; flex-direction: column; gap: 2px; }
    .fldesk-sideitem { position: relative; display: flex; align-items: center; gap: .5rem; block-size: 30px; padding-inline: .625rem; border: 0; border-radius: 4px; background: transparent; color: inherit; font: inherit; text-align: start; }
    .fldesk-sideitem:hover { background: var(--fldesk-hover); }
    .fldesk-sideitem[data-current] { background: var(--fldesk-hover2); }
    .fldesk-sideitem [data-pill] { position: absolute; inset-inline-start: 0; inline-size: 3px; block-size: 16px; border-radius: 999px; background: var(--fldesk-accent); }
    .fldesk-list { flex: 1; min-inline-size: 0; overflow: auto; padding: .25rem .375rem; }
    .fldesk-frow { display: flex; align-items: center; gap: .625rem; inline-size: 100%; block-size: 34px; padding-inline: .625rem; border: 0; border-radius: 4px; background: transparent; color: inherit; font: inherit; text-align: start; }
    .fldesk-frow:hover { background: var(--fldesk-hover); }
    .fldesk-fname { min-inline-size: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .fldesk-fmeta { flex: none; margin-inline-start: auto; display: flex; gap: .875rem; color: var(--fldesk-ink2); font-size: 11.5px; }

    .fldesk-mlist { flex: none; inline-size: 10.5rem; padding: .25rem; border-inline-end: 1px solid var(--fldesk-stroke); background: var(--fldesk-win); overflow: auto; display: flex; flex-direction: column; gap: 2px; }
    .fldesk-mitem { display: block; inline-size: 100%; padding: .4rem .5rem; border: 0; border-radius: 4px; background: transparent; color: inherit; font: inherit; text-align: start; }
    .fldesk-mitem:hover { background: var(--fldesk-hover); }
    .fldesk-mitem[data-current] { background: var(--fldesk-hover2); }
    .fldesk-mrow { display: flex; align-items: center; justify-content: space-between; gap: .375rem; }
    .fldesk-mfrom { font-weight: 600; font-size: 12px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .fldesk-mtime { flex: none; color: var(--fldesk-ink2); font-size: 10.5px; }
    .fldesk-msub { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: var(--fldesk-ink2); font-size: 11.5px; }
    .fldesk-mdot { flex: none; inline-size: 7px; block-size: 7px; border-radius: 999px; background: var(--fldesk-accent); }
    .fldesk-read { flex: 1; min-inline-size: 0; overflow: auto; padding: .75rem 1rem; }
    .fldesk-read h4 { margin: 0; font-size: 13.5px; }
    .fldesk-readmeta { margin: .25rem 0 .5rem; color: var(--fldesk-ink2); font-size: 11px; }
    .fldesk-read p:not(.fldesk-readmeta) { margin: 0; line-height: 1.7; }

    .fldesk-notebody { flex-direction: column; }
    .fldesk-notearea { flex: 1; min-block-size: 0; resize: none; border: 0; padding: .75rem .875rem; background: var(--fldesk-paper); color: var(--fldesk-ink); font: inherit; line-height: 1.7; user-select: text; }
    .fldesk-notearea:focus-visible { outline-offset: -2px; }
    .fldesk-notestatus { flex: none; display: flex; justify-content: space-between; padding: .3rem .75rem; border-block-start: 1px solid var(--fldesk-stroke); background: var(--fldesk-win); color: var(--fldesk-ink2); font-size: 11px; }

    /* Snap preview */
    .fldesk-snapzone { position: absolute; z-index: 500; pointer-events: none; border-radius: 8px; background: color-mix(in oklab, var(--fldesk-accent) 30%, transparent); box-shadow: inset 0 0 0 1.5px var(--fldesk-accent); backdrop-filter: blur(4px); }
    .fldesk-snapzone[data-zone='start'] { inset-block: 0 var(--fldesk-tb); inset-inline: 0 50%; }
    .fldesk-snapzone[data-zone='end'] { inset-block: 0 var(--fldesk-tb); inset-inline: 50% 0; }
    .fldesk-snapzone[data-zone='top'] { inset-block: 0 50%; inset-inline: 0; }

    /* Taskbar */
    .fldesk-taskbar { position: absolute; z-index: 600; inset-inline: 0; inset-block-end: 0; block-size: var(--fldesk-tb); display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; padding-inline: .5rem; background: var(--fldesk-tb-bg); backdrop-filter: blur(30px) saturate(140%); border-block-start: 1px solid var(--fldesk-hairline); color: var(--fldesk-ink); }
    .fldesk-tb-center { justify-self: center; display: flex; align-items: center; gap: 2px; }
    .fldesk-startbtn, .fldesk-tbicon { position: relative; display: grid; place-items: center; inline-size: 40px; block-size: 40px; border: 0; border-radius: 4px; background: transparent; color: inherit; }
    .fldesk-startbtn:hover, .fldesk-tbicon:hover { background: var(--fldesk-tb-hover); }
    .fldesk-logo { display: grid; grid-template-columns: repeat(2, 7px); gap: 1.5px; transition: scale .15s ease; }
    .fldesk-logo i { inline-size: 7px; block-size: 7px; border-radius: 1.5px; background: var(--fldesk-accent); }
    .fldesk-startbtn:hover .fldesk-logo { scale: 1.08; }
    .fldesk-startbtn:active .fldesk-logo { scale: .92; }
    .fldesk-runpill { position: absolute; inset-block-end: 2px; inset-inline: 0; margin-inline: auto; inline-size: 16px; block-size: 3px; border-radius: 999px; background: var(--fldesk-accent); opacity: .45; transition: opacity .15s ease; }
    .fldesk-runpill[data-on='y'] { opacity: 1; }
    .fldesk-tb-tray { justify-self: end; display: flex; align-items: center; gap: 1px; }
    .fldesk-traybtn { display: grid; place-items: center; inline-size: 26px; block-size: 26px; border: 0; border-radius: 4px; background: transparent; color: inherit; }
    .fldesk-traybtn:hover { background: var(--fldesk-tb-hover); }
    .fldesk-clock { display: flex; flex-direction: column; align-items: flex-end; padding: .15rem .4rem; border-radius: 4px; font-size: 11px; line-height: 1.3; cursor: default; }
    .fldesk-clock:hover { background: var(--fldesk-tb-hover); }
    .fldesk-date { color: var(--fldesk-ink2); font-size: 10px; }

    /* Start menu */
    .fldesk-startmenu { position: absolute; z-index: 700; inset-inline: 0; inset-block-end: calc(var(--fldesk-tb) + .5rem); margin-inline: auto; inline-size: min(92%, 30rem); max-block-size: calc(100% - var(--fldesk-tb) - 1rem); overflow-y: auto; padding: 1rem; display: grid; gap: .75rem; align-content: start; border-radius: 8px; background: var(--fldesk-menu); backdrop-filter: blur(30px) saturate(140%); box-shadow: 0 16px 48px #00000059, 0 0 0 1px var(--fldesk-fly-stroke); color: var(--fldesk-ink); }
    .fldesk-sm-t { transition: opacity .18s ease, scale .18s ease, translate .18s ease; }
    .fldesk-sm-t0 { opacity: 0; scale: .96; translate: 0 10px; }
    .fldesk-sm-t1 { opacity: 1; scale: 1; translate: 0 0; }
    .fldesk-search { display: flex; align-items: center; gap: .5rem; block-size: 34px; padding-inline: .625rem; border: 1px solid var(--fldesk-stroke); border-block-end-color: var(--fldesk-ink2); border-radius: 4px; background: var(--fldesk-paper); color: var(--fldesk-ink2); }
    .fldesk-search input { flex: 1; min-inline-size: 0; border: 0; background: transparent; color: var(--fldesk-ink); font: inherit; outline: none; }
    .fldesk-sm-head { display: flex; align-items: center; justify-content: space-between; font-size: 12.5px; font-weight: 600; }
    .fldesk-allapps { display: inline-flex; align-items: center; gap: .3rem; padding: .25rem .5rem; border: 0; border-radius: 4px; background: transparent; color: var(--fldesk-ink2); font: inherit; font-size: 11.5px; }
    .fldesk-allapps:hover { background: var(--fldesk-hover); }
    .fldesk-allapps svg { transition: rotate .2s ease; }
    .fldesk-allapps [data-open='true'] { rotate: 90deg; }

    .fldesk-tiles { display: grid; grid-template-columns: repeat(4, 1fr); grid-auto-rows: 3.4rem; gap: .4rem; }
    .fldesk-tile { position: relative; overflow: clip; border: 0; border-radius: 2px; color: #fff; font: inherit; text-align: start; transition: filter .15s ease, scale .15s ease; }
    .fldesk-tile:hover { filter: brightness(1.12); }
    .fldesk-tile:active { scale: .96; }
    .fldesk-tile[data-size='large'] { grid-column: span 2; grid-row: span 2; }
    .fldesk-tile[data-size='wide'] { grid-column: span 2; }
    .fldesk-tile-glyph { position: absolute; inset-block-start: .45rem; inset-inline-start: .5rem; }
    .fldesk-tile-temp { position: absolute; inset-block-start: 2.1rem; inset-inline-start: .5rem; font-size: 1.7rem; font-weight: 300; letter-spacing: .02em; }
    .fldesk-tile-count { position: absolute; inset-block-start: 1.9rem; inset-inline-start: .5rem; font-size: 1.5rem; font-weight: 300; }
    .fldesk-tile[data-size='large'] .fldesk-tile-count { inset-block-start: auto; inset-inline-start: auto; inset-block-end: 1.6rem; inset-inline-end: .625rem; font-size: 2.4rem; }
    .fldesk-tile-sub { position: absolute; inset-block-start: 3.5rem; inset-inline-start: .5rem; font-size: 10.5px; opacity: .9; }
    .fldesk-tile[data-size='wide'] .fldesk-tile-sub { inset-block-start: auto; inset-block-end: 1.55rem; }
    .fldesk-tile-name { position: absolute; inset-block-end: .35rem; inset-inline-start: .5rem; font-size: 11px; text-shadow: 0 1px 2px #00000059; white-space: nowrap; }
    .fldesk-photos { position: absolute; inset: 0; }
    .fldesk-photo { position: absolute; inset: 0; opacity: 0; transform: rotateY(80deg); animation: fldesk-flip calc(12s * var(--nx-motion, 1)) infinite; }
    .fldesk-photo:nth-child(2) { animation-delay: calc(-4s * var(--nx-motion, 1)); }
    .fldesk-photo:nth-child(3) { animation-delay: calc(-8s * var(--nx-motion, 1)); }
    @keyframes fldesk-flip {
        0% { opacity: 0; transform: rotateY(80deg); }
        10% { opacity: 1; transform: rotateY(0deg); }
        32% { opacity: 1; transform: rotateY(0deg); }
        42% { opacity: 0; transform: rotateY(-80deg); }
        100% { opacity: 0; transform: rotateY(-80deg); }
    }

    .fldesk-apps { display: grid; gap: 2px; }
    .fldesk-approw { display: flex; align-items: center; gap: .625rem; block-size: 36px; padding-inline: .375rem; border: 0; border-radius: 4px; background: transparent; color: inherit; font: inherit; font-size: 12.5px; text-align: start; }
    .fldesk-approw:hover { background: var(--fldesk-hover); }
    .fldesk-approw i { display: grid; place-items: center; inline-size: 26px; block-size: 26px; border-radius: 3px; }
    .fldesk-sm-foot { display: flex; align-items: center; gap: .5rem; margin-inline: -.5rem; margin-block-start: .25rem; padding: .5rem .75rem; border-block-start: 1px solid var(--fldesk-stroke); background: var(--fldesk-hover); font-size: 12px; }
    .fldesk-sm-user { display: grid; place-items: center; inline-size: 26px; block-size: 26px; border-radius: 999px; background: var(--fldesk-accent); color: #fff; font-size: 11px; }
    .fldesk-sm-foot svg { margin-inline-start: auto; color: var(--fldesk-ink2); }

    /* Mobile */
    @media (max-width: 480px) {
        .fldesk-hide-sm { display: none; }
        /* Props table: wrap the cells and tighten them so the whole table
           fits the narrow screen instead of clipping its last column. */
        .nx-data-table th, .nx-data-table td { white-space: normal; padding-inline: .5rem; font-size: var(--nx-text-xs); }
        .fldesk-side { inline-size: 5.5rem; }
        .fldesk-fdate, .fldesk-read { display: none; }
        .fldesk-mlist { inline-size: 9rem; }
        .fldesk-tiles { grid-auto-rows: 3rem; }
    }

    @media (prefers-reduced-motion: reduce) {
        .fldesk-photo { animation: none !important; opacity: 0; transform: none; }
        .fldesk-photo:first-child { opacity: 1; }
        .fldesk-win-t, .fldesk-sm-t { transition: none !important; }
        .fldesk-tile, .fldesk-logo, .fldesk-allapps svg, .fldesk-runpill { transition: none !important; }
    }
</style>
