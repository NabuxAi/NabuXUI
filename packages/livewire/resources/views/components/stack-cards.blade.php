{{-- Overlapping cards that fan out on hover. Children: <x-nx::stack-item :index="0">…</x-nx::stack-item> --}}
@props(['count' => 3, 'overlap' => null])
<ul {{ $attributes->class('nx-stack')->merge(['style' => "--nx-count: {$count}".($overlap ? "; --nx-stack-overlap: {$overlap}" : '')]) }}>{{ $slot }}</ul>
