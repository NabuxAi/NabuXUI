{{--
    <x-nx::auth-card mode="login" brand="Nabu" tagline="…"
        :perks="['Design systems that move', 'One core, five frameworks']"
        model="form" wire:submit="login" />

    Sign in / sign up / reset in one card: a brand panel (deep plate, drifting
    aurora glows, hairline grid — pure CSS) beside a form whose three panes
    share one viewport. Modes switch in the browser (Alpine): the panes glide
    past each other and the card's height morphs; an `nx-mode-change` event is
    dispatched with the new mode. With `model`, the inputs bind to
    model.email / model.password / model.name (remember → model.remember);
    without it they are plain named inputs for a classic POST to `action`.
    Submit: wire:submit="login" — the button spins while it runs — or a plain
    POST. Field errors come from $errors by field name. Every word can be
    overridden per instance with the `words` array (keys:
    login, loginTitle, loginSubtitle, register, registerTitle, registerSubtitle,
    forgot, forgotTitle, forgotSubtitle, email, password, name, remember,
    forgotLink, noAccount, hasAccount, backToLogin).
--}}
@props([
    'mode' => 'login',
    'brand' => 'Nabu',
    'brandMark' => null,
    'tagline' => null,
    'perks' => [],
    'model' => null,
    'remember' => true,
    'words' => [],
    'locale' => null,
])
@php
    use NabuXUI\NabuXUI;
    $id = NabuXUI::id('nx-auth');
    $lang = substr($locale ?? app()->getLocale(), 0, 2);
    // The card's own words live in the core i18n table (resources/lang, generated from it).
    $say = fn (string $key) => __('nabuxui::ui.'.$key, [], $lang);
    $modes = ['login', 'register', 'forgot'];
    $mode = in_array($mode, $modes, true) ? $mode : 'login';

    $defaults = [
        'login' => $say('authLogin'),
        'loginTitle' => $say('authLoginTitle'),
        'loginSubtitle' => $say('authLoginSubtitle'),
        'register' => $say('authRegister'),
        'registerTitle' => $say('authRegisterTitle'),
        'registerSubtitle' => $say('authRegisterSubtitle'),
        'forgot' => $say('authForgot'),
        'forgotTitle' => $say('authForgotTitle'),
        'forgotSubtitle' => $say('authForgotSubtitle'),
        'email' => $say('authEmail'),
        'password' => $say('authPassword'),
        'name' => $say('authName'),
        'remember' => $say('authRemember'),
        'forgotLink' => $say('authForgotLink'),
        'noAccount' => $say('authNoAccount'),
        'hasAccount' => $say('authHasAccount'),
        'backToLogin' => $say('authBackToLogin'),
    ];
    $w = fn (string $key) => $words[$key] ?? $defaults[$key];
    $field = fn (string $name) => $model ? "{$model}.{$name}" : $name;
    // The session's error bag, directly (NabuXUI::error misses ViewErrorBag's
    // magic first(), so the invalid wiring would never light up).
    $errorBag = view()->shared('errors');
    $firstError = fn (string $name) => $name ? ($errorBag?->first($name) ?: null) : null;
    $emailError = $firstError($field('email'));
    $passwordError = $firstError($field('password'));
    $nameError = $firstError($field('name'));

    // With wire:submit, Livewire's wire:loading turns the button busy while the action runs.
    $submitTarget = null;
    foreach ($attributes->getAttributes() as $attr => $handler) {
        if (str_starts_with($attr, 'wire:submit')) {
            $submitTarget = preg_replace('/\(.*$/s', '', (string) $handler) ?: null;
        }
    }
    // The words the Alpine side needs (the live region and the button's name).
    $alpineWords = collect($modes)->mapWithKeys(fn ($m) => [$m => ['title' => (string) $w($m === 'login' ? 'loginTitle' : ($m === 'register' ? 'registerTitle' : 'forgotTitle')), 'submit' => (string) $w($m)]])->all();
    // Where a pane starts: toward the reading start, current, or toward the end.
    $posOf = fn (string $m) => array_search($m, $modes, true) < array_search($mode, $modes, true) ? 'before' : ($m === $mode ? 'current' : 'after');
    $placeholder = 'you@example.com';
@endphp
<form {{ $attributes->class('nx-auth-card')->merge(['novalidate' => true, 'data-mode' => $mode, 'data-nx-reveal' => '', 'aria-labelledby' => "{$id}-title"]) }}
    x-data="nxAuthCard(@js($mode), @js($alpineWords))" x-nx-reveal
    x-bind:data-mode="mode" x-on:submit.capture="submit($event)">
    <aside class="nx-auth-card-media" aria-hidden="true">
        <span class="nx-auth-card-glow" data-i="1"></span>
        <span class="nx-auth-card-glow" data-i="2"></span>
        <span class="nx-auth-card-glow" data-i="3"></span>
        <span class="nx-auth-card-gridlines"></span>
        <div class="nx-auth-card-brand">
            <span class="nx-auth-card-mark">{{ $brandMark ?? mb_substr(trim(strip_tags((string) $brand)) ?: 'N', 0, 1) }}</span>
            <p class="nx-auth-card-name">{{ $brand }}</p>
            @if ($tagline)<p class="nx-auth-card-tagline">{{ $tagline }}</p>@endif
        </div>
        @if (count((array) $perks))
            <ul class="nx-auth-card-perks" data-nx-reveal="group" x-nx-reveal.group>
                @foreach ((array) $perks as $i => $perk)
                    <li style="--nx-i: {{ $i }}">{{ NabuXUI::icon('check') }}<span>{{ $perk }}</span></li>
                @endforeach
            </ul>
        @endif
    </aside>

    <div class="nx-auth-card-body">
        <div class="nx-auth-card-head">
            <h2 class="nx-auth-card-title" id="{{ $id }}-title">
                @foreach ($modes as $m)
                    @php $titleKey = $m === 'login' ? 'loginTitle' : ($m === 'register' ? 'registerTitle' : 'forgotTitle'); @endphp
                    <span @if ($m === $mode) data-current @endif x-bind:data-current="mode === @js($m) ? '' : null" x-bind:aria-hidden="mode === @js($m) ? null : 'true'">{{ $w($titleKey) }}</span>
                @endforeach
            </h2>
            <p class="nx-auth-card-subtitle">
                @foreach ($modes as $m)
                    @php $subtitleKey = $m === 'login' ? 'loginSubtitle' : ($m === 'register' ? 'registerSubtitle' : 'forgotSubtitle'); @endphp
                    <span @if ($m === $mode) data-current @endif x-bind:data-current="mode === @js($m) ? '' : null" x-bind:aria-hidden="mode === @js($m) ? null : 'true'">{{ $w($subtitleKey) }}</span>
                @endforeach
            </p>
        </div>

        <div class="nx-auth-card-viewport" x-ref="viewport">
            <div class="nx-auth-card-measure" x-ref="measure">
                <fieldset class="nx-auth-card-pane" data-pane="login" data-pos="{{ $posOf('login') }}"
                    x-bind:data-pos="pos('login')" @disabled($mode !== 'login') x-bind:disabled="mode !== 'login'">
                    <legend>{{ $w('login') }}</legend>
                    <div class="nx-field" @if ($emailError) data-invalid @endif>
                        <label class="nx-label" for="{{ $id }}-login-email">{{ $w('email') }}<span class="nx-label-required" aria-hidden="true">*</span></label>
                        <div class="nx-input-group">
                            <span class="nx-input-addon">{{ NabuXUI::icon('mail') }}</span>
                            <input class="nx-input" id="{{ $id }}-login-email" type="email" name="email" dir="ltr" autocomplete="email" required placeholder="{{ $placeholder }}"
                                @if ($model) wire:model="{{ $field('email') }}" @endif
                                @if ($emailError) aria-invalid="true" aria-describedby="{{ $id }}-login-email-error" @endif>
                        </div>
                        @error($field('email'))<p class="nx-error" id="{{ $id }}-login-email-error">{{ NabuXUI::icon('alert-circle') }}{{ $message }}</p>@enderror
                    </div>
                    <div class="nx-field" @if ($passwordError) data-invalid @endif>
                        <label class="nx-label" for="{{ $id }}-login-password">{{ $w('password') }}<span class="nx-label-required" aria-hidden="true">*</span></label>
                        <div class="nx-input-group">
                            <span class="nx-input-addon">{{ NabuXUI::icon('lock') }}</span>
                            <input class="nx-input" id="{{ $id }}-login-password" type="password" name="password" dir="ltr" autocomplete="current-password" required
                                @if ($model) wire:model="{{ $field('password') }}" @endif
                                @if ($passwordError) aria-invalid="true" aria-describedby="{{ $id }}-login-password-error" @endif>
                        </div>
                        @error($field('password'))<p class="nx-error" id="{{ $id }}-login-password-error">{{ NabuXUI::icon('alert-circle') }}{{ $message }}</p>@enderror
                    </div>
                    <div class="nx-auth-card-row">
                        @if ($remember)
                            <label class="nx-choice">
                                <input class="nx-checkbox" type="checkbox" name="remember" value="1" checked
                                    @if ($model) wire:model="{{ $field('remember') }}" @endif>
                                <span class="nx-choice-text"><span class="nx-choice-label">{{ $w('remember') }}</span></span>
                            </label>
                        @endif
                        <button type="button" class="nx-auth-card-link" x-on:click="set('forgot')">{{ $w('forgotLink') }}</button>
                    </div>
                    @isset($login){{ $login }}@endisset
                </fieldset>

                <fieldset class="nx-auth-card-pane" data-pane="register" data-pos="{{ $posOf('register') }}"
                    x-bind:data-pos="pos('register')" @disabled($mode !== 'register') x-bind:disabled="mode !== 'register'">
                    <legend>{{ $w('register') }}</legend>
                    <div class="nx-field" @if ($nameError) data-invalid @endif>
                        <label class="nx-label" for="{{ $id }}-register-name">{{ $w('name') }}<span class="nx-label-required" aria-hidden="true">*</span></label>
                        <div class="nx-input-group">
                            <span class="nx-input-addon">{{ NabuXUI::icon('user') }}</span>
                            <input class="nx-input" id="{{ $id }}-register-name" name="name" autocomplete="name" required
                                @if ($model) wire:model="{{ $field('name') }}" @endif
                                @if ($nameError) aria-invalid="true" aria-describedby="{{ $id }}-register-name-error" @endif>
                        </div>
                        @error($field('name'))<p class="nx-error" id="{{ $id }}-register-name-error">{{ NabuXUI::icon('alert-circle') }}{{ $message }}</p>@enderror
                    </div>
                    <div class="nx-field" @if ($emailError) data-invalid @endif>
                        <label class="nx-label" for="{{ $id }}-register-email">{{ $w('email') }}<span class="nx-label-required" aria-hidden="true">*</span></label>
                        <div class="nx-input-group">
                            <span class="nx-input-addon">{{ NabuXUI::icon('mail') }}</span>
                            <input class="nx-input" id="{{ $id }}-register-email" type="email" name="email" dir="ltr" autocomplete="email" required placeholder="{{ $placeholder }}"
                                @if ($model) wire:model="{{ $field('email') }}" @endif
                                @if ($emailError) aria-invalid="true" aria-describedby="{{ $id }}-register-email-error" @endif>
                        </div>
                        @error($field('email'))<p class="nx-error" id="{{ $id }}-register-email-error">{{ NabuXUI::icon('alert-circle') }}{{ $message }}</p>@enderror
                    </div>
                    <div class="nx-field" @if ($passwordError) data-invalid @endif>
                        <label class="nx-label" for="{{ $id }}-register-password">{{ $w('password') }}<span class="nx-label-required" aria-hidden="true">*</span></label>
                        <div class="nx-input-group">
                            <span class="nx-input-addon">{{ NabuXUI::icon('lock') }}</span>
                            <input class="nx-input" id="{{ $id }}-register-password" type="password" name="password" dir="ltr" autocomplete="new-password" required
                                @if ($model) wire:model="{{ $field('password') }}" @endif
                                @if ($passwordError) aria-invalid="true" aria-describedby="{{ $id }}-register-password-error" @endif>
                        </div>
                        @error($field('password'))<p class="nx-error" id="{{ $id }}-register-password-error">{{ NabuXUI::icon('alert-circle') }}{{ $message }}</p>@enderror
                    </div>
                    @isset($register){{ $register }}@endisset
                </fieldset>

                <fieldset class="nx-auth-card-pane" data-pane="forgot" data-pos="{{ $posOf('forgot') }}"
                    x-bind:data-pos="pos('forgot')" @disabled($mode !== 'forgot') x-bind:disabled="mode !== 'forgot'">
                    <legend>{{ $w('forgot') }}</legend>
                    <div class="nx-field" @if ($emailError) data-invalid @endif>
                        <label class="nx-label" for="{{ $id }}-forgot-email">{{ $w('email') }}<span class="nx-label-required" aria-hidden="true">*</span></label>
                        <p class="nx-hint" id="{{ $id }}-forgot-email-hint">{{ $w('forgotSubtitle') }}</p>
                        <div class="nx-input-group">
                            <span class="nx-input-addon">{{ NabuXUI::icon('mail') }}</span>
                            <input class="nx-input" id="{{ $id }}-forgot-email" type="email" name="email" dir="ltr" autocomplete="email" required placeholder="{{ $placeholder }}"
                                aria-describedby="{{ $id }}-forgot-email-hint{{ $emailError ? ' '.$id.'-forgot-email-error' : '' }}"
                                @if ($model) wire:model="{{ $field('email') }}" @endif
                                @if ($emailError) aria-invalid="true" @endif>
                        </div>
                        @error($field('email'))<p class="nx-error" id="{{ $id }}-forgot-email-error">{{ NabuXUI::icon('alert-circle') }}{{ $message }}</p>@enderror
                    </div>
                    @isset($forgot){{ $forgot }}@endisset
                </fieldset>
            </div>
        </div>

        <p class="nx-visually-hidden" aria-live="polite" x-text="labels[mode].title"></p>

        <x-nx::button type="submit" variant="primary" size="lg" block class="nx-auth-card-submit" :target="$submitTarget"
            x-bind:aria-label="labels[mode].submit">
            <span class="nx-auth-card-submit-labels" aria-hidden="true">
                @foreach ($modes as $m)
                    <span @if ($m === $mode) data-current @endif x-bind:data-current="mode === @js($m) ? '' : null">{{ $w($m) }}</span>
                @endforeach
            </span>
        </x-nx::button>

        <p class="nx-auth-card-switch">
            <span class="nx-auth-card-switch-row" @if ($mode === 'login') data-current @endif x-bind:data-current="mode === 'login' ? '' : null" x-bind:aria-hidden="mode === 'login' ? null : 'true'">
                {{ $w('noAccount') }}<button type="button" class="nx-auth-card-link" x-bind:tabindex="mode === 'login' ? null : -1" x-on:click="set('register')">{{ $w('register') }}</button>
            </span>
            <span class="nx-auth-card-switch-row" @if ($mode === 'register') data-current @endif x-bind:data-current="mode === 'register' ? '' : null" x-bind:aria-hidden="mode === 'register' ? null : 'true'">
                {{ $w('hasAccount') }}<button type="button" class="nx-auth-card-link" x-bind:tabindex="mode === 'register' ? null : -1" x-on:click="set('login')">{{ $w('login') }}</button>
            </span>
            <span class="nx-auth-card-switch-row" @if ($mode === 'forgot') data-current @endif x-bind:data-current="mode === 'forgot' ? '' : null" x-bind:aria-hidden="mode === 'forgot' ? null : 'true'">
                <button type="button" class="nx-auth-card-link" x-bind:tabindex="mode === 'forgot' ? null : -1" x-on:click="set('login')">
                    {{ NabuXUI::icon('arrow-left') }}{{ $w('backToLogin') }}
                </button>
            </span>
        </p>
    </div>
</form>
