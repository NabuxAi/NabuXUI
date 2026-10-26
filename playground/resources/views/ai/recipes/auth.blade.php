{{-- auth: the sign-up card the prompt asked for — email, six-digit code, submit. --}}
@php $fa = app()->getLocale() === 'fa'; $say = fn (string $e, string $f) => $fa ? $f : $e; @endphp
<div style="max-inline-size: 26rem; margin-inline: auto; display: grid; gap: 1rem">
    <div style="display: grid; gap: .3rem; text-align: center">
        <h3 style="margin: 0; font: 700 var(--nx-text-xl) / 1.25 var(--nx-font-display)">{{ $say('Create your account', 'حساب خود را بسازید') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted); font-size: var(--nx-text-sm)">{{ $say('Two minutes, no card required.', 'دو دقیقه، بدون کارت.') }}</p>
    </div>
    <x-nx::field :label="$say('Work email', 'ایمیل کاری')" name="ai-auth-email">
        <x-nx::input type="email" name="ai-auth-email" :placeholder="$say('you@team.dev', 'you@team.dev')" />
    </x-nx::field>
    <x-nx::otp name="ai-auth-code" :label="$say('Verification code', 'کد تأیید')" />
    <x-nx::checkbox name="ai-auth-terms" :label="$say('I agree to the terms', 'شرایط را می‌پذیرم')" />
    <x-nx::button variant="primary" icon-end="arrow-right">{{ $say('Create account', 'ساختن حساب') }}</x-nx::button>
    <p style="margin: 0; text-align: center; font-size: var(--nx-text-xs); color: var(--nx-text-muted)">
        {{ $say('Already aboard?', 'از قبل عضوید؟') }}
        <a href="/components/flows/auth-flow" wire:navigate style="color: var(--nx-accent-text)">{{ $say('Sign in', 'ورود') }}</a>
    </p>
</div>
