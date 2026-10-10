{{--
    Glass pricing over gradient blobs: three Vitral plans on one drifting
    colour field — the middle one lifts itself, wears a gradient rim that
    breathes light, and carries the popular badge; every card’s button sends
    a sheen across its face on hover.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹', '.' => '٫', '%' => '٪']) : $s;
    $plans = [
        ['key' => 'starter', 'name' => ['Starter', 'استارتر'], 'price' => '0', 'free' => true,
         'tag' => ['For the first dashboard', 'برای نخستین داشبورد'],
         'feats' => [['1 workspace, 3 seats', '۱ ورک‌اسپیس، ۳ صندلی'], ['7-day trace retention', 'نگهداری ۷ روزهٔ تریس'], ['Community support', 'پشتیبانی انجمن']],
         'cta' => ['Start free', 'شروع رایگان'], 'hot' => false],
        ['key' => 'growth', 'name' => ['Growth', 'رشد'], 'price' => '24',
         'tag' => ['For teams shipping weekly', 'برای تیم‌هایی که هفتگی منتشر می‌کنند'],
         'feats' => [['Unlimited workspaces', 'ورک‌اسپیس نامحدود'], ['30-day trace retention', 'نگهداری ۳۰ روزهٔ تریس'], ['Slack + webhook alerts', 'هشدارهای اسلک و وب‌هوک'], ['Error budgets & SLOs', 'بودجهٔ خطا و SLO']],
         'cta' => ['Start 14-day trial', 'شروع ۱۴ روز آزمایشی'], 'hot' => true],
        ['key' => 'scale', 'name' => ['Scale', 'مقیاس'], 'price' => '79',
         'tag' => ['For platform teams', 'برای تیم‌های پلتفرم'],
         'feats' => [['SSO / SAML & SCIM', 'SSO / SAML و SCIM'], ['1-year retention, EU or Singapore', 'نگهداری ۱ ساله، اروپا یا سنگاپور'], ['Audit log & dedicated support', 'لاگ ممیزی و پشتیبانی اختصاصی']],
         'cta' => ['Talk to sales', 'گفت‌وگو با فروش'], 'hot' => false],
    ];
@endphp
<style>
    .gpc-root {
        --gpc-glass: rgba(255,255,255,.15); --gpc-glass-2: rgba(255,255,255,.06);
        --gpc-rim: rgba(255,255,255,.44); --gpc-sheen: rgba(255,255,255,.5);
        --gpc-ink: #10142e; --gpc-muted: rgba(16,20,46,.64);
        --gpc-hover: rgba(255,255,255,.22);
        --gpc-rim-a: var(--nx-lapis-500); --gpc-rim-b: var(--nx-cyan-400); --gpc-rim-c: var(--nx-violet-500);
        display: grid; gap: 1.75rem; justify-items: center; inline-size: 100%;
    }
    html[data-theme="dark"] .gpc-root {
        --gpc-glass: rgba(21,26,56,.5); --gpc-glass-2: rgba(21,26,56,.3);
        --gpc-rim: rgba(255,255,255,.24); --gpc-sheen: rgba(255,255,255,.22);
        --gpc-ink: #eef1ff; --gpc-muted: rgba(238,241,255,.66);
        --gpc-hover: rgba(255,255,255,.08);
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .gpc-root {
            --gpc-glass: rgba(21,26,56,.5); --gpc-glass-2: rgba(21,26,56,.3);
            --gpc-rim: rgba(255,255,255,.24); --gpc-sheen: rgba(255,255,255,.22);
            --gpc-ink: #eef1ff; --gpc-muted: rgba(238,241,255,.66);
            --gpc-hover: rgba(255,255,255,.08);
        }
    }

    .gpc-stage { position: relative; isolation: isolate; overflow: clip; display: grid; place-items: center;
                 inline-size: 100%; padding: clamp(3.5rem, 7vw, 5rem) 1.25rem 3rem;
                 border-radius: var(--nx-radius-2xl); background: var(--nx-bg); }
    .gpc-blobs { position: absolute; inset: 0; z-index: -2; filter: blur(34px) saturate(1.25); }
    .gpc-blobs i { position: absolute; inline-size: 42%; aspect-ratio: 1; border-radius: 50%; opacity: .85;
                   animation: gpc-drift 21s ease-in-out infinite alternate; }
    .gpc-blobs i:nth-child(1) { inset-block-start: -12%; inset-inline-start: -7%; background: var(--nx-lapis-500); }
    .gpc-blobs i:nth-child(2) { inset-block-end: -18%; inset-inline-start: 26%; background: var(--nx-violet-500); animation-delay: -8s; }
    .gpc-blobs i:nth-child(3) { inset-block-start: 2%; inset-inline-end: -11%; background: var(--nx-cyan-400); animation-delay: -14s; }
    .gpc-blobs i:nth-child(4) { inset-block-end: -10%; inset-inline-end: 18%; inline-size: 24%; background: var(--nx-gold-400); animation-delay: -4s; }
    @keyframes gpc-drift { to { translate: calc(7% * var(--nx-motion, 1)) calc(-6% * var(--nx-motion, 1)); scale: calc(1 + .1 * var(--nx-motion, 1)); } }

    .gpc-grid { position: relative; z-index: 1; display: grid; gap: 1.4rem; align-items: end;
                grid-template-columns: repeat(auto-fit, minmax(15rem, 1fr));
                inline-size: min(100%, 63rem); }
    .gpc-card { display: grid; gap: 1.05rem; justify-items: center; text-align: center;
                padding: 1.75rem 1.35rem 1.5rem; border-radius: var(--nx-radius-xl);
                background: linear-gradient(125deg, var(--gpc-glass), var(--gpc-glass-2));
                border: 1px solid var(--gpc-rim);
                box-shadow: inset 0 1px 0 var(--gpc-sheen), 0 16px 38px rgba(13,16,50,.16);
                backdrop-filter: blur(18px) saturate(1.4); -webkit-backdrop-filter: blur(18px) saturate(1.4);
                transition: translate .25s ease, box-shadow .25s ease, border-color .25s ease; }
    .gpc-card:hover { translate: 0 -.35rem; border-color: color-mix(in oklab, var(--gpc-rim) 72%, white); }
    .gpc-card--hot { translate: 0 -.55rem; border: 1.5px solid transparent;
                background: linear-gradient(125deg, var(--gpc-glass), var(--gpc-glass-2)) padding-box,
                            linear-gradient(160deg, var(--gpc-rim-a), var(--gpc-rim-b) 55%, var(--gpc-rim-c)) border-box;
                animation: gpc-breathe 4.5s ease-in-out infinite alternate; }
    .gpc-card--hot:hover { translate: 0 -.9rem; }
    @keyframes gpc-breathe {
        from { box-shadow: inset 0 1px 0 var(--gpc-sheen), 0 16px 38px rgba(13,16,50,.16),
                           0 0 30px color-mix(in oklab, var(--gpc-rim-a) 22%, transparent); }
        to   { box-shadow: inset 0 1px 0 var(--gpc-sheen), 0 16px 38px rgba(13,16,50,.16),
                           0 0 58px color-mix(in oklab, var(--gpc-rim-c) 40%, transparent); }
    }
    .gpc-badge { position: absolute; inset-block-start: -.8rem; inset-inline: 0; margin-inline: auto;
                 display: inline-flex; align-items: center; gap: .35rem; inline-size: max-content;
                 padding: .32rem .8rem; border-radius: 999px;
                 background: linear-gradient(120deg, var(--nx-gold-400), var(--nx-gold-500));
                 color: #241a02; font: 700 .72rem / 1 var(--nx-font-sans); letter-spacing: .02em;
                 box-shadow: 0 6px 16px rgba(13,16,50,.22); }
    .gpc-badge svg { inline-size: .8rem; block-size: .8rem; }
    .gpc-card--hot { position: relative; }
    .gpc-name { margin: 0; font: 700 var(--nx-text-lg) / 1.2 var(--nx-font-display); color: var(--gpc-ink); }
    .gpc-tag { margin: 0; font-size: .78rem; color: var(--gpc-muted); max-inline-size: 22ch; }
    .gpc-figure { display: flex; flex-wrap: wrap; align-items: baseline; justify-content: center; gap: .3rem; }
    .gpc-cur { font-weight: 700; font-size: var(--nx-text-lg); color: var(--gpc-ink); }
    .gpc-amount { font: 800 clamp(2.5rem, 7vw, 3rem) / 1 var(--nx-font-display);
                  letter-spacing: var(--nx-tracking-tight); color: var(--gpc-ink); }
    .gpc-per { font-size: .76rem; color: var(--gpc-muted); }
    html[dir="rtl"] .gpc-cur { order: 2; }
    html[dir="rtl"] .gpc-per { order: 3; }
    .gpc-free { font-size: .76rem; font-weight: 600; color: var(--gpc-muted); min-block-size: 1.1em; }
    .gpc-feats { display: grid; gap: .55rem; justify-items: start; inline-size: 100%; margin: 0; padding: 0; list-style: none; }
    .gpc-feats li { display: flex; align-items: center; gap: .5rem; color: var(--gpc-ink);
                    font-size: var(--nx-text-sm); text-align: start; }
    .gpc-feats svg { flex: none; inline-size: 1rem; block-size: 1rem; color: var(--nx-green-500); }
    .gpc-go { position: relative; overflow: clip; display: inline-flex; align-items: center; justify-content: center;
              gap: .45rem; inline-size: 100%; block-size: 2.85rem; padding-inline: 1.1rem; border-radius: 999px;
              border: 1px solid var(--gpc-rim); background: var(--gpc-glass); color: var(--gpc-ink);
              font: 600 .88rem / 1 var(--nx-font-sans); text-decoration: none; cursor: pointer;
              backdrop-filter: blur(12px) saturate(1.3); -webkit-backdrop-filter: blur(12px) saturate(1.3);
              box-shadow: inset 0 1px 0 var(--gpc-sheen), 0 8px 20px rgba(13,16,50,.12);
              transition: background-color .2s ease, translate .2s ease, border-color .2s ease; }
    .gpc-go svg { inline-size: .95rem; block-size: .95rem; }
    html[dir="rtl"] .gpc-go svg { transform: scaleX(-1); }
    .gpc-go:hover { background: var(--gpc-hover); border-color: color-mix(in oklab, var(--gpc-rim) 72%, white); translate: 0 -2px; }
    .gpc-go:focus-visible { outline: 2px solid var(--gpc-rim-a); outline-offset: 3px; }
    .gpc-go::after { content: ''; position: absolute; inset-block: -60%; inset-inline-start: -75%; inline-size: 45%;
                     background: linear-gradient(105deg, transparent, rgba(255,255,255,.5), transparent);
                     transform: skewX(-18deg); pointer-events: none;
                     transition: inset-inline-start .6s ease; }
    .gpc-go:hover::after { inset-inline-start: 135%; }
    .gpc-go--primary { border: none; color: #fff;
              background: linear-gradient(120deg, var(--nx-lapis-500), var(--nx-violet-500));
              box-shadow: 0 12px 28px color-mix(in oklab, var(--nx-lapis-500) 38%, transparent); }
    .gpc-go--primary:hover { background: linear-gradient(120deg, var(--nx-lapis-500), var(--nx-violet-500));
              box-shadow: 0 16px 34px color-mix(in oklab, var(--nx-violet-500) 46%, transparent); }
    .gpc-note { margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted); text-align: center; max-inline-size: 56ch; }
    @media (prefers-reduced-motion: reduce) {
        .gpc-blobs i { animation: none; }
        .gpc-card--hot { animation: none;
                box-shadow: inset 0 1px 0 var(--gpc-sheen), 0 16px 38px rgba(13,16,50,.16),
                            0 0 40px color-mix(in oklab, var(--gpc-rim-a) 30%, transparent); }
        .gpc-root * { transition-duration: .01ms !important; animation-duration: .01ms !important; }
        .gpc-go::after { display: none; }
    }
</style>

<div class="gpc-root">
    <section class="pg-box" style="justify-items: center">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Three plans, one pane of glass', 'سه پلن، یک صفحه شیشه') }}</h3>
            <p style="margin: 0; max-width: 54ch; color: var(--nx-text-muted)">
                {{ $say('The Growth card lifts off the field: its rim is a gradient that breathes light, the badge rides its crown, and every button throws a sheen across its face when you hover.', 'کارت رشد از میدان بلند می‌شود: rimش گرادیانی است که نور می‌کشد، بج بر تاجش می‌نشیند و هر دکمه با هاور یک برق از صورتش می‌گذراند.') }}
            </p>
        </div>

        <div class="gpc-stage">
            <div class="gpc-blobs" aria-hidden="true"><i></i><i></i><i></i><i></i></div>
            <div class="gpc-grid">
                @foreach ($plans as $plan)
                    <article class="gpc-card {{ $plan['hot'] ? 'gpc-card--hot' : '' }}" aria-label="{{ $say($plan['name'][1], $plan['name'][0]) }}">
                        @if ($plan['hot'])
                            <span class="gpc-badge">{!! \NabuXUI\NabuXUI::icon('star') !!}{{ $say('Popular', 'محبوب') }}</span>
                        @endif
                        <h4 class="gpc-name">{{ $say($plan['name'][0], $plan['name'][1]) }}</h4>
                        <p class="gpc-tag">{{ $say($plan['tag'][0], $plan['tag'][1]) }}</p>
                        <div class="gpc-figure">
                            <span class="gpc-cur">{{ $say('$', 'دلار') }}</span>
                            <span class="gpc-amount">{{ $num($plan['price']) }}</span>
                            <span class="gpc-per">{{ ($plan['free'] ?? false) ? $say('forever', 'برای همیشه') : $say('per seat / month', 'به‌ازای هر صندلی در ماه') }}</span>
                        </div>
                        @if (($plan['free'] ?? false))
                            <p class="gpc-free">{{ $say('No card, no clock', 'نه کارت، نه ساعت') }}</p>
                        @else
                            <span class="gpc-free" aria-hidden="true"></span>
                        @endif
                        <ul class="gpc-feats">
                            @foreach ($plan['feats'] as [$en, $faText])
                                <li>{!! \NabuXUI\NabuXUI::icon('check') !!}{{ $say($en, $faText) }}</li>
                            @endforeach
                        </ul>
                        <a class="gpc-go {{ $plan['hot'] ? 'gpc-go--primary' : '' }}" href="/components">
                            {{ $say($plan['cta'][0], $plan['cta'][1]) }}
                            {!! \NabuXUI\NabuXUI::icon($plan['hot'] ? 'sparkles' : 'arrow-right') !!}
                        </a>
                    </article>
                @endforeach
            </div>
        </div>

        <p class="gpc-note">
            {{ $say('Prices in USD, VAT aside; seats flex mid-cycle and the invoice follows the Frankfurt or Singapore entity you pick at signup.', 'قیمت‌ها به دلار، بدون VAT؛ صندلی‌ها وسط دوره قابل تغییرند و صورتحساب از شرکت فرانکفورت یا سنگاپوری که هنگام ثبت‌نام انتخاب می‌کنی می‌آید.') }}
        </p>
    </section>
</div>
