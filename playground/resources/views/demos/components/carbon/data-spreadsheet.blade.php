{{--
    Carbon's data spreadsheet as a monthly budget canvas: lettered columns,
    numbered rows, cell-level selection with the 2px carbon-blue ring, a
    coordinate box and formula bar that follow the active cell, and arrow-key
    navigation across the grid. The variants row shows a sparse sheet and a
    header-less one.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹', ',' => '٬']) : $s;
    $cols = ['A', 'B', 'C', 'D', 'E'];
    $rows = [1, 2, 3, 4, 5, 6];
    // Budget data: [rowLabel][colLetter] — sparse on purpose.
    $data = [
        1 => ['', $fa ? 'فروردین' : 'Farvardin', $fa ? 'اردیبهشت' : 'Ordibehesht', $fa ? 'خرداد' : 'Khordad', ''],
        2 => [$fa ? 'درآمد' : 'Revenue', '84,000', '91,500', '88,200', ''],
        3 => [$fa ? 'زیرساخت' : 'Infrastructure', '22,400', '22,400', '23,900', ''],
        4 => [$fa ? 'پشتیبانی' : 'Support', '11,800', '12,600', '12,100', ''],
        5 => [$fa ? 'سود' : 'Margin', '49,800', '56,500', '52,200', ''],
        6 => ['', '', '', '', ''],
    ];
    $cell = fn (int $r, string $c): string => $data[$r][array_search($c, $cols, true)] ?? '';
@endphp
<style>
    .cbsp-root {
        --cbsp-accent: #0f62fe; --cbsp-accent-hover: #0353e9;
        --cbsp-text: #161616; --cbsp-text-secondary: #525252;
        --cbsp-border: #e0e0e0; --cbsp-border-strong: #8d8d8d;
        --cbsp-layer: #f4f4f4; --cbsp-layer-hover: #e8e8e8;
        --cbsp-header: #e0e0e0;
        --cbsp-mono: 'IBM Plex Mono', Menlo, Consolas, monospace;
        --cbsp-font: 'IBM Plex Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        font-family: var(--cbsp-font);
        display: grid; gap: 2rem; justify-items: center;
    }
    html[data-theme="dark"] .cbsp-root {
        --cbsp-accent: #4589ff; --cbsp-accent-hover: #78a9ff;
        --cbsp-text: #f4f4f4; --cbsp-text-secondary: #c6c6c6;
        --cbsp-border: #393939; --cbsp-border-strong: #8d8d8d;
        --cbsp-layer: #262626; --cbsp-layer-hover: #333333;
        --cbsp-header: #393939;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .cbsp-root {
            --cbsp-accent: #4589ff; --cbsp-accent-hover: #78a9ff;
            --cbsp-text: #f4f4f4; --cbsp-text-secondary: #c6c6c6;
            --cbsp-border: #393939; --cbsp-border-strong: #8d8d8d;
            --cbsp-layer: #262626; --cbsp-layer-hover: #333333;
            --cbsp-header: #393939;
        }
    }
    .cbsp { inline-size: min(100%, 44rem); border: 1px solid var(--cbsp-border-strong); background: var(--cbsp-layer); }
    .cbsp-ruler { display: flex; border-block-end: 1px solid var(--cbsp-border-strong); }
    .cbsp-ref { display: grid; place-items: center; min-inline-size: 3.5rem; padding: .5rem .625rem; border-inline-end: 1px solid var(--cbsp-border-strong);
                font: 600 .8125rem/1 var(--cbsp-mono); color: var(--cbsp-text); background: var(--cbsp-header); }
    .cbsp-formula { flex: 1; display: flex; align-items: center; gap: .5rem; padding: .5rem .625rem; overflow: hidden;
                    font: 400 .8125rem/1.3 var(--cbsp-mono); color: var(--cbsp-text); }
    .cbsp-formula b { font-weight: 400; color: var(--cbsp-text-secondary); }
    .cbsp-scroll { overflow-x: auto; }
    .cbsp-grid { display: grid; grid-template-columns: 3.5rem repeat(5, minmax(5.5rem, 1fr)); min-inline-size: 34rem; }
    .cbsp-colhead, .cbsp-rowhead { display: grid; place-items: center; block-size: 2rem; font: 400 .75rem/1 var(--cbsp-mono);
                                   color: var(--cbsp-text-secondary); background: var(--cbsp-header);
                                   border-inline-end: 1px solid var(--cbsp-border); border-block-end: 1px solid var(--cbsp-border); }
    .cbsp-rowhead { position: relative; }
    .cbsp-colhead[data-hi], .cbsp-rowhead[data-hi] { background: color-mix(in srgb, var(--cbsp-accent) 18%, var(--cbsp-header)); color: var(--cbsp-text); }
    .cbsp-cell { display: flex; align-items: center; min-block-size: 2rem; padding-inline: .625rem; border-inline-end: 1px solid var(--cbsp-border);
                 border-block-end: 1px solid var(--cbsp-border); background: var(--cbsp-layer); color: var(--cbsp-text);
                 font: 400 .8125rem/1.2 var(--cbsp-font); text-align: start; cursor: cell; overflow: hidden; white-space: nowrap; }
    .cbsp-cell:hover { background: var(--cbsp-layer-hover); }
    .cbsp-cell:focus-visible { outline: none; }
    .cbsp-cell[data-active] { outline: 2px solid var(--cbsp-accent); outline-offset: -2px; background: var(--cbsp-layer-hover); }
    .cbsp-cell[data-num] { font-family: var(--cbsp-mono); justify-content: flex-end; }
    .cbsp-cell[data-total] { font-weight: 600; }
    .cbsp-hint { margin: .75rem 0 0; font-size: .8125rem; color: var(--nx-text-muted); max-width: 56ch; }
    .cbsp-spec { display: grid; gap: 1.5rem; justify-items: center; }
    .cbsp-spec-row { display: flex; flex-wrap: wrap; gap: 1.5rem; justify-content: center; align-items: flex-start; }
    .cbsp-spec-cell { display: grid; gap: .5rem; }
    .cbsp-spec-cell > small { font-size: .72rem; color: var(--nx-text-muted); }
    .cbsp-spec .cbsp { inline-size: min(19rem, 84vw); }
    .cbsp-spec .cbsp-grid { grid-template-columns: 2.5rem repeat(3, minmax(4rem, 1fr)); min-inline-size: 0; }
    .cbsp-spec .cbsp-colhead:first-child { inline-size: 2.5rem; }
    .cbsp-sparse .cbsp-cell[data-empty] { background: color-mix(in srgb, var(--cbsp-layer) 55%, transparent); }
    :where(.nx-js) .pg:has(.cbsp-root) .nx-data-table[data-nx-reveal] tbody tr { opacity: 1; translate: none; }
    @media (max-width: 480px) {
        .pg:has(.cbsp-root) .nx-data-table :is(th, td) { white-space: normal; padding-inline: .5rem; overflow-wrap: break-word; }
        .cbsp { inline-size: 100%; }
    }
    @media (prefers-reduced-motion: reduce) {
        .cbsp-root * { transition-duration: .01ms !important; animation-duration: .01ms !important; }
    }
</style>

<div class="cbsp-root"
    x-data="{
        cols: ['A', 'B', 'C', 'D', 'E'],
        rows: [1, 2, 3, 4, 5, 6],
        active: 'B2',
        get ref() { const m = this.active.match(/^([A-Z]+)(\d+)$/); return m ? { c: m[1], r: +m[2] } : { c: 'A', r: 1 } },
        get hiCol() { return this.ref.c },
        get hiRow() { return this.ref.r },
        value(el) { return (el?.dataset?.value || '').trim() },
        cellEl(c, r) { return this.$root.querySelector('[data-ref=' + c + r + ']') },
        select(c, r) {
            if (!this.cols.includes(c) || !this.rows.includes(r)) return;
            this.active = c + r;
            this.$nextTick(() => this.cellEl(c, r)?.focus({ preventScroll: false }));
        },
        move(e) {
            const d = { ArrowUp: [0, -1], ArrowDown: [0, 1], ArrowLeft: [-1, 0], ArrowRight: [1, 0] }[e.key];
            if (!d) return;
            e.preventDefault();
            const rtl = document.documentElement.dir === 'rtl';
            const dCol = rtl ? -d[0] : d[0];
            const idx = this.cols.indexOf(this.ref.c);
            const c = this.cols[Math.min(this.cols.length - 1, Math.max(0, idx + dCol))];
            const r = Math.min(this.rows.length, Math.max(1, this.ref.r + d[1]));
            this.select(c, r);
        },
    }">
    <section class="pg-box" style="justify-items: center">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Not a table — a canvas', 'جدول نیست — بوم است') }}</h3>
            <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
                {{ $say('Lettered columns, numbered rows, and cell-level selection: click any cell or walk with the arrow keys — the 2px blue ring, the coordinate box and the formula bar all follow you. Empty cells keep their slot so the grid never shifts.', 'ستون‌های حرفی، سطرهای عددی و انتخابِ سل‌به‌سل: هر سل را کلیک کنید یا با کلیدهای جهت حرکت کنید — حلقهٔ آبی ۲پیکسلی، جعبهٔ مختصات و نوار فرمول همه دنبال شما می‌آیند. سل‌های خالی جای خود را نگه می‌دارند تا شبکه جابه‌جا نشود.') }}
            </p>
        </div>

        <div class="cbsp" role="grid" aria-label="{{ $say('Quarterly budget', 'بودجهٔ سه‌ماهه') }}"
            x-on:keydown="move($event)">
            <div class="cbsp-ruler">
                <span class="cbsp-ref" x-text="active">B2</span>
                <span class="cbsp-formula"><b>ƒx</b><span x-text="value(cellEl(ref.c, ref.r)) || '—'"></span></span>
            </div>
            <div class="cbsp-scroll">
                <div class="cbsp-grid">
                    <span class="cbsp-colhead" aria-hidden="true"></span>
                    @foreach ($cols as $col)
                        <span class="cbsp-colhead" role="columnheader" x-bind:data-hi="(hiCol === '{{ $col }}') ? 'true' : null">{{ $col }}</span>
                    @endforeach
                    @foreach ($rows as $row)
                        <span class="cbsp-rowhead" role="rowheader" x-bind:data-hi="(hiRow === {{ $row }}) ? 'true' : null">{{ $num((string) $row) }}</span>
                        @foreach ($cols as $col)
                            @php $v = $cell($row, $col); $isNum = preg_match('/^[\d,]/', $v) === 1; @endphp
                            <button type="button" class="cbsp-cell" role="gridcell"
                                data-ref="{{ $col }}{{ $row }}"
                                data-value="{{ $v }}"
                                x-bind:data-active="(active === '{{ $col }}{{ $row }}') ? 'true' : null"
                                x-bind:tabindex="(active === '{{ $col }}{{ $row }}') ? '0' : '-1'"
                                @if ($isNum) data-num @endif
                                @if ($row === 5 && $col !== 'A') data-total @endif
                                x-on:click="select('{{ $col }}', {{ $row }})">
                                @if ($isNum){{ $num($v) }}@else{{ $v }}@endif
                            </button>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
        <p class="cbsp-hint">
            {{ $say('Row ۵ is the margin line — the data-total cells stay bold at every zoom, and the ruler column headers highlight with the active cell.', 'سطر ۵ خط سود است — سل‌های data-total در هر بزرگ‌نمایی توپر می‌مانند و سربرگ‌های خط‌کش با سلِ فعال هایلایت می‌شوند.') }}
        </p>
    </section>
</div>

<section class="pg-box" style="justify-items: center">
    <div class="cbsp-root" style="inline-size: 100%">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('The whole family', 'همهٔ خانواده') }}</h3>
            <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
                {{ $say('Sparse data, header-less ruler and the compact ruler — the canvas shape never shifts under the data.', 'دیتای خلوت، خط‌کش بی‌سربرگ و خط‌کش فشرده — شکل بوم زیر داده هرگز جابه‌جا نمی‌شود.') }}
            </p>
        </div>
        <div class="cbsp-spec">
        <div class="cbsp-spec-row">
            <div class="cbsp-spec-cell">
                <div class="cbsp cbsp-sparse" aria-hidden="true">
                    <div class="cbsp-grid">
                        <span class="cbsp-colhead"></span>
                        <span class="cbsp-colhead">A</span><span class="cbsp-colhead">B</span><span class="cbsp-colhead">C</span>
                        @foreach ([1 => ['۹٬۲۰۰', '', '۴٬۱۰۰'], 2 => ['', '۷٬۸۰۰', '']] as $r => $cells)
                            <span class="cbsp-rowhead">{{ $num((string) $r) }}</span>
                            @foreach ($cells as $v)
                                <span class="cbsp-cell" data-num{{ $v === '' ? ' data-empty' : '' }}>{{ $v }}</span>
                            @endforeach
                        @endforeach
                    </div>
                </div>
                <small>sparse</small>
            </div>
            <div class="cbsp-spec-cell">
                <div class="cbsp" aria-hidden="true">
                    <div class="cbsp-grid">
                        <span class="cbsp-colhead"></span>
                        <span class="cbsp-colhead">A</span><span class="cbsp-colhead">B</span><span class="cbsp-colhead">C</span>
                        <span class="cbsp-rowhead">۱</span>
                        <span class="cbsp-cell" data-num>۱۲٬۵۰۰</span><span class="cbsp-cell"></span><span class="cbsp-cell" data-num>۹۹۰</span>
                    </div>
                </div>
                <small>header-less ruler · single row</small>
            </div>
        </div>
        </div>
    </div>
</section>
