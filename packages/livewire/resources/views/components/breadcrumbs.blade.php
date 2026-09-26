{{-- items: [['label' => …, 'href' => …], …, ['label' => 'Current page']] --}}
@props(['items' => []])
<nav {{ $attributes->class('nx-breadcrumbs')->merge(['aria-label' => __('nabuxui::ui.breadcrumb')]) }}>
    <ol>
        @foreach ($items as $i => $item)
            <li>
                @if (! empty($item['href']) && $i < count($items) - 1)
                    <a href="{{ $item['href'] }}">{{ $item['label'] }}</a>
                @else
                    <span @if ($i === count($items) - 1) aria-current="page" style="color: var(--nx-text); font-weight: 600" @endif>{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
