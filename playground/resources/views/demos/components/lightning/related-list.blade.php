{{--
    Lightning Related Lists on the same opportunity: two icon-headed cards —
    Contacts and Files — each with its count, a New action, row lines with
    secondary meta, a row overflow menu and the View All footer. The contact
    card adds a real row (and removes one), and the second card stays quiet
    to show the family. Below, the header tiles of four related objects.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $jsf = fn (string $s): string => str_replace('"', '&quot;', json_encode($s, JSON_UNESCAPED_UNICODE));
@endphp
<style>
    .sll-root {
        --sll-blue: #0176D3; --sll-blue-soft: color-mix(in srgb, #0176D3 8%, #FFFFFF);
        --sll-green: #04844B; --sll-purple: #6739B7; --sll-orange: #FE9339; --sll-red: #BA0517;
        --sll-text: #181818; --sll-weak: #444444; --sll-muted: #706E6B;
        --sll-border: #DDDBDA; --sll-bg: #F3F3F3; --sll-card: #FFFFFF;
        font-family: 'Salesforce Sans', -apple-system, 'Segoe UI', Roboto, sans-serif;
        color: var(--sll-text);
        display: grid; gap: 1.5rem;
    }
    html[data-theme="dark"] .sll-root {
        --sll-blue: #0D9DDA; --sll-blue-soft: color-mix(in srgb, #0D9DDA 16%, #232323);
        --sll-green: #0E9E5B; --sll-purple: #A57DE8; --sll-orange: #FE9339; --sll-red: #FE5C4C;
        --sll-text: #F3F3F3; --sll-weak: #CECECE; --sll-muted: #A5A5A5;
        --sll-border: #474747; --sll-bg: #181818; --sll-card: #232323;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .sll-root {
            --sll-blue: #0D9DDA; --sll-blue-soft: color-mix(in srgb, #0D9DDA 16%, #232323);
            --sll-green: #0E9E5B; --sll-purple: #A57DE8; --sll-orange: #FE9339; --sll-red: #FE5C4C;
            --sll-text: #F3F3F3; --sll-weak: #CECECE; --sll-muted: #A5A5A5;
            --sll-border: #474747; --sll-bg: #181818; --sll-card: #232323;
        }
    }
    .sll-root, .sll-root *, .sll-root *::before, .sll-root *::after { box-sizing: border-box; }
    .sll-root :focus-visible { outline: 2px solid var(--sll-blue); outline-offset: 2px; }

    .sll-row { display: flex; flex-wrap: wrap; gap: 1rem; justify-content: center; align-items: flex-start;
               inline-size: min(100%, 52rem); margin-inline: auto; }
    .sll-card { flex: 1 1 17rem; min-inline-size: 0; border: 1px solid var(--sll-border); border-radius: .25rem;
                background: var(--sll-card); display: grid; }
    .sll-head { display: flex; align-items: center; gap: .5rem; padding: .7rem .9rem; }
    .sll-tile { flex: none; display: grid; place-items: center; inline-size: 1.5rem; block-size: 1.5rem;
                border-radius: .25rem; background: var(--sll-tint, var(--sll-blue)); color: #FFFFFF;
                font-size: .72rem; font-weight: 700; }
    .sll-head h3 { margin: 0; font-size: .95rem; font-weight: 700; min-inline-size: 0;
                overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .sll-head .sll-new { margin-inline-start: auto; }
    .sll-list { margin: 0; padding: 0; list-style: none; }
    .sll-item { display: flex; align-items: center; gap: .6rem; padding: .55rem .9rem; }
    .sll-item + .sll-item { border-block-start: 1px solid var(--sll-border); }
    .sll-item > div { min-inline-size: 0; flex: 1; }
    .sll-item b { display: block; font-size: .85rem; font-weight: 600; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .sll-item span { display: block; font-size: .76rem; color: var(--sll-muted); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .sll-rowmenu { position: relative; flex: none; }
    .sll-dots { border: 0; background: transparent; color: var(--sll-muted); cursor: pointer;
                padding: .1rem .35rem; border-radius: .25rem; font-weight: 700; }
    .sll-dots:hover { background: var(--sll-blue-soft); color: var(--sll-blue); }
    .sll-menu { position: absolute; z-index: 5; inset-inline-end: 0; inset-block-start: 1.5rem; min-inline-size: 8.5rem;
                padding: .2rem 0; border: 1px solid var(--sll-border); border-radius: .25rem; background: var(--sll-card);
                box-shadow: 0 2px 6px rgba(0, 0, 0, .14); }
    .sll-menu button { display: flex; inline-size: 100%; align-items: center; gap: .45rem; padding: .4rem .75rem;
                border: 0; background: transparent; color: inherit; font: inherit; font-size: .8rem; cursor: pointer; text-align: start; }
    .sll-menu button:hover { background: var(--sll-blue-soft); }
    .sll-menu [data-tone="danger"] { color: var(--sll-red); }
    .sll-foot { border-block-start: 1px solid var(--sll-border); padding: .5rem .9rem; }
    .sll-foot a { color: var(--sll-blue); font-size: .8rem; font-weight: 600; text-decoration: none;
                display: inline-flex; align-items: center; gap: .25rem; }
    .sll-foot a:hover { text-decoration: underline; }
    .sll-file { display: flex; align-items: center; gap: .6rem; }
    .sll-file .nx-icon { color: var(--sll-muted); }
    .sll-btn { padding: .35rem .8rem; border: 1px solid var(--sll-border); border-radius: .25rem;
               background: var(--sll-card); color: var(--sll-blue); font: inherit; font-size: .78rem; font-weight: 600;
               cursor: pointer; transition: background .15s ease; }
    .sll-btn:hover { background: var(--sll-blue-soft); }
    .sll-count { font-size: .78rem; color: var(--sll-muted); }

    .sll-spec { display: flex; flex-wrap: wrap; gap: 1.25rem; justify-content: center; align-items: flex-start; }
    .sll-spec-cell { display: grid; gap: .4rem; justify-items: center; }
    .sll-spec-cell > small { font-size: .72rem; color: var(--sll-muted); }
    :where(.nx-js) .pg:has(.sll-root) .nx-data-table[data-nx-reveal] tbody tr { opacity: 1; translate: none; }
    @media (prefers-reduced-motion: reduce) {
        .sll-root * { transition-duration: .01ms !important; }
    }
</style>

<div class="sll-root"
    x-data="{
        menuFor: null,
        people: [
            { name: 'سارا احمدی', en: 'Sara Ahmadi', role: 'مدیر خرید', roleEn: 'Head of purchasing', mail: 'sara@acme.example' },
            { name: 'رضا کاظمی', en: 'Reza Kazemi', role: 'کارشناس فنی', roleEn: 'Technical reviewer', mail: 'reza@acme.example' },
            { name: 'مریم رحیمی', en: 'Maryam Rahimi', role: 'مدیر مالی', roleEn: 'Finance manager', mail: 'maryam@acme.example' },
        ],
        files: [
            { name: 'پیش‌فاکتور-۱۴۰۴.pdf', en: 'Quote-1404.pdf', meta: '۲۴۸ کیلوبایت · دیروز', metaEn: '248 KB · yesterday' },
            { name: 'قرارداد-نابو.docx', en: 'Contract-Nabu.docx', meta: '۱٫۱ مگابایت · ۳ روز پیش', metaEn: '1.1 MB · 3 days ago' },
        ],
        newCount: 0,
        get t() { return document.documentElement.lang === 'fa' ? 'fa' : 'en' },
        openMenu(id) { this.menuFor = this.menuFor === id ? null : id },
        removePerson(i) { this.people.splice(i, 1); this.menuFor = null },
        addPerson() {
            const pool = [
                { name: 'علی موسوی', en: 'Ali Mousavi', role: 'کارشناس خرید', roleEn: 'Buyer', mail: 'ali@acme.example' },
                { name: 'نگار صادقی', en: 'Negar Sadeghi', role: 'روابط عمومی', roleEn: 'Comms', mail: 'negar@acme.example' },
            ];
            this.people.push(pool[this.newCount % pool.length]); this.newCount++;
        },
    }"
    x-on:keydown.escape="menuFor = null"
    x-on:click.outside="menuFor = null">
    <section class="pg-box" style="justify-items: center; inline-size: 100%">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The opportunity, unpacked', 'همان فرصت، باز شده') }}</h3>
            <p style="margin: 0; max-width: 46ch; color: var(--nx-text-muted)">
                {{ $say('Two related lists side by side: add a contact with New, remove one from its ⋯ menu, and watch both counts keep honest.', 'دو لیست مرتبط کنار هم: با «جدید» مخاطب اضافه کنید، از منوی ⋯ حذفش کنید و شمارنده‌ها را صادق نگه دارید.') }}
            </p>
        </div>

        <div class="sll-row">
            <article class="sll-card" style="--sll-tint: var(--sll-purple)">
                <header class="sll-head">
                    <span class="sll-tile" aria-hidden="true">{{ $fa ? 'م' : 'C' }}</span>
                    <h3>{{ $say('Contacts', 'مخاطب‌ها') }} (<span x-text="people.length"></span>)</h3>
                    <button type="button" class="sll-btn sll-new" x-on:click="addPerson()">{{ $say('New', 'جدید') }}</button>
                </header>
                <ul class="sll-list">
                    <template x-for="(p, i) in people" :key="p.mail + i">
                        <li class="sll-item">
                            <div>
                                <b x-text="t === 'fa' ? p.name : p.en"></b>
                                <span x-text="(t === 'fa' ? p.role : p.roleEn) + ' · ' + p.mail"></span>
                            </div>
                            <span class="sll-rowmenu">
                                <button type="button" class="sll-dots" aria-haspopup="menu"
                                    :aria-expanded="menuFor === 'p' + i"
                                    :aria-label="(t === 'fa' ? {!! $jsf('اقدام‌های ') !!} : {!! $jsf('Actions for ') !!}) + (t === 'fa' ? p.name : p.en)"
                                    x-on:click="openMenu('p' + i)">⋯</button>
                                <nav class="sll-menu" role="menu" x-show="menuFor === 'p' + i" x-cloak>
                                    <button type="button" role="menuitem"><x-nx::icon name="external-link" /> {{ $say('Open', 'باز کردن') }}</button>
                                    <button type="button" role="menuitem"><x-nx::icon name="edit" /> {{ $say('Edit', 'ویرایش') }}</button>
                                    <button type="button" role="menuitem" data-tone="danger" x-on:click="removePerson(i)"><x-nx::icon name="trash" /> {{ $say('Remove', 'حذف') }}</button>
                                </nav>
                            </span>
                        </li>
                    </template>
                </ul>
                <footer class="sll-foot">
                    <a href="#" x-on:click.prevent>{{ $say('View all', 'مشاهدهٔ همه') }} <x-nx::icon name="chevron-left" style="inline-size: .9em; block-size: .9em" /></a>
                </footer>
            </article>

            <article class="sll-card" style="--sll-tint: var(--sll-orange)">
                <header class="sll-head">
                    <span class="sll-tile" aria-hidden="true">{{ $fa ? 'پ' : 'F' }}</span>
                    <h3>{{ $say('Files', 'فایل‌ها') }} (<span x-text="files.length"></span>)</h3>
                </header>
                <ul class="sll-list">
                    <template x-for="f in files" :key="f.en">
                        <li class="sll-item">
                            <span class="sll-file">
                                <x-nx::icon name="file" style="inline-size: 1.2em; block-size: 1.2em" />
                                <div>
                                    <b x-text="t === 'fa' ? f.name : f.en"></b>
                                    <span x-text="t === 'fa' ? f.meta : f.metaEn"></span>
                                </div>
                            </span>
                        </li>
                    </template>
                </ul>
                <footer class="sll-foot">
                    <a href="#" x-on:click.prevent>{{ $say('View all', 'مشاهدهٔ همه') }} <x-nx::icon name="chevron-left" style="inline-size: .9em; block-size: .9em" /></a>
                </footer>
            </article>
        </div>
    </section>

    <section class="pg-box" style="justify-items: center; inline-size: 100%">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('The header tells the object', 'سربرگ، آبجکت را لو می‌دهد') }}</h3>
            <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
                {{ $say('Same card anatomy, different tint and initial — you read the relationship before reading a word.', 'همان کالبد کارت با رنگ و حرفِ متفاوت — رابطه را پیش از خواندن حتی یک واژه می‌فهمید.') }}
            </p>
        </div>
        <div class="sll-spec" style="inline-size: 100%">
            <div class="sll-spec-cell">
                <span class="sll-tile" style="--sll-tint: var(--sll-purple); inline-size: 2.25rem; block-size: 2.25rem; font-size: 1rem" aria-hidden="true">{{ $fa ? 'م' : 'C' }}</span>
                <small>{{ $say('Contacts', 'مخاطب‌ها') }}</small>
            </div>
            <div class="sll-spec-cell">
                <span class="sll-tile" style="--sll-tint: var(--sll-orange); inline-size: 2.25rem; block-size: 2.25rem; font-size: 1rem" aria-hidden="true">{{ $fa ? 'پ' : 'F' }}</span>
                <small>{{ $say('Files', 'فایل‌ها') }}</small>
            </div>
            <div class="sll-spec-cell">
                <span class="sll-tile" style="--sll-tint: var(--sll-green); inline-size: 2.25rem; block-size: 2.25rem; font-size: 1rem" aria-hidden="true">{{ $fa ? 'ن' : 'T' }}</span>
                <small>{{ $say('Tasks', 'وظایف') }}</small>
            </div>
            <div class="sll-spec-cell">
                <span class="sll-tile" style="--sll-tint: var(--sll-blue); inline-size: 2.25rem; block-size: 2.25rem; font-size: 1rem" aria-hidden="true">{{ $fa ? 'ی' : 'N' }}</span>
                <small>{{ $say('Notes', 'یادداشت‌ها') }}</small>
            </div>
            <div class="sll-spec-cell">
                <span class="sll-count" style="font-weight: 700; color: var(--sll-text)">(۵)</span>
                <small>count</small>
            </div>
            <div class="sll-spec-cell">
                <span class="sll-foot" style="border: 0; padding: 0"><a href="#" x-on:click.prevent>{{ $say('View all', 'مشاهدهٔ همه') }}</a></span>
                <small>view-all</small>
            </div>
        </div>
    </section>
</div>
