{{-- people: [['name' => …, 'src' => …], …] --}}
@props(['people' => [], 'max' => 5, 'size' => null, 'label' => null])
@php $shown = array_slice($people, 0, $max); @endphp
@php $rest = count($people) - count($shown); @endphp
<div {{ $attributes->class('nx-avatar-group')->merge(['role' => 'group', 'aria-label' => $label]) }}>
    @foreach ($shown as $person)
        <x-nx::tooltip :text="$person['name']"><x-nx::avatar :name="$person['name']" :src="$person['src'] ?? null" :size="$size" tabindex="0" /></x-nx::tooltip>
    @endforeach
    @if ($rest > 0)
        <span class="nx-avatar nx-avatar-more" @if ($size && $size !== 'md') data-size="{{ $size }}" @endif role="img" aria-label="{{ __('nabuxui::ui.more', ['count' => $rest]) }}"><span aria-hidden="true">+{{ $rest }}</span></span>
    @endif
</div>
