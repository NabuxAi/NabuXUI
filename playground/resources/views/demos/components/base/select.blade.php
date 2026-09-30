{{--
    The select in real flows: a checkout address where the city list follows
    the country, and a ticket bar with an assignee and a priority that carries
    an error.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $country = (string) ($state['country'] ?? 'ir');
    $cities = [
        'ir' => ['tehran' => $say('Tehran', 'تهران'), 'isfahan' => $say('Isfahan', 'اصفهان'), 'shiraz' => $say('Shiraz', 'شیراز')],
        'de' => ['berlin' => 'Berlin', 'munich' => 'Munich', 'cologne' => 'Cologne'],
        'jp' => ['tokyo' => 'Tokyo', 'osaka' => 'Osaka', 'kyoto' => 'Kyoto'],
    ];
    $countryNames = ['ir' => $say('Iran', 'ایران'), 'de' => $say('Germany', 'آلمان'), 'jp' => $say('Japan', 'ژاپن')];

    $priority = (string) ($state['priority'] ?? '');
    $agents = [
        'maryam' => 'مریم صادقی', 'saman' => 'سامان دهقان', 'linh' => 'Nguyen Linh',
    ];
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Checkout address — the city follows the country', 'آدرس پرداخت — شهر دنبال کشور می‌آید') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Switch the country and the city list swaps with it; the placeholder keeps the choice honest until one is picked.', 'کشور را عوض کنید تا فهرست شهرها با آن عوض شود؛ جای‌نگه‌دار انتخاب را تا زمان انتخاب واقعی صادق نگه می‌دارد.') }}
        </p>
    </div>
    <div class="pg-grid">
        <x-nx::select label="{{ $say('Country', 'کشور') }}" placeholder="{{ $say('Choose a country', 'یک کشور انتخاب کنید') }}" :options="$countryNames" wire:model.live="state.country" />
        <x-nx::select label="{{ $say('City', 'شهر') }}" :placeholder="$say('Choose a city', 'یک شهر انتخاب کنید')" :options="$cities[$country] ?? []" wire:model="state.city" />
        <x-nx::input label="{{ $say('Postal code', 'کد پستی') }}" inputmode="numeric" :hint="$say('Digits only — 10 in Iran.', 'فقط رقم — در ایران ۱۰ رقم.')" wire:model.blur="state.postal" />
    </div>
    <x-nx::button variant="primary" icon="check" wire:click="save('{{ $say('Shipping saved', 'آدرس ارسال ذخیره شد') }}')">{{ $say('Continue to shipping', 'ادامه به ارسال') }}</x-nx::button>
</section>

<section class="pg-box" style="gap: 1rem">
    <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A ticket bar, with a priority that must be chosen', 'نوار تیکت، با اولولیتی که باید انتخاب شود') }}</h3>
    <p style="margin: 0; color: var(--nx-text-muted)">
        {{ $say('The assignee is optional; the priority is not — leaving it empty shows the field error, and one option can be disabled for off-hours.', 'مسئول اختیاری است؛ اولولیت نه — خالی ماندنش خطای فیلد را نشان می‌دهد و یک گزینه هم برای خارج از ساعت کاری ازکار افتاده است.') }}
    </p>
    <div class="pg-grid">
        <x-nx::select label="{{ $say('Assignee', 'مسئول') }}" size="sm" :options="$agents" wire:model="state.agent" />
        <x-nx::select label="{{ $say('Priority', 'اولولیت') }}" size="sm" :placeholder="$say('Pick one', 'انتخاب کنید')" :options="[
            ['value' => 'low', 'label' => $say('Low', 'کم')],
            ['value' => 'normal', 'label' => $say('Normal', 'معمولی')],
            ['value' => 'high', 'label' => $say('High', 'زیاد')],
            ['value' => 'urgent', 'label' => $say('Urgent (support offline)', 'فوری (پشتیبانی آفلاین)'), 'disabled' => true],
        ]" :error="$priority === '' ? $say('A ticket without a priority is invisible to the queue.', 'تیکت بدون اولولیت برای صف دیده نمی‌شود.') : null" wire:model.live="state.priority" />
    </div>
</section>
