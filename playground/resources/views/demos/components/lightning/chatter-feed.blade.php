{{--
    The Chatter feed on a record page: the share-an-update publisher that
    opens its attach tools and Share button on focus, then a feed of posts
    with initials avatars, time stamps, like counters that untoggle, and
    comment threads that grow through the inline composer. Persian numerals
    in the fa locale; the specimen row shows the publisher states.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp
<style>
    .slf-root {
        --slf-blue: #0176D3; --slf-blue-soft: color-mix(in srgb, #0176D3 8%, #FFFFFF);
        --slf-green: #04844B; --slf-purple: #6739B7; --slf-orange: #FE9339;
        --slf-text: #181818; --slf-weak: #444444; --slf-muted: #706E6B;
        --slf-border: #DDDBDA; --slf-bg: #F3F3F3; --slf-card: #FFFFFF;
        font-family: 'Salesforce Sans', -apple-system, 'Segoe UI', Roboto, sans-serif;
        color: var(--slf-text);
        display: grid; gap: 1.5rem;
    }
    html[data-theme="dark"] .slf-root {
        --slf-blue: #0D9DDA; --slf-blue-soft: color-mix(in srgb, #0D9DDA 16%, #232323);
        --slf-green: #0E9E5B; --slf-purple: #A57DE8; --slf-orange: #FE9339;
        --slf-text: #F3F3F3; --slf-weak: #CECECE; --slf-muted: #A5A5A5;
        --slf-border: #474747; --slf-bg: #181818; --slf-card: #232323;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .slf-root {
            --slf-blue: #0D9DDA; --slf-blue-soft: color-mix(in srgb, #0D9DDA 16%, #232323);
            --slf-green: #0E9E5B; --slf-purple: #A57DE8; --slf-orange: #FE9339;
            --slf-text: #F3F3F3; --slf-weak: #CECECE; --slf-muted: #A5A5A5;
            --slf-border: #474747; --slf-bg: #181818; --slf-card: #232323;
        }
    }
    .slf-root, .slf-root *, .slf-root *::before, .slf-root *::after { box-sizing: border-box; }
    .slf-root :focus-visible { outline: 2px solid var(--slf-blue); outline-offset: 2px; }

    .slf-feed { inline-size: min(100%, 40rem); margin-inline: auto; display: grid; gap: .75rem; }
    .slf-publisher { border: 1px solid var(--slf-border); border-radius: .25rem; background: var(--slf-card); padding: .75rem .85rem;
                display: grid; gap: .6rem; }
    .slf-pub-row { display: flex; align-items: center; gap: .6rem; }
    .slf-ava { flex: none; display: grid; place-items: center; inline-size: 2rem; block-size: 2rem; border-radius: 50%;
               background: var(--slf-tint, var(--slf-blue)); color: #FFFFFF; font-size: .68rem; font-weight: 700; }
    .slf-pub-row input { flex: 1; min-inline-size: 0; border: 0; background: transparent; color: inherit; font: inherit; font-size: .88rem; }
    .slf-pub-row input::placeholder { color: var(--slf-muted); }
    .slf-pub-row input:focus-visible { outline: none; }
    .slf-tools { display: flex; align-items: center; gap: .25rem; }
    .slf-tool { display: grid; place-items: center; inline-size: 2rem; aspect-ratio: 1; border: 0; border-radius: .25rem;
                background: transparent; color: var(--slf-muted); cursor: pointer; transition: background .15s ease, color .15s ease; }
    .slf-tool:hover { background: var(--slf-blue-soft); color: var(--slf-blue); }
    .slf-share { margin-inline-start: auto; padding: .4rem 1.1rem; border: 1px solid var(--slf-blue); border-radius: .25rem;
                background: var(--slf-blue); color: #FFFFFF; font: inherit; font-size: .8rem; font-weight: 700; cursor: pointer;
                transition: opacity .15s ease, background .15s ease; }
    .slf-share:disabled { opacity: .45; cursor: not-allowed; }
    .slf-share:not(:disabled):hover { background: color-mix(in srgb, var(--slf-blue) 85%, #000); }
    .slf-post { border: 1px solid var(--slf-border); border-radius: .25rem; background: var(--slf-card); padding: .75rem .85rem;
                display: grid; gap: .55rem; }
    .slf-post-head { display: flex; align-items: center; gap: .6rem; }
    .slf-post-head b { font-size: .85rem; }
    .slf-post-head time { font-size: .74rem; color: var(--slf-muted); }
    .slf-post-head small { font-size: .74rem; color: var(--slf-muted); margin-inline-start: auto; }
    .slf-post p { margin: 0; font-size: .88rem; line-height: 1.6; }
    .slf-acts { display: flex; gap: .4rem; }
    .slf-act { display: inline-flex; align-items: center; gap: .35rem; padding: .3rem .7rem; border: 0; border-radius: .25rem;
               background: transparent; color: var(--slf-muted); font: inherit; font-size: .78rem; font-weight: 600; cursor: pointer;
               transition: background .15s ease, color .15s ease; }
    .slf-act:hover { background: var(--slf-blue-soft); color: var(--slf-blue); }
    .slf-act[aria-pressed="true"] { color: var(--slf-blue); }
    .slf-act .nx-icon { inline-size: 1em; block-size: 1em; }
    .slf-comments { border-block-start: 1px solid var(--slf-border); padding-block-start: .6rem; display: grid; gap: .55rem; }
    .slf-comment { display: flex; gap: .55rem; }
    .slf-comment .slf-ava { inline-size: 1.6rem; block-size: 1.6rem; font-size: .6rem; }
    .slf-comment-body { min-inline-size: 0; flex: 1; }
    .slf-comment-body b { font-size: .8rem; }
    .slf-comment-body time { font-size: .7rem; color: var(--slf-muted); margin-inline-start: .4rem; }
    .slf-comment-body p { margin: .1rem 0 0; font-size: .82rem; line-height: 1.55; }
    .slf-compose { display: flex; gap: .55rem; align-items: center; }
    .slf-compose .slf-ava { background: var(--slf-purple); }
    .slf-compose input { flex: 1; min-inline-size: 0; padding: .45rem .6rem; border: 1px solid var(--slf-border);
                border-radius: .25rem; background: var(--slf-card); color: inherit; font: inherit; font-size: .8rem; }
    .slf-empty { text-align: center; color: var(--slf-muted); font-size: .82rem; padding: .4rem 0 0; }

    .slf-spec { display: flex; flex-wrap: wrap; gap: 1.25rem; justify-content: center; align-items: flex-start; }
    .slf-spec-cell { display: grid; gap: .4rem; justify-items: center; }
    .slf-spec-cell > small { font-size: .72rem; color: var(--slf-muted); }
    .slf-spec-pub { inline-size: 13rem; }
    :where(.nx-js) .pg:has(.slf-root) .nx-data-table[data-nx-reveal] tbody tr { opacity: 1; translate: none; }
    @media (prefers-reduced-motion: reduce) {
        .slf-root * { transition-duration: .01ms !important; }
    }
</style>

<div class="slf-root"
    x-data="{
        draft: '',
        posts: [
            {
                who: { fa: 'سارا احمدی', en: 'Sara Ahmadi' }, ini: 'سا', inie: 'SA', tint: '#0176D3',
                time: { fa: '۲ ساعت پیش', en: '2 hours ago' }, meta: { fa: 'روی فرصت', en: 'on the opportunity' },
                body: { fa: 'پیش‌فاکتور بازبینی‌شده را برای آکمی فرستادم — منتظر پاسخ تیم خرید هستیم. ✅', en: 'Sent the revised quote to Acme — waiting on their purchasing team now. ✅' },
                likes: 3, liked: false,
                comments: [
                    { who: { fa: 'رضا کاظمی', en: 'Reza Kazemi' }, ini: 'رک', inie: 'RK', tint: '#6739B7',
                      time: { fa: '۱ ساعت پیش', en: '1 hour ago' },
                      body: { fa: 'جدول قیمت نهایی هم پیوست شد؛ نسخهٔ حقوقی فردا آماده است.', en: 'Attached the final pricing sheet; the legal copy lands tomorrow.' } },
                ],
            },
            {
                who: { fa: 'مریم رحیمی', en: 'Maryam Rahimi' }, ini: 'مر', inie: 'MR', tint: '#04844B',
                time: { fa: 'دیروز', en: 'yesterday' }, meta: { fa: 'روی فرصت', en: 'on the opportunity' },
                body: { fa: 'تأیید تخفیف از مدیر فروش گرفتم — تا سقف ۵٪ آزادیم.', en: 'Got the discount approved by the sales director — we are clear up to 5%.' },
                likes: 7, liked: true,
                comments: [],
            },
        ],
        openComments: [true, false],
        get t() { return document.documentElement.lang === 'fa' ? 'fa' : 'en' },
        num(n) { return this.t === 'fa' ? String(n).replace(/\d/g, d => '۰۱۲۳۴۵۶۷۸۹'[d]) : String(n) },
        who(p) { return this.t === 'fa' ? p.who.fa : p.who.en },
        when(p) { return this.t === 'fa' ? p.time.fa : p.time.en },
        meta(p) { return this.t === 'fa' ? p.meta.fa : p.meta.en },
        body(p) { return this.t === 'fa' ? p.body.fa : p.body.en },
        share() {
            const v = this.draft.trim();
            if (! v) return;
            this.posts.unshift({
                who: { fa: 'شما', en: 'You' }, ini: 'ش', inie: 'YO', tint: '#FE9339',
                time: { fa: 'همین حالا', en: 'just now' }, meta: { fa: 'روی فرصت', en: 'on the opportunity' },
                body: { fa: v, en: v }, likes: 0, liked: false, comments: [],
            });
            this.openComments.unshift(false);
            this.draft = '';
        },
        like(i) {
            const p = this.posts[i];
            p.liked = ! p.liked;
            p.likes += p.liked ? 1 : -1;
        },
        comment(i, input) {
            const v = input.value.trim();
            if (! v) return;
            this.posts[i].comments.push({
                who: { fa: 'شما', en: 'You' }, ini: 'ش', inie: 'YO', tint: '#FE9339',
                time: { fa: 'همین حالا', en: 'just now' }, body: { fa: v, en: v },
            });
            input.value = '';
        },
    }">
    <section class="pg-box" style="justify-items: center; inline-size: 100%">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Collaboration, right on the record', 'همکاری، دقیقاً روی رکورد') }}</h3>
            <p style="margin: 0; max-width: 46ch; color: var(--nx-text-muted)">
                {{ $say('Share an update, toggle the likes, and grow a comment thread — every post lands on the opportunity for the whole deal team to see.', 'به‌روزرسانی به‌اشتراک بگذارید، لایک‌ها را بزنید و رشتهٔ کامنت را بلند کنید — هر پست روی خود فرصت می‌نشیند تا کل تیم معامله ببیند.') }}
            </p>
        </div>

        <div class="slf-feed">
            <form class="slf-publisher" x-on:submit.prevent="share()">
                <div class="slf-pub-row">
                    <span class="slf-ava" style="--slf-tint: var(--slf-orange)" aria-hidden="true">{{ $fa ? 'ش' : 'YO' }}</span>
                    <input type="text" x-model="draft" placeholder="{{ $say('Share an update…', 'اشتراک به‌روزرسانی…') }}"
                        aria-label="{{ $say('Share an update', 'اشتراک به‌روزرسانی') }}">
                </div>
                <div class="slf-tools">
                    <button type="button" class="slf-tool" aria-label="{{ $say('Attach a file', 'پیوست فایل') }}"><x-nx::icon name="paperclip" /></button>
                    <button type="button" class="slf-tool" aria-label="{{ $say('Add an image', 'افزودن تصویر') }}"><x-nx::icon name="image" /></button>
                    <button type="button" class="slf-tool" aria-label="{{ $say('Mention someone', 'اشاره به کسی') }}"><x-nx::icon name="users" /></button>
                    <button type="submit" class="slf-share" :disabled="! draft.trim()">{{ $say('Share', 'اشتراک') }}</button>
                </div>
            </form>

            <template x-for="(p, i) in posts" :key="p.ini + when(p) + i">
                <article class="slf-post">
                    <header class="slf-post-head">
                        <span class="slf-ava" :style="'--slf-tint: ' + p.tint" aria-hidden="true" x-text="t === 'fa' ? p.ini : p.inie"></span>
                        <b x-text="who(p)"></b>
                        <time x-text="when(p)"></time>
                        <small x-text="meta(p)"></small>
                    </header>
                    <p x-text="body(p)"></p>
                    <div class="slf-acts">
                        <button type="button" class="slf-act" :aria-pressed="p.liked" x-on:click="like(i)">
                            <x-nx::icon name="heart" />
                            <span x-text="p.likes > 0 ? num(p.likes) : ''"></span>
                            {{ $say('Like', 'لایک') }}
                        </button>
                        <button type="button" class="slf-act" :aria-expanded="openComments[i]" x-on:click="openComments[i] = ! openComments[i]">
                            <x-nx::icon name="message" />
                            <span x-text="p.comments.length > 0 ? num(p.comments.length) : ''"></span>
                            {{ $say('Comment', 'نظر') }}
                        </button>
                    </div>
                    <div class="slf-comments" x-show="openComments[i]" x-cloak>
                        <template x-for="(c, j) in p.comments" :key="j">
                            <div class="slf-comment">
                                <span class="slf-ava" :style="'--slf-tint: ' + c.tint" aria-hidden="true" x-text="t === 'fa' ? c.ini : c.inie"></span>
                                <div class="slf-comment-body">
                                    <b x-text="t === 'fa' ? c.who.fa : c.who.en"></b>
                                    <time x-text="t === 'fa' ? c.time.fa : c.time.en"></time>
                                    <p x-text="t === 'fa' ? c.body.fa : c.body.en"></p>
                                </div>
                            </div>
                        </template>
                        <div class="slf-compose">
                            <span class="slf-ava" aria-hidden="true">{{ $fa ? 'ش' : 'YO' }}</span>
                            <input type="text" x-ref="compose" placeholder="{{ $say('Write a comment…', 'نظر بنویسید…') }}"
                                aria-label="{{ $say('Write a comment', 'نوشتن نظر') }}" x-on:keydown.enter="comment(i, $event.target)">
                        </div>
                    </div>
                </article>
            </template>

            <p class="slf-empty">{{ $say('Older posts live in the full feed.', 'پست‌های قدیمی‌تر در فید کامل‌اند.') }}</p>
        </div>
    </section>

    <section class="pg-box" style="justify-items: center; inline-size: 100%">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('The publisher’s two states', 'دو حالت ناشر') }}</h3>
            <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
                {{ $say('Collapsed on the placeholder; focused, it spreads the attach tools and arms the Share button.', 'بسته روی placeholder می‌ماند؛ با تمرکز، ابزارهای پیوست باز می‌شوند و دکمهٔ اشتراک مسلح می‌شود.') }}
            </p>
        </div>
        <div class="slf-spec" style="inline-size: 100%">
            <div class="slf-spec-cell">
                <span class="slf-publisher slf-spec-pub" aria-hidden="true">
                    <span class="slf-pub-row"><span class="slf-ava" style="--slf-tint: var(--slf-orange)">{{ $fa ? 'ش' : 'YO' }}</span><span style="font-size: .82rem; color: var(--slf-muted)">{{ $say('Share an update…', 'اشتراک به‌روزرسانی…') }}</span></span>
                </span>
                <small>collapsed</small>
            </div>
            <div class="slf-spec-cell">
                <span class="slf-publisher slf-spec-pub" aria-hidden="true">
                    <span class="slf-pub-row"><span class="slf-ava" style="--slf-tint: var(--slf-orange)">{{ $fa ? 'ش' : 'YO' }}</span><span style="font-size: .82rem; color: var(--slf-muted)">{{ $say('Deal update…', 'به‌روزرسانی معامله…') }}</span></span>
                    <span class="slf-tools">
                        <span class="slf-tool"><x-nx::icon name="paperclip" /></span>
                        <span class="slf-tool"><x-nx::icon name="image" /></span>
                        <span class="slf-share" style="opacity: .45">{{ $say('Share', 'اشتراک') }}</span>
                    </span>
                </span>
                <small>focused · draft</small>
            </div>
        </div>
    </section>
</div>
