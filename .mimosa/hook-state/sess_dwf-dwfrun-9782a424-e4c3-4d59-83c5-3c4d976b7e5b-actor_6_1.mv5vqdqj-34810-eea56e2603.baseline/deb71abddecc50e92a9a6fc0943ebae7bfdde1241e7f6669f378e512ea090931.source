{{--
    The spotlight grid's real scenario: the feature wall of a product page —
    one mouse spotlight sweeps the whole wall, the card under the hand lights
    its 1px border through an exclude mask and takes a soft inner wash, and
    keyboard focus paints the same glow without any pointer at all.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;

    $features = [
        ['id' => 'edge',    'fa' => 'تحویل لبه',        'en' => 'Edge delivery',   'faNote' => 'شبکهٔ تحویل با '.$num('28').'+ نقطه، از فرانکفورت تا سنگاپور.', 'enNote' => 'A delivery network with 28+ points, Frankfurt to Singapore.', 'meta' => $num('28') . '+ PoP'],
        ['id' => 'query',   'fa' => 'کوئری زنده',        'en' => 'Live queries',    'faNote' => 'پاسخ زیر '.$num('40').' میلی‌ثانیه حتی روی رویدادهای امروز.', 'enNote' => 'Answers under 40ms even on today’s events.', 'meta' => 'p95 ' . $num('38') . 'ms'],
        ['id' => 'replay',  'fa' => 'بازپخش نشست',       'en' => 'Session replay',  'faNote' => 'همان کلیک، همان مکث — با پوشش دادهٔ حساس پوشیده.', 'enNote' => 'The exact click, the exact pause — with sensitive fields masked.', 'meta' => $num('4K') . ' · ' . $num('60')],
        ['id' => 'guards',  'fa' => 'ریل‌های نگهبان',     'en' => 'Guardrails',      'faNote' => 'هشدار بودجه پیش از رسیدن قبض، نه بعدش.', 'enNote' => 'Budget alerts before the invoice lands, not after.', 'meta' => $num('3') . ' ' . $say('tiers', 'سطح')],
        ['id' => 'sync',    'fa' => 'همگام‌سازی انبار',   'en' => 'Warehouse sync',  'faNote' => 'BigQuery و Snowflake، هر پانزده دقیقه.', 'enNote' => 'BigQuery and Snowflake, every fifteen minutes.', 'meta' => $num('15') . 'min'],
        ['id' => 'sso',     'fa' => 'ورود یکپارچه',      'en' => 'Enterprise SSO',  'faNote' => 'SAML و SCIM با یک کلید، بدون پروژهٔ شش‌ماهه.', 'enNote' => 'SAML and SCIM with one toggle, no six-month project.', 'meta' => 'SAML · SCIM'],
        ['id' => 'sdk',     'fa' => 'کیت‌های توسعه',      'en' => 'SDKs',            'faNote' => 'پنج فریم‌ورک، یک API؛ از رویداد خام تا داشبورد.', 'enNote' => 'Five frameworks, one API; raw event to dashboard.', 'meta' => $num('5') . ' ' . $say('frameworks', 'فریم‌ورک')],
        ['id' => 'status',  'fa' => 'وضعیت و هشدار',     'en' => 'Status & alerts', 'faNote' => 'صفحهٔ وضعیت عمومی و هشدارهایی که به‌وقت می‌رسند.', 'enNote' => 'A public status page and alerts that arrive on time.', 'meta' => $num('99.99') . '%'],
    ];
@endphp

<style>
    .mfxsg-root {
        --mfxsg-glow: #4f46e5; --mfxsg-glow-soft: rgba(79, 70, 229, .16);
        --mfxsg-wash: rgba(79, 70, 229, .09);
        --mfxsg-ink: #16181d; --mfxsg-ink-soft: #5c6270;
        --mfxsg-line: #d9dde4; --mfxsg-surface: #ffffff; --mfxsg-floor: #f7f8fa;
        display: grid; gap: 1.25rem;
        font-family: var(--nx-font-display, system-ui);
    }
    html[data-theme="dark"] .mfxsg-root {
        --mfxsg-glow: #8b83ff; --mfxsg-glow-soft: rgba(139, 131, 255, .2);
        --mfxsg-wash: rgba(139, 131, 255, .12);
        --mfxsg-ink: #f2f4f8; --mfxsg-ink-soft: #9aa1b0;
        --mfxsg-line: #2e3243; --mfxsg-surface: #1a1d2b; --mfxsg-floor: #12141d;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .mfxsg-root {
            --mfxsg-glow: #8b83ff; --mfxsg-glow-soft: rgba(139, 131, 255, .2);
            --mfxsg-wash: rgba(139, 131, 255, .12);
            --mfxsg-ink: #f2f4f8; --mfxsg-ink-soft: #9aa1b0;
            --mfxsg-line: #2e3243; --mfxsg-surface: #1a1d2b; --mfxsg-floor: #12141d;
        }
    }
    .mfxsg-head { display: grid; gap: .4rem; text-align: center; justify-items: center; }
    .mfxsg-head p { margin: 0; max-inline-size: 56ch; color: var(--nx-text-muted); }
    .mfxsg-grid {
        position: relative; overflow: clip; border-radius: 1.25rem; background: var(--mfxsg-floor);
        display: grid; gap: 1rem; padding: clamp(1rem, 3vw, 1.5rem);
        grid-template-columns: repeat(auto-fit, minmax(min(15rem, 100%), 1fr));
    }
    .mfxsg-grid::before {
        content: ''; position: absolute; inset: 0; z-index: 1; pointer-events: none;
        background: radial-gradient(260px circle at var(--mx, 50%) var(--my, 8%), var(--mfxsg-glow-soft), transparent 70%);
        opacity: 0; transition: opacity .3s ease;
    }
    .mfxsg-grid[data-on="yes"]::before { opacity: 1; }
    .mfxsg-card {
        position: relative; display: grid; gap: .5rem; align-content: start;
        padding: 1.25rem; border: 1px solid var(--mfxsg-line); border-radius: 1rem;
        background: var(--mfxsg-surface); color: var(--mfxsg-ink);
        transition: background-color .25s ease;
    }
    /* The 1px ring feeds off a light that lives in the card’s own coordinates. */
    .mfxsg-card::before {
        content: ''; position: absolute; inset: 0; border-radius: inherit; padding: 1px;
        background: radial-gradient(220px circle at var(--cx, 50%) var(--cy, 50%), var(--mfxsg-glow), transparent 70%);
        mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        mask-composite: exclude; -webkit-mask-composite: xor;
        opacity: 0; transition: opacity .25s ease; pointer-events: none;
    }
    .mfxsg-card:is([data-hot], :focus-within)::before { opacity: 1; }
    .mfxsg-card[data-hot] {
        background:
            radial-gradient(220px circle at var(--cx) var(--cy), var(--mfxsg-wash), transparent 65%),
            var(--mfxsg-surface);
    }
    .mfxsg-card[data-hot] .mfxsg-title { color: var(--mfxsg-glow); }
    .mfxsg-title { margin: 0; font-size: 1rem; font-weight: 700; }
    .mfxsg-note { margin: 0; font-size: .84rem; line-height: 1.8; color: var(--mfxsg-ink-soft); }
    .mfxsg-meta {
        justify-self: start; margin-block-start: .35rem; padding: .28rem .7rem;
        border: 1px solid var(--mfxsg-line); border-radius: 999px;
        font: 600 .7rem / 1 var(--nx-font-mono, ui-monospace); color: var(--mfxsg-ink-soft);
    }
    .mfxsg-card:focus-within { outline: none; }
    .mfxsg-card :focus-visible { outline: 2px solid var(--mfxsg-glow); outline-offset: 3px; border-radius: .4rem; }
    .mfxsg-card a { color: inherit; text-decoration: none; }
    @media (prefers-reduced-motion: reduce) {
        .mfxsg-grid::before {
            background: radial-gradient(320px circle at 50% 22%, var(--mfxsg-glow-soft), transparent 70%);
            opacity: 1;
        }
        .mfxsg-grid::before, .mfxsg-card, .mfxsg-card::before { transition: none; }
    }
</style>

<section class="pg-box" style="padding: 0; overflow: clip">
    <div class="mfxsg-root"
        x-data="{
            on: false, reduced: false,
            cards() { return Array.from(this.$refs.grid.querySelectorAll('.mfxsg-card')) },
            onMove(event) {
                if (this.reduced) return
                const grid = this.$refs.grid, r = grid.getBoundingClientRect(),
                px = event.clientX - r.left, py = event.clientY - r.top
                grid.style.setProperty('--mx', px + 'px')
                grid.style.setProperty('--my', py + 'px')
                this.cards().forEach((card) => {
                    const c = card.getBoundingClientRect(),
                    cx = event.clientX - c.left, cy = event.clientY - c.top
                    card.style.setProperty('--cx', cx + 'px')
                    card.style.setProperty('--cy', cy + 'px')
                    if (cx >= 0 && cy >= 0 && cx <= c.width && cy <= c.height) card.setAttribute('data-hot', '')
                    else card.removeAttribute('data-hot')
                })
                this.on = true
            },
            onLeave() {
                this.on = false
                this.cards().forEach((card) => card.removeAttribute('data-hot'))
            },
            init() {
                this.reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches
                const grid = this.$refs.grid
                this.move = (e) => this.onMove(e)
                this.leave = () => this.onLeave()
                grid.addEventListener('pointermove', this.move)
                grid.addEventListener('pointerleave', this.leave)
            },
            destroy() {
                const grid = this.$refs.grid
                grid.removeEventListener('pointermove', this.move)
                grid.removeEventListener('pointerleave', this.leave)
            },
        }">
        <div class="mfxsg-head">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Nova Metrics — everything the dashboard does', 'نوا متریکس — هر کاری داشبورد می‌کند') }}</h3>
            <p>{{ $say('Drag your pointer across the wall: one halo follows your hand over the whole grid, and the card you are on lights its own border from inside.', 'اشاره‌گر را روی دیوار بکشید: یک هاله روی کل گرید دنبال دست شما می‌آید و کارتی که روی ایستاده از درون، حاشیهٔ خودش را روشن می‌کند.') }}</p>
        </div>

        <div class="mfxsg-grid" x-ref="grid" x-bind:data-on="on ? 'yes' : 'no'">
            @foreach ($features as $f)
                <article class="mfxsg-card" tabindex="-1">
                    <h4 class="mfxsg-title"><a href="#mfxsg-{{ $f['id'] }}">{{ $say($f['en'], $f['fa']) }}</a></h4>
                    <p class="mfxsg-note">{{ $say($f['enNote'], $f['faNote']) }}</p>
                    <span class="mfxsg-meta">{{ $f['meta'] }}</span>
                </article>
            @endforeach
        </div>
    </div>
</section>
