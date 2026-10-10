{{--
    GitHub's hex-coloured Label: a live labels page where a picker form mints
    new chips (name + hue swatches + instant preview), rows are deletable and
    the CounterLabel keeps score — then the classic palette strip.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (int|string $n): string => $fa ? strtr((string) $n, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : (string) $n;
    $labels = [
        ['name' => ['en' => 'bug', 'fa' => 'باگ'], 'hue' => '#d73a4a', 'desc' => ['en' => 'Something is broken', 'fa' => 'چیزی خراب شده'], 'count' => 12],
        ['name' => ['en' => 'design', 'fa' => 'طراحی'], 'hue' => '#0e8a16', 'desc' => ['en' => 'Visual and UX work', 'fa' => 'کار بصری و تجربهٔ کاربری'], 'count' => 5],
        ['name' => ['en' => 'enhancement', 'fa' => 'بهبود'], 'hue' => '#a2eeef', 'desc' => ['en' => 'New feature or request', 'fa' => 'قابلیت یا درخواست تازه'], 'count' => 9],
        ['name' => ['en' => 'good first issue', 'fa' => 'شروع خوب'], 'hue' => '#7057ff', 'desc' => ['en' => 'A friendly first task', 'fa' => 'تسک دوستانه برای شروع'], 'count' => 3],
    ];
@endphp
<style>
    @import url('https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap');
    .prl-root {
        --prl-canvas: #ffffff; --prl-subtle: #f6f8fa; --prl-fg: #1f2328; --prl-muted: #59636e;
        --prl-border: #d1d9e0; --prl-accent: #0969da; --prl-danger: #cf222e;
        font-family: 'Figtree', 'Inter', 'Vazirmatn', sans-serif;
        color: var(--prl-fg);
    }
    html[data-theme="dark"] .prl-root {
        --prl-canvas: #0d1117; --prl-subtle: #151b23; --prl-fg: #f0f6fc; --prl-muted: #9198a1;
        --prl-border: #3d444d; --prl-accent: #4493f8; --prl-danger: #f85149;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .prl-root {
            --prl-canvas: #0d1117; --prl-subtle: #151b23; --prl-fg: #f0f6fc; --prl-muted: #9198a1;
            --prl-border: #3d444d; --prl-accent: #4493f8; --prl-danger: #f85149;
        }
    }
    .prl-root :focus-visible { outline: 2px solid var(--prl-accent); outline-offset: 2px; border-radius: 6px; }

    .prl-page { inline-size: min(100%, 42rem); margin-inline: auto; border: 1px solid var(--prl-border); border-radius: 6px; background: var(--prl-canvas); }
    .prl-head { display: flex; flex-wrap: wrap; gap: .5rem 1rem; align-items: center; padding: .625rem 1rem; border-block-end: 1px solid var(--prl-border); background: var(--prl-subtle); }
    .prl-head h4 { margin: 0; font: 600 .875rem/1.2 inherit; }
    .prl-count { display: inline-grid; place-items: center; min-inline-size: 1.25rem; padding-inline: .4rem; block-size: 1.25rem; border-radius: 2em; background: color-mix(in srgb, var(--prl-border) 55%, transparent); font: 500 .75rem/1 inherit; }
    .prl-row { display: flex; flex-wrap: wrap; gap: .5rem 1rem; align-items: center; padding: .625rem 1rem; border-block-end: 1px solid var(--prl-border); }
    .prl-row:last-child { border-block-end: 0; }
    .prl-chip { display: inline-flex; align-items: center; block-size: 1.25rem; padding-inline: .45rem; border-radius: 2em; font: 500 .75rem/1 inherit; white-space: nowrap; color: color-mix(in srgb, var(--hue) 58%, var(--prl-fg)); background: color-mix(in srgb, var(--hue) 10%, var(--prl-canvas)); border: 1px solid color-mix(in srgb, var(--hue) 42%, transparent); }
    .prl-desc { flex: 1 1 10rem; min-inline-size: 0; font: 400 .75rem/1.4 inherit; color: var(--prl-muted); overflow-wrap: anywhere; }
    .prl-uses { font: 400 .75rem/1 inherit; color: var(--prl-muted); white-space: nowrap; }
    .prl-uses a { color: inherit; text-decoration: none; }
    .prl-uses a:hover { color: var(--prl-accent); }
    .prl-del { flex: none; inline-size: 1.6rem; aspect-ratio: 1; display: grid; place-items: center; border: 0; border-radius: 6px; background: none; color: var(--prl-muted); cursor: pointer; }
    .prl-del:hover { color: var(--prl-danger); background: color-mix(in srgb, var(--prl-danger) 10%, transparent); }
    .prl-form { display: flex; flex-wrap: wrap; gap: .75rem; align-items: center; padding: .875rem 1rem; border-block-start: 1px solid var(--prl-border); background: var(--prl-subtle); }
    .prl-input { inline-size: 11rem; max-inline-size: 100%; block-size: 2rem; padding-inline: .6rem; border: 1px solid var(--prl-border); border-radius: 6px; background: var(--prl-canvas); color: var(--prl-fg); font: 400 .8125rem/1 inherit; }
    .prl-swatches { display: flex; gap: .3rem; }
    .prl-swatch { inline-size: 1.35rem; aspect-ratio: 1; border-radius: 6px; border: 2px solid transparent; cursor: pointer; padding: 0; }
    .prl-swatch[aria-pressed="true"] { border-color: var(--prl-fg); }
    .prl-preview { display: inline-flex; align-items: center; gap: .4rem; font: 400 .75rem/1 inherit; color: var(--prl-muted); }
    .prl-btn { block-size: 2rem; padding-inline: .85rem; border: 0; border-radius: 6px; background: #1f883d; color: #fff; font: 500 .8125rem/1 inherit; cursor: pointer; }
    html[data-theme="dark"] .prl-btn { background: #238636; }
    .prl-btn:hover { background: #1a7f37; }
    .prl-btn:disabled { opacity: .55; cursor: not-allowed; }
    .prl-strip { display: flex; flex-wrap: wrap; gap: .5rem; align-items: center; justify-content: center; }
    .prl-more { display: inline-grid; place-items: center; padding-inline: .5rem; block-size: 1.25rem; border-radius: 2em; background: color-mix(in srgb, var(--prl-border) 55%, transparent); font: 500 .75rem/1 inherit; }
    /* The shared demo template hides the props table's rows until it scrolls
       into view (data-nx-reveal on x-nx::data-table); a capture that never
       scrolls records an empty table. Pin this page's rows visible. */
    html:has(.prl-root) .nx-data-table[data-nx-reveal]:not([data-nx-revealed]) tbody tr { opacity: 1; translate: none; }
    @media (max-width: 480px) {
        .prl-input { inline-size: 100%; }
        .prl-form .prl-btn { flex: 1 1 100%; }
    }
    @media (prefers-reduced-motion: reduce) {
        .prl-root * { transition-duration: .01ms !important; }
    }
</style>

<div class="prl-root" x-data='{
        rtl: document.documentElement.dir === "rtl",
        labels: @json($labels),
        draft: "", hue: "#d93f0b",
        hues: ["#d73a4a", "#d93f0b", "#0e8a16", "#0075ca", "#a2eeef", "#7057ff", "#ffffff"],
        create() {
            const n = this.draft.trim();
            if (!n) return;
            this.labels.unshift({ name: { en: n, fa: n }, hue: this.hue, desc: { en: "", fa: "" }, count: 0, fresh: true });
            this.draft = "";
        },
        num(n) { return this.rtl ? String(n).replace(/\d/g, (d) => "۰۱۲۳۴۵۶۷۸۹"[d]) : String(n) },
        labelName(item) { return this.rtl ? item.name.fa : item.name.en },
        labelDesc(item) { return this.rtl ? item.desc.fa : item.desc.en },
    }'>
    <section class="pg-box" style="gap: 1.25rem">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Six characters of hex, one identity', 'شش نویسهٔ هگز، یک هویت') }}</h3>
            <p style="margin: 0; max-width: 48ch; color: var(--nx-text-muted)">
                {{ $say('Mint a label: type a name, tap a hue swatch and watch the preview pick its matching border and tinted field. Created chips join the taxonomy — and leave with the ✕.', 'لیبل بسازید: نامی بنویسید، یک نمونهٔ رنگ لمس کنید و ببینید پیش‌نمایش چطور حاشیهٔ هم‌رنگ و زمینهٔ رنگی‌اش را می‌گیرد. چیپ‌های ساخته‌شده به تاکسونومی می‌پیوندند — و با ✕ می‌روند.') }}
            </p>
        </div>

        <div class="prl-page">
            <div class="prl-head">
                <h4>{{ $say('Labels', 'لیبل‌ها') }}</h4>
                <span class="prl-count" aria-live="polite" x-text="num(labels.length)"></span>
                <span style="margin-inline-start: auto; font: 400 .75rem/1 inherit; color: var(--prl-muted)">{{ $say('nabux / dashboard', 'nabux / dashboard') }}</span>
            </div>

            <template x-for="(item, i) in labels" :key="i">
                <div class="prl-row">
                    <span class="prl-chip" :style="'--hue: ' + item.hue" x-text="labelName(item)"></span>
                    <span class="prl-desc" x-text="labelDesc(item) || (rtl ? 'بدون توضیح' : 'no description')"></span>
                    <span class="prl-uses"><a href="#" x-on:click.prevent x-text="rtl ? num(item.count) + ' ایشو' : item.count + ' issues'"></a></span>
                    <button type="button" class="prl-del" :aria-label="rtl ? 'حذف ' + labelName(item) : 'Delete ' + labelName(item)" x-on:click="labels.splice(i, 1)">
                        <svg aria-hidden="true" viewBox="0 0 16 16" width="14" height="14" fill="currentColor" style="display:block"><path d="M3.72 3.72a.75.75 0 0 1 1.06 0L8 6.94l3.22-3.22a.749.749 0 0 1 1.275.326.749.749 0 0 1-.215.734L9.06 8l3.22 3.22a.749.749 0 0 1-.326 1.275.749.749 0 0 1-.734-.215L8 9.06l-3.22 3.22a.751.751 0 0 1-1.042-.018.751.751 0 0 1-.018-1.042L6.94 8 3.72 4.78a.75.75 0 0 1 0-1.06Z"/></svg>
                    </button>
                </div>
            </template>

            <form class="prl-form" x-on:submit.prevent="create()">
                <input class="prl-input" type="text" x-model="draft" :placeholder="rtl ? 'نام لیبل تازه' : 'new label name'" :aria-label="rtl ? 'نام لیبل' : 'Label name'">
                <span class="prl-swatches" role="group" :aria-label="rtl ? 'انتخاب رنگ' : 'Pick a hue'">
                    <template x-for="h in hues" :key="h">
                        <button type="button" class="prl-swatch" :style="'--hue: ' + h + '; background: ' + h" :aria-pressed="hue === h" :aria-label="h" x-on:click="hue = h"></button>
                    </template>
                </span>
                <span class="prl-preview">
                    {{ $say('preview', 'پیش‌نمایش') }}
                    <span class="prl-chip" :style="'--hue: ' + hue" x-text="draft.trim() || (rtl ? 'نام' : 'name')"></span>
                </span>
                <button type="submit" class="prl-btn" :disabled="!draft.trim()">{{ $say('Create label', 'ساخت لیبل') }}</button>
            </form>
        </div>
    </section>
</div>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('The classic palette', 'پالت کلاسیک') }}</h3>
        <p style="margin: 0; max-width: 50ch; color: var(--nx-text-muted)">
            {{ $say('Every GitHub repo speaks in these hues — and even the white «wontfix» chip keeps a visible border. The trailing counter trims the rest of the set.', 'هر مخزن گیت‌هاب با همین رنگ‌ها حرف می‌زند — و حتی چیپ سفید «wontfix» هم حاشیهٔ دیدنی دارد. شمارندهٔ آخر، باقی مجموعه را می‌چیند.') }}
        </p>
    </div>
    <div class="prl-root prl-strip">
        <span class="prl-chip" style="--hue: #d73a4a">bug</span>
        <span class="prl-chip" style="--hue: #0075ca">documentation</span>
        <span class="prl-chip" style="--hue: #a2eeef">enhancement</span>
        <span class="prl-chip" style="--hue: #7057ff">good first issue</span>
        <span class="prl-chip" style="--hue: #008672">help wanted</span>
        <span class="prl-chip" style="--hue: #ffffff">wontfix</span>
        <span class="prl-chip" style="--hue: #d93f0b">priority: critical</span>
        <span class="prl-more">{{ $say('+6 more', '+۶ بیشتر') }}</span>
    </div>
</section>
