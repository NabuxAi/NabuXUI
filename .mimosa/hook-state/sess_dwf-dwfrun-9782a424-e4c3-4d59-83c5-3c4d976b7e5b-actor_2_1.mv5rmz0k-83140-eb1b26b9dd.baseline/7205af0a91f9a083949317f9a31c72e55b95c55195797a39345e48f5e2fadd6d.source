{{--
    The FAQ section: a side column that holds the heading, the talk-to-sales
    CTA and the "all questions" link, beside a single-open accordion whose
    answers unfurl through a 0fr→1fr grid row — the height animates without
    a single line of measuring JavaScript.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;

    $faqs = [
        ['id' => 'trial',
         'q' => $say('Does the trial need a credit card?', 'آیا دورهٔ آزمایشی نیاز به کارت بانکی دارد؟'),
         'a' => $say('No card, 14 full days. You get one reminder email on day 12 and nothing ever auto-charges — the workspace simply waits for you to pick a plan.', 'نه؛ ۱۴ روز کامل، بدون کارت. روز دوازدهم یک ایمیل یادآوری می‌آید و هیچ چیزی خودکار شارژ نمی‌شود — ورک‌اسپیس فقط می‌ماند تا پلن انتخاب کنید.')],
        ['id' => 'data',
         'q' => $say('Where does our data live?', 'داده‌هایمان کجا نگهداری می‌شود؟'),
         'a' => $say('In the region you pick when signing up — Frankfurt, Dublin or Singapore — and it never leaves it. Encrypted at rest with AES-256 and in flight with TLS 1.3.', 'در همان ناحیه‌ای که هنگام ثبت‌نام انتخاب می‌کنید — فرانکفورت، دوبلین یا سنگاپور — و هرگز از آن خارج نمی‌شود. رمزگذاری در حالت سکون با AES-256 و در حال انتقال با TLS 1.3.')],
        ['id' => 'plan',
         'q' => $say('Can we switch plans mid-month?', 'می‌توانیم وسط ماه پلن را عوض کنیم؟'),
         'a' => $say('Yes. Changes are prorated to the minute and only the difference shows up on the next invoice. No restarts, no migration, no downtime.', 'بله. تغییرات تا دقیقه دقیق‌حساب می‌شود و فقط اختلاف همان روزها در فاکتور بعدی می‌آید. بدون ری‌استارت، بدون مهاجرت، بدون قطعی.')],
        ['id' => 'exit',
         'q' => $say('What does leaving look like?', 'خروج از سرویس چطور است؟'),
         'a' => $say('One click: a full JSON and CSV export of every workspace, then hard deletion of everything within 30 days — confirmed by email, with the log attached.', 'با یک کلیک: خروجی کامل JSON و CSV از همهٔ فضاهای کاری، سپس حذف قطعی همه‌چیز ظرف ۳۰ روز — با تأیید ایمیلی و پیوستِ گزارش.')],
        ['id' => 'sso',
         'q' => $say('Which plans include SSO?', 'ورود تک‌نشان در کدام پلن‌ها هست؟'),
         'a' => $say('SAML and OIDC single sign-on ships with Scale; Okta and Active Directory are first-class. SCIM user provisioning is on the roadmap for this quarter.', 'ورود تک‌نشان SAML و OIDC همراه پلن مقیاس است؛ اکتا و اکتیو دایرکتوری پشتیبانی کامل می‌شوند. راه‌اندازی کاربر با SCIM نقشهٔ راه همین فصل است.')],
        ['id' => 'support',
         'q' => $say('When does support answer?', 'پشتیبانی چه ساعتی پاسخ می‌دهد؟'),
         'a' => $say('The Dublin and Singapore desks cover 24/7 between them. First response is under 4 hours on Growth and under 1 hour on Scale, measured — not promised.', 'میز دوبلین و سنگاپور با هم پوشش ۲۴/۷ می‌دهند. نخستین پاسخ در پلن رشد زیر ۴ ساعت و در مقیاس زیر ۱ ساعت است — اندازه‌گیری‌شده، نه وعده‌ داده‌شده.')],
    ];
@endphp
<style>
    .secfq-root {
        --secfq-page: #F8FAFC; --secfq-ink: #4F46E5; --secfq-border: #E2E8F0;
        --secfq-text: #0F172A; --secfq-muted: #64748B;
        font-family: 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif;
        color: var(--secfq-text);
        display: grid;
        gap: 2rem;
    }
    html[data-theme="dark"] .secfq-root {
        --secfq-page: #0B1120; --secfq-ink: #818CF8; --secfq-border: #1E293B;
        --secfq-text: #F1F5F9; --secfq-muted: #94A3B8;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .secfq-root {
            --secfq-page: #0B1120; --secfq-ink: #818CF8; --secfq-border: #1E293B;
            --secfq-text: #F1F5F9; --secfq-muted: #94A3B8;
        }
    }
    .secfq {
        inline-size: 100%; padding: clamp(1.75rem, 4.5vw, 3.25rem) clamp(1.25rem, 4vw, 2.5rem);
        border: 1px solid var(--secfq-border); border-radius: 1.4rem; background: var(--secfq-page);
        display: grid; grid-template-columns: minmax(0, 20rem) minmax(0, 1fr); gap: clamp(1.5rem, 4vw, 3.5rem);
        align-items: start;
    }
    .secfq-side { display: grid; gap: .9rem; align-content: start; position: sticky; inset-block-start: 1rem; }
    .secfq-eyebrow { margin: 0; font-size: .72rem; font-weight: 700; letter-spacing: .14em;
                     text-transform: uppercase; color: var(--secfq-ink); }
    .secfq-side h3 { margin: 0; font-size: clamp(1.35rem, 2.8vw, 1.9rem); font-weight: 800;
                     letter-spacing: -.02em; line-height: 1.3; }
    .secfq-side > p { margin: 0; font-size: .88rem; line-height: 1.75; color: var(--secfq-muted); }
    .secfq-cta { display: inline-flex; align-items: center; gap: .55rem; justify-self: start;
                 margin-block-start: .35rem; padding: .68rem 1.15rem; border: none; border-radius: .8rem;
                 background: var(--secfq-ink); color: #fff; font: 600 .85rem/1 inherit; cursor: pointer;
                 transition: filter .2s ease; }
    html[data-theme="dark"] .secfq-cta { color: #0B1120; }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .secfq-cta { color: #0B1120; }
    }
    .secfq-cta:hover { filter: brightness(1.08); }
    .secfq-cta:focus-visible { outline: 2px solid var(--secfq-ink); outline-offset: 2px; }
    .secfq-cta svg { inline-size: 1rem; block-size: 1rem; stroke: currentColor; stroke-width: 2;
                     fill: none; stroke-linecap: round; stroke-linejoin: round; }
    .secfq-all { display: inline-flex; align-items: center; gap: .4rem; margin-block-start: .4rem;
                 color: var(--secfq-ink); font-size: .85rem; font-weight: 600; text-decoration: none; }
    .secfq-all:hover { text-decoration: underline; }
    .secfq-all svg { inline-size: .95rem; block-size: .95rem; stroke: currentColor; stroke-width: 2;
                     fill: none; stroke-linecap: round; stroke-linejoin: round; }
    html[dir="rtl"] .secfq-all svg { rotate: 180deg; }

    .secfq-item { display: grid; grid-template-rows: auto 0fr;
                  transition: grid-template-rows .32s cubic-bezier(.2, 0, 0, 1); }
    .secfq-item[data-open="true"] { grid-template-rows: auto 1fr; }
    .secfq-q { display: flex; align-items: center; gap: 1rem; inline-size: 100%; padding: 1.05rem .15rem;
               border: none; border-block-end: 1px solid var(--secfq-border); background: transparent;
               font: 650 .95rem/1.5 inherit; color: inherit; cursor: pointer; text-align: start;
               transition: color .2s ease; }
    .secfq-q:hover { color: var(--secfq-ink); }
    .secfq-q:focus-visible { outline: 2px solid var(--secfq-ink); outline-offset: -2px; }
    .secfq-a { overflow: hidden; }
    .secfq-a > div { padding: .15rem .15rem 1.15rem; font-size: .87rem; line-height: 1.8;
                     color: var(--secfq-muted); max-inline-size: 62ch;
                     opacity: .85; transition: opacity .32s ease; }
    .secfq-item[data-open="true"] .secfq-a > div { opacity: 1; }
    .secfq-plus { flex: none; position: relative; inline-size: 1.05rem; aspect-ratio: 1;
                  margin-inline-start: auto; color: var(--secfq-ink);
                  transition: rotate .32s cubic-bezier(.2, 0, 0, 1); }
    .secfq-plus::before, .secfq-plus::after { content: ''; position: absolute; inset-block-start: calc(50% - 1px);
                  inset-inline-start: 0; inline-size: 100%; block-size: 2px; border-radius: 2px; background: currentColor; }
    .secfq-plus::after { rotate: 90deg; }
    .secfq-item[data-open="true"] .secfq-plus { rotate: 45deg; }
    .secfq-note { margin: 0; font-size: .72rem; color: var(--secfq-muted); }
    @media (max-width: 760px) {
        .secfq { grid-template-columns: 1fr; }
        .secfq-side { position: static; }
    }
    @media (prefers-reduced-motion: reduce) {
        .secfq-root * { transition-duration: .01ms !important; animation-duration: .01ms !important; }
    }
</style>

<div class="secfq-root" x-data="{ open: 'trial' }">
    <section class="secfq" aria-labelledby="secfq-title">
        <div class="secfq-side">
            <p class="secfq-eyebrow">{{ $say('Frequently asked', 'پرسش‌های پرتکرار') }}</p>
            <h3 id="secfq-title">{{ $say('What teams ask before they start', 'چیزی که تیم‌ها پیش از شروع می‌پرسند') }}</h3>
            <p>{{ $say('Six honest answers about the trial, the data and the exit. Anything else, a human in Dublin or Singapore answers within the hour.', 'شش پاسخ رک دربارهٔ دورهٔ آزمایشی، داده‌ها و خروج. برای هر چیز دیگر، یک آدم واقعی در دوبلین یا سنگاپور زیر یک ساعت جواب می‌دهد.') }}</p>
            <button type="button" class="secfq-cta">
                <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2.5"/><path d="M3.5 7.5l8.5 6 8.5-6"/></svg>
                {{ $say('Talk to sales', 'گفت‌وگو با فروش') }}
            </button>
            <a class="secfq-all" href="#">
                {{ $say('All questions', 'همهٔ پرسش‌ها') }}
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4.5 12h14M13 6.5l5.5 5.5L13 17.5"/></svg>
            </a>
        </div>

        <div>
            @foreach ($faqs as $faq)
                <div class="secfq-item" :data-open="String(open === '{{ $faq['id'] }}')">
                    <h4 style="margin: 0; display: contents">
                        <button type="button" class="secfq-q" x-on:click="open = open === '{{ $faq['id'] }}' ? null : '{{ $faq['id'] }}'"
                            :aria-expanded="String(open === '{{ $faq['id'] }}')" aria-controls="secfq-a-{{ $faq['id'] }}">
                            <span>{{ $faq['q'] }}</span>
                            <i class="secfq-plus" aria-hidden="true"></i>
                        </button>
                    </h4>
                    <div class="secfq-a" id="secfq-a-{{ $faq['id'] }}" role="region">
                        <div><p style="margin: 0">{{ $faq['a'] }}</p></div>
                    </div>
                </div>
            @endforeach
            <p class="secfq-note" style="margin-block-start: 1.1rem">
                {{ $say('Average first response, last 90 days: 38 minutes.', 'میانگین نخستین پاسخ، ۹۰ روز اخیر: ۳۸ دقیقه.') }}
            </p>
        </div>
    </section>
</div>
