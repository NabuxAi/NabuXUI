{{--
    Password strength's real scenarios: a sign-up form that penalises the
    user's own name and email (the password is bound with wire:model), and a
    stricter admin policy in Persian.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $labels = $fa ? [
        'scores' => ['خیلی ضعیف', 'ضعیف', 'متوسط', 'خوب', 'قوی'],
        'rules' => [
            'length' => 'دست‌کم {min} نویسه',
            'lower' => 'یک حرف کوچک',
            'upper' => 'یک حرف بزرگ',
            'number' => 'یک رقم',
            'symbol' => 'یک نماد',
        ],
        'show' => 'نمایش گذرواژه',
        'hide' => 'پنهان‌کردن گذرواژه',
        'strength' => 'قدرت',
        'met' => 'انجام شد',
        'unmet' => 'هنوز نه',
    ] : [];
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Create your account', 'ساخت حساب') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Type a password: the four segments fill with the score’s colour and word, the rules tick off one by one, and the eye shows what you typed. Try “hossein2024” — it contains the name above, so it scores lower than its length suggests.', 'گذرواژه‌ای بنویسید: چهار بخش با رنگ و واژهٔ امتیاز پر می‌شوند، قواعد یکی‌یکی تیک می‌خورند و چشم نوشته را نشان می‌دهد. «hossein2024» را امتحان کنید — نام بالا را دارد، پس کمتر از آن‌چه طولش نشان می‌دهد امتیاز می‌گیرد.') }}
        </p>
    </div>
    <form x-on:submit.prevent="$wire.ping({{ \Illuminate\Support\Js::from($say('Account created', 'حساب ساخته شد')) }})" style="display: grid; gap: 1rem; max-inline-size: 26rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-xl); padding: 1.25rem">
        <x-nx::input :label="$say('Name', 'نام')" value="Hossein Rahimi" readonly />
        <x-nx::input :label="$say('Email', 'ایمیل')" value="hossein@nabu.example" dir="ltr" readonly />
        <x-nx::password-strength wire:model="state.password" :label="$say('Password', 'گذرواژه')" :labels="$labels"
            :user-inputs="['Hossein Rahimi', 'hossein@nabu.example']" dir="ltr" />
        <x-nx::button type="submit" variant="primary">{{ $say('Create account', 'ساخت حساب') }}</x-nx::button>
    </form>
</section>

<div class="pg-grid">
    <section class="pg-box" dir="rtl" lang="fa" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Admin policy, in Persian', 'سیاست مدیران، به فارسی') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('Twelve characters minimum: shorter passwords cap at “weak” however varied they are. The bars fill from the right.', 'دست‌کم دوازده نویسه: گذرواژهٔ کوتاه‌تر هر قدر هم متنوع، حداکثر «ضعیف» می‌گیرد. نوارها از راست پر می‌شوند.') }}
        </p>
        <x-nx::password-strength label="گذرواژهٔ مدیر" :min-length="12" hint="برای حساب‌های مدیریتی" :labels="[
            'scores' => ['خیلی ضعیف', 'ضعیف', 'متوسط', 'خوب', 'قوی'],
            'rules' => ['length' => 'دست‌کم {min} نویسه', 'lower' => 'یک حرف کوچک', 'upper' => 'یک حرف بزرگ', 'number' => 'یک رقم', 'symbol' => 'یک نماد'],
            'show' => 'نمایش گذرواژه', 'hide' => 'پنهان‌کردن گذرواژه', 'strength' => 'قدرت', 'met' => 'انجام شد', 'unmet' => 'هنوز نه',
        ]" />
    </section>

    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Just the essentials', 'فقط ضروری‌ها') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('Show only some rules — every rule still counts toward the score.', 'فقط بعضی قواعد را نشان دهید — همهٔ قواعد باز هم در امتیاز حساب می‌شوند.') }}
        </p>
        <x-nx::password-strength :label="$say('New password', 'گذرواژهٔ تازه')" :rules="['length', 'number']" :labels="$labels" />
    </section>
</div>
