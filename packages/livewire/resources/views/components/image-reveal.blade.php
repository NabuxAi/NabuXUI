{{--
    <x-nx::image-reveal :src="$imageUrl" alt="A lighthouse at dusk" :progress="$progress" ratio="16 / 9" label="Generating" />

    An image placeholder that resolves out of pixel noise into the loaded image:
    while `progress` (0–100) is below 100 — or `src` is still empty — the noise
    keeps moving and gets finer; once the image has loaded it dissolves outward
    while the image sharpens. Without `progress` it simply waits for the load
    (lazy images). `placeholder` is an optional tiny blurred preview. Without
    Livewire, dispatch `nx-image-update` { progress, src } on the element.
--}}
@props([
    'src' => null,
    'alt' => '',
    'placeholder' => null,
    'progress' => null,
    'ratio' => null,
    'label' => 'Loading',
    'errorLabel' => 'Could not load the image',
])
@php
    $value = $progress === null ? null : max(0, min(100, (int) round((float) $progress)));
    $pending = ! $src || ($value !== null && $value < 100);
@endphp
<div {{ $attributes->class('nx-image-reveal')->merge([
    'data-pending' => $pending ? '' : null,
    'data-progress' => $value,
    'style' => $ratio ? "--_ratio: {$ratio}" : null,
]) }} x-data="nxImageReveal" x-on:nx-image-update="update($event.detail ?? {})" wire:ignore.self>
    <data class="nx-visually-hidden" x-ref="sync" value="{{ $value }}" data-src="{{ $src }}" aria-hidden="true"></data>
    @if ($placeholder)
        <img class="nx-image-reveal-placeholder" src="{{ $placeholder }}" alt="" aria-hidden="true">
    @else
        <div class="nx-image-reveal-placeholder" aria-hidden="true"></div>
    @endif
    <canvas class="nx-image-reveal-noise" aria-hidden="true"></canvas>
    <img class="nx-image-reveal-img" @if ($src) src="{{ $src }}" @endif alt="{{ $alt }}" decoding="async">
    @if ($value !== null)
        <div class="nx-image-reveal-status" role="progressbar" aria-label="{{ $label }}" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $value }}">
            <span class="nx-image-reveal-status-row">
                <span class="nx-image-reveal-label">{{ $label }}</span>
                <span class="nx-image-reveal-error">{{ $errorLabel }}</span>
                <span>{{ $value }}%</span>
            </span>
            <span class="nx-image-reveal-bar"><i style="--_p: {{ $value / 100 }}"></i></span>
        </div>
    @else
        <div class="nx-image-reveal-status" role="status">
            <span class="nx-image-reveal-label">{{ $label }}</span>
            <span class="nx-image-reveal-error">{{ $errorLabel }}</span>
        </div>
    @endif
</div>
