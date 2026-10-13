{{--
    The feature grid as Atlas tells it: a breathing bento on pure white where
    two wide cells illustrate the story (the semantic graph and the edge map)
    and four quiet cells carry line icons — pure CSS, the hover is the only
    motion and it is a whisper.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;

    $features = [
        'search' => [
            'span' => 2, 'index' => '01',
            'title' => $say('Semantic search', 'جست‌وجوی معنایی'),
            'copy' => $say('Ask “who owns the billing flow?” and get the page, the owner and last week’s decision — not a wall of keyword matches.', 'بپرسید «مالک جریان پرداخت کیست؟» و صفحه، مالک و تصمیم هفتهٔ پیش را بگیرید — نه دیواری از نتایج کلیدواژه‌ای.'),
        ],
        'live' => [
            'index' => '02',
            'title' => $say('Live co-editing', 'هم‌نویسی زنده'),
            'copy' => $say('Cursors carry your teammates’ names; comments resolve themselves.', 'مکان‌نماها نام هم‌تیمی‌ها را یدک می‌کشند؛ نظرها خودشان حل می‌شوند.'),
        ],
        'history' => [
            'index' => '03',
            'title' => $say('Version history', 'تاریخ نسخه‌ها'),
            'copy' => $say('Every page, every change, forever — diffable down to the word.', 'هر صفحه، هر تغییر، برای همیشه — تا سطح واژه قابل مقایسه.'),
        ],
        'edge' => [
            'span' => 2, 'index' => '04',
            'title' => $say('Edge-fast everywhere', 'روی لبهٔ جهان'),
            'copy' => $say('Pages render in 34 regions — Frankfurt, Singapore, Toronto — so the wiki opens before the coffee does.', 'صفحه‌ها در ۳۴ ناحیه رندر می‌شوند — فرانکفورت، سنگاپور، تورنتو — تا دانش‌نامه پیش از قهوه باز شود.'),
        ],
        'integrations' => [
            'index' => '05',
            'title' => $say('Integrations', 'یکپارچه‌سازی‌ها'),
            'copy' => $say('Slack, GitHub and Figma, two clicks each.', 'اسلک، گیت‌هاب و فیگما؛ هرکدام با دو کلیک.'),
        ],
        'permissions' => [
            'index' => '06',
            'title' => $say('Granular permissions', 'مجوزهای دقیق'),
            'copy' => $say('SSO, roles and space-level locks your security team will sign off on.', 'ورود تک‌نشان، نقش‌ها و قفل‌های سطح فضایی که تیم امنیتتان تأییدش می‌کند.'),
        ],
    ];
@endphp
<style>
    .secfg-root {
        --secfg-accent: #0F766E; --secfg-page: #FFFFFF; --secfg-card: #FFFFFF;
        --secfg-border: #E7E5E4; --secfg-text: #1C1917; --secfg-muted: #78716C; --secfg-faint: #A8A29E;
        font-family: 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif;
        color: var(--secfg-text);
        display: grid;
        gap: 2rem;
    }
    html[data-theme="dark"] .secfg-root {
        --secfg-accent: #2DD4BF; --secfg-page: #0C0A09; --secfg-card: #141210;
        --secfg-border: #292524; --secfg-text: #FAFAF9; --secfg-muted: #A8A29E; --secfg-faint: #78716C;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .secfg-root {
            --secfg-accent: #2DD4BF; --secfg-page: #0C0A09; --secfg-card: #141210;
            --secfg-border: #292524; --secfg-text: #FAFAF9; --secfg-muted: #A8A29E; --secfg-faint: #78716C;
        }
    }
    .secfg {
        inline-size: 100%; padding: clamp(2rem, 5vw, 3.75rem) clamp(1.25rem, 4vw, 2.5rem);
        border: 1px solid var(--secfg-border); border-radius: 1.4rem; background: var(--secfg-page);
        display: grid; gap: 2rem; justify-items: center;
    }
    .secfg-head { display: grid; gap: .7rem; justify-items: center; text-align: center; }
    .secfg-head h3 { margin: 0; font-size: clamp(1.4rem, 3vw, 2rem); font-weight: 800; letter-spacing: -.02em; line-height: 1.25; }
    .secfg-head p { margin: 0; max-inline-size: 48ch; font-size: .92rem; line-height: 1.75; color: var(--secfg-muted); }
    .secfg-grid { display: grid; gap: 1rem; grid-template-columns: repeat(4, minmax(0, 1fr));
                  inline-size: 100%; max-inline-size: 62rem; }
    .secfg-cell { display: grid; gap: .65rem; align-content: start;
                  padding: 1.5rem; border: 1px solid var(--secfg-border); border-radius: 1.2rem;
                  background: var(--secfg-card); transition: border-color .3s ease, translate .3s ease; }
    .secfg-cell:hover { border-color: color-mix(in oklab, var(--secfg-accent) 55%, var(--secfg-border)); translate: 0 -2px; }
    .secfg-cell[data-span="2"] { grid-column: span 2; }
    .secfg-index { font: 700 .68rem/1 'JetBrains Mono', ui-monospace, monospace; letter-spacing: .12em; color: var(--secfg-faint); }
    .secfg-cell:hover .secfg-index { color: var(--secfg-accent); }
    .secfg-cell h4 { margin: 0; font-size: 1.02rem; font-weight: 700; letter-spacing: -.01em; }
    .secfg-cell p { margin: 0; font-size: .84rem; line-height: 1.7; color: var(--secfg-muted); }
    .secfg-icon { inline-size: 1.75rem; block-size: 1.75rem; stroke: var(--secfg-muted); stroke-width: 1.75;
                  fill: none; stroke-linecap: round; stroke-linejoin: round;
                  transition: stroke .3s ease; }
    .secfg-cell:hover .secfg-icon { stroke: var(--secfg-accent); }
    .secfg-art { inline-size: 100%; max-inline-size: 20rem; justify-self: center; margin-block: .5rem .25rem; }
    .secfg-art .secfg-line { stroke: var(--secfg-border); stroke-width: 1.5; fill: none; }
    .secfg-art .secfg-line-acc { stroke: var(--secfg-accent); stroke-width: 1.5; fill: none;
                                 stroke-dasharray: 3 5; opacity: .8; }
    .secfg-art .secfg-node { fill: var(--secfg-muted); }
    .secfg-art .secfg-node-acc { fill: var(--secfg-accent); }
    .secfg-art .secfg-node-ring { fill: none; stroke: var(--secfg-accent); stroke-width: 1.25; opacity: .5; }
    .secfg-art .secfg-word { font: 500 8.5px 'JetBrains Mono', monospace; fill: var(--secfg-faint); letter-spacing: .08em; }
    .secfg-art .secfg-word-acc { fill: var(--secfg-accent); font-weight: 700; }
    .secfg-cell:focus-within { border-color: var(--secfg-accent); }
    @media (max-width: 760px) {
        .secfg-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 540px) {
        .secfg-grid { grid-template-columns: 1fr; }
        .secfg-cell[data-span="2"] { grid-column: auto; }
        .secfg-cell:hover { translate: none; }
    }
    @media (prefers-reduced-motion: reduce) {
        .secfg-root * { transition-duration: .01ms !important; animation-duration: .01ms !important; }
    }
</style>

<div class="secfg-root">
    <section class="secfg" aria-labelledby="secfg-title">
        <header class="secfg-head">
            <h3 id="secfg-title">{{ $say('Everything a team needs to remember, in one calm place', 'هرچه تیم باید به یاد داشته باشد، در یک جای آرام') }}</h3>
            <p>{{ $say('Atlas is the knowledge base that keeps up with the team — six quiet tools, no busywork, zero training day.', 'اتلس دانش‌نامه‌ای است که با تیم جاوا می‌ماند — شش ابزار آرام، بدون کار اضافی، بدون روز آموزش.') }}</p>
        </header>

        <div class="secfg-grid">
            @foreach ($features as $key => $feature)
                <article class="secfg-cell" @if (isset($feature['span'])) data-span="{{ $feature['span'] }}" @endif>
                    <span class="secfg-index" aria-hidden="true">{{ $num($feature['index']) }}</span>

                    @if ($key === 'search')
                        <svg class="secfg-art" viewBox="0 0 260 120" role="img" aria-label="{{ $say('Semantic graph', 'گراف معنایی') }}">
                            <path class="secfg-line" d="M40 60 L100 32 L165 58 M100 32 L150 96 M165 58 L215 88 M100 32 L60 98 M165 58 L215 26"/>
                            <line class="secfg-line-acc" x1="100" y1="32" x2="165" y2="58"/>
                            <line class="secfg-line-acc" x1="165" y1="58" x2="215" y2="26"/>
                            <circle class="secfg-node" cx="40" cy="60" r="5"/>
                            <circle class="secfg-node" cx="60" cy="98" r="5"/>
                            <circle class="secfg-node" cx="150" cy="96" r="5"/>
                            <circle class="secfg-node" cx="215" cy="88" r="5"/>
                            <circle class="secfg-node-acc" cx="100" cy="32" r="6"/>
                            <circle class="secfg-node-ring" cx="100" cy="32" r="11"/>
                            <circle class="secfg-node-acc" cx="165" cy="58" r="6"/>
                            <circle class="secfg-node-ring" cx="165" cy="58" r="11"/>
                            <circle class="secfg-node-acc" cx="215" cy="26" r="6"/>
                            <text class="secfg-word" x="88" y="16">WHO</text>
                            <text class="secfg-word secfg-word-acc" x="153" y="44">OWNS</text>
                            <text class="secfg-word" x="203" y="14">BILLING</text>
                            <text class="secfg-word" x="176" y="112">RUNBOOK.MD</text>
                            <text class="secfg-word" x="12" y="80">ONCALL</text>
                            <text class="secfg-word" x="138" y="80">API</text>
                        </svg>
                    @elseif ($key === 'edge')
                        <svg class="secfg-art" viewBox="0 0 260 120" role="img" aria-label="{{ $say('Edge map', 'نقشهٔ لبه') }}">
                            <path class="secfg-line" d="M14 86 C 60 78, 92 66, 118 62 M118 62 C 152 58, 186 52, 246 30 M118 62 C 160 70, 200 82, 246 92"/>
                            <path class="secfg-line-acc" d="M14 86 C 70 60, 160 40, 246 30"/>
                            <circle class="secfg-node-acc" cx="14" cy="86" r="5"/>
                            <circle class="secfg-node-ring" cx="14" cy="86" r="10"/>
                            <circle class="secfg-node" cx="118" cy="62" r="4"/>
                            <circle class="secfg-node-acc" cx="246" cy="30" r="5"/>
                            <circle class="secfg-node-ring" cx="246" cy="30" r="10"/>
                            <circle class="secfg-node" cx="246" cy="92" r="4"/>
                            <text class="secfg-word" x="6" y="106">FRA</text>
                            <text class="secfg-word secfg-word-acc" x="238" y="16">SIN</text>
                            <text class="secfg-word" x="238" y="108">YYZ</text>
                            <text class="secfg-word" x="104" y="46">24 MS</text>
                        </svg>
                    @else
                        <svg class="secfg-icon" viewBox="0 0 24 24" aria-hidden="true">
                            @if ($key === 'live')
                                <circle cx="9" cy="8" r="3.2"/><path d="M3.5 19c.8-3 3-4.5 5.5-4.5s4.7 1.5 5.5 4.5"/><path d="M16 5.5a3 3 0 0 1 0 5"/>
                            @elseif ($key === 'history')
                                <path d="M4 7v5h5"/><path d="M4.5 12a8 8 0 1 0 2-5.3"/><path d="M12 8v4.5l3 1.8"/>
                            @elseif ($key === 'integrations')
                                <rect x="3.5" y="3.5" width="7" height="7" rx="1.5"/><rect x="13.5" y="13.5" width="7" height="7" rx="1.5"/><path d="M10.5 7h6a3 3 0 0 1 3 3v3.5"/><path d="M7 10.5V14a3 3 0 0 0 3 3h4"/>
                            @else
                                <rect x="5" y="10.5" width="14" height="9.5" rx="2"/><path d="M8 10.5V8a4 4 0 0 1 8 0v2.5"/><circle cx="12" cy="15.2" r="1.3"/>
                            @endif
                        </svg>
                    @endif

                    <h4>{{ $feature['title'] }}</h4>
                    <p>{{ $feature['copy'] }}</p>
                </article>
            @endforeach
        </div>
    </section>
</div>
