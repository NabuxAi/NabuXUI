{{--
    Dynamic island's real scenarios: the top of a phone screen whose island
    morphs between idle, a running timer (compact → expanded), an incoming call
    and a delivery notification.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $icon = fn (string $name) => \NabuXUI\NabuXUI::icon($name);
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A phone’s live activities', 'فعالیت‌های زندهٔ یک گوشی') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Start a timer, take a call or get a delivery update. Tap the compact timer to expand it. The shape springs to each view while the content cross-fades.', 'تایمر بگذارید، تماس بگیرید یا خبر ارسال بگیرید. روی تایمرِ فشرده بزنید تا باز شود. شکل با فنر به اندازهٔ هر نما می‌رسد و محتوا محو‌به‌محو عوض می‌شود.') }}
        </p>
    </div>

    <div x-data="{
            seconds: 0, timer: null,
            get clock() { const m = String(Math.floor(this.seconds / 60)).padStart(2, '0'); const s = String(this.seconds % 60).padStart(2, '0'); return m + ':' + s; },
            startTimer() { this.seconds = 300; clearInterval(this.timer); this.timer = setInterval(() => { if (this.seconds > 0) this.seconds--; }, 1000); $dispatch('nx-island-show', 'timer'); },
            stopTimer() { clearInterval(this.timer); this.timer = null; $dispatch('nx-island-show', 'idle'); },
        }"
        style="display: grid; gap: 1.25rem; justify-items: center">
        <div style="inline-size: min(100%, 24rem); block-size: 15rem; padding: .75rem; border-radius: 2.5rem; background: var(--nx-surface-2); box-shadow: inset 0 0 0 1px var(--nx-border)">
            <x-nx::dynamic-island view="idle" :label="$say('Live activity', 'فعالیت زنده')">
                <x-nx::island-view name="idle">
                    <div style="inline-size: 6.5rem; block-size: 1.25rem"></div>
                </x-nx::island-view>

                <x-nx::island-view name="timer">
                    <button type="button" class="nx-island-toggle" x-on:click="show('timer-open')" aria-label="{{ $say('Expand timer', 'باز کردن تایمر') }}">
                        <span style="color: var(--nx-warning)">{{ $icon('play') }}</span>
                        <span class="nx-island-figure" x-text="clock">05:00</span>
                    </button>
                </x-nx::island-view>

                <x-nx::island-view name="timer-open" size="expanded">
                    <div class="nx-island-row" data-spread>
                        <div class="nx-island-stack">
                            <span class="nx-island-sub">{{ $say('Tea timer', 'تایمر چای') }}</span>
                            <span class="nx-island-figure" style="font-size: var(--nx-text-3xl)" x-text="clock">05:00</span>
                        </div>
                        <div class="nx-island-row">
                            <button type="button" class="nx-island-btn" data-icon-only aria-label="{{ $say('Collapse', 'جمع کردن') }}" x-on:click="show('timer')">{{ $icon('chevron-up') }}</button>
                            <button type="button" class="nx-island-btn" data-icon-only data-tone="danger" aria-label="{{ $say('Stop timer', 'توقف تایمر') }}" x-on:click="stopTimer()">{{ $icon('stop') }}</button>
                        </div>
                    </div>
                </x-nx::island-view>

                <x-nx::island-view name="call" size="expanded">
                    <div class="nx-island-row">
                        <span class="nx-island-avatar" aria-hidden="true">{{ $say('S', 'س') }}</span>
                        <div class="nx-island-stack">
                            <span class="nx-island-sub">{{ $say('Incoming call', 'تماس ورودی') }}</span>
                            <span class="nx-island-title">{{ $say('Sara Novak', 'سارا احمدی') }}</span>
                        </div>
                        <span class="nx-island-wave" aria-hidden="true" style="margin-inline-start: auto">@for ($i = 0; $i < 5; $i++)<i style="--nx-i: {{ $i }}"></i>@endfor</span>
                    </div>
                    <div class="nx-island-actions">
                        <button type="button" class="nx-island-btn" data-tone="danger" style="flex: 1" x-on:click="show('idle')">{{ $icon('x') }} {{ $say('Decline', 'رد') }}</button>
                        <button type="button" class="nx-island-btn" data-tone="success" style="flex: 1" x-on:click="show('oncall')">{{ $icon('check') }} {{ $say('Answer', 'پاسخ') }}</button>
                    </div>
                </x-nx::island-view>

                <x-nx::island-view name="oncall">
                    <button type="button" class="nx-island-toggle" x-on:click="show('idle')" aria-label="{{ $say('End call', 'پایان تماس') }}">
                        <span class="nx-island-dot" aria-hidden="true"></span>
                        <span class="nx-island-title">{{ $say('Sara', 'سارا') }}</span>
                        <span class="nx-island-wave" aria-hidden="true">@for ($i = 0; $i < 4; $i++)<i style="--nx-i: {{ $i }}"></i>@endfor</span>
                    </button>
                </x-nx::island-view>

                <x-nx::island-view name="delivery" size="expanded">
                    <div class="nx-island-row">
                        <span class="nx-island-avatar" aria-hidden="true" style="background: var(--nx-gradient-gold)">{{ $icon('home') }}</span>
                        <div class="nx-island-stack">
                            <span class="nx-island-title">{{ $say('Your order is 2 stops away', 'سفارش شما ۲ ایستگاه فاصله دارد') }}</span>
                            <span class="nx-island-sub">{{ $say('Courier Marco · arriving 14:20', 'پیک: رضا · رسیدن ۱۴:۲۰') }}</span>
                        </div>
                    </div>
                    <div class="nx-island-actions">
                        <button type="button" class="nx-island-btn" style="flex: 1" x-on:click="show('idle')">{{ $say('OK', 'باشه') }}</button>
                    </div>
                </x-nx::island-view>
            </x-nx::dynamic-island>
        </div>

        <div class="pg-row" style="justify-content: center">
            <x-nx::button variant="secondary" icon="play" x-on:click="startTimer()">{{ $say('Start a 5-min timer', 'تایمر ۵ دقیقه‌ای') }}</x-nx::button>
            <x-nx::button variant="secondary" icon="user" x-on:click="$dispatch('nx-island-show', 'call')">{{ $say('Incoming call', 'تماس ورودی') }}</x-nx::button>
            <x-nx::button variant="secondary" icon="bell" x-on:click="$dispatch('nx-island-show', 'delivery')">{{ $say('Delivery update', 'خبر ارسال') }}</x-nx::button>
            <x-nx::button variant="ghost" x-on:click="$dispatch('nx-island-show', 'idle')">{{ $say('Back to idle', 'بازگشت به حالت آرام') }}</x-nx::button>
        </div>
    </div>
</section>
