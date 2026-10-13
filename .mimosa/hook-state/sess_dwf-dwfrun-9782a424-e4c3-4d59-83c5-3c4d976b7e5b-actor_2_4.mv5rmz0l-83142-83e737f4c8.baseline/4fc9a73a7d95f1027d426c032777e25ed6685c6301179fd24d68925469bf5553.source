{{-- Children are the items (each wrapped in an <li> for you if you pass <li>s yourself, keep them). --}}
@props(['duration' => 40, 'reverse' => false, 'gap' => null, 'controls' => true])
<section {{ $attributes->class('nx-marquee')->merge(['data-direction' => $reverse ? 'reverse' : null, 'style' => "--nx-duration: {$duration}s".($gap ? "; --nx-marquee-gap: {$gap}" : '')]) }}
    x-data="{ paused: false }" x-bind:data-paused="paused ? '' : null">
    <div class="nx-marquee-track">
        <ul class="nx-marquee-group">{{ $slot }}</ul>
        <ul class="nx-marquee-group" aria-hidden="true" inert>{{ $slot }}</ul>
    </div>
    @if ($controls)
        <button type="button" class="nx-marquee-toggle" x-bind:aria-pressed="paused ? 'true' : 'false'"
            x-bind:aria-label="paused ? @js(__('nabuxui::ui.play')) : @js(__('nabuxui::ui.pause'))" aria-label="{{ __('nabuxui::ui.pause') }}" @click="paused = ! paused">
            <span x-show="! paused">{{ \NabuXUI\NabuXUI::icon('pause') }}</span>
            <span x-show="paused" x-cloak>{{ \NabuXUI\NabuXUI::icon('play') }}</span>
        </button>
    @endif
</section>
