{{--
    A workflow summary that opens on hover, focus or tap: details grow in and the actions slide in one by one.

    <x-nx::workflow-card title="Nightly sync" description="Pull orders, update stock." status="Running" live
        :members="[['name' => 'Kenji Sato'], ['name' => 'María López', 'src' => '/img/maria.jpg']]"
        :meta="['Trigger' => 'Every day at 02:00', 'Last run' => '3 min ago']">
        <x-slot:actions>
            <x-nx::icon-button icon="play" label="Run now" wire:click="run" />
            <x-nx::icon-button icon="edit" label="Edit" />
        </x-slot:actions>
    </x-nx::workflow-card>

    status-tone: neutral | accent | success | warning | danger | info | gold. `live` makes the status dot breathe;
    `expanded` keeps the card open. meta: ['Label' => 'value'] or [['label' => …, 'value' => …]].
--}}
@props(['title', 'description' => null, 'icon' => 'zap', 'status' => null, 'statusTone' => 'success', 'live' => false, 'members' => [], 'membersLabel' => null, 'meta' => [], 'expanded' => false, 'titleAs' => 'h3'])
@php
    $id = \NabuXUI\NabuXUI::id('nx-workflow');
    $rows = [];
    foreach ($meta as $label => $value) {
        $rows[] = is_array($value) ? $value : ['label' => $label, 'value' => $value];
    }
@endphp
<article {{ $attributes->class('nx-workflow')->merge(['tabindex' => '0', 'aria-labelledby' => $id, 'data-expanded' => $expanded ? '' : null]) }}>
    <div class="nx-workflow-head">
        <span class="nx-workflow-icon">@if (is_string($icon) && \NabuXUI\NabuXUI::hasIcon($icon)){{ \NabuXUI\NabuXUI::icon($icon) }}@else{{ $icon }}@endif</span>
        <div>
            <{{ $titleAs }} class="nx-workflow-title" id="{{ $id }}">{{ $title }}</{{ $titleAs }}>
            @if ($description)<p class="nx-workflow-description">{{ $description }}</p>@endif
        </div>
        @if ($status)<x-nx::badge :tone="$statusTone" dot :pulse="(bool) $live">{{ $status }}</x-nx::badge>@endif
    </div>
    @if (count($rows))
        <div class="nx-workflow-details">
            <div>
                <dl class="nx-workflow-meta">
                    @foreach ($rows as $row)
                        <div class="nx-workflow-row"><dt>{{ $row['label'] ?? '' }}</dt><dd>{{ $row['value'] ?? '' }}</dd></div>
                    @endforeach
                </dl>
            </div>
        </div>
    @endif
    @if (count($members) || isset($actions))
        <div class="nx-workflow-foot">
            @if (count($members))<x-nx::avatar-group :people="$members" size="sm" :max="4" :label="$membersLabel" />@endif
            @isset($actions)<div class="nx-workflow-actions">{{ $actions }}</div>@endisset
        </div>
    @endif
</article>
