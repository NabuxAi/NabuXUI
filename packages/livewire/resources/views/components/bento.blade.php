@props(['columns' => 3, 'rowHeight' => null])
<div {{ $attributes->class('nx-bento')->merge(['data-nx-reveal' => 'group', 'style' => "--nx-cols: {$columns}".($rowHeight ? "; --nx-row: {$rowHeight}" : '')]) }} x-data x-nx-reveal.group>{{ $slot }}</div>
