{{--
    Sticky stack cards on a pricing runway: four Meridian plans, each in an
    88vh zone, sticking a little lower than the one before. One rAF-throttled
    scroll pass (measured against each card's own position, cleaned up on
    destroy — the effects/scroll-progress pattern) writes every card's
    coverage value; CSS turns it into the scale + dim of the buried card.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;
    $plans = [
        ['starter', $say('Starter', 'آغازگر'), $say('from $0', 'از ۰ دلار'), $say('For the first release', 'برای نخستین انتشار'),
            [$say('2 rollout pipelines', '۲ خط انتشار'), $say('3 regions', '۳ ناحیه'), $say('Weekly digest', 'خلاصهٔ هفتگی')], '#2f9e6e'],
        ['growth', $say('Growth', 'رشد'), $say('from $49', 'از ۴۹ دلار'), $say('For teams shipping weekly', 'برای تیم‌هایی که هر هفته منتشر می‌کنند'),
            [$say('10 pipelines', '۱۰ خط انتشار'), $say('12 regions', '۱۲ ناحیه'), $say('Canary + auto-rollback', 'کاناری + واگرد خودکار')], '#5647e6'],
        ['scale', $say('Scale', 'مقیاس'), $say('from $199', 'از ۱۹۹ دلار'), $say('For fleets of services', 'برای ناوگان سرویس‌ها'),
            [$say('Unlimited pipelines', 'خط انتشار نامحدود'), $say('24 regions', '۲۴ ناحیه'), $say('Progressive delivery + SLA', 'تحویل تدریجی + SLA')], '#c2530f'],
        ['enterprise', $say('Enterprise', 'سازمانی'), $say('Custom', 'سفارشی'), $say('For regulated scale', 'برای مقیاسِ زیر مقررات'),
            [$say('Private cloud', 'ابر اختصاصی'), $say('SOC 2 + audit trail', 'SOC 2 + رد حسابرسی'), $say('Dedicated engineer', 'مهندس اختصاصی')], '#0f62fe'],
    ];
@endphp
<style>
    .scs-root {
        --scs-text: #1d1b2e; --scs-muted: #5c5a75; --scs-border: #e4e4ef;
        --scs-surface: #ffffff; --scs-stage: #f4f4fa; --scs-chip: #eeeef7;
        font-family: var(--nx-font-display, system-ui);
        color: var(--scs-text);
    }
    html[data-theme="dark"] .scs-root {
        --scs-text: #f0eefc; --scs-muted: #a9a6c6; --scs-border: #34314e;
        --scs-surface: #1b1a2c; --scs-stage: #12111f; --scs-chip: #262440;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .scs-root {
            --scs-text: #f0eefc; --scs-muted: #a9a6c6; --scs-border: #34314e;
            --scs-surface: #1b1a2c; --scs-stage: #12111f; --scs-chip: #262440;
        }
    }
    .scs-stage {
        position: relative; display: grid; overflow: clip;
        border-radius: 1.25rem; background: var(--scs-stage);
        border: 1px solid var(--scs-border);
    }
    .scs-track { display: grid; }
    .scs-zone { block-size: 88vh; }
    .scs-card {
        --scs-b: 0;
        position: sticky; inset-block-start: calc(4.5rem + var(--i) * .875rem);
        inline-size: min(100% - 2rem, 34rem); margin-inline: auto;
        padding: clamp(1.15rem, 3vw, 1.75rem);
        border-radius: 1.5rem; border: 1px solid var(--scs-border);
        background: var(--scs-surface);
        box-shadow: 0 18px 44px rgba(24, 20, 52, .12);
        scale: calc(1 - var(--scs-b) * .06);
        opacity: calc(1 - var(--scs-b) * .55);
    }
    .scs-card::before {
        content: ''; position: absolute; inset-block-start: 0; inset-inline: 1.5rem;
        block-size: 3px; border-radius: 999px; background: var(--scs-accent);
    }
    .scs-kicker { margin: 0; font-size: .78rem; font-weight: 600; letter-spacing: .08em;
                  text-transform: uppercase; color: var(--scs-accent); }
    .scs-name { margin: .3rem 0 0; font-size: clamp(1.45rem, 4vw, 1.9rem); font-weight: 700; }
    .scs-price { margin: .45rem 0 1.1rem; font-size: 1.05rem; color: var(--scs-muted); }
    .scs-price b { color: var(--scs-text); font-size: 1.5rem; }
    .scs-perks { margin: 0; padding: 0; list-style: none; display: grid; gap: .55rem; }
    .scs-perks li { display: flex; align-items: baseline; gap: .6rem; color: var(--scs-muted); font-size: .95rem; }
    .scs-perks li::before {
        content: '✓'; flex: none; inline-size: 1.3rem; aspect-ratio: 1; display: grid; place-items: center;
        border-radius: 50%; color: var(--scs-accent); background: color-mix(in oklab, var(--scs-accent) 14%, transparent);
        font-size: .72rem; font-weight: 700; align-self: center;
    }
    .scs-foot { display: flex; flex-wrap: wrap; align-items: center; gap: .75rem;
                margin-block-start: 1.35rem; padding-block-start: 1.1rem; border-block-start: 1px dashed var(--scs-border); }
    .scs-pick { block-size: 2.6rem; padding-inline: 1.35rem; border: 1px solid var(--scs-border); border-radius: 999px;
                background: var(--scs-surface); color: var(--scs-text); font: 600 .92rem var(--nx-font-display, system-ui);
                cursor: pointer; transition: background-color .15s ease, color .15s ease, border-color .15s ease; }
    .scs-pick:hover { border-color: var(--scs-accent); color: var(--scs-accent); }
    .scs-pick[aria-pressed="true"] { background: var(--scs-accent); border-color: var(--scs-accent); color: #fff; }
    .scs-pick:focus-visible { outline: 2px solid var(--scs-accent); outline-offset: 2px; }
    .scs-aud { margin: 0; font-size: .8rem; color: var(--scs-muted); }
    .scs-chosen { margin-inline-start: auto; padding: .35rem .8rem; border-radius: 999px; background: var(--scs-chip);
                  font-size: .78rem; font-weight: 600; color: var(--scs-text); }
    .scs-note { margin: 1rem 0 0; padding: .8rem 1.1rem; border-radius: 1rem; background: var(--scs-chip);
                font-size: .88rem; color: var(--scs-muted); }
    :where(.nx-js) .pg:has(.scs-root) .nx-data-table[data-nx-reveal] tbody tr { opacity: 1; translate: none; }
    @media (max-width: 480px) {
        .scs-zone { block-size: 84vh; }
        .scs-card { inset-block-start: calc(3.5rem + var(--i) * .625rem); }
    }
    @media (prefers-reduced-motion: reduce) {
        .scs-root * { transition-duration: .01ms !important; animation-duration: .01ms !important; }
    }
</style>

<div class="scs-root"
    x-data="{
        plan: null,
        stop: () => {},
        init() {
            const cards = [...this.$root.querySelectorAll('.scs-card')];
            let frame = 0;
            const measure = () => {
                frame = 0;
                cards.forEach((card, i) => {
                    const next = cards[i + 1];
                    if (!next) return;
                    const top = card.getBoundingClientRect().top;
                    const nt = next.getBoundingClientRect().top;
                    const covered = Math.min(1, Math.max(0, (window.innerHeight - nt) / (window.innerHeight - top)));
                    card.style.setProperty('--scs-b', covered.toFixed(3));
                });
            };
            const schedule = () => { if (!frame) frame = requestAnimationFrame(measure); };
            window.addEventListener('scroll', schedule, { passive: true });
            window.addEventListener('resize', schedule, { passive: true });
            this.stop = () => {
                cancelAnimationFrame(frame);
                window.removeEventListener('scroll', schedule);
                window.removeEventListener('resize', schedule);
            };
            measure();
        },
        destroy() { this.stop(); },
    }">
    <section class="pg-box" style="gap: 1.25rem">
        <div style="display: grid; gap: .5rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Four plans, one runway', 'چهار پلن، یک باند فرود') }}</h3>
            <p style="margin: 0; max-inline-size: 54ch; color: var(--nx-text-muted)">
                {{ $say('Scroll and the Growth card lands on Starter, Scale on Growth — every buried card keeps a visible rim, shrinks by six percent and dims, so the stack reads as depth rather than a pile. Picking a plan stays live inside the sticky card.', 'اسکرول کنید تا کارت «رشد» روی «آغازگر» و «مقیاس» روی «رشد» بنشیند — هر کارتِ زیرین لبهٔ دیده‌شده را نگه می‌دارد، شش درصد کوچک و محو می‌شود تا انبار، عمق خوانده شود نه تلنبار. انتخاب پلن هم داخل همان کارت چسبان زنده است.') }}
            </p>
        </div>

        <div class="scs-stage">
            <div class="scs-track">
                @foreach ($plans as $i => [$id, $name, $price, $audience, $perks, $accent])
                    <div class="scs-zone">
                        <article class="scs-card" style="--i: {{ $i }}; --scs-accent: {{ $accent }}">
                            <p class="scs-kicker">{{ $say('Meridian · plan '.($i + 1), 'مریدین · پلن '.$num((string) ($i + 1))) }}</p>
                            <h4 class="scs-name">{{ $name }}</h4>
                            <p class="scs-price"><b>{{ $price }}</b> {{ $say('/ month', '/ ماه') }}</p>
                            <ul class="scs-perks">
                                @foreach ($perks as $perk)
                                    <li>{{ $perk }}</li>
                                @endforeach
                            </ul>
                            <div class="scs-foot">
                                <button type="button" class="scs-pick" x-bind:aria-pressed="plan === '{{ $id }}' ? 'true' : 'false'" x-on:click="plan = '{{ $id }}'">
                                    {{ $say('Choose plan', 'انتخاب پلن') }}
                                </button>
                                <p class="scs-aud">{{ $audience }}</p>
                                <span class="scs-chosen" x-show="plan === '{{ $id }}'" x-cloak>
                                    {{ $say('In your cart', 'در سبد شما') }} · {{ $name }}
                                </span>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>

        <p class="scs-note" role="status" x-show="plan" x-cloak style="text-align: center">
            {{ $say('Workspace seeded with the', 'ورک‌اسپیس با پلن') }}
            <b x-text="plan ? ({{ json_encode([$plans[0][1], $plans[1][1], $plans[2][1], $plans[3][1]]) }}[['starter','growth','scale','enterprise'].indexOf(plan)]) : ''"></b>
            {{ $say('plan — billing starts after the first rollout.', 'آماده شد — صورت‌حساب بعد از نخستین انتشار شروع می‌شود.') }}
        </p>
    </section>
</div>
