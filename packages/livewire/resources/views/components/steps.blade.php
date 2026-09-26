{{-- steps: [['title' => …, 'description' => …]]; `current` is the index in progress. --}}
@props(['steps' => [], 'current' => 0, 'orientation' => 'horizontal'])
<ol {{ $attributes->class('nx-steps')->merge(['data-orientation' => $orientation === 'vertical' ? 'vertical' : null]) }}>
    @foreach ($steps as $i => $step)
        @php $status = $i < $current ? 'complete' : ($i === $current ? 'current' : 'upcoming'); @endphp
        <li class="nx-step" data-status="{{ $status }}" @if ($status === 'current') aria-current="step" @endif>
            <span class="nx-step-marker">@if ($status === 'complete'){{ \NabuXUI\NabuXUI::icon('check') }}@endif</span>
            <span class="nx-step-text">
                <span class="nx-step-title">{{ $step['title'] }}</span>
                @if (! empty($step['description']))<span class="nx-step-description">{{ $step['description'] }}</span>@endif
            </span>
        </li>
    @endforeach
</ol>
