{{--
    The giant wordmark footer on a light stage: four link columns, a huge
    outline "Meridian" that floods with colour when you hover or focus the
    footer (and recolours from its swatches), and the closing © row with the
    global offices and the social row.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;
@endphp
<style>
    .wmk-root {
        --wmk-bg: #FAFAF9; --wmk-border: #E7E5E4;
        --wmk-text: #1C1917; --wmk-muted: #78716C;
        font-family: 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif;
        color: var(--wmk-text);
    }
    html[data-theme="dark"] .wmk-root {
        --wmk-bg: #0C0A09; --wmk-border: #292524;
        --wmk-text: #FAFAF9; --wmk-muted: #A8A29E;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .wmk-root {
            --wmk-bg: #0C0A09; --wmk-border: #292524;
            --wmk-text: #FAFAF9; --wmk-muted: #A8A29E;
        }
    }
    .wmk-foot { padding: clamp(2rem, 5vw, 3.5rem) clamp(1rem, 4vw, 2.5rem) 1.25rem;
                background: var(--wmk-bg); border-block-start: 1px solid var(--wmk-border); }
    .wmk-cols { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 1.5rem;
                max-inline-size: 72rem; margin-inline: auto; }
    .wmk-cols h4 { margin: 0 0 .75rem; font-size: .75rem; font-weight: 600;
                   letter-spacing: .06em; text-transform: uppercase; color: var(--wmk-muted); }
    .wmk-cols a { display: block; padding-block: .225rem; font-size: .875rem; color: var(--wmk-text);
                  text-decoration: none; transition: color .15s ease, padding-inline-start .15s ease; }
    .wmk-cols a:hover { color: var(--wmk-accent); padding-inline-start: .25rem; text-decoration: underline; }
    .wmk-cols a:focus-visible { outline: 2px solid var(--wmk-accent); outline-offset: 2px; border-radius: 2px; }
    .wmk-mark { margin: clamp(2rem, 6vw, 3.5rem) 0 0; text-align: center; white-space: nowrap; overflow: clip;
                font: 800 clamp(2.6rem, 15.5vw, 8.75rem)/1 'Inter', ui-sans-serif, sans-serif;
                letter-spacing: -.04em; direction: ltr; user-select: none;
                color: transparent; -webkit-text-stroke: 1.5px var(--wmk-accent);
                transition: color .45s ease, -webkit-text-stroke-color .45s ease; }
    .wmk-foot:is(:hover, :focus-within) .wmk-mark { color: var(--wmk-accent); }
    .wmk-base { display: flex; flex-wrap: wrap; gap: .75rem 1.5rem; align-items: center; justify-content: space-between;
                max-inline-size: 72rem; margin-block-start: clamp(1.25rem, 4vw, 2rem); margin-inline: auto;
                padding-block-start: 1rem; border-block-start: 1px solid var(--wmk-border); }
    .wmk-copy { margin: 0; font-size: .75rem; color: var(--wmk-muted); }
    .wmk-tools { display: flex; flex-wrap: wrap; gap: 1rem; align-items: center; }
    .wmk-swatches { display: flex; gap: .5rem; align-items: center; }
    .wmk-swatches span { font-size: .7rem; color: var(--wmk-muted); }
    .wmk-swatches button { inline-size: 1.25rem; aspect-ratio: 1; border-radius: 50%; cursor: pointer;
                           border: 2px solid var(--wmk-bg); outline: 1px solid var(--wmk-border);
                           transition: transform .15s ease, outline-color .15s ease; }
    .wmk-swatches button:hover { transform: scale(1.15); }
    .wmk-swatches button[aria-pressed="true"] { outline: 2px solid var(--wmk-text); }
    .wmk-swatches button:focus-visible { outline: 2px solid var(--wmk-text); outline-offset: 2px; }
    .wmk-social { display: flex; gap: .25rem; }
    .wmk-social a { display: grid; place-items: center; inline-size: 2rem; aspect-ratio: 1; border-radius: 50%;
                    color: var(--wmk-muted); transition: color .15s ease, background-color .15s ease; }
    .wmk-social a:hover { color: var(--wmk-text); background: var(--wmk-border); }
    .wmk-social a:focus-visible { outline: 2px solid var(--wmk-accent); outline-offset: 2px; }
    .wmk-social svg { inline-size: 1rem; block-size: 1rem; fill: currentColor; }
    @media (max-width: 560px) {
        .wmk-cols { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .wmk-base { justify-content: center; text-align: center; }
    }
    @media (prefers-reduced-motion: reduce) {
        .wmk-root * { transition-duration: .01ms !important; animation-duration: .01ms !important; }
    }
</style>

<div class="wmk-root" x-data="{
    accent: '#4F46E5',
    swatches: [
        { hex: '#4F46E5', label: '{{ $say('Indigo', 'بنفش') }}' },
        { hex: '#EA580C', label: '{{ $say('Ember', 'نارنجی') }}' },
        { hex: '#059669', label: '{{ $say('Fern', 'سبز') }}' },
        { hex: '#0284C7', label: '{{ $say('Ocean', 'آبی اقیانوس') }}' },
    ],
}" x-bind:style="'--wmk-accent:' + accent">
    <section class="pg-box" style="padding: 0; overflow: clip">
        <footer class="wmk-foot">
            <nav class="wmk-cols" aria-label="{{ $say('Site footer', 'فوتر سایت') }}">
                @foreach ([
                    ['head' => $say('Product', 'محصول'), 'links' => [
                        [$say('Feature flags', 'فیچر فلگ‌ها'), 'flags'],
                        [$say('Observability', 'مشاهده‌پذیری'), 'observability'],
                        [$say('Release automation', 'اتوماسیون انتشار'), 'automation'],
                        [$say('Integrations', 'یکپارچه‌سازی‌ها'), 'integrations'],
                        [$say('Pricing', 'قیمت‌گذاری'), 'pricing'],
                    ]],
                    ['head' => $say('Company', 'شرکت'), 'links' => [
                        [$say('About', 'دربارهٔ ما'), 'about'],
                        [$say('Careers', 'فرصت‌های شغلی'), 'careers'],
                        [$say('Customers', 'مشتریان'), 'customers'],
                        [$say('Brand', 'برند'), 'brand'],
                        [$say('Contact', 'تماس'), 'contact'],
                    ]],
                    ['head' => $say('Resources', 'منابع'), 'links' => [
                        [$say('Documentation', 'مستندات'), 'docs'],
                        [$say('API reference', 'مرجع API'), 'api'],
                        [$say('Guides', 'راهنماها'), 'guides'],
                        [$say('Status', 'وضعیت سرویس'), 'status'],
                        [$say('Community', 'جامعه'), 'community'],
                    ]],
                    ['head' => $say('Legal', 'قانونی'), 'links' => [
                        [$say('Privacy', 'حریم خصوصی'), 'privacy'],
                        [$say('Terms', 'شرایط'), 'terms'],
                        [$say('Security', 'امنیت'), 'security'],
                        [$say('DPA', 'DPA'), 'dpa'],
                        [$say('Cookies', 'کوکی‌ها'), 'cookies'],
                    ]],
                ] as $column)
                    <div>
                        <h4>{{ $column['head'] }}</h4>
                        @foreach ($column['links'] as [$label, $anchor])
                            <a href="#{{ $anchor }}">{{ $label }}</a>
                        @endforeach
                    </div>
                @endforeach
            </nav>

            <p class="wmk-mark" dir="ltr" lang="en" aria-hidden="true">Meridian</p>

            <div class="wmk-base">
                <p class="wmk-copy">
                    © {{ $num('2026') }} Meridian —
                    {{ $say('Frankfurt · Dublin · Singapore', 'فرانکفورت · دوبلین · سنگاپور') }}
                </p>
                <div class="wmk-tools">
                    <div class="wmk-swatches" role="group" aria-label="{{ $say('Wordmark colour', 'رنگ وردمارک') }}">
                        <span aria-hidden="true">{{ $say('Ink', 'رنگ') }}</span>
                        <template x-for="swatch in swatches" :key="swatch.hex">
                            <button type="button" class="wmk-swatch" x-bind:aria-pressed="accent === swatch.hex"
                                x-bind:aria-label="swatch.label" x-bind:style="'background:' + swatch.hex"
                                x-on:click="accent = swatch.hex"></button>
                        </template>
                    </div>
                    <div class="wmk-social">
                        <a href="#x" aria-label="X">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231 5.451-6.231Zm-1.161 17.52h1.833L7.084 4.126H5.117l11.966 15.644Z"/></svg>
                        </a>
                        <a href="#github" aria-label="GitHub">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 .5C5.65.5.5 5.65.5 12c0 5.08 3.29 9.39 7.86 10.91.58.11.79-.25.79-.55v-2.15c-3.2.69-3.87-1.36-3.87-1.36-.52-1.33-1.28-1.69-1.28-1.69-1.04-.71.08-.7.08-.7 1.15.08 1.76 1.19 1.76 1.19 1.03 1.76 2.69 1.25 3.35.96.1-.75.4-1.25.72-1.54-2.55-.29-5.23-1.28-5.23-5.68 0-1.26.45-2.28 1.19-3.09-.12-.29-.52-1.46.11-3.05 0 0 .97-.31 3.17 1.18a11 11 0 0 1 5.78 0c2.2-1.49 3.17-1.18 3.17-1.18.63 1.59.23 2.76.11 3.05.74.81 1.19 1.83 1.19 3.09 0 4.41-2.69 5.38-5.25 5.67.41.35.77 1.05.77 2.12v3.14c0 .3.21.67.8.55A11.5 11.5 0 0 0 23.5 12C23.5 5.65 18.35.5 12 .5Z"/></svg>
                        </a>
                        <a href="#linkedin" aria-label="LinkedIn">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4.98 3.5a2.49 2.49 0 1 1-.02 4.98 2.49 2.49 0 0 1 .02-4.98ZM3 9h4v12H3V9Zm7 0h3.8v1.7h.05c.53-1 1.83-2.05 3.77-2.05 4.03 0 4.78 2.65 4.78 6.1V21h-4v-5.5c0-1.31-.03-3-1.83-3-1.83 0-2.11 1.43-2.11 2.9V21h-4V9Z"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    </section>
</div>
