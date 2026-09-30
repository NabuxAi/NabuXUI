{{--
    The pit slider's real scenarios: a product's sound settings bound to live
    Livewire properties, and a light-following variant on a second panel.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<div class="pg-grid">
    <section class="pg-box" style="gap: 1.25rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Sound settings', 'تنظیمات صدا') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('Drag or nudge with the keys: the sticks under the thumb sink into the pit and the value floats above it. Both sliders write straight to the server.', 'بکشید یا با کلیدها ریز کنید: میله‌های زیر thumb در گودال فرو می‌روند و مقدار بالایش شناور می‌ماند. هر دو اسلایدر مستقیم روی سرور می‌نویسند.') }}
            </p>
        </div>
        <x-nx::pit-slider :label="$say('Effects volume', 'صدای جلوه‌ها')" min="0" max="100" value="42" suffix="%" wire:model.live="state.volume" />
        <x-nx::pit-slider :label="$say('Music volume', 'صدای موسیقی')" min="0" max="100" value="68" suffix="%" :bars="48" wire:model.live="state.music" />
        <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
            {{ $say('On the server (wire:model.live):', 'روی سرور (wire:model.live):') }}
            {{ $say('effects', 'جلوه‌ها') }} <code>{{ $state['volume'] ?? '—' }}</code> ·
            {{ $say('music', 'موسیقی') }} <code>{{ $state['music'] ?? '—' }}</code>
        </p>
        <x-nx::button size="sm" variant="secondary" icon="check" wire:click="save(@js($say('Sound profile saved', 'پروفایل صدا ذخیره شد')))">{{ $say('Save sound profile', 'ذخیرهٔ پروفایل صدا') }}</x-nx::button>
    </section>

    <section class="pg-box" style="gap: 1.25rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A slider that follows the page', 'اسلایدری که از صفحه پیروی می‌کند') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say(':dark="false" drops the always-dark panel and lets the slider live on the surface — this one trims a reading lamp’s warmth, decimals and all.', ':dark="false" پنل همیشه‌تیره را کنار می‌گذارد و اسلایدر روی سطح صفحه می‌نشیند — این‌جا گرمای نور یک چراغ مطالعه را با اعشار تنظیم می‌کند.') }}
            </p>
        </div>
        <x-nx::pit-slider :label="$say('Reading lamp warmth', 'گرمای چراغ مطالعه')" :dark="false" min="2200" max="6500" step="50" value="3400" :decimals="0" suffix="K" wire:model.live="state.warmth" />
        <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
            {{ $say('Warmth on the server:', 'گرمای نور روی سرور:') }} <code>{{ $state['warmth'] ?? '—' }}</code>
        </p>
    </section>
</div>
