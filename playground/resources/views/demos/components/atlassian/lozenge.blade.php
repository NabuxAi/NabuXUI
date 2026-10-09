{{--
    The Atlassian lozenge on a live Jira work-item list: each row's status
    chip cycles through the six appearances on click, and the second box lays
    out the whole subtle/bold family with the 200px clamp.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    // appearance => [en label, fa label] — the six official lozenge appearances.
    $statuses = [
        'backlog'    => ['Backlog', 'کارهای عقب'],
        'inprogress' => ['In progress', 'در جریان'],
        'review'     => ['In review', 'در بازبینی'],
        'done'       => ['Done', 'انجام شد'],
        'moved'      => ['Moved', 'جابه‌جا شد'],
        'removed'    => ['Removed', 'حذف شد'],
    ];
    $labels = array_map(static fn (array $s): string => $say($s[0], $s[1]), $statuses);
    $issues = [
        ['key' => 'NABU-241', 'en' => 'Payment webhook retries', 'fa' => 'تلاش دوبارهٔ وب‌هوک پرداخت', 'start' => 'review'],
        ['key' => 'NABU-247', 'en' => 'Advanced search tokens', 'fa' => 'توکن‌های جست‌وجوی پیشرفته', 'start' => 'inprogress'],
        ['key' => 'NABU-253', 'en' => 'Dark theme audit', 'fa' => 'ممیزی تم تیره', 'start' => 'backlog'],
        ['key' => 'NABU-258', 'en' => 'Webhook secret rotation', 'fa' => 'چرخش رمز وب‌هوک', 'start' => 'done'],
    ];
@endphp
<style>
    .atlz-root {
        --atlz-blue: #0052CC; --atlz-ink: #172B4D; --atlz-muted: #626F86; --atlz-subtle: #44546F;
        --atlz-border: #DFE1E6; --atlz-hover: #F1F2F4; --atlz-surface: #FFFFFF;
        --atlz-green: #1F845A; --atlz-green-tint: #DCFFF1;
        --atlz-yellow: #A54800; --atlz-yellow-tint: #FFF7D6;
        --atlz-red: #AE2A19; --atlz-red-solid: #B40000; --atlz-red-tint: #FFEDEB;
        --atlz-purple: #5E4DB2; --atlz-purple-solid: #6E5DC6; --atlz-purple-tint: #EAE6FF;
        font-family: Inter, system-ui, sans-serif; color: var(--atlz-ink);
    }
    html[data-theme="dark"] .atlz-root {
        --atlz-blue: #388BFF; --atlz-ink: #C7D1DB; --atlz-muted: #8590A2; --atlz-subtle: #A9B8C4;
        --atlz-border: #2C3136; --atlz-hover: #1D2125; --atlz-surface: #161A1D;
        --atlz-green: #4BCE97; --atlz-green-tint: #1C322A;
        --atlz-yellow: #F5CD47; --atlz-yellow-tint: #38301C;
        --atlz-red: #F87168; --atlz-red-solid: #C9372C; --atlz-red-tint: #3B2222;
        --atlz-purple: #9F8FEF; --atlz-purple-solid: #6E5DC6; --atlz-purple-tint: #2A2745;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .atlz-root {
            --atlz-blue: #388BFF; --atlz-ink: #C7D1DB; --atlz-muted: #8590A2; --atlz-subtle: #A9B8C4;
            --atlz-border: #2C3136; --atlz-hover: #1D2125; --atlz-surface: #161A1D;
            --atlz-green: #4BCE97; --atlz-green-tint: #1C322A;
            --atlz-yellow: #F5CD47; --atlz-yellow-tint: #38301C;
            --atlz-red: #F87168; --atlz-red-solid: #C9372C; --atlz-red-tint: #3B2222;
            --atlz-purple: #9F8FEF; --atlz-purple-solid: #6E5DC6; --atlz-purple-tint: #2A2745;
        }
    }
    .atlz-root :focus-visible { outline: 2px solid var(--atlz-blue); outline-offset: 2px; }

    .atlz-chip { display: inline-block; padding: 2px 8px; border-radius: 3px; font: 700 .7rem/1.6 Inter, system-ui;
                 letter-spacing: .2px; max-inline-size: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .atlz-chip[data-tone='backlog']    { background: var(--atlz-hover); color: var(--atlz-subtle); }
    .atlz-chip[data-tone='inprogress'] { background: var(--atlz-blue-tint, #E9F2FF); }
    .atlz-chip[data-tone='review']     { background: var(--atlz-yellow-tint); color: var(--atlz-yellow); }
    .atlz-chip[data-tone='done']       { background: var(--atlz-green-tint); color: var(--atlz-green); }
    .atlz-chip[data-tone='moved']      { background: var(--atlz-purple-tint); color: var(--atlz-purple); }
    .atlz-chip[data-tone='removed']    { background: var(--atlz-red-tint); color: var(--atlz-red); }
    .atlz-chip[data-tone='inprogress'] { color: #0055CC; }
    html[data-theme="dark"] .atlz-chip[data-tone='inprogress'],
    html:not([data-theme="light"]) .atlz-chip[data-tone='inprogress'] { color: #579DFF; }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .atlz-chip[data-tone='inprogress'] { color: #579DFF; }
    }
    .atlz-chip[data-bold] { color: #fff; }
    .atlz-chip[data-tone='backlog'][data-bold]    { background: #44546F; }
    .atlz-chip[data-tone='inprogress'][data-bold] { background: #0052CC; }
    .atlz-chip[data-tone='review'][data-bold]     { background: #B38600; }
    .atlz-chip[data-tone='done'][data-bold]       { background: #1F845A; }
    .atlz-chip[data-tone='moved'][data-bold]      { background: var(--atlz-purple-solid); }
    .atlz-chip[data-tone='removed'][data-bold]    { background: var(--atlz-red-solid); }
    html[data-theme="dark"] .atlz-chip[data-tone='inprogress'][data-bold] { background: #1D7AFC; }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .atlz-chip[data-tone='inprogress'][data-bold] { background: #1D7AFC; }
    }

    .atlz-board { inline-size: min(100%, 34rem); margin-inline: auto; border: 1px solid var(--atlz-border);
                  border-radius: 6px; background: var(--atlz-surface); overflow: clip; }
    .atlz-row { display: flex; align-items: center; gap: .75rem; inline-size: 100%; padding: .7rem .9rem;
                border: none; background: none; text-align: start; cursor: pointer; font: inherit;
                transition: background .15s ease; min-inline-size: 0; }
    .atlz-row + .atlz-row { border-block-start: 1px solid var(--atlz-border); }
    .atlz-row:hover { background: var(--atlz-hover); }
    .atlz-key { flex: none; font: 600 .75rem Inter, system-ui; color: var(--atlz-blue); }
    .atlz-name { flex: 1 1 auto; min-inline-size: 0; font: 400 .85rem/1.4 Inter, system-ui; color: var(--atlz-ink);
                 overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .atlz-chipbtn { flex: none; border: none; background: none; padding: 2px; cursor: pointer; border-radius: 3px; }
    .atlz-pop { inline-size: 14px; opacity: 0; transition: opacity .15s ease; flex: none; }
    .atlz-row:hover .atlz-pop { opacity: .6; }
    .atlz-flip { animation: atlz-swap .25s ease; }
    @keyframes atlz-swap { from { opacity: 0; scale: .85; } to { opacity: 1; scale: 1; } }

    .atlz-family { display: grid; gap: .9rem 2rem; grid-template-columns: repeat(auto-fit, minmax(min(100%, 13rem), 1fr)); inline-size: 100%; max-inline-size: 40rem; justify-items: center; }
    .atlz-cell { display: grid; gap: .4rem; justify-items: center; text-align: center; min-inline-size: 0; }
    .atlz-cell > small { font: 500 .7rem/1.4 Inter, system-ui; color: var(--atlz-muted); letter-spacing: .3px; }
    @media (prefers-reduced-motion: reduce) {
        .atlz-root * { animation-duration: .01ms !important; transition-duration: .01ms !important; }
    }

    /* Pin the page's «Important props» rows for full-page captures — ships
       with this partial only, so it stays scoped to this demo page. */
    .nx-data-table[data-nx-reveal]:not([data-nx-revealed]) tbody tr { opacity: 1; translate: none; }
</style>

<div class="atlz-root"
    x-data="{
        cycle: ['backlog', 'inprogress', 'review', 'done', 'moved', 'removed'],
        labels: @js($labels),
        states: @js(array_combine(array_keys($issues), array_column($issues, 'start'))),
        next(key) { const i = this.cycle.indexOf(this.states[key]); this.states[key] = this.cycle[(i + 1) % this.cycle.length]; },
    }">
    <section class="pg-box" style="justify-items: center">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The chip that betrays the sprint', 'برچسبی که حالِ اسپرینت را لو می‌دهد') }}</h3>
            <p style="margin: 0; max-width: 50ch; color: var(--nx-text-muted)">
                {{ $say('A real work-item list — click any status chip and it walks the six official appearances, exactly the cycle a Jira drag would set.', 'یک فهرست کار واقعی — روی هر برچسب وضعیت کلیک کنید تا از میان شش ظاهر رسمی بچرخد؛ همان چرخه‌ای که یک کشیدن در جیرا می‌سازد.') }}
            </p>
        </div>

        <div class="atlz-board" role="list" aria-label="{{ $say('Work items', 'کارها') }}">
            @foreach ($issues as $issue)
                <button type="button" class="atlz-row" role="listitem" x-on:click="next('{{ $issue['key'] }}')"
                        title="{{ $say('Cycle the status', 'چرخاندن وضعیت') }}"
                        aria-label="{{ $say('Cycle status of ' . $issue['key'], 'چرخاندن وضعیت ' . $issue['key']) }}">
                    <span class="atlz-key">{{ $issue['key'] }}</span>
                    <span class="atlz-name">{{ $say($issue['en'], $issue['fa']) }}</span>
                    <span class="atlz-chip" :data-tone="states['{{ $issue['key'] }}']"
                          x-text="labels[states['{{ $issue['key'] }}']]"
                          x-effect="const t = states['{{ $issue['key'] }}']; if (t !== $el.dataset.prev) { $el.dataset.prev = t; $el.classList.remove('atlz-flip'); void $el.offsetWidth; $el.classList.add('atlz-flip'); }">{{ $labels[$issue['start']] }}</span>
                    <svg class="atlz-pop" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>
                </button>
            @endforeach
        </div>
        <p style="margin: 0; font: 400 .75rem/1.5 Inter, system-ui; color: var(--nx-text-muted)">
            {{ $say('Hovering shows the little drag hint; the flip animation plays on every change.', 'با رفتن نشانگر، راهنمای کوچک جابه‌جایی دیده می‌شود؛ انیمیشن فلیپ با هر تغییر پخش می‌شود.') }}
        </p>
    </section>
</div>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('The whole family, subtle and bold', 'همهٔ خانواده، ملایم و غلیظ') }}</h3>
        <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
            {{ $say('Subtle is a pale surface with a dark tint of the same hue; bold fills solid for when the status must shout. Long labels clamp at 200px.', 'ملایم سطح کم‌رنگ با تُن تیرهٔ همان رنگ است؛ غلیظ توپر می‌شود وقتی وضعیت باید داد بزند. برچسب بلند در ۲۰۰ پیکسل فشرده می‌شود.') }}
        </p>
    </div>
    <div class="atlz-root" style="inline-size: 100%">
        <div class="atlz-family">
            @foreach ($statuses as $tone => $label)
                <div class="atlz-cell">
                    <span class="atlz-chip" data-tone="{{ $tone }}">{{ $say($label[0], $label[1]) }}</span>
                    <small>{{ $tone }}</small>
                </div>
                <div class="atlz-cell">
                    <span class="atlz-chip" data-tone="{{ $tone }}" data-bold>{{ $say($label[0], $label[1]) }}</span>
                    <small>{{ $tone }} · bold</small>
                </div>
            @endforeach
            <div class="atlz-cell">
                <span class="atlz-chip" data-tone="inprogress">{{ $fa ? 'برچسب خیلی بلند که در دویست پیکسل فشرده می‌شود تا ردیف نریزد' : 'A very long label that clamps inside two hundred pixels so the row never breaks' }}</span>
                <small>maxWidth · 200px</small>
            </div>
        </div>
    </div>
</section>
