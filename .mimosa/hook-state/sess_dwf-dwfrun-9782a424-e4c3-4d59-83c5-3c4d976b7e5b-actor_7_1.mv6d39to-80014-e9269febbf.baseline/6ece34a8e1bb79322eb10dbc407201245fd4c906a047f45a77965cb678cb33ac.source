{{--
    <x-nx::password-strength wire:model.live="password" label="Password" :user-inputs="[$name, $email]" />

    A password field with a segmented strength meter (scored in the browser by
    the core's passwordStrength), a rule checklist that ticks off and a
    show/hide toggle. wire:model and every other attribute land on the input.
    Pass `labels` to translate: ['scores' => [5 words], 'rules' => ['length' =>
    …, 'lower' => …], 'show' => …, 'hide' => …, 'strength' => …, 'met' => …, 'unmet' => …].
--}}
@props([
    'label' => 'Password',
    'hint' => null,
    'error' => null,
    'minLength' => 8,
    'rules' => ['length', 'lower', 'upper', 'number', 'symbol'],
    'userInputs' => [],
    'labels' => [],
])
@php
    use NabuXUI\NabuXUI;
    $id = $attributes->get('id') ?? NabuXUI::id('nx-password');
    $error ??= NabuXUI::error(NabuXUI::fieldName($attributes));
    $labels = (array) $labels;
    $scores = $labels['scores'] ?? ['Too weak', 'Weak', 'Fair', 'Good', 'Strong'];
    $ruleText = array_merge([
        'length' => 'At least {min} characters',
        'lower' => 'A lowercase letter',
        'upper' => 'An uppercase letter',
        'number' => 'A number',
        'symbol' => 'A symbol',
    ], (array) ($labels['rules'] ?? []));
    $show = $labels['show'] ?? 'Show password';
    $hide = $labels['hide'] ?? 'Hide password';
    $met = $labels['met'] ?? 'done';
    $unmet = $labels['unmet'] ?? 'not yet';
    $strength = $labels['strength'] ?? 'Strength';
    $described = implode(' ', array_filter([$hint ? "{$id}-hint" : null, "{$id}-meter", "{$id}-rules", $error ? "{$id}-error" : null]));
    $options = ['minLength' => (int) $minLength, 'userInputs' => array_values(array_filter((array) $userInputs)), 'scores' => $scores];
@endphp
<div @class(['nx-password', 'nx-field', $attributes->get('class')]) @if ($attributes->get('style')) style="{{ $attributes->get('style') }}" @endif
    data-score="0" data-empty @if ($error) data-invalid @endif
    x-data="nxPassword(@js($options))" x-bind:data-score="result().score" x-bind:data-empty="value ? null : ''" wire:ignore.self>
    <label class="nx-label" for="{{ $id }}">{{ $label }}</label>
    @if ($hint)<p class="nx-hint" id="{{ $id }}-hint">{{ $hint }}</p>@endif
    <div class="nx-input-group">
        <input {{ $attributes->except(['class', 'style', 'id'])->merge([
                'id' => $id,
                'type' => 'password',
                'autocomplete' => 'new-password',
                'spellcheck' => 'false',
                'aria-describedby' => $described,
                'aria-invalid' => $error ? 'true' : null,
            ])->class('nx-input') }}
            x-ref="input" x-bind:type="visible ? 'text' : 'password'" x-on:input="value = $event.target.value">
        <button type="button" class="nx-password-toggle" aria-pressed="false" aria-controls="{{ $id }}" aria-label="{{ $show }}"
            x-bind:aria-pressed="visible ? 'true' : 'false'" x-bind:aria-label="visible ? @js($hide) : @js($show)" x-on:click="visible = ! visible">
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2.5 12S6 5.5 12 5.5 21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12z"/><circle cx="12" cy="12" r="3"/></svg></span>
            <span data-off><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10.6 5.6A10 10 0 0 1 12 5.5c6 0 9.5 6.5 9.5 6.5a17 17 0 0 1-2.8 3.6M6.6 6.6A17 17 0 0 0 2.5 12S6 18.5 12 18.5a9.6 9.6 0 0 0 5.4-1.6"/><path d="M9.9 9.9a3 3 0 0 0 4.2 4.2M3 3l18 18"/></svg></span>
        </button>
    </div>
    <div class="nx-password-meter" id="{{ $id }}-meter">
        <span class="nx-password-bars" aria-hidden="true"><i></i><i></i><i></i><i></i></span>
        <span class="nx-password-label" aria-live="polite"><span class="nx-visually-hidden" x-show="value">{{ $strength }}: </span><span x-text="word()"></span></span>
    </div>
    <ul class="nx-password-rules" id="{{ $id }}-rules">
        @foreach ((array) $rules as $rule)
            @continue(! isset($ruleText[$rule]))
            <li x-bind:data-met="met(@js($rule)) ? '' : null">
                <span class="nx-password-tick" aria-hidden="true">
                    <span><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><circle cx="12" cy="12" r="3"/></svg></span>
                    <span data-off>{{ NabuXUI::icon('check') }}</span>
                </span>
                {{ str_replace('{min}', NabuXUI::formatNumber((int) $minLength), $ruleText[$rule]) }}
                <span class="nx-visually-hidden" x-text="met(@js($rule)) ? @js(', '.$met) : @js(', '.$unmet)">, {{ $unmet }}</span>
            </li>
        @endforeach
    </ul>
    @if ($error)<p class="nx-error" id="{{ $id }}-error">{{ NabuXUI::icon('alert-circle') }}{{ $error }}</p>@endif
</div>
