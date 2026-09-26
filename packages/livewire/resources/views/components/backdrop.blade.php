{{-- Decorative, pure CSS: aurora | grid | stars | beams | dots. Put it first inside a positioned section. --}}
@props(['variant' => 'aurora'])
<div {{ $attributes->class('nx-backdrop')->merge(['data-variant' => $variant]) }} aria-hidden="true"><i></i><i></i><i></i></div>
