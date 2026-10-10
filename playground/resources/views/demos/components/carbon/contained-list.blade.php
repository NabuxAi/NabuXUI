{{--
    Carbon's contained list: one flat frame that is its own container. Scenario
    one filters a members list live from the header's icon action; scenario two
    is a settings list with sections and working switches; the variants row
    compares inset and size.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;
@endphp
<style>
    @import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&display=swap');
    .cbcl-root {
        --cbcl-accent: #0f62fe; --cbcl-accent-hover: #0353e9;
        --cbcl-text: #161616; --cbcl-text-secondary: #525252;
        --cbcl-border: #e0e0e0; --cbcl-border-strong: #8d8d8d;
        --cbcl-layer: #f4f4f4; --cbcl-layer-hover: #e8e8e8;
        --cbcl-font: 'IBM Plex Sans', 'Inter', 'Vazirmatn', sans-serif;
        font-family: var(--cbcl-font);
        display: grid; gap: 2rem; justify-items: center;
    }
    html[data-theme="dark"] .cbcl-root {
        --cbcl-accent: #4589ff; --cbcl-accent-hover: #78a9ff;
        --cbcl-text: #f4f4f4; --cbcl-text-secondary: #c6c6c6;
        --cbcl-border: #393939; --cbcl-border-strong: #8d8d8d;
        --cbcl-layer: #262626; --cbcl-layer-hover: #333333;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .cbcl-root {
            --cbcl-accent: #4589ff; --cbcl-accent-hover: #78a9ff;
            --cbcl-text: #f4f4f4; --cbcl-text-secondary: #c6c6c6;
            --cbcl-border: #393939; --cbcl-border-strong: #8d8d8d;
            --cbcl-layer: #262626; --cbcl-layer-hover: #333333;
        }
    }
    .cbcl { inline-size: min(100%, 34rem); border: 1px solid var(--cbcl-border); background: var(--cbcl-layer); }
    .cbcl-head { display: flex; align-items: center; gap: .5rem; padding: .75rem 1rem; border-block-end: 1px solid var(--cbcl-border); }
    .cbcl-head b { font-weight: 600; font-size: .875rem; color: var(--cbcl-text); }
    .cbcl-head small { margin-inline-start: auto; font-size: .75rem; color: var(--cbcl-text-secondary); }
    .cbcl-search { position: relative; margin-inline-start: auto; }
    .cbcl-search svg { position: absolute; inset-block-start: 50%; inset-inline-start: .5rem; translate: 0 -50%; color: var(--cbcl-text-secondary); pointer-events: none; }
    .cbcl-search input { block-size: 2rem; inline-size: 10.5rem; padding-inline: 2rem .625rem; border: none;
                         border-block-end: 1px solid var(--cbcl-border-strong); background: transparent;
                         color: var(--cbcl-text); font: 400 .8125rem/1 var(--cbcl-font); }
    .cbcl-search input:focus-visible { outline: none; border-block-end: 2px solid var(--cbcl-accent); }
    .cbcl ul { margin: 0; padding: 0; list-style: none; }
    .cbcl-section-title { padding: .375rem 1rem; font-size: .75rem; color: var(--cbcl-text-secondary);
                          background: var(--cbcl-layer-hover); border-block: 1px solid var(--cbcl-border); }
    .cbcl-row { inline-size: 100%; display: flex; align-items: center; gap: .75rem; padding: .6875rem 1rem; border: none;
                border-block-end: 1px solid var(--cbcl-border); background: transparent; color: var(--cbcl-text);
                font: 400 .875rem/1.4 var(--cbcl-font); text-align: start; cursor: pointer; }
    .cbcl-row:hover { background: var(--cbcl-layer-hover); }
    .cbcl-row:focus-visible { outline: 2px solid var(--cbcl-accent); outline-offset: -2px; }
    .cbcl-row small { font-size: .75rem; color: var(--cbcl-text-secondary); }
    .cbcl-row .cbcl-meta { margin-inline-start: auto; display: inline-flex; align-items: center; gap: .5rem; }
    .cbcl-row svg { flex: none; color: var(--cbcl-text-secondary); }
    .cbcl-tag { display: inline-grid; place-items: center; padding: 0 .5rem; block-size: 1.5rem;
                border: 1px solid; font-size: .75rem; white-space: nowrap; }
    .cbcl-tag[data-tone='green'] { border-color: #24a148; color: #0e6027; }
    .cbcl-tag[data-tone='gray'] { border-color: #8d8d8d; color: var(--cbcl-text-secondary); }
    .cbcl-tag[data-tone='blue'] { border-color: #0f62fe; color: #0043ce; }
    html[data-theme="dark"] .cbcl-tag[data-tone='green'] { border-color: #42be65; color: #a7f0ba; }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .cbcl-tag[data-tone='green'] { border-color: #42be65; color: #a7f0ba; }
    }
    .cbcl-switch { appearance: none; position: relative; inline-size: 2.75rem; block-size: 1.5rem; flex: none; margin: 0;
                   border: 1px solid var(--cbcl-border-strong); background: var(--cbcl-layer); cursor: pointer;
                   transition: background-color .11s ease-in, border-color .11s ease-in; }
    .cbcl-switch::after { content: ''; position: absolute; inset-block-start: 50%; inset-inline-start: .25rem; translate: 0 -50%;
                          inline-size: 1rem; block-size: 1rem; background: var(--cbcl-border-strong);
                          transition: inset-inline-start .11s ease-in, background-color .11s ease-in; }
    .cbcl-switch:checked { border-color: var(--cbcl-accent); background: var(--cbcl-accent); }
    .cbcl-switch:checked::after { inset-inline-start: calc(100% - 1.25rem); background: #fff; }
    .cbcl-switch:focus-visible { outline: 2px solid var(--cbcl-accent); outline-offset: 1px; }
    .cbcl-empty { padding: 1rem; font-size: .8125rem; color: var(--cbcl-text-secondary); }
    .cbcl-foot { padding: .625rem 1rem; font-size: .75rem; color: var(--cbcl-text-secondary); border-block-start: 2px solid var(--cbcl-accent); }
    .cbcl-inset .cbcl-row, .cbcl-inset .cbcl-section-title { padding-inline: 0; }
    .cbcl-inset .cbcl-section-title { padding-inline: 0; }
    .cbcl[data-size='sm'] .cbcl-row { padding-block: .375rem; font-size: .8125rem; }
    .cbcl[data-size='lg'] .cbcl-row { padding-block: 1rem; }
    .cbcl-spec { display: grid; gap: 1.5rem; justify-items: center; }
    .cbcl-spec-row { display: flex; flex-wrap: wrap; gap: 1.5rem; justify-content: center; align-items: flex-start; }
    .cbcl-spec-cell { display: grid; gap: .5rem; }
    .cbcl-spec-cell > small { font-size: .72rem; color: var(--nx-text-muted); }
    .cbcl-spec .cbcl { inline-size: min(17rem, 82vw); }
    :where(.nx-js) .pg:has(.cbcl-root) .nx-data-table[data-nx-reveal] tbody tr { opacity: 1; translate: none; }
    @media (max-width: 480px) {
        .cbcl-search input { inline-size: 8rem; }
        .pg:has(.cbcl-root) .nx-data-table :is(th, td) { white-space: normal; padding-inline: .5rem; overflow-wrap: break-word; }
    }
    @media (prefers-reduced-motion: reduce) {
        .cbcl-root * { transition-duration: .01ms !important; animation-duration: .01ms !important; }
    }
</style>

<div class="cbcl-root">
    <section class="pg-box" style="justify-items: center"
        x-data="{
            q: '',
            members: [
                { name: '{{ $say('Sara Mohammadi', 'سارا محمدی') }}', role: '{{ $say('Owner', 'مالک') }}', tone: 'green', state: '{{ $say('Owner', 'مالک') }}', on: true },
                { name: '{{ $say('Amir Rostami', 'امیر رستمی') }}', role: '{{ $say('Developer', 'توسعه‌دهنده') }}', tone: 'blue', state: '{{ $say('Active', 'فعال') }}', on: true },
                { name: '{{ $say('Niloofar Karimi', 'نیلوفر کریمی') }}', role: '{{ $say('Support', 'پشتیبانی') }}', tone: 'blue', state: '{{ $say('Active', 'فعال') }}', on: true },
                { name: '{{ $say('Kian Berg', 'کیان احمدی') }}', role: '{{ $say('Billing', 'صورتحساب') }}', tone: 'gray', state: '{{ $say('Invited', 'دعوت‌شده') }}', on: false },
            ],
            get filtered() { const q = this.q.trim(); return q ? this.members.filter(m => m.name.includes(q) || m.role.includes(q)) : this.members },
        }">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A list that is its own container', 'فهرستی که خودش ظرف است') }}</h3>
            <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
                {{ $say('Header, action and rows live inside one flat frame — nothing floats outside it. The search box in the header live-filters the members below.', 'سربرگ، اکشن و ردیف‌ها داخل یک قاب تخت زندگی می‌کنند — هیچ‌چیز بیرون آن شناور نیست. جست‌وجوی سربرگ، اعضای پایین را زنده فیلتر می‌کند.') }}
            </p>
        </div>

        <div class="cbcl" role="group" aria-label="{{ $say('Workspace members', 'اعضای فضای کاری') }}">
            <header class="cbcl-head">
                <b>{{ $say('Members', 'اعضا') }}</b>
                <span class="cbcl-search">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
                    <input type="search" x-model="q" placeholder="{{ $say('Find a member', 'یافتن عضو') }}" aria-label="{{ $say('Find a member', 'یافتن عضو') }}">
                </span>
            </header>
            <ul>
                <template x-for="m in filtered" :key="m.name">
                    <li>
                        <button type="button" class="cbcl-row">
                            <span>
                                <span x-text="m.name"></span>
                                <small x-text="' · ' + m.role"></small>
                            </span>
                            <span class="cbcl-meta">
                                <span class="cbcl-tag" x-bind:data-tone="m.tone" x-text="m.state"></span>
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 6l6 6-6 6"/></svg>
                            </span>
                        </button>
                    </li>
                </template>
            </ul>
            <p class="cbcl-empty" x-show="filtered.length === 0" x-cloak>
                {{ $say('No member matches — the frame stays up, only the rows leave.', 'هیچ عضوی نمی‌خورد — قاب سرِ جایش می‌ماند، فقط ردیف‌ها می‌روند.') }}
            </p>
            <div class="cbcl-foot" role="status">
                <span x-text="filtered.length.toLocaleString('{{ $fa ? 'fa-IR' : 'en-US' }}') + ' {{ $say('of', 'از') }} ' + members.length.toLocaleString('{{ $fa ? 'fa-IR' : 'en-US' }}') + ' {{ $say('members', 'عضو') }}'">{{ $num('4') }} {{ $say('of', 'از') }} {{ $num('4') }} {{ $say('members', 'عضو') }}</span>
            </div>
        </div>
    </section>

    <section class="pg-box" style="justify-items: center"
        x-data="{
            prefs: { twoFa: true, apiKeys: false, email: true, sms: false },
            count() { return Object.values(this.prefs).filter(Boolean).length.toLocaleString('{{ $fa ? 'fa-IR' : 'en-US' }}') },
        }">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('Sections, switches, one frame', 'بخش‌بندی، سوییچ، یک قاب') }}</h3>
            <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
                {{ $say('The contained list is Carbon’s answer to grouped settings: section titles band across the frame and every row is a working control.', 'فهرست دربرگیرنده پاسخ کربن به تنظیمات گروهی است: عنوان بخش‌ها عرض قاب را می‌بندند و هر ردیف یک کنترل واقعی است.') }}
            </p>
        </div>

        <div class="cbcl" role="group" aria-label="{{ $say('Notifications and security', 'امنیت و اعلان‌ها') }}">
            <header class="cbcl-head">
                <b>{{ $say('Security & notifications', 'امنیت و اعلان‌ها') }}</b>
                <small x-text="count() + ' {{ $say('active', 'فعال') }}'"></small>
            </header>
            <ul>
                <li class="cbcl-section-title" role="presentation">{{ $say('Security', 'امنیت') }}</li>
                <li><label class="cbcl-row">
                    <span>{{ $say('Two-factor authentication', 'احراز هویت دومرحله‌ای') }}</span>
                    <span class="cbcl-meta"><input type="checkbox" class="cbcl-switch" x-model="prefs.twoFa"></span>
                </label></li>
                <li><label class="cbcl-row">
                    <span>{{ $say('API keys', 'کلیدهای API') }} <small>· {{ $num('3') }}</small></span>
                    <span class="cbcl-meta"><input type="checkbox" class="cbcl-switch" x-model="prefs.apiKeys"></span>
                </label></li>
                <li class="cbcl-section-title" role="presentation">{{ $say('Notifications', 'اعلان‌ها') }}</li>
                <li><label class="cbcl-row">
                    <span>{{ $say('Email digest', 'خلاصهٔ ایمیلی') }} <small>· {{ $say('Mondays ' . $num('8:00'), 'دوشنبه‌ها ۸:۰۰') }}</small></span>
                    <span class="cbcl-meta"><input type="checkbox" class="cbcl-switch" x-model="prefs.email"></span>
                </label></li>
                <li><label class="cbcl-row">
                    <span>{{ $say('SMS on-call alerts', 'پیامک اعلان آن‌کال') }}</span>
                    <span class="cbcl-meta"><input type="checkbox" class="cbcl-switch" x-model="prefs.sms"></span>
                </label></li>
            </ul>
        </div>
    </section>
</div>

<section class="pg-box" style="justify-items: center">
    <div class="cbcl-root" style="inline-size: 100%">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('The whole family', 'همهٔ خانواده') }}</h3>
            <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
                {{ $say('Default, inset and the two compact sizes — the frame never changes, only the rhythm does.', 'پیش‌فرض، inset و دو اندازهٔ فشرده — قاب هرگز عوض نمی‌شود، فقط ریتم عوض می‌شود.') }}
            </p>
        </div>
        <div class="cbcl-spec">
        <div class="cbcl-spec-row">
            @foreach ([['md', false, 'پیش‌فرض', 'default'], ['md', true, 'inset', 'inset'], ['sm', false, 'sm', 'sm'], ['lg', false, 'lg', 'lg']] as [$size, $inset, $labelFa, $labelEn])
                <div class="cbcl-spec-cell">
                    <div class="cbcl{{ $inset ? ' cbcl-inset' : '' }}" data-size="{{ $size }}">
                        <header class="cbcl-head"><b>{{ $say('Reports', 'گزارش‌ها') }}</b></header>
                        <ul>
                            <li><button type="button" class="cbcl-row"><span>{{ $say('Daily', 'روزانه') }}</span></button></li>
                            <li><button type="button" class="cbcl-row"><span>{{ $say('Weekly', 'هفتگی') }}</span></button></li>
                        </ul>
                    </div>
                    <small>{{ $labelEn }}</small>
                </div>
            @endforeach
        </div>
        </div>
    </div>
</section>
