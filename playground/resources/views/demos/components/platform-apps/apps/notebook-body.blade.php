{{--
    The notes app's shared body — included as the default slot of every
    <x-nx::app-window> on the notebook demo page (all four platform skins).
    Static sample data only: a list pane + an editor pane, wired with a few
    lines of local Alpine (current note) so each skin feels alive. Scoped
    under .pnb-*; tokens only, no Blade inside the style block.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $notes = [
        ['id' => 'n1', 'title' => $say('Weekly groceries', 'خرید هفته'), 'date' => $say('Oct 24', '۲ آبان'),
         'body' => $say("Bread, eggs, the good honey — and coffee beans this time.", 'نان، تخم‌مرغ، عسل خوب — و این بار دانهٔ قهوه.'), 'words' => 11],
        ['id' => 'n2', 'title' => $say('Kickoff agenda', 'دستور جلسهٔ شروع'), 'date' => $say('Oct 22', '۳۱ مهر'),
         'body' => $say('Scope, naming, and the one demo we must not miss.', 'دامنه، نام‌گذاری، و همان دمویی که نباید از دست برود.'), 'words' => 13],
        ['id' => 'n3', 'title' => $say('Book notes — Shape Up', 'یادداشت کتاب — Shape Up'), 'date' => $say('Oct 18', '۲۷ مهر'),
         'body' => $say('Six-week cycles; appetite before estimate.', 'چرخه‌های شش‌هفته‌ای؛ اشتها قبل از برآورد.'), 'words' => 9],
        ['id' => 'n4', 'title' => $say('Trip packing', 'چمدان سفر'), 'date' => $say('Oct 12', '۲۱ مهر'),
         'body' => $say('Passport, charger, the blue notebook.', 'گذرنامه، شارژر، دفترچهٔ آبی.'), 'words' => 7],
    ];
@endphp
<style>
    .pnb-body { display: flex; flex-wrap: wrap; gap: .875rem; align-content: start; }
    .pnb-list { flex: 1 1 9rem; display: grid; gap: .25rem; align-content: start; }
    .pnb-note { display: grid; gap: .125rem; padding: .55rem .7rem; border: 0; border-radius: var(--nx-radius-md);
                background: transparent; color: var(--nx-text); text-align: start; cursor: pointer;
                font: inherit; transition-property: scale, background-color; transition-duration: var(--nx-dur-fast); }
    .pnb-note:hover { background: var(--nx-surface-2); }
    .pnb-note:active { scale: calc(1 - 0.04 * var(--nx-motion)); }
    .pnb-note:focus-visible { outline: 2px solid var(--nx-ring); outline-offset: 2px; }
    .pnb-note[aria-current='true'] { background: var(--nx-accent-soft); }
    .pnb-note-title { font: 600 var(--nx-text-sm) / 1.4 var(--nx-font-sans); }
    .pnb-note-date { font-size: var(--nx-text-xs); color: var(--nx-text-subtle); }
    .pnb-editor { flex: 3 1 15rem; display: grid; gap: .625rem; align-content: start;
                  padding: .875rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg);
                  background: var(--nx-surface-2); }
    .pnb-editor-title { margin: 0; font: 700 var(--nx-text-base) / 1.4 var(--nx-font-sans); }
    .pnb-editor-text { margin: 0; min-block-size: 5.5rem; padding: .625rem .75rem; border: 1px solid var(--nx-border);
                       border-radius: var(--nx-radius-md); background: var(--nx-surface); color: var(--nx-text);
                       font: 400 var(--nx-text-sm) / 1.8 var(--nx-font-sans); resize: vertical; }
    .pnb-editor-meta { display: flex; flex-wrap: wrap; gap: .5rem .875rem; align-items: center; justify-content: space-between;
                       font-size: var(--nx-text-xs); color: var(--nx-text-muted); }
    .pnb-chip { display: inline-flex; align-items: center; gap: .3rem; padding-inline: .55rem; block-size: 1.4rem;
                border-radius: var(--nx-radius-full); background: var(--nx-surface-3);
                font-size: var(--nx-text-xs); color: var(--nx-text-muted); }
    .pnb-chip .nx-icon { inline-size: .85rem; block-size: .85rem; }
</style>
<div class="pnb-body" x-data="{ current: @js($notes[0]['id']) }">
    <div class="pnb-list" role="list" aria-label="{{ $say('Notes', 'یادداشت‌ها') }}">
        @foreach ($notes as $note)
            <button type="button" class="pnb-note" role="listitem" @if ($loop->first) aria-current="true" @endif
                x-bind:aria-current="current === @js($note['id']) ? 'true' : null" x-on:click="current = @js($note['id'])">
                <span class="pnb-note-title">{{ $note['title'] }}</span>
                <span class="pnb-note-date">{{ $note['date'] }}</span>
            </button>
        @endforeach
    </div>
    <div class="pnb-editor">
        @foreach ($notes as $note)
            <div x-show="current === @js($note['id'])" @if (! $loop->first) x-cloak @endif style="display: grid; gap: .625rem">
                <h4 class="pnb-editor-title">{{ $note['title'] }}</h4>
                <textarea class="pnb-editor-text" aria-label="{{ $note['title'] }}">{{ $note['body'] }}</textarea>
                <div class="pnb-editor-meta">
                    <span>{{ NabuXUI::formatNumber($note['words']).' '.$say('words', 'واژه') }}</span>
                    <span class="pnb-chip">{{ NabuXUI::icon('check') }}{{ $say('Saved', 'ذخیره شد') }}</span>
                </div>
            </div>
        @endforeach
    </div>
</div>
