{{--
    segment-spin in its real fields: three status cards at three sizes —
    a contacts sync counting real contacts, a video upload that completes,
    and a flight search whose first server call fails until the retry goes
    green. The indicator slot holds one size through working/done/error.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<style>
    .lds-root {
        --lds-fg: #1c1917; --lds-muted: #78716c; --lds-border: #e7e5e4; --lds-bg: #ffffff;
        --lds-accent: #0a84ff; --lds-ok: #059669; --lds-err: #dc2626;
        display: grid; gap: 1.6rem;
        font-family: 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif;
        color: var(--lds-fg);
    }
    html[data-theme="dark"] .lds-root {
        --lds-fg: #fafaf9; --lds-muted: #a8a29e; --lds-border: #292524; --lds-bg: #1c1917;
        --lds-accent: #64a8ff; --lds-ok: #34d399; --lds-err: #f87171;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .lds-root {
            --lds-fg: #fafaf9; --lds-muted: #a8a29e; --lds-border: #292524; --lds-bg: #1c1917;
            --lds-accent: #64a8ff; --lds-ok: #34d399; --lds-err: #f87171;
        }
    }
    .lds-grid { display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(15.5rem, 1fr)); }
    .lds-card {
        display: grid; gap: .75rem; align-content: start; padding: 1.2rem;
        border: 1px solid var(--lds-border); border-radius: 1.1rem; background: var(--lds-bg);
    }
    .lds-head { display: flex; align-items: center; gap: .8rem; }
    .lds-head h4 { margin: 0; font-size: .9rem; font-weight: 700; }
    .lds-slot {
        display: inline-grid; place-items: center; flex: none; color: var(--lds-accent);
        inline-size: var(--lds-size); block-size: var(--lds-size);
    }
    .lds-slot svg { inline-size: 100%; block-size: 100%; }
    .lds-slot circle {
        fill: none; stroke: currentColor; stroke-width: 2.4; stroke-linecap: round;
        stroke-dasharray: 10.5 89.5; stroke-dashoffset: -6;
        animation: lds-fade .96s linear infinite;
        animation-delay: calc(var(--lds-i) * -.12s);
    }
    .lds-slot path {
        fill: none; stroke: currentColor; stroke-width: 2.4; stroke-linecap: round; stroke-linejoin: round;
        stroke-dasharray: 26; stroke-dashoffset: 26; animation: lds-draw .45s ease-out forwards;
    }
    .lds-card[data-state='done'] .lds-slot { color: var(--lds-ok); }
    .lds-card[data-state='error'] .lds-slot { color: var(--lds-err); }
    .lds-slot .lds-alert circle {
        animation: none; opacity: 1; stroke-dasharray: none; stroke-width: 2;
    }
    .lds-slot .lds-alert path {
        stroke-dasharray: none; stroke-dashoffset: 0; animation: none; stroke-width: 2.6;
    }
    .lds-meta { margin: 0; font-size: .78rem; color: var(--lds-muted); min-block-size: 1.1em; }
    .lds-meta[data-tone='ok'] { color: var(--lds-ok); }
    .lds-meta[data-tone='err'] { color: var(--lds-err); }
    .lds-act {
        justify-self: start; border: none; background: transparent; color: var(--lds-accent);
        font: 700 .76rem/1 inherit; cursor: pointer;
        text-decoration: underline; text-underline-offset: .22em;
    }
    .lds-act:focus-visible { outline: 2px solid var(--lds-accent); outline-offset: 2px; }
    .lds-act[data-tone='err'] { color: var(--lds-err); }
    .lds-fine { font-size: .68rem; color: var(--lds-muted); letter-spacing: .04em; }
    @keyframes lds-fade { 0%, 100% { opacity: .12; } 42% { opacity: 1; } }
    @keyframes lds-draw { to { stroke-dashoffset: 0; } }
    @media (prefers-reduced-motion: reduce) {
        .lds-slot circle { animation: none; opacity: .85; }
        .lds-slot path { animation: none; stroke-dashoffset: 0; }
    }
</style>

<div class="lds-root"
    x-data="{
        fa: @js($fa),
                    sync: { state: 'working', n: 384 },
        upload: { state: 'working', speed: 12.4 },
        search: { state: 'idle', routes: 42 },
        ticks: [],
        fmt(n, dec) {
            const s = dec !== undefined ? Number(n).toFixed(dec) : String(n);
            return this.fa ? s.replace(/\d/g, (d) => '۰۱۲۳۴۵۶۷۸۹'[d]) : s;
        },
        every(fn, ms) { const t = setInterval(fn, ms); this.ticks.push(t); return t; },
        init() {
            this.every(() => {
                if (this.sync.state !== 'working') return;
                this.sync.n = Math.min(512, this.sync.n + 3 + Math.floor(Math.random() * 5));
                if (this.sync.n >= 512) this.sync.state = 'done';
            }, 240);
            this.every(() => {
                if (this.upload.state !== 'working') return;
                this.upload.speed = 10.8 + Math.random() * 3.1;
            }, 320);
            this.every(() => { if (this.upload.state === 'working') this.upload.state = 'done'; }, 4600);
            this.searchRun(false);
        },
        searchRun(retry) {
            if (this.search.state === 'working') return;
            this.search.state = 'working';
            this.search.routes = 42;
            this.every(() => {
                this.search.state = retry ? 'done' : 'error';
                if (retry) this.search.routes = 6;
            }, retry ? 1900 : 2600);
        },
        destroy() { this.ticks.forEach((t) => clearInterval(t)); },
    }">

    <section class="pg-box" style="gap: 1.25rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('One spinner, three jobs, three sizes', 'یک نشانگر، سه کار، سه اندازه') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('Eight arcs fade in sequence — the eye reads a turn, the browser spends almost nothing. Small in a list-row sync, medium on an upload, large on a full search; the slot keeps its size as working becomes a tick or an alert.', 'هشت کمان پشت‌سرهم محو و روشن می‌شوند — چشم چرخش می‌بیند، مرورگر تقریباً هیچ هزینه نمی‌کند. کوچک در سطرِ همگام‌سازی، متوسط روی بارگذاری، بزرگ روی جست‌وجوی تمام‌صفحه؛ خانهٔ نشانگر در تبدیل به تیک یا هشدار هم‌قد می‌ماند.') }}
            </p>
        </div>

        <div class="lds-grid">
            {{-- contacts sync — small --}}
            <article class="lds-card" x-bind:data-state="sync.state" style="--lds-size: 18px">
                <div class="lds-head">
                    <span class="lds-slot" role="status" x-bind:aria-label="sync.state === 'working' ? '{{ $say('Syncing contacts', 'همگام‌سازی مخاطبین') }}' : '{{ $say('Sync complete', 'همگام‌سازی کامل شد') }}'">
                        <template x-if="sync.state === 'working'">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                @foreach (range(0, 7) as $i)
                                    <circle cx="12" cy="12" r="8.5" pathLength="100" transform="rotate({{ $i * 45 }} 12 12)" style="--lds-i: {{ $i }}"/>
                                @endforeach
                            </svg>
                        </template>
                        <template x-if="sync.state === 'done'">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4.5 12.5l5 5 10-11"/></svg>
                        </template>
                    </span>
                    <h4>{{ $say('Contacts · Outlook', 'مخاطبین · Outlook') }}</h4>
                </div>
                <p class="lds-meta" x-bind:data-tone="sync.state === 'done' ? 'ok' : ''" aria-live="polite" x-text="sync.state === 'done'
                    ? '{{ $say('Complete · 512 contacts', 'کامل شد · ۵۱۲ مخاطب') }}'
                    : '{{ $say('Syncing', 'در حال همگام‌سازی') }} · ' + fmt(sync.n) + ' / ' + fmt(512)"></p>
                <span class="lds-fine">18px</span>
            </article>

            {{-- video upload — medium --}}
            <article class="lds-card" x-bind:data-state="upload.state" style="--lds-size: 26px">
                <div class="lds-head">
                    <span class="lds-slot" role="status" x-bind:aria-label="upload.state === 'working' ? '{{ $say('Uploading video', 'بارگذاری ویدیو') }}' : '{{ $say('Upload complete', 'بارگذاری کامل شد') }}'">
                        <template x-if="upload.state === 'working'">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                @foreach (range(0, 7) as $i)
                                    <circle cx="12" cy="12" r="8.5" pathLength="100" transform="rotate({{ $i * 45 }} 12 12)" style="--lds-i: {{ $i }}"/>
                                @endforeach
                            </svg>
                        </template>
                        <template x-if="upload.state === 'done'">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4.5 12.5l5 5 10-11"/></svg>
                        </template>
                    </span>
                    <h4>{{ $say('Upload · q4-cut.mp4', 'بارگذاری · q4-cut.mp4') }}</h4>
                </div>
                <p class="lds-meta" x-bind:data-tone="upload.state === 'done' ? 'ok' : ''" aria-live="polite" x-text="upload.state === 'working'
                    ? fmt(upload.speed, 1) + 'MB/s · ' + fmt(214) + 'MB'
                    : '{{ $say('Uploaded · queued for processing', 'بارگذاری شد · در صف پردازش') }}'"></p>
                <span class="lds-fine">26px</span>
            </article>

            {{-- flight search — large --}}
            <article class="lds-card" x-bind:data-state="search.state" style="--lds-size: 38px">
                <div class="lds-head">
                    <span class="lds-slot" role="status" x-bind:aria-label="search.state === 'working' ? '{{ $say('Searching flights', 'جست‌وجوی پرواز') }}' : '{{ $say('Search finished', 'جست‌وجو تمام شد') }}'">
                        <template x-if="search.state === 'working'">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                @foreach (range(0, 7) as $i)
                                    <circle cx="12" cy="12" r="8.5" pathLength="100" transform="rotate({{ $i * 45 }} 12 12)" style="--lds-i: {{ $i }}"/>
                                @endforeach
                            </svg>
                        </template>
                        <template x-if="search.state === 'error'">
                            <svg class="lds-alert" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7.5v5.2m0 3.3v.5"/></svg>
                        </template>
                        <template x-if="search.state === 'done'">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4.5 12.5l5 5 10-11"/></svg>
                        </template>
                    </span>
                    <h4>{{ $say('Flights · Singapore – Frankfurt', 'پروازها · سنگاپور – فرانکفورت') }}</h4>
                </div>
                <p class="lds-meta" x-bind:data-tone="search.state === 'error' ? 'err' : (search.state === 'done' ? 'ok' : '')" aria-live="polite" x-text="search.state === 'working'
                    ? '{{ $say('Scanning 42 routes…', 'در حال پیمایش ۴۲ مسیر…') }}'
                    : search.state === 'error' ? '{{ $say('Server did not answer (503).', 'سرور پاسخ نداد (۵۰۳).') }}'
                    : '{{ $say('6 results found · from €318', '۶ نتیجه پیدا شد · از €۳۱۸') }}'"></p>
                <button type="button" class="lds-act"
                    x-show="search.state !== 'working'"
                    x-bind:data-tone="search.state === 'error' ? 'err' : ''"
                    x-on:click="searchRun(search.state !== 'idle')"
                    x-text="search.state === 'error' ? '{{ $say('Retry search', 'تلاش دوباره') }}' : '{{ $say('Search again', 'جست‌وجوی دوباره') }}'"></button>
                <span class="lds-fine">38px</span>
            </article>
        </div>
    </section>
</div>
