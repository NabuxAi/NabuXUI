{{--
    Tall dark cards side by side; the hovered, focused or tapped one rises and its colour climbs from the bottom.

    <x-nx::team-cards label="Our team" :members="[
        ['name' => 'Kenji Sato', 'role' => 'Design lead', 'avatar' => '/img/kenji.jpg', 'color' => 'violet'],
        ['name' => 'María López', 'role' => 'Engineering', 'color' => 'cyan'],
    ]" />

    Member keys: name, role, avatar (image URL; initials otherwise), color (lapis | violet | cyan | gold or any
    CSS colour), href (makes the card a link).
--}}
@props(['members' => [], 'label' => null, 'nameAs' => 'h3', 'cardWidth' => null])
@php
    $named = ['lapis', 'violet', 'cyan', 'gold'];
    $base = \NabuXUI\NabuXUI::id('nx-team');
    $vars = '--nx-count: '.max(count($members), 1).';'.($cardWidth ? " --nx-team-card-width: {$cardWidth};" : '');
@endphp
<div {{ $attributes->class('nx-team')->merge(['style' => $vars]) }} x-data>
    <ul class="nx-team-list" @if ($label) aria-label="{{ $label }}" @endif>
        @foreach ($members as $i => $member)
            @php
                $id = $base.'-'.$i;
                $color = $member['color'] ?? null;
                $toneAttr = in_array($color, $named, true) ? $color : null;
                $toneStyle = $color && ! $toneAttr ? "--nx-tone: {$color}" : null;
                $href = $member['href'] ?? null;
                $cardTag = $href ? 'a' : 'article';
            @endphp
            <li class="nx-team-item">
                {{-- Without a link it is still focusable, so a keyboard or a tap can raise it like a hover does. --}}
                <{{ $cardTag }} class="nx-team-card" data-theme="dark" @if ($toneAttr) data-tone="{{ $toneAttr }}" @endif @if ($toneStyle) style="{{ $toneStyle }}" @endif
                    @if ($href) href="{{ $href }}" @else tabindex="0" aria-labelledby="{{ $id }}" x-on:pointerdown="$event.pointerType !== 'mouse' && $el.focus()" @endif>
                    <div class="nx-team-text">
                        <{{ $nameAs }} class="nx-team-name" id="{{ $id }}">{{ $member['name'] ?? '' }}</{{ $nameAs }}>
                        @if (! empty($member['role']))<p class="nx-team-role">{{ $member['role'] }}</p>@endif
                    </div>
                    <x-nx::avatar :name="$member['name'] ?? ''" :src="$member['avatar'] ?? null" size="lg" aria-hidden="true" />
                </{{ $cardTag }}>
            </li>
        @endforeach
    </ul>
</div>
