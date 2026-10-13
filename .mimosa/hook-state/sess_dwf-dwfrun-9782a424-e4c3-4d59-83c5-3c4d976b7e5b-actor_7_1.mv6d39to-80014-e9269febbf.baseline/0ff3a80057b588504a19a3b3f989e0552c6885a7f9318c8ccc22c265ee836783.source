{{--
    The precision slider's real scenarios: an agent configuration panel
    (temperature, budget) plus a timeout slider with a ticked scale.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<div class="pg-grid">
    <section class="pg-box" style="gap: 1.25rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Tuning the agent', 'تنظیم ایجنت') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('The headline rolls to every new value; temperature goes live to the server, the budget posts with the form.', 'عدد تیتر با هر مقدار تازه می‌غلتد؛ دما زنده به سرور می‌رود و بودجه با فرم پست می‌شود.') }}
            </p>
        </div>
        <x-nx::precision-slider :label="$say('Creativity (temperature)', 'خلاقیت (دما)')" min="0" max="2" step="0.01" value="0.7" decimals="2" wire:model.live="state.temperature" />
        <x-nx::precision-slider :label="$say('Monthly budget', 'بودجهٔ ماهانه')" prefix="$" min="0" max="5000" step="50" value="1200" :ticks="250" :major-every="4" wire:model.live="state.budget" />
        <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
            {{ $say('On the server:', 'روی سرور:') }}
            {{ $say('temperature', 'دما') }} <code>{{ $state['temperature'] ?? '—' }}</code> ·
            {{ $say('budget', 'بودجه') }} <code>{{ $state['budget'] ?? '—' }}</code>
        </p>
        <x-nx::button size="sm" variant="secondary" icon="check" wire:click="save(@js($say('Agent configuration saved', 'پیکربندی ایجنت ذخیره شد')))">{{ $say('Save configuration', 'ذخیرهٔ پیکربندی') }}</x-nx::button>
    </section>

    <section class="pg-box" style="gap: 1.25rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A scale worth reading', 'مقیاسی که می‌ارزد خواندن') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('ticks and major-every shape the ruler; unit rides beside the number. This one sets how long an agent may think before handing over.', 'ticks و major-every خط‌کش را می‌سازند؛ unit کنار عدد می‌نشیند. این یکی تعیین می‌کند ایجنت پیش از واگذار چقدر حق دارد فکر کند.') }}
            </p>
        </div>
        <x-nx::precision-slider :label="$say('Thinking time before handoff', 'زمان فکر پیش از واگذار')" min="5" max="60" step="1" value="20" unit="s" :ticks="5" :major-every="3" wire:model.live="state.patience" />
        <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
            {{ $say('Patience on the server:', 'صبر روی سرور:') }} <code>{{ $state['patience'] ?? '—' }}</code>
        </p>
    </section>
</div>
