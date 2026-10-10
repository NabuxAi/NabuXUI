{{--
    Glass inputs over a living colour field: the Vitral invite card carries an
    email field whose rim catches the light on focus and re-tints with the
    address (rose for a broken one, green for a deliverable one) plus a search
    field over the integration directory — both with a clear button that
    arrives with the first keystroke. The four states then sit as labelled
    specimens on the same kind of stage.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹', '.' => '٫', '%' => '٪']) : $s;
@endphp
<style>
    .gin-root {
        --gin-glass: rgba(255,255,255,.15); --gin-glass-2: rgba(255,255,255,.06);
        --gin-rim: rgba(255,255,255,.44); --gin-sheen: rgba(255,255,255,.5);
        --gin-ink: #10142e; --gin-muted: rgba(16,20,46,.64);
        --gin-accent: #5a6cff; --gin-rose: #e5484d; --gin-green: #12a150;
        --gin-hover: rgba(255,255,255,.22);
        display: grid; gap: 2.25rem; justify-items: center; inline-size: 100%;
    }
    html[data-theme="dark"] .gin-root {
        --gin-glass: rgba(21,26,56,.5); --gin-glass-2: rgba(21,26,56,.3);
        --gin-rim: rgba(255,255,255,.24); --gin-sheen: rgba(255,255,255,.22);
        --gin-ink: #eef1ff; --gin-muted: rgba(238,241,255,.66);
        --gin-accent: #8a94ff; --gin-rose: #ff7a7e; --gin-green: #3ecf8e;
        --gin-hover: rgba(255,255,255,.08);
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .gin-root {
            --gin-glass: rgba(21,26,56,.5); --gin-glass-2: rgba(21,26,56,.3);
            --gin-rim: rgba(255,255,255,.24); --gin-sheen: rgba(255,255,255,.22);
            --gin-ink: #eef1ff; --gin-muted: rgba(238,241,255,.66);
            --gin-accent: #8a94ff; --gin-rose: #ff7a7e; --gin-green: #3ecf8e;
            --gin-hover: rgba(255,255,255,.08);
        }
    }

    .gin-stage { position: relative; isolation: isolate; overflow: clip; display: grid; place-items: center;
                 inline-size: 100%; min-block-size: 24rem; padding: 3rem 1.25rem;
                 border-radius: var(--nx-radius-2xl); background: var(--nx-bg); }
    .gin-blobs { position: absolute; inset: 0; z-index: -2; filter: blur(30px) saturate(1.25); }
    .gin-blobs i { position: absolute; inline-size: 44%; aspect-ratio: 1; border-radius: 50%; opacity: .85;
                   animation: gin-drift 17s ease-in-out infinite alternate; }
    .gin-blobs i:nth-child(1) { inset-block-start: -14%; inset-inline-start: -8%; background: var(--nx-lapis-500); }
    .gin-blobs i:nth-child(2) { inset-block-end: -20%; inset-inline-start: 18%; background: var(--nx-violet-500); animation-delay: -5s; }
    .gin-blobs i:nth-child(3) { inset-block-start: 6%; inset-inline-end: -10%; background: var(--nx-cyan-400); animation-delay: -9s; }
    .gin-blobs i:nth-child(4) { inset-block-end: -8%; inset-inline-end: 14%; inline-size: 26%; background: var(--nx-gold-400); animation-delay: -12s; }
    @keyframes gin-drift { to { translate: calc(8% * var(--nx-motion, 1)) calc(-6% * var(--nx-motion, 1)); scale: calc(1 + .1 * var(--nx-motion, 1)); } }

    .gin-card { position: relative; z-index: 1; display: grid; gap: 1.15rem; inline-size: min(100%, 27rem);
                padding: 1.75rem 1.4rem; border-radius: var(--nx-radius-xl);
                background: linear-gradient(125deg, var(--gin-glass), var(--gin-glass-2));
                border: 1px solid var(--gin-rim);
                box-shadow: inset 0 1px 0 var(--gin-sheen), 0 18px 44px rgba(13,16,50,.16);
                backdrop-filter: blur(18px) saturate(1.4); -webkit-backdrop-filter: blur(18px) saturate(1.4); }
    .gin-head { display: grid; gap: .3rem; }
    .gin-title { margin: 0; font: 700 var(--nx-text-xl) / 1.25 var(--nx-font-display); color: var(--gin-ink); }
    .gin-sub { margin: 0; font-size: var(--nx-text-sm); color: var(--gin-muted); }
    .gin-field { display: flex; align-items: center; gap: .55rem; inline-size: 100%; min-inline-size: 0;
                 padding: .85rem .95rem; border-radius: .9rem;
                 background: linear-gradient(125deg, var(--gin-glass), var(--gin-glass-2));
                 border: 1px solid var(--gin-rim);
                 box-shadow: inset 0 1px 0 var(--gin-sheen), 0 8px 22px rgba(13,16,50,.12);
                 backdrop-filter: blur(14px) saturate(1.4); -webkit-backdrop-filter: blur(14px) saturate(1.4);
                 transition: border-color .2s ease, box-shadow .25s ease, background-color .2s ease; }
    .gin-field:hover { border-color: color-mix(in oklab, var(--gin-rim) 70%, white); }
    .gin-field > svg { flex: none; inline-size: 1.1rem; block-size: 1.1rem; color: var(--gin-muted); }
    .gin-field input { flex: 1; min-inline-size: 0; border: none; background: none; padding: 0; outline: none;
                       color: var(--gin-ink); font: 500 .95rem / 1.4 var(--nx-font-sans); }
    .gin-field input::placeholder { color: var(--gin-muted); }
    .gin-field:focus-within, .gin-field[data-force="focus"] {
        border-color: color-mix(in oklab, var(--gin-accent) 60%, white);
        box-shadow: inset 0 1px 0 var(--gin-sheen),
                    0 0 0 4px color-mix(in oklab, var(--gin-accent) 26%, transparent),
                    0 14px 34px color-mix(in oklab, var(--gin-accent) 22%, transparent); }
    .gin-field[data-state="error"] { border-color: color-mix(in oklab, var(--gin-rose) 62%, white); }
    .gin-field[data-state="error"]:focus-within, .gin-field[data-state="error"][data-force="focus"] {
        box-shadow: inset 0 1px 0 var(--gin-sheen),
                    0 0 0 4px color-mix(in oklab, var(--gin-rose) 26%, transparent),
                    0 14px 34px color-mix(in oklab, var(--gin-rose) 20%, transparent); }
    .gin-field[data-state="success"] { border-color: color-mix(in oklab, var(--gin-green) 58%, white); }
    .gin-field[data-state="success"]:focus-within, .gin-field[data-state="success"][data-force="focus"] {
        box-shadow: inset 0 1px 0 var(--gin-sheen),
                    0 0 0 4px color-mix(in oklab, var(--gin-green) 24%, transparent),
                    0 14px 34px color-mix(in oklab, var(--gin-green) 20%, transparent); }
    .gin-clear { display: inline-grid; place-items: center; flex: none; inline-size: 1.6rem; aspect-ratio: 1;
                 padding: 0; border: 1px solid var(--gin-rim); border-radius: 50%;
                 background: var(--gin-glass); color: var(--gin-muted); cursor: pointer;
                 transition: color .18s ease, background-color .18s ease, border-color .18s ease; }
    .gin-clear svg { inline-size: .75rem; block-size: .75rem; }
    .gin-clear:hover { color: var(--gin-ink); background: var(--gin-hover); border-color: color-mix(in oklab, var(--gin-rim) 70%, white); }
    .gin-clear:focus-visible { outline: 2px solid var(--gin-accent); outline-offset: 2px; }
    .gin-note { display: flex; align-items: center; gap: .45rem; margin: 0; min-block-size: 1.15em;
                font-size: .78rem; color: var(--gin-muted); }
    .gin-note svg { flex: none; inline-size: .95rem; block-size: .95rem; }
    .gin-note[data-tone="error"] { color: var(--gin-rose); }
    .gin-note[data-tone="success"] { color: var(--gin-green); }
    .gin-hits { display: grid; gap: .25rem; margin: 0; padding: 0; list-style: none;
                max-block-size: 10.5rem; overflow-y: auto; scrollbar-width: thin; }
    .gin-hit { display: flex; align-items: center; gap: .6rem; padding: .5rem .65rem; border-radius: .7rem;
               color: var(--gin-ink); font-size: .88rem; transition: background-color .18s ease; }
    .gin-hit svg { flex: none; inline-size: .95rem; block-size: .95rem; color: var(--gin-muted); }
    .gin-hit:hover { background: var(--gin-hover); }
    .gin-hit small { margin-inline-start: auto; font-size: .7rem; color: var(--gin-muted); }
    .gin-count { margin: 0; font-size: .74rem; color: var(--gin-muted); }
    .gin-spec { display: grid; gap: 1.5rem; justify-items: center; }
    .gin-spec-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(11.5rem, 1fr)); gap: 1rem 1.25rem; }
    .gin-spec-cell { display: grid; gap: .5rem; }
    .gin-spec-cell > small { font-size: .72rem; color: var(--nx-text-muted); }
    .gin-spec .gin-field { pointer-events: none; }
    @media (prefers-reduced-motion: reduce) {
        .gin-blobs i { animation: none; }
        .gin-root * { transition-duration: .01ms !important; animation-duration: .01ms !important; }
    }
</style>

<div class="gin-root"
    x-data="{
        isFa: {{ $fa ? 'true' : 'false' }},
        email: '',
        q: '',
        apps: ['Slack', 'Linear', 'Datadog', 'GitHub', 'Figma', 'Sentry'],
        n(v) { const d = '۰۱۲۳۴۵۶۷۸۹'; return this.isFa ? String(v).replace(/[0-9]/g, (x) => d[+x]) : String(v); },
        get emailOk() { return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(this.email.trim()); },
        get emailBad() { return this.email.trim().length > 3 && !this.emailOk; },
        get emailState() { return this.emailOk ? 'success' : (this.emailBad ? 'error' : ''); },
        get matches() { const q = this.q.trim().toLowerCase(); return q ? this.apps.filter((a) => a.toLowerCase().includes(q)) : this.apps; },
        get countLine() { return this.isFa
            ? this.n(this.matches.length) + ' از ' + this.n(this.apps.length) + ' یکپارچه‌سازی'
            : this.matches.length + ' of ' + this.apps.length + ' integrations'; },
    }">
    <section class="pg-box" style="justify-items: center">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Invite the team, find the pipes', 'تیم را دعوت کن، اتصال‌ها را پیدا کن') }}</h3>
            <p style="margin: 0; max-width: 54ch; color: var(--nx-text-muted)">
                {{ $say('Two glass fields over the same drifting colour field: focus settles a light halo around the box, the clear button arrives with the first keystroke, and the rim turns rose or green as the address proves itself.', 'دو فیلد شیشه‌ای روی یک میدان رنگِ در حال حرکت: فوکوس هالهٔ نوری دور کادر می‌نشاند، دکمهٔ پاک‌کردن با اولین حرف می‌رسد و rim با اثبات‌شدن نشانی به گلبهی یا سبز می‌گراید.') }}
            </p>
        </div>

        <div class="gin-stage">
            <div class="gin-blobs" aria-hidden="true"><i></i><i></i><i></i><i></i></div>
            <div class="gin-card">
                <div class="gin-head">
                    <h4 class="gin-title">{{ $say('Bring your team to Vitral', 'تیم‌تان را به Vitral بیاورید') }}</h4>
                    <p class="gin-sub">{{ $say('They land on the Frankfurt workspace, seats are billed monthly.', 'آنها به ورک‌اسپیس فرانکفورت می‌آیند؛ صندلی‌ها ماهانه حساب می‌شوند.') }}</p>
                </div>

                <label class="gin-field" :data-state="emailState">
                    {!! \NabuXUI\NabuXUI::icon('mail') !!}
                    <input type="email" x-model="email" autocomplete="off"
                        placeholder="mia@northwind.dev" aria-label="{{ $say('Work email', 'ایمیل کاری') }}">
                    <button type="button" class="gin-clear" x-show="email.length > 0" x-cloak
                        x-on:click="email = ''"
                        :aria-label="isFa ? 'پاک‌کردن نشانی' : 'Clear the address'">
                        {!! \NabuXUI\NabuXUI::icon('x') !!}
                    </button>
                </label>
                <p class="gin-note" data-tone="success" x-show="emailOk" x-cloak>
                    {!! \NabuXUI\NabuXUI::icon('check-circle') !!}
                    {{ $say('Looks deliverable — a magic link, no password to remember.', 'قابل تحویل به نظر می‌رسد — یک لینک جادویی، بدون رمزی که به یاد بسپارید.') }}
                </p>
                <p class="gin-note" data-tone="error" x-show="emailBad" x-cloak>
                    {!! \NabuXUI\NabuXUI::icon('alert-circle') !!}
                    {{ $say('That address looks incomplete — the domain is missing a tail.', 'این نشانی ناقص به نظر می‌رسد — دامنه دُمش را گم کرده.') }}
                </p>

                <div class="gin-field">
                    {!! \NabuXUI\NabuXUI::icon('search') !!}
                    <input type="search" x-model="q" autocomplete="off"
                        :placeholder="isFa ? 'جست‌وجو میان ' + n(6) + ' یکپارچه‌سازی…' : 'Search all ' + n(6) + ' integrations…'"
                        aria-label="{{ $say('Search integrations', 'جست‌وجوی یکپارچه‌سازی‌ها') }}">
                    <button type="button" class="gin-clear" x-show="q.length > 0" x-cloak
                        x-on:click="q = ''"
                        :aria-label="isFa ? 'پاک‌کردن جست‌وجو' : 'Clear the search'">
                        {!! \NabuXUI\NabuXUI::icon('x') !!}
                    </button>
                </div>
                <ul class="gin-hits" :aria-label="isFa ? 'نتایج' : 'Results'">
                    @foreach ([['Slack', 'message', 'Connected', 'وصل است'], ['Linear', 'layers', 'Connected', 'وصل است'], ['Datadog', 'chart', 'Available', 'در دسترس'], ['GitHub', 'folder', 'Connected', 'وصل است'], ['Figma', 'image', 'Available', 'در دسترس'], ['Sentry', 'shield', 'Available', 'در دسترس']] as [$app, $ic, $tagEn, $tagFa])
                        <li class="gin-hit" x-show="matches.includes('{{ $app }}')">
                            {!! \NabuXUI\NabuXUI::icon($ic) !!}
                            <span>{{ $app }}</span>
                            <small>{{ $say($tagEn, $tagFa) }}</small>
                        </li>
                    @endforeach
                    <li class="gin-note" x-show="matches.length === 0" x-cloak style="padding-inline-start: .65rem">
                        {{ $say('Nothing matched — try “data” or “git”.', 'چیزی پیدا نشد — «data» یا «git» را امتحان کن.') }}
                    </li>
                </ul>
                <p class="gin-count" x-text="countLine"></p>
            </div>
        </div>
    </section>

    <section class="pg-box" style="justify-items: center">
        <div class="gin-spec" style="inline-size: 100%">
            <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
                <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('The four states, one skin', 'چهار حالت، یک پوست') }}</h3>
                <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
                    {{ $say('Idle, focused, error and success — the halo follows the state’s colour, and everything below sits on the same glass recipe.', 'آرام، متمرکز، خطا و موفقیت — هاله از رنگ حالت پیروی می‌کند و همه‌چیز زیرین از همان دستور شیشه است.') }}
                </p>
            </div>
            <div class="gin-spec-row">
                <div class="gin-spec-cell">
                    <div class="gin-field">{!! \NabuXUI\NabuXUI::icon('mail') !!}<input type="text" readonly placeholder="{{ $say('mia@northwind.dev', 'mia@northwind.dev') }}"></div>
                    <small>idle</small>
                </div>
                <div class="gin-spec-cell">
                    <div class="gin-field" data-force="focus">{!! \NabuXUI\NabuXUI::icon('search') !!}<input type="text" readonly value="datadog"></div>
                    <small>focus</small>
                </div>
                <div class="gin-spec-cell">
                    <div class="gin-field" data-state="error">{!! \NabuXUI\NabuXUI::icon('mail') !!}<input type="text" readonly value="jonas@arcadia"></div>
                    <small>error</small>
                </div>
                <div class="gin-spec-cell">
                    <div class="gin-field" data-state="success">{!! \NabuXUI\NabuXUI::icon('mail') !!}<input type="text" readonly value="lena@vitral.io"></div>
                    <small>success</small>
                </div>
            </div>
        </div>
    </section>
</div>
