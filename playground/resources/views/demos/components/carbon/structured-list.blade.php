{{--
    Carbon's structured list on a hosting dashboard: the selection variant
    turns every row into one big radio (selected layer inverts, the footer
    summary follows), a second list shows the plain key-value shape, and the
    variants row compares md/xl and flush sizing.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;
@endphp
<style>
    @import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&display=swap');
    .cbsl-root {
        --cbsl-accent: #0f62fe; --cbsl-accent-hover: #0353e9;
        --cbsl-text: #161616; --cbsl-text-secondary: #525252;
        --cbsl-border: #e0e0e0; --cbsl-border-strong: #8d8d8d;
        --cbsl-layer: #f4f4f4; --cbsl-layer-2: #e8e8e8; --cbsl-selected: #e0e0e0;
        --cbsl-font: 'IBM Plex Sans', 'Inter', 'Vazirmatn', sans-serif;
        font-family: var(--cbsl-font);
        display: grid; gap: 2rem; justify-items: center;
    }
    html[data-theme="dark"] .cbsl-root {
        --cbsl-accent: #4589ff; --cbsl-accent-hover: #78a9ff;
        --cbsl-text: #f4f4f4; --cbsl-text-secondary: #c6c6c6;
        --cbsl-border: #393939; --cbsl-border-strong: #8d8d8d;
        --cbsl-layer: #262626; --cbsl-layer-2: #333333; --cbsl-selected: #393939;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .cbsl-root {
            --cbsl-accent: #4589ff; --cbsl-accent-hover: #78a9ff;
            --cbsl-text: #f4f4f4; --cbsl-text-secondary: #c6c6c6;
            --cbsl-border: #393939; --cbsl-border-strong: #8d8d8d;
            --cbsl-layer: #262626; --cbsl-layer-2: #333333; --cbsl-selected: #393939;
        }
    }
    .cbsl { inline-size: min(100%, 44rem); border-block-end: 1px solid var(--cbsl-border); background: var(--cbsl-layer); }
    .cbsl-row { display: grid; grid-template-columns: 2rem 1.1fr 1fr auto; gap: 1rem; align-items: center;
                padding: .875rem 1rem; border-block-start: 1px solid var(--cbsl-border); color: var(--cbsl-text); }
    .cbsl-row[data-head] { padding-block: .5rem; font-size: .75rem; font-weight: 400; color: var(--cbsl-text-secondary); }
    .cbsl-row[data-head] span:last-child { justify-self: end; }
    .cbsl-row:not([data-head]) { cursor: pointer; transition: background-color .11s ease-in; }
    .cbsl-row:not([data-head]):hover { background: var(--cbsl-layer-2); }
    .cbsl-row:not([data-head]):has(:focus-visible) { outline: 2px solid var(--cbsl-accent); outline-offset: -2px; }
    .cbsl-row:not([data-head]):has(input:checked) { background: var(--cbsl-selected); box-shadow: inset 3px 0 0 var(--cbsl-accent); }
    html[dir="rtl"] .cbsl-row:not([data-head]):has(input:checked) { box-shadow: inset -3px 0 0 var(--cbsl-accent); }
    .cbsl-radio { appearance: none; inline-size: 1.125rem; aspect-ratio: 1; margin: 0; border-radius: 50%;
                  border: 1px solid var(--cbsl-border-strong); background: transparent; cursor: pointer;
                  transition: border-color .11s ease-in, box-shadow .11s ease-in; }
    .cbsl-radio:hover { border-color: var(--cbsl-accent); }
    .cbsl-radio:checked { border-color: var(--cbsl-accent); box-shadow: inset 0 0 0 .3rem var(--cbsl-accent); }
    .cbsl b { font-weight: 600; }
    .cbsl small { font-size: .75rem; color: var(--cbsl-text-secondary); }
    .cbsl-state { display: inline-flex; align-items: center; gap: .375rem; font-size: .75rem; color: var(--cbsl-text-secondary); }
    .cbsl-state i { inline-size: .5rem; aspect-ratio: 1; background: var(--cbsl-live, #24a148); }
    .cbsl-summary { display: flex; flex-wrap: wrap; align-items: center; gap: .75rem 1.5rem; margin-block-start: 1rem;
                    padding: .75rem 1rem; background: var(--cbsl-layer); border-block-start: 2px solid var(--cbsl-accent);
                    font-size: .875rem; color: var(--cbsl-text); }
    .cbsl-go { block-size: 2.5rem; margin-inline-start: auto; padding-inline: 1rem; border: none; background: var(--cbsl-accent);
               color: #fff; font: 400 .875rem/1 var(--cbsl-font); cursor: pointer; transition: background-color .11s ease-in; }
    .cbsl-go:hover { background: var(--cbsl-accent-hover); }
    .cbsl-go:focus-visible, .cbsl-radio:focus-visible { outline: 2px solid var(--cbsl-accent); outline-offset: 1px; }
    .cbsl-kv { grid-template-columns: 10rem 1fr; cursor: default; }
    .cbsl-kv:hover { background: var(--cbsl-layer); }
    .cbsl-flush .cbsl-row { padding-inline: 0; }
    .cbsl[data-size='md'] .cbsl-row:not([data-head]) { padding-block: .5rem; }
    .cbsl-spec { display: grid; gap: 1.5rem; justify-items: center; }
    .cbsl-spec-row { display: flex; flex-wrap: wrap; gap: 1.5rem; justify-content: center; align-items: flex-start; }
    .cbsl-spec-cell { display: grid; gap: .5rem; }
    .cbsl-spec-cell > small { font-size: .72rem; color: var(--nx-text-muted); }
    .cbsl-spec .cbsl { inline-size: min(20rem, 78vw); }
    :where(.nx-js) .pg:has(.cbsl-root) .nx-data-table[data-nx-reveal] tbody tr { opacity: 1; translate: none; }
    @media (max-width: 480px) {
        .cbsl-row { grid-template-columns: 1.5rem 1fr; grid-auto-rows: auto; row-gap: .25rem; }
        .cbsl-row span:nth-child(n + 3) { grid-column: 2; }
        .cbsl-row[data-head] { grid-template-columns: 1fr; }
        .cbsl-row[data-head] span:nth-child(n + 2) { display: none; }
        .cbsl-kv { grid-template-columns: 5.5rem 1fr; gap: .75rem; }
        .pg:has(.cbsl-root) .nx-data-table :is(th, td) { white-space: normal; padding-inline: .5rem; overflow-wrap: break-word; }
    }
    @media (prefers-reduced-motion: reduce) {
        .cbsl-root * { transition-duration: .01ms !important; animation-duration: .01ms !important; }
    }
</style>

<div class="cbsl-root"
    x-data="{
        env: 'prod',
        envs: {
            prod:    { name: '{{ $say('Production', 'تولید') }}',    region: '{{ $say('Frankfurt', 'فرانکفورت') }}', cores: '{{ $num('16') }}', zone: '{{ $say('eu-de-2', 'eu-de-2') }}' },
            staging: { name: '{{ $say('Staging', 'آزمایشی') }}',   region: '{{ $say('Dublin', 'دوبلین') }}',     cores: '{{ $num('8') }}',  zone: '{{ $say('eu-de-1', 'eu-de-1') }}' },
            dev:     { name: '{{ $say('Development', 'توسعه') }}', region: '{{ $say('Singapore', 'سنگاپور') }}', cores: '{{ $num('4') }}',  zone: '{{ $say('ap-se-1', 'ap-se-1') }}' },
        },
        deployed: false,
        get current() { return this.envs[this.env] },
    }">
    <section class="pg-box" style="justify-items: center">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Pick a target, deploy', 'مقصد را بردار، استقرار بزن') }}</h3>
            <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
                {{ $say('Each row is one control: the radio at the start is invisible until you focus it, the selected row inverts to the selected layer with a carbon-blue spine, and the footer summary follows your choice.', 'هر ردیف یک کنترل است: رادیوِ آغازین تا فوکوس پنهان است، ردیف انتخاب‌شده به لایهٔ selected وارون می‌شود با خطِ پشتیِ آبی کربن، و خلاصهٔ پایین همراه انتخاب شما جابه‌جا می‌شود.') }}
            </p>
        </div>

        <div class="cbsl" role="table" aria-label="{{ $say('Deployment target', 'مقصد استقرار') }}">
            <div class="cbsl-row" role="row" data-head>
                <span aria-hidden="true"></span>
                <span role="columnheader">{{ $say('Environment', 'محیط') }}</span>
                <span role="columnheader">{{ $say('Region', 'ناحیه') }}</span>
                <span role="columnheader">{{ $say('Status', 'وضعیت') }}</span>
            </div>
            @foreach (['prod' => ['تولید', 'Production', 'فرانکفورت', 'Frankfurt'], 'staging' => ['آزمایشی', 'Staging', 'دوبلین', 'Dublin'], 'dev' => ['توسعه', 'Development', 'سنگاپور', 'Singapore']] as $key => [$nameFa, $nameEn, $regionFa, $regionEn])
                <label class="cbsl-row" role="row">
                    <input type="radio" class="cbsl-radio" name="cbsl-env" value="{{ $key }}" x-model="env">
                    <b role="cell">{{ $say($nameEn, $nameFa) }}</b>
                    <span role="cell">{{ $say($regionEn, $regionFa) }} <small>· {{ $num(['prod' => '16', 'staging' => '8', 'dev' => '4'][$key]) }} {{ $say('cores', 'هسته') }}</small></span>
                    <span class="cbsl-state" role="cell"><i aria-hidden="true"></i>{{ $say('Ready', 'آماده') }}</span>
                </label>
            @endforeach
        </div>

        <div class="cbsl-summary" role="status">
            <span>
                {{ $say('Deploying to', 'استقرار روی') }}
                <b x-text="current.name"></b>
                <span x-text="'· ' + current.region + ' · ' + current.zone"></span>
            </span>
            <button type="button" class="cbsl-go" x-on:click="deployed = true">{{ $say('Deploy', 'استقرار') }}</button>
            <small style="inline-size: 100%; font-size: .75rem; color: var(--cbsl-text-secondary)" x-show="deployed" x-cloak>
                {{ $say('Build ' . $num('841') . ' queued — rollback available for one hour.', 'بیلد ۸۴۱ در صف قرار گرفت — تا یک ساعت واگرد در دسترس است.') }}
            </small>
        </div>
    </section>

    <section class="pg-box" style="justify-items: center">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('The same list as plain key-value', 'همان فهرست، به‌شکل کل-مقدار') }}</h3>
            <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
                {{ $say('Without selection the structured list is just descriptive rows — the server card below reads flush, hugging its frame with no cell padding.', 'بدون انتخاب، فهرست ساخت‌یافته فقط ردیف‌های تشریحی است — کارت سرور زیر به‌صورت flush خوانده می‌شود و بی‌پدینگ به قاب می‌چسبد.') }}
            </p>
        </div>
        <div class="cbsl cbsl-flush" role="table" aria-label="{{ $say('Server details', 'جزئیات سرور') }}">
            @foreach ([
                [$say('Hostname', 'نام‌میزبان'), 'nabu-app-' . $num('03')],
                [$say('Operating system', 'سیستم‌عامل'), 'Ubuntu 24.04 LTS'],
                [$say('Time zone', 'منطقهٔ زمانی'), $say('Europe/Berlin · CEST', 'اروپا/برلین · CEST')],
                [$say('Created', 'ساخته‌شده در'), $num('2026/09/14')],
            ] as [$key, $value])
                <div class="cbsl-row cbsl-kv" role="row">
                    <small role="cell">{{ $key }}</small>
                    <span role="cell">{{ $value }}</span>
                </div>
            @endforeach
        </div>
    </section>
</div>

<section class="pg-box" style="justify-items: center">
    <div class="cbsl-root" style="inline-size: 100%">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('The whole family', 'همهٔ خانواده') }}</h3>
            <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
                {{ $say('Selection, plain, md sizing and flush — the same two-column rhythm at every size.', 'انتخاب‌دار، ساده، اندازهٔ md و flush — همان ریتم دوستونی در هر اندازه.') }}
            </p>
        </div>
        <div class="cbsl-spec">
        <div class="cbsl-spec-row">
            <div class="cbsl-spec-cell">
                <div class="cbsl" role="table" aria-label="{{ $say('Selection variant', 'گونهٔ انتخابی') }}">
                    <label class="cbsl-row"><input type="radio" class="cbsl-radio" name="cbsl-v1" checked><b role="cell">{{ $say('Queue', 'صف') }}</b><span role="cell">{{ $num('3') }}</span></label>
                    <label class="cbsl-row"><input type="radio" class="cbsl-radio" name="cbsl-v1"><b role="cell">{{ $say('Running', 'در حال اجرا') }}</b><span role="cell">{{ $num('12') }}</span></label>
                </div>
                <small>selection</small>
            </div>
            <div class="cbsl-spec-cell">
                <div class="cbsl" role="table" aria-label="{{ $say('Plain variant', 'گونهٔ ساده') }}">
                    <div class="cbsl-row cbsl-kv"><small role="cell">uptime</small><span role="cell">{{ $say('99.98%', '۹۹٫۹۸٪') }}</span></div>
                    <div class="cbsl-row cbsl-kv"><small role="cell">latency</small><span role="cell">{{ $num('24') }}ms</span></div>
                </div>
                <small>default</small>
            </div>
            <div class="cbsl-spec-cell">
                <div class="cbsl" data-size="md" role="table" aria-label="{{ $say('Medium variant', 'گونهٔ md') }}">
                    <div class="cbsl-row cbsl-kv"><small role="cell">CPU</small><span role="cell">{{ $num('38') }}{{ $say('%', '٪') }}</span></div>
                    <div class="cbsl-row cbsl-kv"><small role="cell">RAM</small><span role="cell">{{ $num('5.2') }}GB</span></div>
                </div>
                <small>md</small>
            </div>
            <div class="cbsl-spec-cell">
                <div class="cbsl cbsl-flush" role="table" aria-label="{{ $say('Flush variant', 'گونهٔ flush') }}">
                    <div class="cbsl-row cbsl-kv"><small role="cell">{{ $say('Region', 'ناحیه') }}</small><span role="cell">eu-de-2</span></div>
                    <div class="cbsl-row cbsl-kv"><small role="cell">{{ $say('Tier', 'رده') }}</small><span role="cell">{{ $say('Business', 'کسب‌وکار') }}</span></div>
                </div>
                <small>flush</small>
            </div>
        </div>
        </div>
    </div>
</section>
