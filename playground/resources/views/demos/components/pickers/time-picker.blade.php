{{--
    Time pickers in context: a food delivery's "schedule for later" (minutes
    in fives, bound to the server, the slot echoed back) and a clinic's
    reminder on a 12-hour face. Scroll a drum, drag it on touch, or focus it
    and use the arrow keys.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $slot = $state['slot'] ?? '19:30';
    $digits = \NabuXUI\NabuXUI::digits();
    $slotText = strtr($slot, array_combine(range(0, 9), $digits));
@endphp
<style>
    .tp-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(17rem, 1fr)); gap: 1rem; }
    .tp-card { display: grid; gap: .875rem; align-content: start; justify-items: start; padding: 1.25rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-xl); background: var(--nx-surface); }
    .tp-card h3 { margin: 0; }
    .tp-card p { margin: 0; color: var(--nx-text-muted); font-size: var(--nx-text-sm); }
</style>

<div class="tp-grid">
    <section class="tp-card" aria-labelledby="tp-food-title">
        <h3 class="pg-title" id="tp-food-title">{{ $say('Schedule delivery', 'زمان‌بندی ارسال') }}</h3>
        <p>{{ $say('Kabab Bonab · arrives within 15 minutes of the slot', 'کباب بناب · تا ۱۵ دقیقه پس از زمان انتخابی می‌رسد') }}</p>
        <x-nx::time-picker name="slot" :label="$say('Delivery time', 'ساعت تحویل')" :value="$slot" :minute-step="5" wire:model.live="state.slot" />
        <x-nx::button variant="primary" icon="check" wire:click="save(@js($say('Delivery scheduled', 'ارسال زمان‌بندی شد')))">
            {{ $say('Deliver at', 'تحویل در') }} {{ $slotText }}
        </x-nx::button>
    </section>

    <section class="tp-card" aria-labelledby="tp-clinic-title">
        <h3 class="pg-title" id="tp-clinic-title">{{ $say('Medication reminder', 'یادآور دارو') }}</h3>
        <p>{{ $say('A 12-hour face with quarter-hour stops; the value is still 24-hour “HH:MM”.', 'صفحهٔ ۱۲ ساعته با گام ربع‌ساعت؛ مقدار همچنان «HH:MM» ۲۴ ساعته است.') }}</p>
        <x-nx::time-picker :label="$say('Reminder time', 'ساعت یادآوری')" value="08:15" :hour-cycle="12" :minute-step="15"
            x-on:nx-change="$wire.ping(@js($say('Reminder set for ', 'یادآور تنظیم شد برای ')) + $event.detail)" />
    </section>
</div>
