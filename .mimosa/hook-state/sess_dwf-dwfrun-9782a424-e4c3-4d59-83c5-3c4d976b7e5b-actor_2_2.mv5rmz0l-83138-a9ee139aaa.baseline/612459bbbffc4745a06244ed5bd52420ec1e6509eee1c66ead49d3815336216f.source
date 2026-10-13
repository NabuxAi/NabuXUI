{{--
    <x-nx::wizard heading="ساخت فضای کاری" subtitle="سه گام و آماده"
        :steps="[
            ['id' => 'account', 'title' => 'حساب', 'description' => 'ایمیل و گذرواژه'],
            ['id' => 'profile', 'title' => 'پروفایل', 'description' => 'نام و نقش'],
        ]"
        step="0" wire:submit="create">
        <x-slot:stepAccount>
            <div class="nx-field">…fields (nx-field / nx-input / wire:model)…</div>
        </x-slot:stepAccount>
        <x-slot:stepProfile>…</x-slot:stepProfile>
    </x-nx::wizard>

    A multi-step form shell on top of the shared steps: a progress bar, the
    steps (completed ones jump back), one pane per step that glides while the
    card's height morphs, per-step validation, and a final review pane that
    reads the answers back from the form's own controls. Each step's fields go
    in a slot named `step` + StudlyCase of the step's id (`stepAccount` for
    id `account`), or `step1`, `step2`, … when the step has no id. "Next" is
    refused until the step in view passes the form's own constraint validation
    (the other panes are disabled fieldsets, so they never judge) — the pane
    shakes, the footer error lights up and the first bad field is focused.
    The final submit re-enables every pane first, so a `wire:submit` (or a
    plain POST to `action`) carries all the answers. `wire:model` inputs work
    inside the panes; an `nx-step-change` event bubbles on every hop. Every
    word can be overridden per instance with the `labels` array (keys: review,
    submit, progress, of, announce, invalid, edit, editStep, empty, yes, back,
    next).
--}}
@props(['steps' => [], 'heading' => null, 'subtitle' => null, 'review' => true, 'step' => 0, 'labels' => [], 'locale' => null])
@php
    use NabuXUI\NabuXUI;
    use Illuminate\Support\Str;

    $locale = str_replace('_', '-', $locale ?? app()->getLocale());
    $lang = substr($locale, 0, 2);
    // The wizard's own words live in the core i18n table (resources/lang, generated from it).
    $say = fn (string $key) => __('nabuxui::ui.'.$key, [], $lang);
    // Kebab-case boolean props arrive as strings; read them like Blade does.
    $review = ! in_array(strtolower((string) $review), ['0', 'false', 'no', 'off', ''], true);
    $words = [
        'review' => $say('wizardReview'),
        'submit' => $say('wizardSubmit'),
        'progress' => $say('wizardProgress'),
        'of' => $say('wizardOf'),
        'announce' => $say('wizardAnnounce'),
        'invalid' => $say('wizardInvalid'),
        'edit' => $say('wizardEdit'),
        'editStep' => $say('wizardEditStep'),
        'empty' => $say('wizardEmpty'),
        'yes' => $say('wizardYes'),
        'back' => $say('back'),
        'next' => $say('next'),
    ];
    $labels = array_merge($words, is_array($labels) ? $labels : []);
    $withParams = fn (string $template, array $params) => str_replace(
        array_map(fn (string $key) => ':'.$key, array_keys($params)),
        array_map(fn ($value) => (string) $value, array_values($params)),
        $template,
    );

    $steps = array_values((array) $steps);
    $step = max(0, min((int) $step, max(0, count($steps) + ($review ? 1 : 0) - 1)));
    $total = count($steps) + ($review ? 1 : 0);
    $atEnd = $step >= $total - 1;
    $percent = (int) round($step / max(1, $total - 1) * 100);

    // Each step: id, title, description and the slot its fields live in.
    $normalize = fn ($value, int $i) => [
        'id' => (string) (is_array($value) ? ($value['id'] ?? 's'.$i) : 's'.$i),
        'title' => (string) (is_array($value) ? ($value['title'] ?? '') : ''),
        'description' => isset($value['description']) ? (string) $value['description'] : null,
    ];
    // Named slots arrive as view variables ($stepAccount for id `account`, or $step1, $step2, …).
    $slotVars = get_defined_vars();
    $slotFor = function (int $i, string $id) use ($slotVars) {
        foreach (['step'.Str::studly($id), 'step'.($i + 1)] as $name) {
            if (isset($slotVars[$name]) && $slotVars[$name] !== null) {
                return $slotVars[$name];
            }
        }

        return null;
    };

    $id = NabuXUI::id('nx-wizard');
    $statusOf = fn (int $i) => $i < $step ? 'complete' : ($i === $step ? 'current' : 'upcoming');
    $posOf = fn (int $i) => $i < $step ? 'before' : ($i === $step ? 'current' : 'after');
    $progressText = $withParams($labels['progress'], [
        'current' => NabuXUI::formatNumber($step + 1, 0, $locale),
        'total' => NabuXUI::formatNumber($total, 0, $locale),
    ]);
    $announceText = $withParams($labels['announce'], [
        'current' => NabuXUI::formatNumber($step + 1, 0, $locale),
        'total' => NabuXUI::formatNumber($total, 0, $locale),
        'title' => $step < count($steps) ? ($normalize($steps[$step] ?? null, $step)['title']) : $labels['review'],
    ]);

    // With wire:submit, Livewire's wire:loading turns the button busy while the action runs.
    $submitTarget = null;
    foreach ($attributes->getAttributes() as $attr => $handler) {
        if (str_starts_with($attr, 'wire:submit')) {
            $submitTarget = preg_replace('/\(.*$/s', '', (string) $handler) ?: null;
        }
    }
@endphp
<form {{ $attributes->class('nx-wizard')->merge(array_filter([
        'novalidate' => true,
        'data-nx-reveal' => '',
        'aria-labelledby' => $heading !== null ? "{$id}-title" : null,
    ], fn ($value) => $value !== null)) }}
    x-data="nxWizard(@js(['steps' => array_map(fn ($s, $i) => $normalize($s, $i), $steps, array_keys($steps)), 'labels' => $labels, 'locale' => $locale, 'review' => $review, 'start' => $step]))"
    x-effect="paint()" x-on:submit.capture="submit($event)">
    <header class="nx-wizard-head">
        <div>
            @if ($heading !== null)<h2 class="nx-wizard-title" id="{{ $id }}-title">{{ $heading }}</h2>@endif
            @if ($subtitle)<p class="nx-wizard-subtitle">{{ $subtitle }}</p>@endif
        </div>
        @if (count($steps) > 0)
            <span class="nx-wizard-count" aria-hidden="true" wire:ignore>
                <x-nx::number :value="$step + 1" :reveal="false" :locale="$locale" />
                <span class="nx-wizard-count-sep">{{ $labels['of'] }}</span>
                <x-nx::number :value="$total" :reveal="false" :locale="$locale" />
            </span>
        @endif
    </header>

    @if (count($steps) > 0)
        <div class="nx-wizard-progress" role="progressbar" aria-valuemin="0" aria-valuemax="100"
            aria-valuenow="{{ $percent }}" aria-valuetext="{{ $progressText }}"
            x-bind:aria-valuenow="String(percent())" x-bind:aria-valuetext="progressText()">
            <span class="nx-wizard-progress-track">
                <span class="nx-wizard-progress-fill" style="--nx-wizard-p: {{ $percent }}"
                    x-bind:style="'--nx-wizard-p: ' + percent()"></span>
            </span>
        </div>

        <ol class="nx-steps nx-wizard-steps" aria-label="{{ $progressText }}" x-bind:aria-label="progressText()">
            @foreach ($steps as $i => $raw)
                @php
                    $s = $normalize($raw, $i);
                    $state = $statusOf($i);
                @endphp
                <li class="nx-step" data-status="{{ $state }}"
                    @if ($state === 'current') aria-current="step" @endif
                    x-bind:data-status="status({{ $i }})" x-bind:aria-current="current === {{ $i }} ? 'step' : null">
                    <button type="button" class="nx-wizard-step-jump" @disabled($i >= $step)
                        x-bind:disabled="{{ $i }} >= current"
                        x-bind:aria-label="{{ $i }} < current ? editLabel({{ $i }}) : null"
                        x-on:click="jump({{ $i }})">
                        <span class="nx-step-marker">{{ NabuXUI::icon('check') }}</span>
                        <span class="nx-step-text">
                            <span class="nx-step-title">{{ $s['title'] }}</span>
                            @if ($s['description'])<span class="nx-step-description">{{ $s['description'] }}</span>@endif
                        </span>
                    </button>
                </li>
            @endforeach
            @if ($review)
                <li class="nx-step" data-status="{{ $statusOf($total - 1) }}" @if ($atEnd) aria-current="step" @endif
                    x-bind:data-status="status({{ $total - 1 }})" x-bind:aria-current="current === {{ $total - 1 }} ? 'step' : null">
                    <button type="button" class="nx-wizard-step-jump" disabled>
                        <span class="nx-step-marker">{{ NabuXUI::icon('check') }}</span>
                        <span class="nx-step-text">
                            <span class="nx-step-title">{{ $labels['review'] }}</span>
                        </span>
                    </button>
                </li>
            @endif
        </ol>
    @endif

    <div class="nx-wizard-viewport" x-ref="viewport">
        <div class="nx-wizard-measure" x-ref="measure">
            @foreach ($steps as $i => $raw)
                @php
                    $s = $normalize($raw, $i);
                    $content = $slotFor($i, $s['id']);
                    $legend = $s['title'] !== ''
                        ? $s['title']
                        : $withParams($labels['progress'], [
                            'current' => NabuXUI::formatNumber($i + 1, 0, $locale),
                            'total' => NabuXUI::formatNumber($total, 0, $locale),
                        ]);
                @endphp
                <fieldset class="nx-wizard-pane" data-step="{{ $i }}" data-title="{{ $s['title'] }}"
                    data-pos="{{ $posOf($i) }}" @disabled($i !== $step)
                    x-bind:data-pos="pos({{ $i }})" x-bind:disabled="{{ $i }} !== current"
                    x-bind:data-invalid="invalid && current === {{ $i }} ? '' : null">
                    <legend>{{ $legend }}</legend>
                    @if ($content){{ $content }}@endif
                </fieldset>
            @endforeach
            @if ($review)
                <fieldset class="nx-wizard-pane" data-step="review" data-title="{{ $labels['review'] }}"
                    data-pos="{{ $posOf($total - 1) }}" @disabled(! $atEnd)
                    x-bind:data-pos="pos({{ $total - 1 }})" x-bind:disabled="current !== {{ $total - 1 }}"
                    x-bind:data-invalid="invalid && atEnd() ? '' : null">
                    <legend>{{ $labels['review'] }}</legend>
                    <dl class="nx-wizard-summary">
                        <template x-for="group in summary" :key="group.step">
                            <div class="nx-wizard-summary-group">
                                <div class="nx-wizard-summary-head">
                                    <dt class="nx-wizard-summary-step" x-text="group.title"></dt>
                                    <button type="button" class="nx-wizard-summary-edit"
                                        x-bind:aria-label="editLabel(group.step)" x-on:click="jump(group.step)">
                                        {{ NabuXUI::icon('edit') }}<span x-text="labels.edit"></span>
                                    </button>
                                </div>
                                <div class="nx-wizard-summary-rows">
                                    <template x-for="(row, ri) in group.rows" :key="ri">
                                        <div class="nx-wizard-summary-row">
                                            <span class="nx-wizard-summary-label" x-text="row.label"></span>
                                            <dd class="nx-wizard-summary-value" x-bind:data-empty="row.empty ? '' : null" x-text="row.value"></dd>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </dl>
                </fieldset>
            @endif
        </div>
    </div>

    <p class="nx-visually-hidden" role="status" aria-live="polite" x-text="announce">{{ $announceText }}</p>

    <footer class="nx-wizard-foot">
        <x-nx::button type="button" variant="ghost" size="lg" class="nx-wizard-back" data-off="{{ $step === 0 ? 'on' : 'off' }}" x-bind:data-off="current === 0 ? 'on' : 'off'" x-on:click="back()">
            {{ NabuXUI::icon('arrow-left') }}<span>{{ $labels['back'] }}</span>
        </x-nx::button>
        <p class="nx-wizard-error" x-bind:data-invalid="invalid ? '' : null" aria-live="polite">
            <span x-show="invalid">{{ NabuXUI::icon('alert-circle') }}<span x-text="labels.invalid"></span></span>
        </p>
        <x-nx::button type="submit" variant="primary" size="lg" class="nx-wizard-next" :target="$submitTarget" x-bind:aria-label="atEnd() ? labels.submit : labels.next">
            <span class="nx-wizard-next-labels" aria-hidden="true">
                <span @if (! $atEnd) data-current @endif x-bind:data-current="atEnd() ? null : ''">{{ $labels['next'] }}</span>
                <span @if ($atEnd) data-current @endif x-bind:data-current="atEnd() ? '' : null">{{ $labels['submit'] }}</span>
            </span>
        </x-nx::button>
    </footer>
</form>
