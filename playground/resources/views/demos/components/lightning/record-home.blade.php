{{--
    The Lightning Record Home for a real opportunity: the oversized object
    icon tile, breadcrumbs, the follow star, the action rail with its brand
    button and overflow menu, and the Highlights panel that folds its key
    fields away and back. A second row specimens the object tiles and the
    follow states.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $jsf = fn (string $s): string => str_replace('"', '&quot;', json_encode($s, JSON_UNESCAPED_UNICODE));

    $objects = [
        ['opportunity', 'ف', 'O', '#04844B', $say('Opportunity', 'فرصت')],
        ['account', 'آ', 'A', '#0176D3', $say('Account', 'حساب')],
        ['contact', 'س', 'C', '#6739B7', $say('Contact', 'مخاطب')],
        ['lead', 'س', 'L', '#FE9339', $say('Lead', 'سرنخ')],
    ];
@endphp
<style>
    .slr-root {
        --slr-blue: #0176D3; --slr-blue-soft: color-mix(in srgb, #0176D3 8%, #FFFFFF);
        --slr-green: #04844B; --slr-purple: #6739B7; --slr-orange: #FE9339;
        --slr-text: #181818; --slr-weak: #444444; --slr-muted: #706E6B;
        --slr-border: #DDDBDA; --slr-bg: #F3F3F3; --slr-card: #FFFFFF;
        font-family: 'Salesforce Sans', -apple-system, 'Segoe UI', Roboto, sans-serif;
        color: var(--slr-text);
        display: grid; gap: 1.5rem;
    }
    html[data-theme="dark"] .slr-root {
        --slr-blue: #0D9DDA; --slr-blue-soft: color-mix(in srgb, #0D9DDA 16%, #232323);
        --slr-green: #0E9E5B; --slr-purple: #A57DE8; --slr-orange: #FE9339;
        --slr-text: #F3F3F3; --slr-weak: #CECECE; --slr-muted: #A5A5A5;
        --slr-border: #474747; --slr-bg: #181818; --slr-card: #232323;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .slr-root {
            --slr-blue: #0D9DDA; --slr-blue-soft: color-mix(in srgb, #0D9DDA 16%, #232323);
            --slr-green: #0E9E5B; --slr-purple: #A57DE8; --slr-orange: #FE9339;
            --slr-text: #F3F3F3; --slr-weak: #CECECE; --slr-muted: #A5A5A5;
            --slr-border: #474747; --slr-bg: #181818; --slr-card: #232323;
        }
    }
    .slr-root, .slr-root *, .slr-root *::before, .slr-root *::after { box-sizing: border-box; }
    .slr-root :focus-visible { outline: 2px solid var(--slr-blue); outline-offset: 2px; }

    .slr-card { position: relative; inline-size: min(100%, 46rem); margin-inline: auto;
                border: 1px solid var(--slr-border); border-radius: .25rem; background: var(--slr-card); }
    .slr-head { display: flex; flex-wrap: wrap; align-items: center; gap: .9rem; padding: 1rem; }
    .slr-tile { flex: none; display: grid; place-items: center; inline-size: 3rem; block-size: 3rem;
                border-radius: .25rem; background: var(--slr-tint, var(--slr-green)); color: #FFFFFF;
                font-size: 1.35rem; font-weight: 700; }
    .slr-who { min-inline-size: 0; flex: 1 1 12rem; }
    .slr-who small { display: block; font-size: .75rem; color: var(--slr-muted); }
    .slr-who h2 { margin: .1rem 0 0; font-size: 1.15rem; font-weight: 700; display: flex; align-items: center; gap: .4rem; }
    .slr-star { border: 0; padding: .15rem; background: transparent; cursor: pointer; color: var(--slr-muted);
                display: grid; place-items: center; border-radius: .25rem; transition: color .15s ease, scale .15s ease; }
    .slr-star[aria-pressed="true"] { color: #FFB75D; }
    .slr-star:active { scale: .85; }
    .slr-rail { display: flex; flex-wrap: wrap; gap: .4rem; }
    .slr-btn { position: relative; padding: .45rem .95rem; border: 1px solid var(--slr-border); border-radius: .25rem;
               background: var(--slr-card); color: var(--slr-text); font: inherit; font-size: .82rem; cursor: pointer;
               transition: background .15s ease; }
    .slr-btn:hover { background: var(--slr-bg); }
    .slr-btn[data-variant="brand"] { background: var(--slr-blue); border-color: var(--slr-blue); color: #FFFFFF; font-weight: 600; }
    .slr-btn[data-variant="brand"]:hover { background: color-mix(in srgb, var(--slr-blue) 85%, #000); }
    .slr-more { padding-inline: .55rem; font-weight: 700; letter-spacing: .1em; }
    .slr-menu { position: absolute; z-index: 5; inset-inline-end: 1rem; inset-block-start: 3.4rem; min-inline-size: 10rem;
                padding: .25rem 0; border: 1px solid var(--slr-border); border-radius: .25rem; background: var(--slr-card);
                box-shadow: 0 2px 6px rgba(0, 0, 0, .14); }
    .slr-menu button { display: flex; inline-size: 100%; align-items: center; gap: .5rem; padding: .45rem .85rem;
                border: 0; background: transparent; color: inherit; font: inherit; font-size: .82rem; cursor: pointer; text-align: start; }
    .slr-menu button:hover { background: var(--slr-blue-soft); }
    .slr-highlights { border-block-start: 2px solid var(--slr-tint, var(--slr-green)); padding: .85rem 1rem 1rem; }
    .slr-hl-head { display: flex; align-items: center; justify-content: space-between; gap: .5rem; }
    .slr-hl-head h3 { margin: 0; font-size: .95rem; font-weight: 700; }
    .slr-hl-toggle { border: 0; background: transparent; color: var(--slr-blue); font: inherit; font-size: .78rem;
                cursor: pointer; display: inline-flex; align-items: center; gap: .2rem; padding: .15rem .3rem; border-radius: .25rem; }
    .slr-hl-toggle .nx-icon { transition: rotate .2s ease; }
    .slr-hl-toggle[aria-expanded="true"] .nx-icon { rotate: 180deg; }
    .slr-fields { display: grid; grid-template-columns: repeat(auto-fit, minmax(9rem, 1fr)); gap: .8rem 1.25rem; margin: .8rem 0 0; }
    .slr-field dt { font-size: .72rem; color: var(--slr-muted); }
    .slr-field dd { margin: .1rem 0 0; font-size: .88rem; font-weight: 700; }
    .slr-badge { display: inline-flex; align-items: center; gap: .35rem; padding: .1rem .55rem; border-radius: .9rem;
                background: color-mix(in srgb, var(--slr-tint, var(--slr-green)) 16%, var(--slr-card));
                color: var(--slr-tint, var(--slr-green)); font-size: .78rem; font-weight: 700; }
    .slr-hide { display: none; }
    .slr-toast { display: flex; align-items: center; gap: .5rem; margin: .85rem 1rem 1rem; padding: .55rem .75rem;
                border-radius: .25rem; background: color-mix(in srgb, var(--slr-green) 12%, var(--slr-card));
                color: var(--slr-green); font-size: .8rem; font-weight: 600; }

    .slr-spec { display: flex; flex-wrap: wrap; gap: 1.25rem; justify-content: center; align-items: flex-start; }
    .slr-spec-cell { display: grid; gap: .4rem; justify-items: center; }
    .slr-spec-cell > small { font-size: .72rem; color: var(--slr-muted); }
    .slr-spec-tile { inline-size: 2.25rem; block-size: 2.25rem; font-size: 1.05rem; }
    :where(.nx-js) .pg:has(.slr-root) .nx-data-table[data-nx-reveal] tbody tr { opacity: 1; translate: none; }
    @media (prefers-reduced-motion: reduce) {
        .slr-root * { transition-duration: .01ms !important; }
    }
</style>

<div class="slr-root"
    x-data="{
        follow: false,
        menu: false,
        expanded: false,
        starMsg: '',
        toggleFollow() {
            this.follow = !this.follow;
            this.starMsg = this.follow ? '«لایسنس سالانهٔ نابو» دنبال شد' : 'دنبال‌کردن لغو شد';
            clearTimeout(this._t); this._t = setTimeout(() => this.starMsg = '', 2400);
        },
    }"
    x-on:keydown.escape="menu = false"
    x-on:click.outside="menu = false">
    <section class="pg-box" style="justify-items: center; inline-size: 100%">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The frame every record lives in', 'قابی که هر رکورد در آن جا می‌گیرد') }}</h3>
            <p style="margin: 0; max-width: 46ch; color: var(--nx-text-muted)">
                {{ $say('Follow the record with the star, open the ⋯ rail, and fold the Highlights panel down and back — the same three gestures a rep makes all day.', 'با ستاره رکورد را دنبال کنید، منوی ⋯ را باز کنید و پنل هایلایت را جمع و باز کنید — همان سه حرکتی که کارشناس فروش همه‌روزه می‌زند.') }}
            </p>
        </div>

        <div class="slr-card" style="--slr-tint: var(--slr-green)">
            <div class="slr-head">
                <span class="slr-tile" aria-hidden="true">ف</span>
                <div class="slr-who">
                    <small>{{ $say('Opportunities / Industrial customers', 'فرصت‌ها / مشتریان صنعتی') }}</small>
                    <h2>
                        {{ $say('Nabu annual license', 'لایسنس سالانهٔ نابو') }}
                        <button type="button" class="slr-star" :aria-pressed="follow"
                            :aria-label="follow ? {!! $jsf($say('Following — unfollow', 'دنبال می‌شود — لغو دنبال')) !!} : {!! $jsf($say('Follow record', 'دنبال کردن رکورد')) !!}"
                            x-on:click="toggleFollow()">
                            <x-nx::icon name="star" x-bind:class="follow ? '' : ''" style="inline-size: 1.1em; block-size: 1.1em" />
                        </button>
                    </h2>
                </div>
                <div class="slr-rail">
                    <button type="button" class="slr-btn" data-variant="brand">{{ $say('Edit', 'ویرایش') }}</button>
                    <button type="button" class="slr-btn">{{ $say('Clone', 'همانندسازی') }}</button>
                    <button type="button" class="slr-btn slr-more" aria-haspopup="menu" :aria-expanded="menu"
                        aria-label="{{ $say('More actions', 'اقدام‌های بیشتر') }}"
                        x-on:click="menu = !menu">⋯</button>
                </div>
                <nav class="slr-menu" role="menu" x-show="menu" x-cloak>
                    <button type="button" role="menuitem"><x-nx::icon name="trash" /> {{ $say('Delete', 'حذف') }}</button>
                    <button type="button" role="menuitem"><x-nx::icon name="upload" /> {{ $say('Attach file', 'پیوست فایل') }}</button>
                    <button type="button" role="menuitem"><x-nx::icon name="mail" /> {{ $say('Send email', 'ارسال ایمیل') }}</button>
                </nav>
            </div>

            <div class="slr-highlights">
                <div class="slr-hl-head">
                    <h3>{{ $say('Highlights', 'هایلایت‌ها') }}</h3>
                    <button type="button" class="slr-hl-toggle" :aria-expanded="expanded" x-on:click="expanded = !expanded">
                        <span x-text="expanded ? {!! $jsf($say('Show less', 'نمایش کمتر')) !!} : {!! $jsf($say('Show more', 'نمایش بیشتر')) !!}"></span>
                        <x-nx::icon name="chevron-down" />
                    </button>
                </div>
                <dl class="slr-fields" style="margin-block-end: 0">
                    <div class="slr-field">
                        <dt>{{ $say('Amount', 'مبلغ') }}</dt>
                        <dd>{{ $say('2,400,000,000 IRR', '۲٬۴۰۰٬۰۰۰٬۰۰۰ ریال') }}</dd>
                    </div>
                    <div class="slr-field">
                        <dt>{{ $say('Stage', 'مرحله') }}</dt>
                        <dd><span class="slr-badge">{{ $say('Negotiation/Review', 'مذاکره و بازبینی') }}</span></dd>
                    </div>
                    <div class="slr-field">
                        <dt>{{ $say('Close date', 'تاریخ بستن') }}</dt>
                        <dd>{{ $say('Feb 28, 1405', '۲۸ اسفند ۱۴۰۴') }}</dd>
                    </div>
                </dl>
                <dl class="slr-fields" style="margin-block-start: .8rem" :class="expanded ? '' : 'slr-hide'">
                    <div class="slr-field">
                        <dt>{{ $say('Owner', 'مالک') }}</dt>
                        <dd>{{ $say('Sara Ahmadi', 'سارا احمدی') }}</dd>
                    </div>
                    <div class="slr-field">
                        <dt>{{ $say('Probability', 'احتمال موفقیت') }}</dt>
                        <dd>{{ $say('75%', '۷۵٪') }}</dd>
                    </div>
                    <div class="slr-field">
                        <dt>{{ $say('Next step', 'گام بعدی') }}</dt>
                        <dd>{{ $say('Send the revised quote', 'ارسال پیش‌فاکتور بازبینی‌شده') }}</dd>
                    </div>
                </dl>
            </div>

            <p class="slr-toast" x-show="starMsg" x-cloak style="margin-block: 0 1rem">
                <x-nx::icon name="check-circle" />
                <span x-text="starMsg"></span>
            </p>
        </div>
    </section>

    <section class="pg-box" style="justify-items: center; inline-size: 100%">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('One tile per object', 'برای هر آبجکت یک کاشی') }}</h3>
            <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
                {{ $say('The tile paints the object colour behind the record’s initial; the Highlights border and stage badge inherit it.', 'کاشی، رنگ آبجکت را پشت حرف اول رکورد می‌گذارد؛ خط پنل هایلایت و نشانِ مرحله هم از همان ارث می‌برند.') }}
            </p>
        </div>
        <div class="slr-spec" style="inline-size: 100%">
            @foreach ($objects as [$key, $faLetter, $enLetter, $color, $label])
                <div class="slr-spec-cell">
                    <span class="slr-tile slr-spec-tile" style="--slr-tint: {{ $color }}" aria-hidden="true">{{ $fa ? $faLetter : $enLetter }}</span>
                    <small>{{ $label }}</small>
                </div>
            @endforeach
            <div class="slr-spec-cell">
                <button type="button" class="slr-star" aria-pressed="true" style="color: #FFB75D" aria-label="{{ $say('Following', 'دنبال می‌شود') }}">
                    <x-nx::icon name="star" style="inline-size: 1.4em; block-size: 1.4em" />
                </button>
                <small>follow = true</small>
            </div>
            <div class="slr-spec-cell">
                <button type="button" class="slr-star" aria-pressed="false" style="color: var(--slr-muted)" aria-label="{{ $say('Not following', 'دنبال نمی‌شود') }}">
                    <x-nx::icon name="star" style="inline-size: 1.4em; block-size: 1.4em" />
                </button>
                <small>follow = false</small>
            </div>
        </div>
    </section>
</div>
