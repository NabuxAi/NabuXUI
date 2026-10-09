<?php

/**
 * Demo manifest of the "primer" group — GitHub Primer rebuilt as faithful,
 * interactive skins: the iconic issue list, state labels, hex-coloured label
 * chips, the PR conversation timeline, emoji reaction bars, the query-syntax
 * filter bar, the merge panel, the underline nav with CounterLabels and the
 * Actions run list. Scenarios live at
 * resources/views/demos/components/primer/{slug}.blade.php.
 * See App\Support\DemoCatalog for the entry shape.
 */
return [
    '__group' => ['fa' => 'گیت‌هاب — پرایمر', 'en' => 'GitHub Primer'],

    'issue-list' => [
        'title' => ['fa' => 'فهرست ایشو', 'en' => 'Issue List'],
        'icon' => 'alert-circle',
        'oneLiner' => [
            'fa' => 'ردیف‌های ایشو با آیکن وضعیت دایره‌ای، عنوان پررنگ، متای خاکستری و چیپ‌های لیبل؛ همان صفحهٔ Issues گیت‌هاب که از اولین نگاه سیستم را لو می‌دهد.',
            'en' => 'GitHub\'s iconic Issues page as a component: circular state icons, bold titles, gray meta rows and label chips. The instant "this is GitHub" pattern.',
        ],
        'js' => true,
        'docs' => 'https://primer.style/components/action-list',
        'props' => [
            ['name' => 'state icon', 'type' => 'octicon · 16px', 'default' => "'issue-open'", 'note' => [
                'fa' => 'آیکن دایره‌ای ۱۶ پیکسلی در آغاز ردیف؛ باز سبز، بستهٔ کامل بنفش، برنامه‌ریزی‌نشده خاکستری.',
                'en' => 'The 16px circular icon that opens the row; open green, completed purple, not-planned gray.',
            ]],
            ['name' => 'title', 'type' => 'weight', 'default' => "'400 → hover accent'", 'note' => [
                'fa' => 'عنوان عادی است و فقط هاور به آبی گیت‌هاب می‌رود — بی‌حالت‌های اضافه.',
                'en' => 'The title is plain and only turns GitHub blue on hover — no extra states.',
            ]],
            ['name' => 'meta row', 'type' => '12px muted', 'default' => "'#۵۹۶۳۶e'", 'note' => [
                'fa' => 'ردیف متای خاکستری ۱۲ پیکسلی: شماره، باز‌شده توسط، زمان نسبی.',
                'en' => 'The 12px gray meta row: number, opened by, relative time.',
            ]],
            ['name' => 'labels', 'type' => 'chips', 'default' => "'end-aligned'", 'note' => [
                'fa' => 'چیپ‌های لیبل در انتهای ردیف می‌نشینند و روی موبایل می‌پیچند.',
                'en' => 'Label chips sit at the end of the row and wrap on mobile.',
            ]],
        ],
        'code' => <<<'BLADE'
        <a class="pri-row" href="#">
            <span class="pri-state" data-state="open">○</span>
            <span class="pri-body">
                <span class="pri-title">کارت آمار در موبایل سرریز می‌کند</span>
                <span class="pri-meta">#۴۱۲ باز شد ۳ روز پیش توسط سارا</span>
            </span>
            <span class="pri-label" style="--hue: #d73a4a">باگ</span>
        </a>

        <style>
        .pri-row { display: flex; gap: 8px; padding: 8px 16px; border-block-end: 1px solid #d1d9e0; }
        .pri-state[data-state='open'] { color: #1a7f37; }
        .pri-title { font-size: 16px; }
        .pri-meta { color: #59636e; font-size: 12px; }
        .pri-label { border: 1px solid color-mix(in srgb, var(--hue) 40%, transparent);
                     color: var(--hue); border-radius: 2em; padding: 0 7px; font-size: 12px; }
        </style>
        BLADE,
    ],

    'state-label' => [
        'title' => ['fa' => 'لیبل وضعیت', 'en' => 'State Label'],
        'icon' => 'check-circle',
        'oneLiner' => [
            'fa' => 'قرص‌های وضعیت با آیکن دایره‌ای: سبز Open (#1A7F37)، بنفش Merged (#8250DF)، قرمز Closed و خاکستری Draft؛ کوچک‌ترین اتمی که بلافاصله می‌گوید گیت‌هاب است.',
            'en' => 'Open, Closed, Merged, Draft pills with circular octicons in Primer\'s success green, done purple and danger red. The smallest atom that screams GitHub.',
        ],
        'js' => true,
        'docs' => 'https://primer.style/components/state-label',
        'props' => [
            ['name' => 'status', 'type' => 'state', 'default' => "'issueOpen'", 'note' => [
                'fa' => 'issueOpen · issueClosed · prOpen · prMerged · prClosed · draft؛ هر آیتم‌شمار و رنگش با هم عوض می‌شوند.',
                'en' => 'issueOpen · issueClosed · prOpen · prMerged · prClosed · draft; the octicon and the colour switch together.',
            ]],
            ['name' => 'icon', 'type' => 'octicon · 16px', 'default' => "'filled circle'", 'note' => [
                'fa' => 'آیکن دایرهٔ توپُر سفید روی زمینهٔ رنگی — امضای StateLabel.',
                'en' => 'A filled white circle glyph on the tinted field — the StateLabel signature.',
            ]],
            ['name' => 'radius', 'type' => 'length', 'default' => "'2em'", 'note' => [
                'fa' => 'قرص تمام‌گرد؛ متن ۱۲ پیکسل با وزن ۵۰۰.',
                'en' => 'The fully rounded pill; 12px text at weight 500.',
            ]],
            ['name' => 'size', 'type' => 'height', 'default' => "'24 · 32'", 'note' => [
                'fa' => 'دو اندازهٔ رسمی: کوچک درون ردیف‌ها، بزرگ در سربرگ ایشو و PR.',
                'en' => 'The two official sizes: small inside rows, large on the issue/PR header.',
            ]],
        ],
        'code' => <<<'BLADE'
        <span class="prs-label" data-status="open">
            <svg viewBox="0 0 16 16">…</svg> Open
        </span>
        <span class="prs-label" data-status="merged">
            <svg viewBox="0 0 16 16">…</svg> Merged
        </span>

        <style>
        .prs-label { display: inline-flex; align-items: center; gap: 4px; block-size: 24px;
                     padding-inline: 10px; border-radius: 2em; font: 500 12px system-ui; }
        .prs-label[data-status='open'] { background: #1A7F37; color: #fff; }
        .prs-label[data-status='merged'] { background: #8250DF; color: #fff; }
        </style>
        BLADE,
    ],

    'label-chip' => [
        'title' => ['fa' => 'چیپ لیبل', 'en' => 'Label Chip'],
        'icon' => 'sparkles',
        'oneLiner' => [
            'fa' => 'لیبل‌های کاملاً گرد با رنگ Hex دلخواه، حاشیهٔ هم‌رنگ و متن اشباع‌شده؛ سامانهٔ برچسب‌گذاری رنگارنگ گیت‌هاب که هر مخزن را در یک نگاه قابل‌تفکیک می‌کند.',
            'en' => 'Fully-rounded hex-colored labels with matching borders and saturated text. GitHub\'s color-coded taxonomy that makes every repo scannable.',
        ],
        'js' => true,
        'docs' => 'https://primer.style/components/label',
        'props' => [
            ['name' => 'hue', 'type' => 'color', 'default' => "'#d73a4a'", 'note' => [
                'fa' => 'رنگ Hex دلخواه؛ متن از همین hue ساخته می‌شود.',
                'en' => 'Any hex colour; the text derives from the same hue.',
            ]],
            ['name' => 'border', 'type' => 'alpha', 'default' => "'≈ 40%'", 'note' => [
                'fa' => 'حاشیه هم‌رنگِ متن با آلفای کم — لیبل‌های سفید هم دیده می‌شوند.',
                'en' => 'A same-hue border at low alpha — even white labels stay visible.',
            ]],
            ['name' => 'radius', 'type' => 'length', 'default' => "'2em'", 'note' => [
                'fa' => 'گردی کامل؛ ارتفاع ۲۰ پیکسل و متن ۱۲ پیکسل.',
                'en' => 'Full rounding; 20px height, 12px text.',
            ]],
            ['name' => 'group', 'type' => 'layout', 'default' => "'inline · 8px gap'", 'note' => [
                'fa' => 'لیبل‌ها در ردیف انعطاف‌پذیر می‌پیچند؛ +۱۰ بیشتر با شمارنده.',
                'en' => 'Labels wrap in a flex row; a +10 more counter trims the rest.',
            ]],
        ],
        'code' => <<<'BLADE'
        <span class="prl-label" style="--hue: #7057ff">good first issue</span>
        <span class="prl-label" style="--hue: #008672">help wanted</span>
        <span class="prl-label" style="--hue: #ffffff">wontfix</span>

        <style>
        .prl-label { display: inline-flex; align-items: center; block-size: 20px;
                     padding-inline: 7px; border-radius: 2em; font: 500 12px system-ui;
                     color: color-mix(in srgb, var(--hue) 70%, black);
                     border: 1px solid color-mix(in srgb, var(--hue) 40%, transparent); }
        html[data-theme="dark"] .prl-label {
            color: color-mix(in srgb, var(--hue) 80%, white); }
        </style>
        BLADE,
    ],

    'pr-timeline' => [
        'title' => ['fa' => 'تایم‌لاین پول‌ریکوئست', 'en' => 'PR Timeline'],
        'icon' => 'message',
        'oneLiner' => [
            'fa' => 'ریل عمودی رویدادها با آیکن‌های اکت‌آیکون داخل حباب‌های دایره‌ای: کامیت، ریویو، کامنت و force-push؛ قلب بصری صفحهٔ Conversation هر پول‌ریکوئست.',
            'en' => 'A vertical event rail with octicons in circular bubbles: commits, reviews, comments, force-pushes. The visual heart of every PR conversation.',
        ],
        'js' => true,
        'docs' => 'https://primer.style/components/timeline',
        'props' => [
            ['name' => 'rail', 'type' => '2px line', 'default' => "'border-muted'", 'note' => [
                'fa' => 'خط عمودی مویی پشت حباب‌ها؛ در RTL هم از سمت آغازین می‌گذرد.',
                'en' => 'The hairline vertical rail behind the bubbles; it hugs the start side in RTL too.',
            ]],
            ['name' => 'badge', 'type' => '32px circle', 'default' => "'octicon 16'", 'note' => [
                'fa' => 'حباب ۳۲ پیکسلی با آیکن رویداد؛ رنگش نوع رویداد را لو می‌دهد.',
                'en' => 'The 32px bubble carrying the event octicon; its colour names the event kind.',
            ]],
            ['name' => 'condense', 'type' => 'state', 'default' => "'false'", 'note' => [
                'fa' => 'حالت فشرده، کامیت‌های حاشیه‌ای را زیر «and ۳ more commits» جمع می‌کند.',
                'en' => 'Condensed mode folds side commits under "and 3 more commits".',
            ]],
            ['name' => 'comment card', 'type' => 'body', 'default' => "'markdown'", 'note' => [
                'fa' => 'کامنت‌ها با سربرگ نویسنده/زمان و بدنهٔ مارک‌داون در بدنهٔ تایم‌لاین می‌نشینند.',
                'en' => 'Comments sit on the rail with an author/time header and a markdown body.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="prt-item">
            <span class="prt-badge" data-kind="commit"><svg>…</svg></span>
            <div class="prt-body">
                <b>سارا کامیت زد</b> <time>۲ ساعت پیش</time>
                <p>فیلتر تاریخ را اصلاح کرد</p>
            </div>
        </div>

        <style>
        .prt-item { position: relative; display: flex; gap: 12px; padding-block-end: 24px; }
        .prt-item::before { content: ''; position: absolute; inset-block: 0; inset-inline-start: 15px;
                            inline-size: 2px; background: #d1d9e0; }
        .prt-badge { position: relative; z-index: 1; display: grid; place-items: center;
                     inline-size: 32px; aspect-ratio: 1; border-radius: 50%;
                     background: #ddf4ff; color: #0969da; }
        </style>
        BLADE,
    ],

    'reaction-bar' => [
        'title' => ['fa' => 'نوار واکنش‌ها', 'en' => 'Reaction Bar'],
        'icon' => 'heart',
        'oneLiner' => [
            'fa' => 'دکمه‌های واکنش ایموجی با شمارندهٔ گردی و پاپ‌اور افزودن واکنش؛ همان 😃🚀eyes گیت‌هاب که فیدبک سریع را به زبان ایموجی ترجمه می‌کند.',
            'en' => 'Emoji reaction pills with counters and an add-reaction picker. GitHub\'s 😃🚀👀 shorthand for instant, human feedback.',
        ],
        'js' => true,
        'docs' => 'https://primer.style/components/counter-label',
        'props' => [
            ['name' => 'pill', 'type' => 'button', 'default' => "'emoji + count'", 'note' => [
                'fa' => 'ایموجی و شمارندهٔ گرد در یک دکمه؛ واکنش شما حاشیهٔ آبی می‌گیرد.',
                'en' => 'Emoji and a rounded counter in one button; your own reaction gets the blue ring.',
            ]],
            ['name' => 'counter', 'type' => 'CounterLabel', 'default' => "'min-width 20px'", 'note' => [
                'fa' => 'شمارندهٔ گرد خاکستری؛ صفر مخفی می‌شود و قرص کل حذف می‌شود.',
                'en' => 'The rounded gray counter; at zero the pill drops out entirely.',
            ]],
            ['name' => 'add', 'type' => 'button', 'default' => "'smiley +'", 'note' => [
                'fa' => 'دکمهٔ + با پاپ‌اور ایموجی‌ها؛ واکنش تازه را همان‌جا می‌سازد.',
                'en' => 'The + button with an emoji popover; it mints a fresh reaction pill in place.',
            ]],
            ['name' => 'who', 'type' => 'tooltip', 'default' => "'aria-label'", 'note' => [
                'fa' => 'برچسب دسترس‌پذیری نام واکنش‌دهنده‌ها را می‌گوید.',
                'en' => 'The accessible label names who reacted.',
            ]],
        ],
        'code' => <<<'BLADE'
        <button class="prr-pill" aria-pressed="false" aria-label="۳ نفر 🚀 زدند">
            🚀 <span class="prr-count">۳</span>
        </button>
        <button class="prr-add" aria-label="افزودن واکنش">🙂⁺</button>

        <style>
        .prr-pill { display: inline-flex; align-items: center; gap: 6px; block-size: 28px;
                    padding-inline: 10px; border-radius: 2em; border: 1px solid #d1d9e0;
                    background: transparent; font: 500 13px system-ui; }
        .prr-count { min-inline-size: 20px; padding-inline: 6px; border-radius: 2em;
                     background: #eff2f5; font-size: 12px; }
        .prr-pill[aria-pressed='true'] { border-color: #0969da; }
        .prr-pill[aria-pressed='true'] .prr-count { background: #ddf4ff; color: #0969da; }
        </style>
        BLADE,
    ],

    'filter-bar' => [
        'title' => ['fa' => 'نوار فیلتر', 'en' => 'Filter Bar'],
        'icon' => 'search',
        'oneLiner' => [
            'fa' => 'نوار فیلتر با جست‌وجوی سینتکس‌محور (is:pr is:open label:bug) و دراپ‌داون‌های Label/Sort/State؛ قدرت کوئری گیت‌هاب در یک اینپوت خاکستریِ به‌ظاهر ساده.',
            'en' => 'Query-syntax search (is:pr is:open label:bug) with Label/Sort/State dropdowns. GitHub\'s search power dressed as one quiet gray input.',
        ],
        'js' => true,
        'docs' => 'https://primer.style/components/filtered-search',
        'props' => [
            ['name' => 'query', 'type' => 'input', 'default' => "'monospace-ish'", 'note' => [
                'fa' => 'اینپوت خاکستری با علامت جست‌وجو؛ کوئری به‌صورت متن خام ویرایش می‌شود.',
                'en' => 'The quiet gray input with a search glyph; the query edits as raw text.',
            ]],
            ['name' => 'menu', 'type' => 'dropdown', 'default' => "'Label · Sort · State'", 'note' => [
                'fa' => 'سه دکمهٔ منو که کلیکشان توکن متناظر را به کوئری می‌افزاید یا عوض می‌کند.',
                'en' => 'Three menu buttons; picking an option swaps in its query token.',
            ]],
            ['name' => 'count', 'type' => 'result line', 'default' => "'«n ایشو»'", 'note' => [
                'fa' => 'خط نتیجه زیر نوار، تعداد زنده را با اعداد فارسی می‌گوید.',
                'en' => 'The result line under the bar speaks the live count.',
            ]],
            ['name' => 'shortcut', 'type' => 'key', 'default' => "'/'", 'note' => [
                'fa' => 'کلید «/» فوکوس را به جست‌وجو می‌برد — عادت همیشگیِ گیت‌هاب.',
                'en' => "The '/' key jumps focus into search — the GitHub habit.",
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="prf-bar">
            <span class="prf-glyph"><svg>…</svg></span>
            <input class="prf-input" value="is:pr is:open label:باگ" spellcheck="false">
            <details class="prf-menu">
                <summary>Label</summary>
                <div class="prf-list">…options…</div>
            </details>
        </div>

        <style>
        .prf-bar { display: flex; align-items: center; gap: 8px; block-size: 32px;
                   padding-inline: 8px; border: 1px solid #d1d9e0; border-radius: 6px; }
        .prf-input { flex: 1; min-inline-size: 0; border: 0; background: transparent;
                     font: 400 14px ui-monospace, monospace; color: #1f2328; }
        .prf-menu[open] .prf-list { display: grid; }
        </style>
        BLADE,
    ],

    'merge-box' => [
        'title' => ['fa' => 'جعبهٔ ادغام', 'en' => 'Merge Box'],
        'icon' => 'layers',
        'oneLiner' => [
            'fa' => 'جعبهٔ ادغام با سه حالت Merge/Squash/Rebase، پیام کامیت پیش‌فرض و دکمهٔ سبز Merge pull request؛ لحظهٔ حقیقت هر PR که فقط گیت‌هاب این‌شکلی دارد.',
            'en' => 'The merge panel with Merge/Squash/Rebase options and the green Merge pull request button. The moment of truth of every PR, unmistakably GitHub.',
        ],
        'js' => true,
        'docs' => 'https://primer.style/components/action-menu',
        'props' => [
            ['name' => 'method', 'type' => 'radio', 'default' => "'merge'", 'note' => [
                'fa' => 'merge · squash · rebase؛ هر انتخاب، عنوان و بدنهٔ پیام کامیت را بازنویسی می‌کند.',
                'en' => 'merge · squash · rebase; each rewrite rewrites the commit title and body.',
            ]],
            ['name' => 'status', 'type' => 'state', 'default' => "'clean'", 'note' => [
                'fa' => 'بدون تعارض: تیک سبز و دکمهٔ فعال؛ تعارض: قرمز و دکمهٔ خاکستری.',
                'en' => 'Clean: a green tick and an armed button; conflicts: red and a muted button.',
            ]],
            ['name' => 'action', 'type' => 'button', 'default' => "'success · 6px'", 'note' => [
                'fa' => 'دکمهٔ سبز Merge pull request؛ پس از ادغام کل جعبه به حالت بنفش merged می‌رود.',
                'en' => 'The green Merge pull request button; after merging the whole box flips to the purple merged state.',
            ]],
            ['name' => 'commit message', 'type' => 'textarea', 'default' => "'PR title + body'", 'note' => [
                'fa' => 'پیام کامیت قابل‌ویرایش با پیش‌نمایش شمارهٔ PR (#۴۱۲).',
                'en' => 'The editable commit message with the PR number (#412) previewed.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="prm-box">
            <header class="prm-status">
                <svg>…</svg> This branch has no conflicts with the base branch
            </header>
            <label><input type="radio" name="prm-method" checked> Create a merge commit</label>
            <label><input type="radio" name="prm-method"> Squash and merge</label>
            <label><input type="radio" name="prm-method"> Rebase and merge</label>
            <button class="prm-go">Merge pull request</button>
        </div>

        <style>
        .prm-box { inline-size: min(100%, 30rem); padding: 16px; border: 1px solid #d1d9e0;
                   border-radius: 6px; background: #f6f8fa; }
        .prm-status { color: #1a7f37; font: 500 14px system-ui; }
        .prm-go { block-size: 32px; padding-inline: 12px; border-radius: 6px; border: 0;
                  background: #1f883d; color: #fff; font: 500 14px system-ui; }
        </style>
        BLADE,
    ],

    'underline-nav' => [
        'title' => ['fa' => 'ناوبری زیرخط‌دار', 'en' => 'Underline Nav'],
        'icon' => 'grid',
        'oneLiner' => [
            'fa' => 'تب‌های زیرخط‌دار Conversation/Commits/Checks/Files changed با شمارنده‌های زندهٔ CounterLabel؛ زیرناوبری امضای گیت‌هاب که نسخهٔ کاملاً سیستمیِ تب معمولی است.',
            'en' => 'Conversation/Commits/Checks/Files changed underlined tabs with live CounterLabels. GitHub\'s signature sub-navigation for issues and PRs.',
        ],
        'js' => true,
        'docs' => 'https://primer.style/components/underline-nav',
        'props' => [
            ['name' => 'selected', 'type' => 'underline', 'default' => "'2px orange?'", 'note' => [
                'fa' => 'زیرخط ۲ پیکسلی روی تب انتخاب‌شده؛ رنگ پیش‌فرض fg-color، در هاور پنجرهٔ گرد خاکستری.',
                'en' => 'The 2px underline on the selected tab; default fg-colour, with a soft gray rounded hover window.',
            ]],
            ['name' => 'counter', 'type' => 'CounterLabel', 'default' => "'rounded 2em'", 'note' => [
                'fa' => 'شمارندهٔ گرد در انتهای هر تب؛ انتخاب‌شده پررنگ‌تر دیده می‌شود.',
                'en' => 'The rounded counter at each tab end; the selected one reads bolder.',
            ]],
            ['name' => 'overflow', 'type' => 'scroll', 'default' => "'inline'", 'note' => [
                'fa' => 'روی موبایل تب‌ها افقی می‌لغزند؛ هیچ‌کس نمی‌شکند.',
                'en' => 'On mobile the tabs glide inline; nothing wraps away.',
            ]],
            ['name' => 'icon', 'type' => 'octicon · 16px', 'default' => "'optional'", 'note' => [
                'fa' => 'هر تب می‌تواند آیکن ۱۶ پیکسلی پیش از برچسب داشته باشد.',
                'en' => 'Each tab may carry a 16px octicon before its label.',
            ]],
        ],
        'code' => <<<'BLADE'
        <nav class="prn-nav" aria-label="صفحهٔ PR">
            <button class="prn-tab" aria-current="page">
                <svg>…</svg> Conversation <span class="prn-count">۴</span>
            </button>
            <button class="prn-tab">
                <svg>…</svg> Commits <span class="prn-count">۷</span>
            </button>
        </nav>

        <style>
        .prn-nav { display: flex; gap: 8px; border-block-end: 1px solid #d1d9e0;
                   overflow-x: auto; }
        .prn-tab { display: inline-flex; align-items: center; gap: 8px; padding: 8px;
                   border: 0; border-block-end: 2px solid transparent; background: none;
                   font: 500 14px system-ui; }
        .prn-tab[aria-current='page'] { border-block-end-color: #1f2328; }
        .prn-count { min-inline-size: 20px; padding-inline: 6px; border-radius: 2em;
                     background: #eff2f5; font: 500 12px/20px system-ui; }
        </style>
        BLADE,
    ],

    'actions-runs' => [
        'title' => ['fa' => 'ران‌های اکشنز', 'en' => 'Actions Runs'],
        'icon' => 'zap',
        'oneLiner' => [
            'fa' => 'فهرست ران‌های CI با اسپینر زرد، تیک سبز، ضربدر قرمز و متای branch+SHA+زمان؛ صفحهٔ Actions که سلامت کد را در یک نگاه لو می‌دهد.',
            'en' => 'CI run list with yellow spinners, green checks, red X marks and branch+SHA+duration meta. The Actions page that shows repo health at a glance.',
        ],
        'js' => true,
        'docs' => 'https://primer.style/components/action-list',
        'props' => [
            ['name' => 'status icon', 'type' => '16px', 'default' => "'spinner · ✓ · ✕'", 'note' => [
                'fa' => 'اسپینر زرد attention برای در جریان، تیک سبز برای موفق، ضربدر قرمز برای شکست، دایرهٔ خاکستری برای skip.',
                'en' => 'The yellow attention spinner for in-progress, green check for success, red X for failure, gray circle for skipped.',
            ]],
            ['name' => 'title row', 'type' => 'workflow · run', 'default' => "'bold + muted'", 'note' => [
                'fa' => 'نام ورک‌فلو پررنگ و پیام کامیت خاکستری زیر آن.',
                'en' => 'The workflow name bold, the commit message gray beneath.',
            ]],
            ['name' => 'meta', 'type' => 'branch · sha · time', 'default' => "'12px'", 'note' => [
                'fa' => 'متا: شاخه، هفت‌رقمیِ SHA و مدت اجرا با فونت ثابت.',
                'en' => 'Meta: branch, the 7-char SHA and the run duration in mono.',
            ]],
            ['name' => 'filter', 'type' => 'segmented', 'default' => "'All · ✓ · ✕'", 'note' => [
                'fa' => 'فیلتر وضعیت بالای فهرست که ردیف‌ها را زنده می‌کاهد.',
                'en' => 'The status filter above the list that live-trims the rows.',
            ]],
        ],
        'code' => <<<'BLADE'
        <a class="pra-run" href="#">
            <span class="pra-status" data-run="running"><svg class="pra-spin">…</svg></span>
            <span class="pra-body">
                <span class="pra-name">CI / build و تست</span>
                <span class="pra-msg">fix: نشت حافظهٔ کش</span>
                <span class="pra-meta">main · <code>a1b2c3d</code> · ۳ دقیقه</span>
            </span>
        </a>

        <style>
        .pra-run { display: flex; gap: 12px; padding: 12px 16px;
                   border-block-end: 1px solid #d1d9e0; }
        .pra-status[data-run='running'] { color: #9a6700; }
        .pra-status[data-run='success'] { color: #1a7f37; }
        .pra-status[data-run='failure'] { color: #cf222e; }
        .pra-spin { animation: pra-spin 1s linear infinite; }
        @keyframes pra-spin { to { rotate: 360deg; } }
        </style>
        BLADE,
    ],
];
