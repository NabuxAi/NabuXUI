{{--
    The active card centred and full size; its neighbours smaller, pushed back and softened at both sides.
    Drag or swipe it, use the buttons, the dots or the arrow keys. "Next" sits toward the inline end (left in RTL).

    <x-nx::depth-carousel label="Stories" :items="[
        'kyoto' => ['title' => '京都の朝', 'description' => '…', 'cover' => 'linear-gradient(…)', 'href' => '/stories/kyoto'],
        'lisboa' => ['title' => 'Lisboa', 'description' => '…', 'image' => '/img/lisboa.jpg'],
    ]">
        <x-slot:lisboa>…any content replaces the built-in slide…</x-slot:lisboa>
    </x-nx::depth-carousel>

    Item keys: title, description, cover (a CSS background), image, alt, href, tone. `loop` (default true) wraps
    around; wire:model="slide" keeps the index in step with a Livewire property.
--}}
@props(['items' => [], 'index' => 0, 'loop' => true, 'dots' => true, 'label' => null, 'cardWidth' => null, 'cardHeight' => null])
@php
    // Copied before any @foreach: inside one, $loop is Blade's loop variable.
    $looping = (bool) $loop;
    $slots = $__laravel_slots ?? [];
    $slotFor = fn ($key) => $slots[$key] ?? $slots[\Illuminate\Support\Str::camel((string) $key)] ?? null;
    $model = \NabuXUI\NabuXUI::model($attributes);
    $named = ['lapis', 'violet', 'cyan', 'gold'];
    $keys = array_keys($items);
    $count = max(count($items), 1);
    $start = $looping ? (((int) $index % $count) + $count) % $count : max(0, min((int) $index, $count - 1));
    $offsetOf = function (int $i) use ($start, $count, $looping) {
        $o = $i - $start;
        if (! $looping) {
            return $o;
        }
        $w = (($o % $count) + $count) % $count;

        return $w > $count / 2 ? $w - $count : $w;
    };
    $locale = str_replace('_', '-', app()->getLocale());
    $num = fn ($n) => \NabuXUI\NabuXUI::formatNumber($n);
    $vars = ($cardWidth ? "--nx-depth-card-width: {$cardWidth};" : '').($cardHeight ? " --nx-depth-card-height: {$cardHeight};" : '');
@endphp
<section {{ $attributes->whereDoesntStartWith('wire:model')->class('nx-depth')->merge(['style' => $vars ?: null, 'aria-roledescription' => 'carousel', 'aria-label' => $label]) }}
    @if ($model) x-data="nxDepthCarousel(@entangle($model), {{ $count }}, @js($looping), @js($locale))" @else x-data="nxDepthCarousel({{ $start }}, {{ $count }}, @js($looping), @js($locale))" @endif
    x-on:keydown="key($event)">
    <div class="nx-depth-stage" x-ref="stage" @if (! $looping && $start === 0) data-at-start @endif @if (! $looping && $start === $count - 1) data-at-end @endif
        @if (! $looping) x-bind:data-at-start="current() === 0 ? '' : null" x-bind:data-at-end="current() === {{ $count - 1 }} ? '' : null" @endif>
        @foreach ($items as $key => $item)
            @php
                $i = $loop->index;
                $o = $offsetOf($i);
                $slide = is_array($item) ? $item : ['title' => (string) $item];
                $tone = $slide['tone'] ?? null;
                $toneAttr = in_array($tone, $named, true) ? $tone : null;
                $content = $slotFor($key);
                $slideTag = ! empty($slide['href']) ? 'a' : 'div';
            @endphp
            <div class="nx-depth-card" role="group" aria-roledescription="slide" aria-label="{{ $num($i + 1) }} / {{ $num($count) }}"
                style="--_o: {{ $o }}; --_d: {{ abs($o) }};{{ $tone && ! $toneAttr ? " --nx-tone: {$tone};" : '' }}" @if ($toneAttr) data-tone="{{ $toneAttr }}" @endif
                @if ($o === 0) data-active @else aria-hidden="true" @endif
                x-bind:style="{ '--_o': offset({{ $i }}), '--_d': Math.abs(offset({{ $i }})) }"
                x-bind:data-active="offset({{ $i }}) === 0 ? '' : null" x-bind:aria-hidden="offset({{ $i }}) === 0 ? null : 'true'"
                x-on:click="offset({{ $i }}) === 0 || go({{ $i }})">
                <div class="nx-depth-content" @if ($o !== 0) inert @endif x-bind:inert="offset({{ $i }}) !== 0">
                    @if ($content)
                        {{ $content }}
                    @else
                        <{{ $slideTag }} class="nx-depth-slide" @if (! empty($slide['href'])) href="{{ $slide['href'] }}" draggable="false" @endif>
                            <div class="nx-depth-media" @if (! empty($slide['cover'])) style="background: {{ $slide['cover'] }}" @endif>@if (! empty($slide['image']))<img src="{{ $slide['image'] }}" alt="{{ $slide['alt'] ?? '' }}" draggable="false" loading="lazy">@endif</div>
                            @if (! empty($slide['title']))<h3 class="nx-depth-title">{{ $slide['title'] }}</h3>@endif
                            @if (! empty($slide['description']))<p class="nx-depth-description">{{ $slide['description'] }}</p>@endif
                        </{{ $slideTag }}>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    <div class="nx-depth-controls">
        <x-nx::button variant="secondary" shape="pill" icon="chevron-left" icon-only :aria-label="__('nabuxui::ui.previous')" :disabled="! $looping && $start === 0"
            x-bind:disabled="{{ $looping ? 'false' : 'current() === 0' }}" x-on:click="step(-1)">{{ __('nabuxui::ui.previous') }}</x-nx::button>
        @if ($dots)
            <div class="nx-depth-dots" role="group" aria-label="{{ __('nabuxui::ui.pagination') }}">
                @foreach ($keys as $i => $key)
                    <button type="button" class="nx-depth-dot" aria-label="{{ __('nabuxui::ui.page', ['page' => $num($i + 1)]) }}" @if ($i === $start) aria-current="true" @endif
                        x-bind:aria-current="current() === {{ $i }} ? 'true' : null" x-on:click="go({{ $i }})"></button>
                @endforeach
            </div>
        @endif
        <x-nx::button variant="secondary" shape="pill" icon="chevron-right" icon-only :aria-label="__('nabuxui::ui.next')" :disabled="! $looping && $start === $count - 1"
            x-bind:disabled="{{ $looping ? 'false' : 'current() === '.($count - 1) }}" x-on:click="step(1)">{{ __('nabuxui::ui.next') }}</x-nx::button>
    </div>
    <p class="nx-visually-hidden" aria-live="polite" x-text="num(current() + 1) + ' / ' + num({{ $count }})">{{ $num($start + 1) }} / {{ $num($count) }}</p>
</section>
