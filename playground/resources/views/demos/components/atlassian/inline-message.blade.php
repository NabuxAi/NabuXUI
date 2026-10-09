{{--
    Atlassian inline messages living inside a real invite form: the email
    field validates as you type — error for a malformed address, warning for
    an existing member, success after the invite goes out — plus a quiet info
    note on the role. All five appearances line up underneath.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp
<style>
    .atim-root {
        --atim-blue: #0052CC; --atim-ink: #172B4D; --atim-ink-strong: #091E42; --atim-muted: #626F86;
        --atim-subtle: #44546F; --atim-border: #DFE1E6; --atim-hover: #F1F2F4; --atim-surface: #FFFFFF;
        --atim-info: #0055CC; --atim-ok: #1F845A; --atim-warn: #A54800; --atim-err: #CA3521; --atim-disc: #5E4DB2;
        font-family: Inter, system-ui, sans-serif; color: var(--atim-ink);
    }
    html[data-theme="dark"] .atim-root {
        --atim-blue: #388BFF; --atim-ink: #C7D1DB; --atim-ink-strong: #E4EAF0; --atim-muted: #8590A2;
        --atim-subtle: #A9B8C4; --atim-border: #2C3136; --atim-hover: #1D2125; --atim-surface: #161A1D;
        --atim-info: #579DFF; --atim-ok: #4BCE97; --atim-warn: #F5CD47; --atim-err: #F87168; --atim-disc: #9F8FEF;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .atim-root {
            --atim-blue: #388BFF; --atim-ink: #C7D1DB; --atim-ink-strong: #E4EAF0; --atim-muted: #8590A2;
            --atim-subtle: #A9B8C4; --atim-border: #2C3136; --atim-hover: #1D2125; --atim-surface: #161A1D;
            --atim-info: #579DFF; --atim-ok: #4BCE97; --atim-warn: #F5CD47; --atim-err: #F87168; --atim-disc: #9F8FEF;
        }
    }
    .atim-root :focus-visible { outline: 2px solid var(--atim-blue); outline-offset: 2px; }

    .atim-panel { inline-size: min(100%, 30rem); margin-inline: auto; border: 1px solid var(--atim-border);
                  border-radius: 6px; background: var(--atim-surface); padding: 1.25rem; display: grid; gap: 1rem; }
    .atim-panel > h4 { margin: 0; font: 500 1.15rem/1.3 "Charlie Display", Inter, system-ui; color: var(--atim-ink-strong); }
    .atim-panel > h4 small { display: block; margin-block-start: .2rem; font: 400 .76rem/1.5 Inter, system-ui; color: var(--atim-muted); }
    .atim-field label { display: block; margin-block-end: .3rem; font: 500 .78rem Inter, system-ui; color: var(--atim-subtle); }
    .atim-field input { inline-size: 100%; box-sizing: border-box; block-size: 2.25rem; padding-inline: .65rem; border: 1px solid var(--atim-border);
                        border-radius: 4px; background: var(--atim-surface); color: var(--atim-ink-strong); font: 400 .85rem Inter, system-ui; transition: border-color .15s ease; }
    .atim-field input:hover { background: var(--atim-hover); }
    .atim-field input[data-state='error'] { border-color: #CA3521; }
    .atim-field input[data-state='error']:focus-visible { outline-color: #CA3521; }
    .atim-msg { display: inline-flex; gap: .5rem; align-items: flex-start; max-inline-size: 100%;
                animation: atim-in .2s ease; }
    @keyframes atim-in { from { opacity: 0; translate: 0 3px; } to { opacity: 1; translate: 0 0; } }
    .atim-msg svg { flex: none; inline-size: 1rem; block-size: 1rem; margin-block-start: .1rem; fill: none;
                    stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
    .atim-msg[data-tone='info'] svg { stroke: var(--atim-info); }
    .atim-msg[data-tone='confirmation'] svg { stroke: var(--atim-ok); }
    .atim-msg[data-tone='warning'] svg { stroke: var(--atim-warn); }
    .atim-msg[data-tone='error'] svg { stroke: var(--atim-err); }
    .atim-msg[data-tone='discovery'] svg { stroke: var(--atim-disc); }
    .atim-msg b { display: block; font: 600 .78rem/1.5 Inter, system-ui; color: var(--atim-ink-strong); }
    .atim-msg small { display: block; font: 400 .74rem/1.5 Inter, system-ui; color: var(--atim-muted); }
    .atim-row { display: flex; flex-wrap: wrap; align-items: center; gap: .6rem; }
    .atim-btn { block-size: 2rem; padding-inline: .8rem; border: none; border-radius: 3px; cursor: pointer;
                font: 500 .8rem/1 Inter, system-ui; color: #fff; background: var(--atim-blue); transition: background .15s ease; }
    .atim-btn:hover { background: color-mix(in srgb, var(--atim-blue) 88%, black); }
    .atim-btn:disabled { cursor: not-allowed; background: color-mix(in srgb, var(--atim-subtle) 30%, transparent); }
    .atim-chips { display: flex; flex-wrap: wrap; gap: .35rem; }
    .atim-chip { display: inline-flex; align-items: center; gap: .4rem; padding: .25rem .55rem; border-radius: 3px;
                 background: var(--atim-hover); font: 500 .74rem/1.4 Inter, system-ui; color: var(--atim-subtle); }
    .atim-chip i { inline-size: .55rem; aspect-ratio: 1; border-radius: 50%; background: var(--atim-blue); }

    .atim-family { display: grid; gap: 1rem 2rem; grid-template-columns: repeat(auto-fit, minmax(min(100%, 14rem), 1fr));
                   inline-size: 100%; max-inline-size: 42rem; justify-items: start; }
    .atim-cell { display: grid; gap: .35rem; min-inline-size: 0; }
    .atim-cell > small { font: 500 .7rem/1.4 Inter, system-ui; color: var(--atim-muted); letter-spacing: .3px; }
    @media (prefers-reduced-motion: reduce) {
        .atim-root * { animation-duration: .01ms !important; transition-duration: .01ms !important; }
    }

    /* Pin the page's «Important props» rows for full-page captures — ships
       with this partial only, so it stays scoped to this demo page. */
    .nx-data-table[data-nx-reveal]:not([data-nx-revealed]) tbody tr { opacity: 1; translate: none; }
</style>

<div class="atim-root"
    x-data="{
        email: '',
        sent: null,
        existing: @js(['ali@nabu.example', 'sara@nabu.example']),
        state() {
            const v = this.email.trim().toLowerCase();
            if (!v) return 'idle';
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v)) return 'error';
            if (this.existing.includes(v)) return 'warn';
            return 'ok';
        },
        send() {
            if (this.state() !== 'ok') return;
            this.sent = this.email.trim().toLowerCase();
            this.email = '';
        },
    }">
    <section class="pg-box" style="justify-items: center">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The explanation at the exact spot', 'توضیح، دقیقاً سر جای اتفاق') }}</h3>
            <p style="margin: 0; max-width: 50ch; color: var(--nx-text-muted)">
                {{ $say('Type into the invite field — a malformed address raises the error, a teammate who is already in raises the warning, and a valid send ends in the green confirmation.', 'در فیلد دعوت تایپ کنید — نشانی بدفرم خطا می‌آورد، همکارِ حاضر هشدار می‌آورد و ارسالِ معتبر به تأیید سبز می‌رسد.') }}
            </p>
        </div>

        <div class="atim-panel">
            <h4>{{ $say('Invite to Nabu platform', 'دعوت به نابو پلتفرم') }}
                <small>{{ $say('Teammates get an email and land on the board directly.', 'همکاران ایمیلی می‌گیرند و مستقیم روی تخته می‌افتند.') }}</small>
            </h4>
            <div class="atim-field">
                <label for="atim-email">{{ $say('Email address', 'نشانی ایمیل') }}</label>
                <input id="atim-email" type="email" x-model="email" autocomplete="off" spellcheck="false"
                       placeholder="{{ $say('name@company.com', 'name@company.com') }}"
                       :data-state="state() === 'error' ? 'error' : null"
                       :aria-invalid="state() === 'error' ? 'true' : 'false'"
                       aria-describedby="atim-live">
            </div>
            <div id="atim-live" aria-live="polite" style="display: grid; gap: .35rem">
                <template x-if="state() === 'error'">
                    <span class="atim-msg" data-tone="error">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16.5v.01"/></svg>
                        <span><b>{{ $say('That does not look like an email address', 'این شبیه نشانی ایمیل نیست') }}</b><small>{{ $say('Check the @ sign and the domain, then try again.', 'علامت @ و دامنه را بررسی کنید و دوباره امتحان کنید.') }}</small></span>
                    </span>
                </template>
                <template x-if="state() === 'warn'">
                    <span class="atim-msg" data-tone="warning">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3L2.5 20h19z" stroke-linejoin="round"/><path d="M12 10v4M12 17.2v.01"/></svg>
                        <span><b>{{ $say('This teammate is already on the board', 'این همکار از قبل روی تخته است') }}</b><small>{{ $say('Invites are skipped for existing members — pick someone new.', 'برای اعضای فعلی دعوتی فرستاده نمی‌شود — کسی تازه انتخاب کنید.') }}</small></span>
                    </span>
                </template>
                <template x-if="sent">
                    <span class="atim-msg" data-tone="confirmation">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M8.5 12.5l2.4 2.4 4.6-5.3"/></svg>
                        <span><b>{{ $say('Invite sent to', 'دعوت‌نامه رفت به') }} <span x-text="sent"></span></b><small>{{ $say('They will appear in the member list within a minute.', 'تا یک دقیقهٔ دیگر در فهرست اعضا دیده می‌شود.') }}</small></span>
                    </span>
                </template>
            </div>
            <div class="atim-msg" data-tone="info">
                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 11v5M12 8v.01"/></svg>
                <span><b>{{ $say('New members join as developers', 'اعضای تازه به‌عنوان توسعه‌دهنده می‌آیند') }}</b><small>{{ $say('The role stays changeable per member, any time.', 'نقش هر عضو هر زمان قابل تغییر می‌ماند.') }}</small></span>
            </div>
            <div class="atim-row">
                <button type="button" class="atim-btn" x-on:click="send()" :disabled="state() !== 'ok'">{{ $say('Send invite', 'ارسال دعوت') }}</button>
                <span style="font: 400 .74rem/1.4 Inter, system-ui; color: var(--atim-muted)">{{ $fa ? '۳ عضو فعلی: علی، سارا، مریم' : '3 current members: Ali, Sara, Maryam' }}</span>
            </div>
            <div class="atim-chips" aria-label="{{ $say('Try these addresses', 'این نشانی‌ها را امتحان کنید') }}">
                <span class="atim-chip"><i aria-hidden="true"></i>hosein@nabu.example</span>
                <span class="atim-chip"><i aria-hidden="true"></i>sara@nabu.example</span>
                <span class="atim-chip"><i aria-hidden="true"></i>{{ $fa ? 'بدون-دامنه@' : 'no-domain@' }}</span>
            </div>
        </div>
    </section>
</div>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('All five appearances', 'هر پنج ظاهر رسمی') }}</h3>
        <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
            {{ $say('Only the icon colour changes — the icon itself stays round, the text stays one short line, the message stays where the reader is.', 'فقط رنگ آیکن عوض می‌شود — خود آیکن گرد می‌ماند، متن یک خط کوتاه می‌ماند و پیام همان‌جا که خواننده است می‌ماند.') }}
        </p>
    </div>
    <div class="atim-root" style="inline-size: 100%">
        <div class="atim-family">
            @foreach ([['info', 'Info', 'اطلاع', 'Board cards now support keyboard drag.', 'کارت‌های تخته حالا جابه‌جایی با کیبورد دارند.'], ['confirmation', 'Confirmation', 'تأیید', 'Backups finished at 03:00 — 12.4 GB kept.', 'پشتیبان‌ها ساعت ۰۳:۰۰ تمام شد — ۱۲٫۴ گیگ نگه‌داشته شد.'], ['warning', 'Warning', 'هشدار', 'The last two builds ran without tests.', 'دو بیلد آخر بدون تست اجرا شده‌اند.'], ['error', 'Error', 'خطا', 'The deploy to production failed at step 4.', 'انتشار به عملیات در گام ۴ شکست خورد.'], ['discovery', 'Discovery', 'کشف', 'Automation rules just landed in your workspace.', 'قواعد اتوماسیون همین حالا به ورک‌اسپیس شما رسید.']] as [$tone, $en, $faT, $enBody, $faBody])
                <div class="atim-cell">
                    <span class="atim-msg" data-tone="{{ $tone }}">
                        @if ($tone === 'info')
                            <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 11v5M12 8v.01"/></svg>
                        @elseif ($tone === 'confirmation')
                            <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M8.5 12.5l2.4 2.4 4.6-5.3"/></svg>
                        @elseif ($tone === 'warning')
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3L2.5 20h19z" stroke-linejoin="round"/><path d="M12 10v4M12 17.2v.01"/></svg>
                        @elseif ($tone === 'error')
                            <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M15 9l-6 6M9 9l6 6"/></svg>
                        @else
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3v3M12 18v3M3 12h3M18 12h3M5.6 5.6l2.1 2.1M16.3 16.3l2.1 2.1M18.4 5.6l-2.1 2.1M7.7 16.3l-2.1 2.1"/><circle cx="12" cy="12" r="3.4"/></svg>
                        @endif
                        <span><b>{{ $say($en, $faT) }}</b><small>{{ $say($enBody, $faBody) }}</small></span>
                    </span>
                    <small>{{ $tone }}</small>
                </div>
            @endforeach
        </div>
    </div>
</section>
