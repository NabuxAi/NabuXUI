{{--
    The music app's shared body — included as the default slot of every
    <x-nx::app-window> on the music demo page (all four platform skins).
    Static sample data: an album grid (gradient tiles from palette tokens, no
    images, no backend) above a playback bar with a play/pause toggle held in
    a couple of lines of local Alpine. Scoped under .pmu-*; tokens only, no
    Blade inside the style block.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $albums = [
        ['title' => $say('Night Drive', 'رانش شب'), 'artist' => $say('Laleh & the Lamps', 'لاله و لامپ‌ها'), 'from' => 'var(--nx-lapis-500)', 'to' => 'var(--nx-violet-700)'],
        ['title' => $say('Sea of Glass', 'دریای شیشه'), 'artist' => $say('Mina Rae', 'مینا رأی'), 'from' => 'var(--nx-cyan-400)', 'to' => 'var(--nx-lapis-600)'],
        ['title' => $say('Golden Hour', 'ساعت طلایی'), 'artist' => $say('The Caspians', 'کاسپی‌ها'), 'from' => 'var(--nx-gold-400)', 'to' => 'var(--nx-violet-500)'],
        ['title' => $say('Tehran Tapes', 'نوارهای تهران'), 'artist' => $say('Ashkan', 'اشکان'), 'from' => 'var(--nx-violet-500)', 'to' => 'var(--nx-cyan-400)'],
        ['title' => $say('Slow Orbit', 'مدار آهسته'), 'artist' => $say('Noor', 'نور'), 'from' => 'var(--nx-lapis-400)', 'to' => 'var(--nx-gold-400)'],
        ['title' => $say('Remixes', 'ریمیکس‌ها'), 'artist' => $say('Various', 'مختلف'), 'from' => 'var(--nx-violet-400)', 'to' => 'var(--nx-lapis-700)'],
    ];
@endphp
<style>
    .pmu-body { display: grid; gap: .875rem; }
    .pmu-grid { display: grid; gap: .625rem; grid-template-columns: repeat(3, minmax(0, 1fr)); }
    /* Phone shells run narrow (the demo caps them at phone width, and the
       page itself goes single-column on small screens): a fixed 3-column grid
       there squeezes each cover to a sliver until the titles stack one glyph
       per line. Auto-fill with a floor keeps covers at least 6.5rem wide —
       three across on a phone, two when the window truly runs out. */
    .nx-app-window:is([data-platform='android'], [data-platform='ios']) .pmu-grid {
        grid-template-columns: repeat(auto-fill, minmax(6.5rem, 1fr));
    }
    .pmu-album { display: grid; gap: .3rem; padding: 0; border: 0; background: none; text-align: start;
                 color: var(--nx-text); font: inherit; cursor: pointer;
                 transition-property: scale, translate; transition-duration: var(--nx-dur-fast); }
    .pmu-album:active { scale: calc(1 - 0.04 * var(--nx-motion)); }
    .pmu-album:focus-visible { outline: 2px solid var(--nx-ring); outline-offset: 2px; border-radius: var(--nx-radius-md); }
    .pmu-cover { aspect-ratio: 1; border-radius: var(--nx-radius-md); outline: 1px solid var(--nx-border);
                 display: grid; place-items: center; color: var(--nx-accent-contrast); }
    .pmu-cover .nx-icon { inline-size: 1.4rem; block-size: 1.4rem; opacity: .9; }
    .pmu-title { font: 600 var(--nx-text-xs) / 1.5 var(--nx-font-sans); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .pmu-artist { font-size: var(--nx-text-xs); color: var(--nx-text-subtle); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .pmu-player { display: grid; gap: .5rem; padding: .7rem .8rem; border: 1px solid var(--nx-border);
                  border-radius: var(--nx-radius-lg); background: var(--nx-surface-2); }
    .pmu-now { display: flex; gap: .625rem; align-items: center; min-inline-size: 0; }
    .pmu-now-cover { flex: none; inline-size: 2.4rem; block-size: 2.4rem; border-radius: var(--nx-radius-sm);
                     display: grid; place-items: center; color: var(--nx-accent-contrast); }
    .pmu-now-text { display: grid; gap: .1rem; min-inline-size: 0; }
    .pmu-now-title { font: 600 var(--nx-text-sm) / 1.4 var(--nx-font-sans); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .pmu-now-artist { font-size: var(--nx-text-xs); color: var(--nx-text-muted); }
    .pmu-play { flex: none; margin-inline-start: auto; inline-size: 2.4rem; block-size: 2.4rem; display: grid; place-items: center;
                border: 1px solid var(--nx-accent-border); border-radius: var(--nx-radius-full);
                background: var(--nx-accent-soft); color: var(--nx-accent-text); cursor: pointer;
                transition-property: scale, background-color; transition-duration: var(--nx-dur-fast); }
    .pmu-play:hover { background: var(--nx-accent); color: var(--nx-accent-text); }
    .pmu-play:active { scale: calc(1 - 0.04 * var(--nx-motion)); }
    .pmu-play:focus-visible { outline: 2px solid var(--nx-ring); outline-offset: 2px; }
    .pmu-play .nx-icon { inline-size: 1.1rem; block-size: 1.1rem; }
    .pmu-track { display: grid; gap: .375rem; }
    .pmu-rail { block-size: .3rem; border-radius: var(--nx-radius-full); background: var(--nx-surface-3); overflow: clip; }
    .pmu-rail span { display: block; block-size: 100%; border-radius: inherit; background: var(--nx-accent); }
    .pmu-times { display: flex; justify-content: space-between; font-size: var(--nx-text-xs);
                 font-variant-numeric: tabular-nums; color: var(--nx-text-subtle); }
    @media (hover: hover) and (pointer: fine) {
        .pmu-album:hover { translate: 0 calc(-3px * var(--nx-motion)); }
        .pmu-cover:hover .nx-icon { opacity: 1; }
    }
</style>
<div class="pmu-body" x-data="{ playing: false }">
    <div class="pmu-grid" role="list" aria-label="{{ $say('Albums', 'آلبوم‌ها') }}">
        @foreach ($albums as $album)
            <button type="button" class="pmu-album" role="listitem" aria-label="{{ $album['title'].' — '.$album['artist'] }}">
                <span class="pmu-cover" aria-hidden="true" style="background: linear-gradient(135deg, {{ $album['from'] }}, {{ $album['to'] }})">{{ NabuXUI::icon('music') }}</span>
                <span class="pmu-title">{{ $album['title'] }}</span>
                <span class="pmu-artist">{{ $album['artist'] }}</span>
            </button>
        @endforeach
    </div>
    <div class="pmu-player">
        <div class="pmu-now">
            <span class="pmu-now-cover" aria-hidden="true" style="background: linear-gradient(135deg, var(--nx-violet-500), var(--nx-lapis-600))">{{ NabuXUI::icon('music') }}</span>
            <span class="pmu-now-text">
                <span class="pmu-now-title">{{ $say('Sea of Glass — Mina Rae', 'دریای شیشه — مینا رأی') }}</span>
                <span class="pmu-now-artist">{{ $say('Album · Sea of Glass', 'آلبوم · دریای شیشه') }}</span>
            </span>
            <button type="button" class="pmu-play" x-on:click="playing = ! playing"
                :aria-label="playing ? @js(__('nabuxui::ui.pause')) : @js(__('nabuxui::ui.play'))" aria-label="{{ __('nabuxui::ui.play') }}">
                <span x-show="! playing">{{ NabuXUI::icon('play') }}</span>
                <span x-show="playing" x-cloak>{{ NabuXUI::icon('pause') }}</span>
            </button>
        </div>
        <div class="pmu-track">
            <div class="pmu-rail" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="38" aria-label="{{ $say('Playback', 'پخش') }}"><span style="inline-size: 38%"></span></div>
            <div class="pmu-times"><span>1:42</span><span>4:26</span></div>
        </div>
    </div>
</div>
