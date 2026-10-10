{{--
    Atlassian's comment family as the conversation of a Jira issue: round
    avatars with initials, linked authors and quiet timestamps, a body with a
    working @mention, a heart that really counts, an inline reply composer
    that appends nested replies — plus the compact and resolved variants.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
    .atcm-root {
        --atcm-blue: #0052CC; --atcm-ink: #172B4D; --atcm-ink-strong: #091E42; --atcm-muted: #626F86;
        --atcm-subtle: #44546F; --atcm-border: #DFE1E6; --atcm-hover: #F1F2F4; --atcm-surface: #FFFFFF;
        --atcm-av1-bg: #F1F2F4; --atcm-av1-ink: #44546F;
        --atcm-av2-bg: #EAE6FF; --atcm-av2-ink: #5E4DB2;
        --atcm-av3-bg: #E9F2FF; --atcm-av3-ink: #0055CC;
        --atcm-av4-bg: #DCFFF1; --atcm-av4-ink: #1F845A;
        --atcm-ok: #1F845A; --atcm-ok-bg: #DCFFF1;
        font-family: 'Inter', 'Vazirmatn', sans-serif; color: var(--atcm-ink);
    }
    html[data-theme="dark"] .atcm-root {
        --atcm-blue: #388BFF; --atcm-ink: #C7D1DB; --atcm-ink-strong: #E4EAF0; --atcm-muted: #8590A2;
        --atcm-subtle: #A9B8C4; --atcm-border: #2C3136; --atcm-hover: #1D2125; --atcm-surface: #161A1D;
        --atcm-av1-bg: #2C3136; --atcm-av1-ink: #A9B8C4;
        --atcm-av2-bg: #2A2745; --atcm-av2-ink: #9F8FEF;
        --atcm-av3-bg: #17263B; --atcm-av3-ink: #579DFF;
        --atcm-av4-bg: #1C322A; --atcm-av4-ink: #4BCE97;
        --atcm-ok: #4BCE97; --atcm-ok-bg: #1C322A;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .atcm-root {
            --atcm-blue: #388BFF; --atcm-ink: #C7D1DB; --atcm-ink-strong: #E4EAF0; --atcm-muted: #8590A2;
            --atcm-subtle: #A9B8C4; --atcm-border: #2C3136; --atcm-hover: #1D2125; --atcm-surface: #161A1D;
            --atcm-av1-bg: #2C3136; --atcm-av1-ink: #A9B8C4;
            --atcm-av2-bg: #2A2745; --atcm-av2-ink: #9F8FEF;
            --atcm-av3-bg: #17263B; --atcm-av3-ink: #579DFF;
            --atcm-av4-bg: #1C322A; --atcm-av4-ink: #4BCE97;
            --atcm-ok: #4BCE97; --atcm-ok-bg: #1C322A;
        }
    }
    .atcm-root :focus-visible { outline: 2px solid var(--atcm-blue); outline-offset: 2px; }

    .atcm-thread { inline-size: min(100%, 34rem); margin-inline: auto; border: 1px solid var(--atcm-border); border-radius: 6px;
                   background: var(--atcm-surface); padding: 1rem 1.1rem; display: grid; gap: .25rem; }
    .atcm-thread > h4 { margin: 0 0 .5rem; font: 500 1rem/1.3 "Charlie Display", Inter, Vazirmatn, system-ui; color: var(--atcm-ink-strong);
                        display: flex; align-items: center; gap: .5rem; flex-wrap: wrap; }
    .atcm-thread > h4 small { font: 600 .7rem Inter, Vazirmatn, system-ui; color: var(--atcm-blue); }
    .atcm { display: flex; gap: .65rem; padding-block: .45rem; min-inline-size: 0; }
    .atcm-av { flex: none; display: grid; place-items: center; inline-size: 2rem; aspect-ratio: 1; border-radius: 50%;
               font: 600 .72rem Inter, Vazirmatn, system-ui; }
    .atcm[data-av='1'] > .atcm-av { background: var(--atcm-av1-bg); color: var(--atcm-av1-ink); }
    .atcm[data-av='2'] > .atcm-av { background: var(--atcm-av2-bg); color: var(--atcm-av2-ink); }
    .atcm[data-av='3'] > .atcm-av { background: var(--atcm-av3-bg); color: var(--atcm-av3-ink); }
    .atcm[data-av='4'] > .atcm-av { background: var(--atcm-av4-bg); color: var(--atcm-av4-ink); }
    .atcm-main { flex: 1 1 auto; min-inline-size: 0; }
    .atcm-head { display: flex; align-items: baseline; gap: .5rem; flex-wrap: wrap; }
    .atcm-head a { font: 600 .82rem Inter, Vazirmatn, system-ui; color: var(--atcm-blue); text-decoration: none; }
    .atcm-head a:hover { text-decoration: underline; }
    .atcm-head time, .atcm-head .edited { font: 400 .7rem Inter, Vazirmatn, system-ui; color: var(--atcm-muted); }
    .atcm-body { margin: .2rem 0 .1rem; font: 400 .82rem/1.65 Inter, Vazirmatn, system-ui; color: var(--atcm-ink); overflow-wrap: anywhere; }
    .atcm-body a { color: var(--atcm-blue); font-weight: 500; text-decoration: none; }
    .atcm-body a:hover { text-decoration: underline; }
    .atcm-body code { padding: .05rem .3rem; border-radius: 3px; background: var(--atcm-hover); font: 500 .74rem "JetBrains Mono", ui-monospace, monospace; color: var(--atcm-ink-strong); }
    .atcm-actions { display: flex; align-items: center; gap: .25rem; margin-block-start: .15rem; }
    .atcm-actions > button { display: inline-flex; align-items: center; gap: .3rem; border: none; background: none; padding: .25rem .45rem;
                             border-radius: 3px; cursor: pointer; font: 600 .72rem Inter, Vazirmatn, system-ui; color: var(--atcm-muted);
                             transition: background .15s ease, color .15s ease; }
    .atcm-actions > button:hover { background: var(--atcm-hover); color: var(--atcm-blue); }
    .atcm-actions svg { inline-size: .85rem; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
    .atcm-actions button[aria-pressed='true'] { color: var(--atcm-blue); }
    .atcm-actions button[aria-pressed='true'] svg { fill: color-mix(in srgb, currentColor 30%, transparent); }

    .atcm-replies { margin-inline-start: 1rem; padding-inline-start: .85rem; border-inline-start: 2px solid var(--atcm-border);
                    display: grid; gap: .1rem; }
    .atcm-form { display: flex; gap: .65rem; padding-block: .55rem; }
    .atcm-form textarea { flex: 1 1 auto; min-inline-size: 0; box-sizing: border-box; min-block-size: 2.5rem; resize: vertical;
                          padding: .5rem .6rem; border: 1px solid var(--atcm-border); border-radius: 4px; background: var(--atcm-surface);
                          color: var(--atcm-ink-strong); font: 400 .8rem/1.55 Inter, Vazirmatn, system-ui; }
    .atcm-form-btns { display: flex; gap: .4rem; align-items: flex-start; }
    .atcm-send { block-size: 1.85rem; padding-inline: .75rem; border: none; border-radius: 3px; cursor: pointer;
                 background: var(--atcm-blue); color: #fff; font: 500 .76rem/1 Inter, Vazirmatn, system-ui; }
    .atcm-send:disabled { cursor: not-allowed; background: color-mix(in srgb, var(--atcm-subtle) 30%, transparent); }
    .atcm-cancel { border: none; background: none; border-radius: 3px; cursor: pointer; padding-inline: .5rem;
                   font: 500 .76rem Inter, Vazirmatn, system-ui; color: var(--atcm-muted); }
    .atcm-cancel:hover { background: var(--atcm-hover); color: var(--atcm-ink); }

    .atcm-variants { display: grid; gap: .8rem 2rem; grid-template-columns: repeat(auto-fit, minmax(min(100%, 17rem), 1fr));
                     inline-size: 100%; max-inline-size: 42rem; }
    .atcm-cell { border: 1px solid var(--atcm-border); border-radius: 6px; background: var(--atcm-surface); padding: .4rem .75rem; }
    .atcm-cell > small { display: block; padding: .35rem .2rem .1rem; font: 500 .7rem/1.4 Inter, Vazirmatn, system-ui; color: var(--atcm-muted); letter-spacing: .3px; }
    .atcm.compact { padding-block: .35rem; }
    .atcm.compact > .atcm-av { inline-size: 1.5rem; font-size: .6rem; }
    .atcm.compact .atcm-body { display: inline; margin: 0; }
    .atcm.resolved .atcm-badge { display: inline-flex; align-items: center; gap: .25rem; padding: 1px 7px; border-radius: 3px;
                                 background: var(--atcm-ok-bg); color: var(--atcm-ok); font: 700 .66rem/1.6 Inter, Vazirmatn, system-ui; }
    .atcm.resolved .atcm-badge svg { inline-size: .7rem; fill: none; stroke: currentColor; stroke-width: 2.4; stroke-linecap: round; stroke-linejoin: round; }
    @media (prefers-reduced-motion: reduce) {
        .atcm-root * { animation-duration: .01ms !important; transition-duration: .01ms !important; }
    }

    /* Pin the page's «Important props» rows for full-page captures — ships
       with this partial only, so it stays scoped to this demo page. */
    .nx-data-table[data-nx-reveal]:not([data-nx-revealed]) tbody tr { opacity: 1; translate: none; }
</style>

<div class="atcm-root"
    x-data="{
        liked: { c1: false, r1: false, c2: false },
        base: { c1: 3, r1: 1, c2: 0 },
        replyTo: null,
        draft: '',
        added: [],
        likes(k) { return this.base[k] + (this.liked[k] ? 1 : 0); },
        send() {
            const body = this.draft.trim();
            if (!body) return;
            this.added.push({ to: this.replyTo, body });
            this.replyTo = null;
            this.draft = '';
        },
    }">
    <section class="pg-box" style="justify-items: center">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Where the issue actually gets decided', 'جایی که مساله واقعاً تصمیم می‌گیرد') }}</h3>
            <p style="margin: 0; max-width: 50ch; color: var(--nx-text-muted)">
                {{ $say('Hearts count for real, Reply opens the inline composer, and what you send lands as a threaded reply — the whole conversation loop, alive.', 'قلب‌ها واقعی می‌شمارند، «پاسخ» همان‌جا نوشتار را باز می‌کند و چیزی که بفرستید به‌شکل پاسخ رشته‌شده می‌نشیند — کل حلقهٔ گفت‌وگو، زنده.') }}
            </p>
        </div>

        <div class="atcm-thread">
            <h4><small>NABU-241</small> {{ $say('Payment webhook', 'وب‌هوک پرداخت') }}</h4>

            <article class="atcm" data-av="1">
                <span class="atcm-av" aria-hidden="true">{{ $fa ? 'م‌ر' : 'MR' }}</span>
                <div class="atcm-main">
                    <header class="atcm-head">
                        <a href="#" x-on:click.prevent>{{ $say('Maryam Rezaei', 'مریم رضایی') }}</a>
                        <time>{{ $fa ? '۲ ساعت پیش' : '2 hours ago' }}</time>
                    </header>
                    <div class="atcm-body">
                        {{ $say('Label set to in-progress. ', 'برچسب را گذاشتم روی «در جریان». ') }}<a href="#" x-on:click.prevent>{{ '@' . ($fa ? 'علی' : 'Ali') }}</a>{{ $say(' move it to review once the checkout check passes — the secret is now ', ' بعد از سبز شدن بررسیِ پرداخت جابه‌جاش کن — رمز تازه این است: ') }}<code>nabu_live_9f2c…</code>
                    </div>
                    <footer class="atcm-actions">
                        <button type="button" x-on:click="replyTo = (replyTo === 'c1' ? null : 'c1')" :aria-expanded="replyTo === 'c1' ? 'true' : 'false'">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 14L4 9l5-5"/><path d="M4 9h9a7 7 0 0 1 7 7v4"/></svg>
                            {{ $say('Reply', 'پاسخ') }}
                        </button>
                        <button type="button" :aria-pressed="liked.c1 ? 'true' : 'false'" x-on:click="liked.c1 = !liked.c1" aria-label="{{ $say('Like', 'پسندیدن') }}">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 20.5C7 16.5 3.5 13.4 3.5 9.7A4.4 4.4 0 0 1 12 7a4.4 4.4 0 0 1 8.5 2.7c0 3.7-3.5 6.8-8.5 10.8z"/></svg>
                            <span x-text="likes('c1')">{{ $fa ? '۳' : '3' }}</span>
                        </button>
                    </footer>

                    <div class="atcm-replies">
                        <article class="atcm" data-av="2">
                            <span class="atcm-av" aria-hidden="true">{{ $fa ? 'ع‌ک' : 'AK' }}</span>
                            <div class="atcm-main">
                                <header class="atcm-head">
                                    <a href="#" x-on:click.prevent>{{ $say('Ali Kazemi', 'علی کاظمی') }}</a>
                                    <time>{{ $fa ? '۴۵ دقیقه پیش' : '45 minutes ago' }}</time>
                                </header>
                                <div class="atcm-body">{{ $say('Checkout is green in staging — moving it now.', 'بررسی پرداخت در آزمایشی سبز است — همین حالا جابه‌جایش می‌کنم.') }}</div>
                                <footer class="atcm-actions">
                                    <button type="button" x-on:click="replyTo = (replyTo === 'r1' ? null : 'r1')" :aria-expanded="replyTo === 'r1' ? 'true' : 'false'">
                                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 14L4 9l5-5"/><path d="M4 9h9a7 7 0 0 1 7 7v4"/></svg>
                                        {{ $say('Reply', 'پاسخ') }}
                                    </button>
                                    <button type="button" :aria-pressed="liked.r1 ? 'true' : 'false'" x-on:click="liked.r1 = !liked.r1" aria-label="{{ $say('Like', 'پسندیدن') }}">
                                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 20.5C7 16.5 3.5 13.4 3.5 9.7A4.4 4.4 0 0 1 12 7a4.4 4.4 0 0 1 8.5 2.7c0 3.7-3.5 6.8-8.5 10.8z"/></svg>
                                        <span x-text="likes('r1')">{{ $fa ? '۱' : '1' }}</span>
                                    </button>
                                </footer>
                                <template x-for="a in added.filter(x => x.to === 'r1')">
                                    <div class="atcm" data-av="4" style="padding-block: .3rem">
                                        <span class="atcm-av" aria-hidden="true">{{ $fa ? 'ش' : 'Y' }}</span>
                                        <div class="atcm-main">
                                            <header class="atcm-head"><b style="font: 600 .82rem Inter, Vazirmatn, system-ui; color: var(--atcm-ink-strong)">{{ $say('You', 'شما') }}</b><time>{{ $say('just now', 'همین حالا') }}</time></header>
                                            <div class="atcm-body" x-text="a.body"></div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </article>
                        <template x-for="a in added.filter(x => x.to === 'c1')">
                            <div class="atcm" data-av="4">
                                <span class="atcm-av" aria-hidden="true">{{ $fa ? 'ش' : 'Y' }}</span>
                                <div class="atcm-main">
                                    <header class="atcm-head"><b style="font: 600 .82rem Inter, Vazirmatn, system-ui; color: var(--atcm-ink-strong)">{{ $say('You', 'شما') }}</b><time>{{ $say('just now', 'همین حالا') }}</time></header>
                                    <div class="atcm-body" x-text="a.body"></div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </article>

            <article class="atcm" data-av="3">
                <span class="atcm-av" aria-hidden="true">{{ $fa ? 'س‌م' : 'SM' }}</span>
                <div class="atcm-main">
                    <header class="atcm-head">
                        <a href="#" x-on:click.prevent>{{ $say('Sara Moradi', 'سارا مرادی') }}</a>
                        <time>{{ $fa ? '۲۰ دقیقه پیش' : '20 minutes ago' }}</time>
                        <span class="edited">{{ $say('· edited', '· ویرایش‌شده') }}</span>
                    </header>
                    <div class="atcm-body">{{ $say('Docs updated: the retry is 5 attempts with a 30s backoff, not 3.', 'مستندات را به‌روز کردم: تلاش دوباره ۵ بار با فاصلهٔ ۳۰ ثانیه است، نه ۳ بار.') }}</div>
                    <footer class="atcm-actions">
                        <button type="button" x-on:click="replyTo = (replyTo === 'c2' ? null : 'c2')" :aria-expanded="replyTo === 'c2' ? 'true' : 'false'">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 14L4 9l5-5"/><path d="M4 9h9a7 7 0 0 1 7 7v4"/></svg>
                            {{ $say('Reply', 'پاسخ') }}
                        </button>
                        <button type="button" :aria-pressed="liked.c2 ? 'true' : 'false'" x-on:click="liked.c2 = !liked.c2" aria-label="{{ $say('Like', 'پسندیدن') }}">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 20.5C7 16.5 3.5 13.4 3.5 9.7A4.4 4.4 0 0 1 12 7a4.4 4.4 0 0 1 8.5 2.7c0 3.7-3.5 6.8-8.5 10.8z"/></svg>
                            <span x-text="likes('c2')">{{ $fa ? '۰' : '0' }}</span>
                        </button>
                    </footer>
                    <template x-for="a in added.filter(x => x.to === 'c2')">
                        <div class="atcm" data-av="4" style="margin-inline-start: 1rem; padding-inline-start: .85rem; border-inline-start: 2px solid var(--atcm-border)">
                            <span class="atcm-av" aria-hidden="true">{{ $fa ? 'ش' : 'Y' }}</span>
                            <div class="atcm-main">
                                <header class="atcm-head"><b style="font: 600 .82rem Inter, Vazirmatn, system-ui; color: var(--atcm-ink-strong)">{{ $say('You', 'شما') }}</b><time>{{ $say('just now', 'همین حالا') }}</time></header>
                                <div class="atcm-body" x-text="a.body"></div>
                            </div>
                        </div>
                    </template>
                </div>
            </article>

            <template x-if="replyTo">
                <div class="atcm-form">
                    <span class="atcm-av" style="background: var(--atcm-av4-bg); color: var(--atcm-av4-ink)" aria-hidden="true">{{ $fa ? 'ش' : 'Y' }}</span>
                    <textarea x-model="draft" rows="2" x-init="$nextTick(() => $el.focus())"
                              placeholder="{{ $say('Write a reply…', 'پاسخی بنویسید…') }}"
                              aria-label="{{ $say('Write a reply', 'نوشتن پاسخ') }}"></textarea>
                    <div class="atcm-form-btns">
                        <button type="button" class="atcm-send" x-on:click="send()" :disabled="!draft.trim()">{{ $say('Send', 'ارسال') }}</button>
                        <button type="button" class="atcm-cancel" x-on:click="replyTo = null; draft = ''">{{ $say('Cancel', 'لغو') }}</button>
                    </div>
                </div>
            </template>
        </div>
    </section>
</div>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('Compact and resolved', 'جمع‌وجور و حل‌شده') }}</h3>
        <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
            {{ $say('The same anatomy at half the height for dense threads — and the resolved badge that closes a discussion without deleting it.', 'همان کالبدشناسی با نصف ارتفاع برای رشته‌های متراکم — و نشانِ «حل شد» که گفت‌وگو را بدون پاک کردنش می‌بندد.') }}
        </p>
    </div>
    <div class="atcm-root" style="inline-size: 100%">
        <div class="atcm-variants">
            <div class="atcm-cell">
                <small>compact</small>
                <article class="atcm compact" data-av="3">
                    <span class="atcm-av" aria-hidden="true">{{ $fa ? 'س‌م' : 'SM' }}</span>
                    <div class="atcm-main">
                        <header class="atcm-head">
                            <a href="#" x-on:click.prevent>{{ $say('Sara Moradi', 'سارا مرادی') }}</a>
                            <time>{{ $fa ? '۲۰ دقیقه پیش' : '20 minutes ago' }}</time>
                        </header>
                        <div class="atcm-body">{{ $say('Docs updated — retries are 5×30s now.', 'مستندات به‌روز شد — تلاش دوباره حالا ۵ بار با ۳۰ ثانیه است.') }}</div>
                    </div>
                </article>
            </div>
            <div class="atcm-cell">
                <small>resolved</small>
                <article class="atcm resolved" data-av="2">
                    <span class="atcm-av" aria-hidden="true">{{ $fa ? 'ع‌ک' : 'AK' }}</span>
                    <div class="atcm-main">
                        <header class="atcm-head">
                            <a href="#" x-on:click.prevent>{{ $say('Ali Kazemi', 'علی کاظمی') }}</a>
                            <time>{{ $fa ? '۱ ساعت پیش' : '1 hour ago' }}</time>
                            <span class="atcm-badge"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 13l4 4L19 7"/></svg>{{ $say('Resolved', 'حل شد') }}</span>
                        </header>
                        <div class="atcm-body">{{ $say('Rotated the secret on staging and production — keeping this thread for the audit log.', 'رمز را در آزمایشی و عملیاتی عوض کردم — این رشته را برای گزارش حسابرسی نگه می‌دارم.') }}</div>
                    </div>
                </article>
            </div>
        </div>
    </div>
</section>
