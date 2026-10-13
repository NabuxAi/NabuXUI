{{--
    The CTA-footer: a short page body ends, and a gradient CTA card floats
    over the footer's edge — badge, title, two buttons — rising in as it
    scrolls into view (IntersectionObserver, once), with a replay control in
    the intro; the link rows and the closing line sit beneath it.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;
@endphp
<style>
    .ctf-root {
        --ctf-body: #FFFFFF; --ctf-foot: #F5F5F4; --ctf-border: #E7E5E4;
        --ctf-text: #1C1917; --ctf-muted: #78716C; --ctf-ink: #4F46E5;
        font-family: 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif;
        color: var(--ctf-text);
        display: grid;
        gap: 1.5rem;
    }
    html[data-theme="dark"] .ctf-root {
        --ctf-body: #171512; --ctf-foot: #0C0A09; --ctf-border: #292524;
        --ctf-text: #FAFAF9; --ctf-muted: #A8A29E; --ctf-ink: #A5B4FC;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .ctf-root {
            --ctf-body: #171512; --ctf-foot: #0C0A09; --ctf-border: #292524;
            --ctf-text: #FAFAF9; --ctf-muted: #A8A29E; --ctf-ink: #A5B4FC;
        }
    }
    .ctf-replay { justify-self: center; display: inline-flex; align-items: center; gap: .45rem;
                  padding: .45rem 1rem; font-size: .8rem; font-weight: 600; cursor: pointer;
                  color: var(--ctf-muted); background: transparent; border: 1px solid var(--ctf-border);
                  border-radius: 999px; transition: color .15s ease, border-color .15s ease; }
    .ctf-replay:hover { color: var(--ctf-text); border-color: var(--ctf-muted); }
    .ctf-replay:focus-visible { outline: 2px solid var(--ctf-ink); outline-offset: 2px; }
    .ctf-replay svg { inline-size: .9rem; block-size: .9rem; stroke: currentColor; stroke-width: 2;
                      fill: none; stroke-linecap: round; stroke-linejoin: round; }
    .ctf-page { background: var(--ctf-body); }
    .ctf-lead { display: grid; gap: .7rem; max-inline-size: 40rem; margin-inline: auto;
                padding-block: clamp(2rem, 6vw, 4rem) 4.5rem; }
    .ctf-lead h4 { margin: 0; font-size: .8rem; font-weight: 600; letter-spacing: .08em;
                   text-transform: uppercase; color: var(--ctf-muted); }
    .ctf-lead p { margin: 0; color: var(--ctf-muted); font-size: .9rem; line-height: 1.7; }
    .ctf-skel { display: grid; gap: .55rem; margin-block-start: 1.25rem; }
    .ctf-skel i { display: block; block-size: .55rem; border-radius: 999px; background: var(--ctf-border); }
    .ctf-skel i:nth-child(2) { inline-size: 86%; }
    .ctf-skel i:nth-child(3) { inline-size: 68%; }
    .ctf-foot { padding: 0 clamp(1rem, 4vw, 2.5rem) 1.25rem;
                background: var(--ctf-foot); border-block-start: 1px solid var(--ctf-border); }
    .ctf-card { position: relative; inline-size: min(100%, 44rem); margin-block-start: -3.5rem; margin-inline: auto;
                display: grid; justify-items: center; gap: 1.1rem; text-align: center;
                padding: clamp(1.75rem, 4vw, 2.75rem) clamp(1.25rem, 4vw, 2rem); color: #fff;
                border-radius: 1.4rem; overflow: clip; isolation: isolate;
                background: linear-gradient(135deg, #6366F1, #8B5CF6 45%, #EC4899);
                box-shadow: 0 24px 48px -16px rgba(139, 92, 246, .5);
                opacity: 0; translate: 0 1.75rem; scale: .97;
                transition: opacity .65s ease, translate .65s ease, scale .65s ease; }
    .ctf-card[data-seen="true"] { opacity: 1; translate: 0 0; scale: 1; }
    .ctf-card::before { content: ''; position: absolute; inset-block-start: -60%; inset-inline-end: -20%;
                        z-index: -1; block-size: 160%; aspect-ratio: 1; pointer-events: none;
                        background: radial-gradient(circle, rgba(255, 255, 255, .22), transparent 60%); }
    .ctf-badge { display: inline-flex; align-items: center; gap: .4rem; padding: .32rem .85rem;
                 font-size: .72rem; font-weight: 600; letter-spacing: .02em;
                 background: rgba(255, 255, 255, .18); border: 1px solid rgba(255, 255, 255, .35);
                 border-radius: 999px; backdrop-filter: blur(4px); }
    .ctf-badge svg { inline-size: .8rem; block-size: .8rem; stroke: currentColor; stroke-width: 2.2;
                     fill: none; stroke-linecap: round; stroke-linejoin: round; }
    .ctf-title { margin: 0; font-size: clamp(1.5rem, 3.6vw, 2.35rem); font-weight: 800; line-height: 1.18;
                 letter-spacing: -.02em; text-wrap: balance; }
    .ctf-actions { display: flex; flex-wrap: wrap; gap: .75rem; justify-content: center; }
    .ctf-btn { display: grid; place-items: center; block-size: 2.85rem; padding-inline: 1.6rem;
               font-size: .92rem; font-weight: 700; text-decoration: none; cursor: pointer;
               color: var(--ctf-ink); background: #fff; border: 1px solid #fff; border-radius: .85rem;
               transition: transform .15s ease, filter .15s ease; }
    .ctf-btn:hover { transform: translateY(-1px); }
    .ctf-btn:active { transform: translateY(0) scale(.98); }
    .ctf-btn:focus-visible { outline: 2px solid #fff; outline-offset: 2px; }
    .ctf-btn--ghost { color: #fff; background: transparent; }
    .ctf-btn--ghost:hover { background: rgba(255, 255, 255, .12); }
    .ctf-links { display: flex; flex-wrap: wrap; gap: .4rem 1.4rem; justify-content: center;
                 max-inline-size: 72rem; margin-block-start: 2.25rem; margin-inline: auto;
                 padding-block-start: 1.4rem; border-block-start: 1px solid var(--ctf-border); }
    .ctf-links a { font-size: .85rem; color: var(--ctf-muted); text-decoration: none;
                   transition: color .15s ease; }
    .ctf-links a:hover { color: var(--ctf-text); text-decoration: underline; }
    .ctf-links a:focus-visible { outline: 2px solid var(--ctf-ink); outline-offset: 2px; border-radius: 2px; }
    .ctf-base { display: flex; flex-wrap: wrap; gap: .5rem 1.5rem; justify-content: space-between;
                max-inline-size: 72rem; margin-block-start: 1.1rem; margin-inline: auto; }
    .ctf-base p { margin: 0; font-size: .75rem; color: var(--ctf-muted); }
    @media (prefers-reduced-motion: reduce) {
        .ctf-root * { transition-duration: .01ms !important; animation-duration: .01ms !important; }
    }
</style>

<div class="ctf-root" x-data="{
    seen: false,
    replay() {
        this.seen = false;
        requestAnimationFrame(() => this.obs.observe(this.$refs.card));
    },
}" x-init="
    obs = new IntersectionObserver(
        (entries) => entries.forEach((entry) => {
            if (entry.isIntersecting) { seen = true; obs.unobserve(entry.target); }
        }),
        { threshold: 0.35 },
    );
    obs.observe($refs.card);
    $cleanup(() => obs.disconnect());
">
    <section class="pg-box" style="justify-items: center; gap: .9rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The banner that closes the pitch', 'بنری که فروش را می‌بندد') }}</h3>
        <p style="margin: 0; max-width: 56ch; color: var(--nx-text-muted); text-align: center">
            {{ $say('Scroll the footer into view and the gradient card rises over its edge — badge, title, two buttons — then stays put. Replay resets the reveal to watch it land again.', 'فوتر را به دید بکش تا کارت گرادیانی از لبه‌اش بالا بیاید — بج، تیتر، دو دکمه — و بعد سر جایش بماند. بازپخش، ظاهرشدن را صفر می‌کند تا دوباره تماشا کنی.') }}
        </p>
        <button type="button" class="ctf-replay" x-on:click="replay()">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/></svg>
            {{ $say('Replay the reveal', 'بازپخش ظاهرشدن') }}
        </button>
    </section>

    <section class="pg-box" style="padding: 0; overflow: clip">
        <div class="ctf-page">
            <div class="ctf-lead">
                <h4>{{ $say('Release 2026.10 — the page keeps going', 'انتشار ۲۰۲۶٫۱۰ — صفحه ادامه دارد') }}</h4>
                <p>
                    {{ $say('Every rollout lands with a guarded flag, a dashboard that never sleeps and a rollback that takes one click. Below this line, the page hands over to its footer.', 'هر انتشار با فلگ محافظ‌دار، داشبوردی که هرگز نمی‌خوابد و واگردی که یک کلیک طول می‌کشد زمین می‌خورد. زیر این خط، صفحه را به فوترش می‌سپارد.') }}
                </p>
                <div class="ctf-skel" aria-hidden="true"><i></i><i></i><i></i></div>
            </div>
        </div>

        <footer class="ctf-foot">
            <div class="ctf-card" x-ref="card" x-bind:data-seen="seen">
                <span class="ctf-badge">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3l2.2 5.2L19.5 10l-5.3 1.8L12 17l-2.2-5.2L4.5 10l5.3-1.8z"/></svg>
                    {{ $say('14-day trial · no credit card', '۱۴ روز آزمایش · بدون کارت بانکی') }}
                </span>
                <h3 class="ctf-title">
                    {{ $say('Start shipping calmly, today.', 'از امروز، با آرامش منتشر کن.') }}
                </h3>
                <div class="ctf-actions">
                    <a class="ctf-btn" href="#start">{{ $say('Start free', 'شروع رایگان') }}</a>
                    <a class="ctf-btn ctf-btn--ghost" href="#sales">{{ $say('Talk to sales', 'گفت‌وگو با فروش') }}</a>
                </div>
            </div>

            <nav class="ctf-links" aria-label="{{ $say('Footer', 'فوتر') }}">
                @foreach ([
                    $say('Feature flags', 'فیچر فلگ‌ها'),
                    $say('Observability', 'مشاهده‌پذیری'),
                    $say('Pricing', 'قیمت‌گذاری'),
                    $say('Documentation', 'مستندات'),
                    $say('Status', 'وضعیت سرویس'),
                    $say('Changelog', 'تغییرات'),
                    $say('Security', 'امنیت'),
                ] as $link)
                    <a href="#{{ Str::slug($link) }}">{{ $link }}</a>
                @endforeach
            </nav>

            <div class="ctf-base">
                <p>© {{ $num('2026') }} Meridian — {{ $say('Frankfurt · Dublin · Singapore', 'فرانکفورت · دوبلین · سنگاپور') }}</p>
                <p>{{ $say('SOC 2 Type II · GDPR · ISO 27001', 'SOC 2 Type II · GDPR · ISO 27001') }}</p>
            </div>
        </footer>
    </section>
</div>
