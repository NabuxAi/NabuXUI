{{--
    The slider as it lives in real products: an ad-budget cap that re-prices
    the month as you drag, a podcast speed dial with decimals and keyboard
    steps, and a thermostat panel where the value bubble only visits on hover.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $budget = (int) ($state['budget'] ?? 350);
    $monthly = ($budget * 30) / 1000;

    $speed = (float) ($state['speed'] ?? 1);
    $remaining = (int) round(42 / max($speed, 0.1));

    $temp = (float) ($state['temp'] ?? 22.5);
    $fan = (int) ($state['fan'] ?? 2);
    $fanWord = [1 => $say('low', 'آرام'), 2 => $say('medium', 'متوسط'), 3 => $say('high', 'تند')][$fan] ?? $say('medium', 'متوسط');
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The ad budget that re-prices the month', 'سقف تبلیغاتی که ماه را دوباره قیمت می‌زند') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The daily cap rides wire:model.live, so the thirty-day estimate rolls while you drag — and the value on the thumb stays put (show-value) with Persian digits straight from the locale.', 'سقف روزانه با wire:model.live وصل است، پس برآورد سی‌روزه همان‌جا که می‌کشید می‌غلتد — و عدد روی دسته با show-value سر جایش می‌ماند، با ارقام فارسیِ همان locale.') }}
        </p>
    </div>
    <x-nx::slider :label="$say('Daily ad cap', 'سقف روزانهٔ تبلیغات')" show-value
        :start-label="$say('50k', '۵۰ هزار')" :end-label="$say('1000k', '۱۰۰۰ هزار')"
        min="50" max="1000" step="25" wire:model.live="state.budget" />
    <div class="pg-row" style="justify-content: space-between">
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('Daily cap:', 'سقف روزانه:') }}
            <strong style="color: var(--nx-text); font-variant-numeric: tabular-nums">{{ NabuXUI::formatNumber($budget).' '.$say('k tomans', 'هزار تومان') }}</strong>
            ·
            {{ $say('30-day estimate:', 'برآورد ۳۰ روزه:') }}
            <strong style="color: var(--nx-text); font-variant-numeric: tabular-nums">{{ NabuXUI::formatNumber($monthly, 1).' '.$say('M tomans', 'میلیون تومان') }}</strong>
        </p>
        <x-nx::button variant="primary" icon="check" wire:click="save('{{ $say('Ad cap saved', 'سقف تبلیغات ذخیره شد') }}')">{{ $say('Save cap', 'ذخیرهٔ سقف') }}</x-nx::button>
    </div>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A podcast speed dial with decimals', 'دیال سرعت پادکست، با رقم اعشار') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Steps of ۰٫۰۵ with two decimal places — the keyboard arrows nudge by exactly one step, and the episode’s remaining time is re-cut live. Native input type=range, so every shortcut you already know works.', 'پله‌های ۰٫۰۵ با دو رقم اعشار — فلش‌های کیبورد دقیقاً یک پله جابه‌جا می‌کنند و زمان باقی‌ماندهٔ قسمت همان‌جا بازچین می‌شود. ورودی بومی type=range است، پس همهٔ میانبرهای آشنا کار می‌کنند.') }}
        </p>
    </div>
    <x-nx::slider :label="$say('Playback speed', 'سرعت پخش')" show-value :decimals="2"
        :start-label="$say('0.5× calm', '۰٫۵× آرام')" :end-label="$say('2.5× fast', '۲٫۵× تند')"
        min="0.5" max="2.5" step="0.05" wire:model.live="state.speed" />
    <p style="margin: 0; color: var(--nx-text-muted)">
        {{ $say('Speed', 'سرعت') }}
        <strong style="color: var(--nx-text); font-variant-numeric: tabular-nums">{{ NabuXUI::formatNumber($speed, 2) }}×</strong>
        ·
        {{ $say('a 42-minute episode finishes in', 'قسمت ۴۲‌دقیقه‌ای در') }}
        <strong style="color: var(--nx-text); font-variant-numeric: tabular-nums">{{ NabuXUI::formatNumber($remaining).' '.$say('minutes', 'دقیقه') }}</strong>
        {{ $say('listens through.', 'تمام می‌شود.') }}
    </p>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The thermostat panel', 'پنل ترموستات') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Two dials of one device: the target temperature in half-degree steps and the fan in three gears. No show-value here — the bubble only visits while you hover or focus, then gets out of the way; the live summary below carries the numbers for screen readers too.', 'دو دیال یک دستگاه: دمای هدف با پله‌های نیم‌درجه و فن با سه دنده. این‌جا show-value نیست — حباب عدد فقط هنگام هاور یا فوکوس می‌آید و بعد کنار می‌رود؛ خلاصهٔ زندهٔ پایین، اعداد را برای صفحه‌خوان‌ها هم با خود دارد.') }}
        </p>
    </div>
    <div class="pg-grid">
        <div style="display: grid; gap: .5rem">
            <div class="pg-row" style="justify-content: space-between">
                <strong style="font-weight: 600">{{ $say('Target temperature', 'دمای هدف') }}</strong>
                <span style="font-variant-numeric: tabular-nums; color: var(--nx-text-muted)">{{ NabuXUI::formatNumber($temp, 1) }}°</span>
            </div>
            <x-nx::slider :label="$say('Target temperature', 'دمای هدف')" :decimals="1"
                :start-label="$say('16° cool', '۱۶° خنک')" :end-label="$say('30° warm', '۳۰° گرم')"
                min="16" max="30" step="0.5" wire:model.live="state.temp" />
        </div>
        <div style="display: grid; gap: .5rem">
            <div class="pg-row" style="justify-content: space-between">
                <strong style="font-weight: 600">{{ $say('Fan gear', 'دندهٔ فن') }}</strong>
                <span style="color: var(--nx-text-muted)">{{ $fanWord }}</span>
            </div>
            <x-nx::slider :label="$say('Fan gear', 'دندهٔ فن')"
                :start-label="$say('1 · quiet', '۱ · بی‌صدا')" :end-label="$say('3 · full', '۳ · تمام‌قد')"
                min="1" max="3" step="1" wire:model.live="state.fan" />
        </div>
    </div>
    <div class="pg-row" style="justify-content: space-between">
        <p style="margin: 0; color: var(--nx-text-muted)" aria-live="polite">
            {{ $say('Heating to', 'گرم می‌کند تا') }}
            <strong style="color: var(--nx-text); font-variant-numeric: tabular-nums">{{ NabuXUI::formatNumber($temp, 1) }}°</strong>
            ·
            {{ $say('fan', 'فن') }}
            <strong style="color: var(--nx-text)">{{ $fanWord }}</strong>
        </p>
        <x-nx::button variant="secondary" wire:click="ping('{{ $say('Thermostat updated', 'ترموستات به‌روز شد') }}')">{{ $say('Apply', 'اعمال') }}</x-nx::button>
    </div>
</section>
