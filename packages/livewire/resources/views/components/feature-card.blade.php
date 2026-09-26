{{--
    A card with a small animated illustration on top. The loops are pure CSS and rest under reduced motion.

    <x-nx::feature-card visual="inbox" title="Smart inbox" description="New mail sorts itself." tone="cyan" />

    visual: inbox (mail arriving and re-sorting) | summary (lines read, then folded into a summary)
            | processing (a bolt with rings and a filling bar) | team (people moving into their slots).
    href makes the whole card a link; the default slot adds content under the description.
--}}
@props(['visual' => 'inbox', 'title', 'description' => null, 'href' => null, 'tone' => null, 'titleAs' => 'h3'])
@php
    $tag = $href ? 'a' : 'article';
    $named = ['lapis', 'violet', 'cyan', 'gold'];
    $toneAttr = in_array($tone, $named, true) ? $tone : null;
    $toneStyle = $tone && ! $toneAttr ? "--nx-tone: {$tone}" : null;
@endphp
<{{ $tag }} {{ $attributes->class('nx-feature')->merge(['href' => $href, 'data-tone' => $toneAttr, 'style' => $toneStyle]) }}>
    <div class="nx-feature-visual" data-visual="{{ $visual }}" aria-hidden="true">
        @if ($visual === 'inbox')
            <div class="nx-fv-inbox">
                @for ($i = 0; $i < 4; $i++)
                    <span class="nx-fv-row" style="--nx-i: {{ $i }}"><b></b><span><i class="nx-fv-line"></i><i class="nx-fv-line"></i></span></span>
                @endfor
            </div>
        @elseif ($visual === 'summary')
            <div class="nx-fv-summary">
                @for ($i = 0; $i < 4; $i++)
                    <i class="nx-fv-line" style="--nx-i: {{ $i }}"></i>
                @endfor
                <span class="nx-fv-pill">{{ \NabuXUI\NabuXUI::icon('sparkles') }}<span><i class="nx-fv-line"></i><i class="nx-fv-line"></i></span></span>
            </div>
        @elseif ($visual === 'processing')
            <div class="nx-fv-processing">
                <span class="nx-fv-core">
                    @for ($i = 0; $i < 3; $i++)
                        <span class="nx-fv-ring" style="--nx-i: {{ $i }}"></span>
                    @endfor
                    <span class="nx-fv-bolt">{{ \NabuXUI\NabuXUI::icon('zap') }}</span>
                </span>
                <span class="nx-fv-bar"><i></i></span>
            </div>
        @elseif ($visual === 'team')
            <div class="nx-fv-team">
                @for ($i = 0; $i < 3; $i++)
                    <span class="nx-fv-slot" style="--nx-i: {{ $i }}"></span>
                @endfor
                @for ($i = 0; $i < 3; $i++)
                    <span class="nx-fv-person" style="--nx-i: {{ $i }}">{{ \NabuXUI\NabuXUI::icon('user') }}</span>
                @endfor
            </div>
        @endif
    </div>
    <div class="nx-feature-body">
        <{{ $titleAs }} class="nx-feature-title">{{ $title }}</{{ $titleAs }}>
        @if ($description)<p class="nx-feature-description">{{ $description }}</p>@endif
        {{ $slot }}
    </div>
</{{ $tag }}>
