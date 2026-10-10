{{--
    The sitemap footer: the whole Meridian site in one dense glance — five
    categories under tiny uppercase headers, links carrying their state as
    icons (spark for new, check for stable, warning for sunsetting), a small
    globe language menu that drops up, and the dotted legal row.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;
@endphp
<style>
    .smf-root {
        --smf-bg: #FFFBF5; --smf-border: #EFE7DA; --smf-panel: #FFFFFF;
        --smf-text: #292524; --smf-link: #57534E; --smf-muted: #A8A29E;
        --smf-accent: #B45309; --smf-new: #F59E0B; --smf-stable: #059669; --smf-sunsetting: #DC2626;
        font-family: 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif;
        color: var(--smf-text);
    }
    html[data-theme="dark"] .smf-root {
        --smf-bg: #1C1917; --smf-border: #2E2A27; --smf-panel: #262220;
        --smf-text: #FAFAF9; --smf-link: #C9C5C1; --smf-muted: #8A847F;
        --smf-accent: #FBBF24;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .smf-root {
            --smf-bg: #1C1917; --smf-border: #2E2A27; --smf-panel: #262220;
            --smf-text: #FAFAF9; --smf-link: #C9C5C1; --smf-muted: #8A847F;
            --smf-accent: #FBBF24;
        }
    }
    .smf-foot { padding: clamp(2rem, 5vw, 3.25rem) clamp(1rem, 4vw, 2.5rem) 1.25rem;
                background: var(--smf-bg); }
    .smf-brand { display: flex; flex-wrap: wrap; gap: .75rem 1.5rem; align-items: center; justify-content: space-between;
                 max-inline-size: 76rem; margin-inline: auto; margin-block-end: 2rem; }
    .smf-brand strong { display: inline-flex; align-items: center; gap: .5rem; font-size: 1rem; font-weight: 800;
                        letter-spacing: -.01em; }
    .smf-brand strong svg { inline-size: 1.15rem; block-size: 1.15rem; color: var(--smf-accent); }
    .smf-brand p { margin: 0; font-size: .78rem; color: var(--smf-muted); }
    .smf-grid { display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 1.5rem 1.25rem;
                max-inline-size: 76rem; margin-inline: auto; }
    .smf-grid h4 { margin: 0 0 .55rem; font-size: .68rem; font-weight: 600; letter-spacing: .09em;
                   text-transform: uppercase; color: var(--smf-muted); }
    .smf-grid ul { list-style: none; margin: 0; padding: 0; }
    .smf-grid a { display: inline-flex; align-items: center; gap: .4rem; padding-block: .21rem;
                  font-size: .8rem; line-height: 1.65; color: var(--smf-link); text-decoration: none;
                  transition: color .15s ease; }
    .smf-grid a:hover { color: var(--smf-accent); text-decoration: underline; }
    .smf-grid a:focus-visible { outline: 2px solid var(--smf-accent); outline-offset: 2px; border-radius: 2px; }
    .smf-grid svg { inline-size: .8rem; block-size: .8rem; flex: none; stroke: currentColor; stroke-width: 2;
                    fill: none; stroke-linecap: round; stroke-linejoin: round; }
    .smf-grid svg[data-status="new"] { color: var(--smf-new); }
    .smf-grid svg[data-status="stable"] { color: var(--smf-stable); }
    .smf-grid svg[data-status="sunsetting"] { color: var(--smf-sunsetting); }
    .smf-tag { padding: .05rem .42rem; font-size: .62rem; font-weight: 700; letter-spacing: .04em;
               color: var(--smf-accent); border: 1px solid var(--smf-border); border-radius: .3rem;
               background: var(--smf-panel); }
    .smf-tag--sunsetting { color: var(--smf-sunsetting); }
    .smf-bottom { display: flex; flex-wrap: wrap; gap: .75rem 1.5rem; align-items: center; justify-content: space-between;
                  max-inline-size: 76rem; margin-block-start: 2.25rem; margin-inline: auto;
                  padding-block-start: 1rem; border-block-start: 1px solid var(--smf-border); }
    .smf-bottom small { font-size: .75rem; color: var(--smf-muted); }
    .smf-legal { display: flex; flex-wrap: wrap; gap: .4rem .5rem; align-items: center; font-size: .75rem; color: var(--smf-muted); }
    .smf-legal a { color: var(--smf-link); text-decoration: none; }
    .smf-legal a:hover { color: var(--smf-accent); text-decoration: underline; }
    .smf-legal i { color: var(--smf-border); font-style: normal; }
    .smf-lang { position: relative; }
    .smf-lang > button { display: inline-flex; align-items: center; gap: .45rem; padding: .4rem .85rem;
                         font: 600 .78rem/1 inherit; color: var(--smf-link); cursor: pointer;
                         background: var(--smf-panel); border: 1px solid var(--smf-border); border-radius: .6rem;
                         transition: border-color .15s ease, color .15s ease; }
    .smf-lang > button:hover { color: var(--smf-text); border-color: var(--smf-muted); }
    .smf-lang > button:focus-visible { outline: 2px solid var(--smf-accent); outline-offset: 2px; }
    .smf-lang > button svg { inline-size: .95rem; block-size: .95rem; stroke: currentColor; stroke-width: 1.8;
                             fill: none; stroke-linecap: round; stroke-linejoin: round; }
    .smf-lang > button .smf-caret { transition: rotate .18s ease; }
    .smf-lang[data-open="true"] > button .smf-caret { rotate: 180deg; }
    .smf-menu { position: absolute; inset-block-end: calc(100% + .45rem); inset-inline-end: 0; z-index: 5;
                min-inline-size: 10rem; margin: 0; padding: .35rem; list-style: none;
                background: var(--smf-panel); border: 1px solid var(--smf-border); border-radius: .7rem;
                box-shadow: 0 14px 34px -14px rgba(0, 0, 0, .3);
                opacity: 0; translate: 0 .35rem; visibility: hidden;
                transition: opacity .18s ease, translate .18s ease, visibility .18s; }
    .smf-lang[data-open="true"] .smf-menu { opacity: 1; translate: 0 0; visibility: visible; }
    .smf-menu li { display: flex; align-items: center; justify-content: space-between; gap: .5rem;
                   padding: .42rem .6rem; font-size: .82rem; color: var(--smf-link); border-radius: .45rem;
                   cursor: pointer; transition: background-color .12s ease, color .12s ease; }
    .smf-menu li:hover { background: var(--smf-bg); color: var(--smf-text); }
    .smf-menu li[aria-selected="true"] { font-weight: 700; color: var(--smf-text); }
    .smf-menu li svg { inline-size: .8rem; block-size: .8rem; stroke: var(--smf-stable); stroke-width: 2.4;
                       opacity: 0; fill: none; stroke-linecap: round; stroke-linejoin: round; }
    .smf-menu li[aria-selected="true"] svg { opacity: 1; }
    @media (max-width: 720px) {
        .smf-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 460px) {
        .smf-grid { grid-template-columns: 1fr; }
        .smf-bottom { justify-content: center; text-align: center; }
        .smf-menu { inset-inline-end: auto; inset-inline-start: 50%; translate: -50% .35rem; }
        .smf-lang[data-open="true"] .smf-menu { translate: -50% 0; }
    }
    @media (prefers-reduced-motion: reduce) {
        .smf-root * { transition-duration: .01ms !important; animation-duration: .01ms !important; }
    }
</style>

<div class="smf-root" x-data="{
    lang: 'en',
    open: false,
    langs: [
        { code: 'en', label: 'English' },
        { code: 'de', label: 'Deutsch' },
        { code: 'fr', label: 'Français' },
        { code: 'ja', label: '日本語' },
        { code: 'es', label: 'Español' },
    ],
}" x-on:keydown.escape.window="open = false">
    <section class="pg-box" style="padding: 0; overflow: clip">
        <footer class="smf-foot">
            <div class="smf-brand">
                <strong>
                    <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9.5" stroke="currentColor" stroke-width="2" fill="none"/><path d="M12 2.5v19M2.5 12h19M5 5.5c3.5 2.2 10.5 2.2 14 0M5 18.5c3.5-2.2 10.5-2.2 14 0" stroke="currentColor" stroke-width="1.6" fill="none"/></svg>
                    Meridian
                </strong>
                <p>{{ $say('The release platform for calm product teams.', 'سکوی انتشار برای تیم‌های محصولِ آرام.') }}</p>
            </div>

            <nav class="smf-grid" aria-label="{{ $say('Site map', 'نقشهٔ سایت') }}">
                @foreach ([
                    ['head' => $say('Product', 'محصول'), 'links' => [
                        ['label' => $say('Feature flags', 'فیچر فلگ‌ها'), 'status' => 'new', 'tag' => $say('New', 'جدید')],
                        ['label' => $say('Observability', 'مشاهده‌پذیری'), 'status' => 'stable'],
                        ['label' => $say('Release automation', 'اتوماسیون انتشار')],
                        ['label' => $say('Integrations', 'یکپارچه‌سازی‌ها')],
                        ['label' => $say('Pricing', 'قیمت‌گذاری')],
                    ]],
                    ['head' => $say('Developers', 'توسعه‌دهندگان'), 'links' => [
                        ['label' => $say('Documentation', 'مستندات'), 'status' => 'stable'],
                        ['label' => $say('API reference', 'مرجع API')],
                        ['label' => $say('SDKs & CLI', 'SDKها و CLI')],
                        ['label' => $say('Legacy API', 'API قدیمی'), 'status' => 'sunsetting', 'tag' => $num('2027')],
                        ['label' => $say('Webhooks', 'وب‌هوک‌ها')],
                    ]],
                    ['head' => $say('Company', 'شرکت'), 'links' => [
                        ['label' => $say('About', 'دربارهٔ ما')],
                        ['label' => $say('Careers', 'فرصت‌های شغلی'), 'status' => 'new', 'tag' => $say('Hiring', 'استخدام')],
                        ['label' => $say('Customers', 'مشتریان')],
                        ['label' => $say('Brand', 'برند')],
                        ['label' => $say('Contact', 'تماس')],
                    ]],
                    ['head' => $say('Resources', 'منابع'), 'links' => [
                        ['label' => $say('Changelog', 'تغییرات')],
                        ['label' => $say('Status', 'وضعیت سرویس'), 'status' => 'stable'],
                        ['label' => $say('Guides', 'راهنماها')],
                        ['label' => $say('Community', 'جامعه')],
                        ['label' => $say('Blog', 'بلاگ')],
                    ]],
                    ['head' => $say('Legal', 'قانونی'), 'links' => [
                        ['label' => $say('Privacy', 'حریم خصوصی')],
                        ['label' => $say('Terms', 'شرایط')],
                        ['label' => $say('Security', 'امنیت')],
                        ['label' => $say('DPA', 'DPA')],
                        ['label' => $say('Cookies', 'کوکی‌ها')],
                    ]],
                ] as $column)
                    <div>
                        <h4>{{ $column['head'] }}</h4>
                        <ul>
                            @foreach ($column['links'] as $link)
                                <li>
                                    <a href="#{{ Str::slug($link['label']) }}">
                                        @if (($link['status'] ?? null) === 'new')
                                            <svg data-status="new" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3l1.7 4.6L18.5 9l-4.8 1.4L12 15l-1.7-4.6L5.5 9l4.8-1.4z"/><path d="M18.5 15.5l.8 2.2 2.2.8-2.2.8-.8 2.2-.8-2.2-2.2-.8 2.2-.8z"/></svg>
                                        @elseif (($link['status'] ?? null) === 'stable')
                                            <svg data-status="stable" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M8.4 12.3l2.4 2.4 4.8-5.2"/></svg>
                                        @elseif (($link['status'] ?? null) === 'sunsetting')
                                            <svg data-status="sunsetting" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 4L2.8 19.5h18.4z"/><path d="M12 10.2v3.6"/><circle cx="12" cy="16.6" r=".4"/></svg>
                                        @endif
                                        {{ $link['label'] }}
                                        @if (!empty($link['tag']))
                                            <span class="smf-tag" @class(['smf-tag--sunsetting' => ($link['status'] ?? null) === 'sunsetting'])>{{ $link['tag'] }}</span>
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </nav>

            <div class="smf-bottom">
                <small>© {{ $num('2026') }} Meridian — {{ $say('Frankfurt · Dublin · Singapore', 'فرانکفورت · دوبلین · سنگاپور') }}</small>
                <div class="smf-legal">
                    <a href="#privacy">{{ $say('Privacy', 'حریم خصوصی') }}</a><i>·</i>
                    <a href="#terms">{{ $say('Terms', 'شرایط') }}</a><i>·</i>
                    <a href="#cookies">{{ $say('Cookies', 'کوکی‌ها') }}</a>
                </div>
                <div class="smf-lang" x-bind:data-open="open ? 'true' : 'false'" x-on:click.outside="open = false">
                    <button type="button" aria-haspopup="listbox" x-bind:aria-expanded="open ? 'true' : 'false'"
                        x-on:click="open = !open">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9.5"/><path d="M12 2.5v19M2.5 12h19M5 5.5c3.5 2.2 10.5 2.2 14 0M5 18.5c3.5-2.2 10.5-2.2 14 0"/></svg>
                        <span x-text="langs.find(l => l.code === lang).label"></span>
                        <svg class="smf-caret" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 9.5l6 6 6-6"/></svg>
                    </button>
                    <ul class="smf-menu" role="listbox" aria-label="{{ $say('Language', 'زبان') }}">
                        <template x-for="option in langs" :key="option.code">
                            <li role="option" x-bind:aria-selected="lang === option.code ? 'true' : 'false'"
                                x-on:click="lang = option.code; open = false">
                                <span x-text="option.label"></span>
                                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>
        </footer>
    </section>
</div>
