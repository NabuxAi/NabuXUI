@props(['title', 'open' => false])
<details {{ $attributes->class('nx-accordion-item') }} @if ($open) open @endif>
    <summary class="nx-accordion-trigger">{{ $title }}</summary>
    <div class="nx-accordion-content">{{ $slot }}</div>
</details>
