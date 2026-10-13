{{--
    The field frame's real scenarios: the controls the packaged inputs don't
    cover — a brand color picker and a support-hours time input in the store's
    brand settings, a password box with a Livewire-backed reveal toggle that
    carries a live error, and a composite daily budget cap with a currency
    addon and a locale-formatted monthly estimate.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $n = fn (float|int $value) => NabuXUI::formatNumber($value, 0, $fa ? 'fa' : 'en');

    // Scenario 1 — store brand settings.
    $brandColor = (string) ($state['brandColor'] ?? '#5647e6');
    $openHour = (string) ($state['openHour'] ?? '09:00');
    $faTime = fn (string $time) => strtr($time, array_combine(range(0, 9), NabuXUI::digits($fa ? 'fa' : 'en')));

    // Scenario 2 — a password with a reveal toggle and a live error.
    $password = (string) ($state['password'] ?? '');
    $pwLen = mb_strlen($password);
    $pwShown = (bool) ($state['pwShown'] ?? false);
    $pwError = $pwLen > 0 && $pwLen < 8
        ? ($fa ? 'رمز باید دست‌کم ۸ نویسه باشد — الان '.$n($pwLen).' نویسه است.' : 'The password needs at least 8 characters — only '.$n($pwLen).' so far.')
        : null;
    $pwDescribed = 'demo-password-hint'.($pwError ? ' demo-password-error' : '');

    // Scenario 3 — a composite daily budget cap.
    $minCap = 50000;
    $capRaw = trim((string) ($state['budget'] ?? ''));
    $budget = (int) ($capRaw === '' ? 0 : $capRaw);
    $capSet = $capRaw !== '';
    $capError = $capSet && $budget < $minCap
        ? ($fa ? 'سقف باید دست‌کم '.$n($minCap).' تومان باشد.' : 'The cap must be at least '.number_format($minCap).' Toman.')
        : null;
    $capDescribed = 'demo-cap-hint'.($capError ? ' demo-cap-error' : '');
    $capHint = $fa
        ? 'کمینهٔ مجاز '.$n($minCap).' تومان است؛ با رسیدن به سقف، چرخش تبلیغ می‌ایستد.'
        : 'The minimum is '.number_format($minCap).' — delivery pauses when the cap is hit.';
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Store brand settings — the controls inputs don’t cover', 'تنظیمات برند فروشگاه — کنترل‌هایی که ورودی‌های آماده پوشش نمی‌دهند') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('A native color picker and a time input, each wrapped in the field frame: the label points at your control through for, the hint gets its id, and the star marks what’s required. The preview beneath updates live.', 'یک انتخاب‌گر رنگ بومی و یک ورودی زمان، هر دو داخل قاب فیلد: برچسب با for به کنترل شما وصل می‌شود، راهنما id خودش را می‌گیرد و ستارهٔ لازم‌بودن هم می‌نشیند. پیش‌نمایش پایین به‌صورت زنده به‌روز می‌شود.') }}
        </p>
    </div>
    <div class="pg-grid">
        <x-nx::field for="demo-brand-color" :label="$say('Brand color', 'رنگ برند')" :hint="$say('Check it against both themes before saving.', 'پیش از ذخیره در هر دو تم روشن و تیره بررسی‌اش کنید.')" required>
            <input type="color" id="demo-brand-color" class="nx-input" list="demo-brand-presets"
                aria-describedby="demo-brand-color-hint"
                style="padding: .375rem; block-size: 2.75rem; cursor: pointer"
                wire:model.live.debounce.200ms="state.brandColor" />
            <datalist id="demo-brand-presets">
                <option value="#5647e6"></option>
                <option value="#6d28d9"></option>
                <option value="#0aa0bb"></option>
                <option value="#f4a93c"></option>
            </datalist>
        </x-nx::field>
        <x-nx::field for="demo-open-hour" :label="$say('Support opens at', 'آغاز پاسخ‌گویی پشتیبانی')" :hint="$say('Visitors see this on the contact card.', 'بازدیدکنندگان این را روی کارت تماس می‌بینند.')">
            <input type="time" id="demo-open-hour" class="nx-input" dir="ltr"
                aria-describedby="demo-open-hour-hint" wire:model="state.openHour" />
        </x-nx::field>
    </div>
    <div class="pg-row" style="justify-content: space-between">
        <p class="pg-row" style="margin: 0; gap: .6rem; color: var(--nx-text-muted)" aria-live="polite">
            <span aria-hidden="true" style="inline-size: 1.75rem; block-size: 1.75rem; border-radius: var(--nx-radius-md); border: 1px solid var(--nx-border); box-shadow: var(--nx-shadow-xs); background: {{ $brandColor }}"></span>
            <code dir="ltr" style="font: 500 var(--nx-text-sm) var(--nx-font-mono); color: var(--nx-text)">{{ strtoupper($brandColor) }}</code>
            ·
            {{ $say('opens at', 'پاسخ‌گویی از') }} {{ $faTime($openHour) }}
        </p>
        <x-nx::button variant="primary" icon="check" wire:click="save('{{ $say('Brand saved', 'برند ذخیره شد') }}')">{{ $say('Save brand', 'ذخیرهٔ برند') }}</x-nx::button>
    </div>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A password with a reveal toggle and a live error', 'رمز عبور با دکمهٔ نمایش و خطای زنده') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Your own control inside an nx-input-group: a native password box plus a toggle button, state kept in the demo’s Livewire state. The error is computed as you type — the frame turns red, the message rolls in, and the wiring (aria-invalid, aria-describedby → hint and error ids) is exactly what the frame renders for you.', 'کنترل خودتان داخل nx-input-group: یک ورودی رمز بومی به‌علاوهٔ دکمهٔ نمایش/پنهان که حالتش در state همین دمو می‌ماند. خطا همزمان با تایپ حساب می‌شود — قاب سرخ می‌شود، پیام می‌آید و سیم‌کشی (aria-invalid و aria-describedby به id راهنما و خطا) همان چیزی است که قاب برایتان رندر می‌کند.') }}
        </p>
    </div>
    <x-nx::field for="demo-password" :label="$say('New password', 'رمز عبور تازه')" :hint="$say('At least 8 characters; mix letters and digits.', 'دست‌کم ۸ نویسه؛ حرف و رقم را قاطی کنید.')" required :error="$pwError">
        <div class="nx-input-group">
            <input class="nx-input" id="demo-password" type="{{ $pwShown ? 'text' : 'password' }}" dir="ltr"
                autocomplete="new-password" inputmode="text" placeholder="••••••••"
                aria-describedby="{{ $pwDescribed }}" aria-invalid="{{ $pwError ? 'true' : 'false' }}"
                wire:model.live.debounce.300ms="state.password" />
            <x-nx::button type="button" variant="ghost" size="sm"
                :aria-pressed="$pwShown ? 'true' : 'false'"
                wire:click="$toggle('state.pwShown')">
                {{ $pwShown ? $say('Hide', 'پنهان') : $say('Show', 'نمایش') }}
            </x-nx::button>
        </div>
        @if ($pwLen >= 8)
            <p class="pg-row" style="margin: 0; gap: .5rem">
                <x-nx::badge tone="success" dot>{{ $say('Ready to save', 'آمادهٔ ذخیره') }}</x-nx::badge>
            </p>
        @endif
    </x-nx::field>
    <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
        {{ $say('The toggle keeps its state through Livewire updates — no Alpine needed, just $toggle on the state array.', 'دکمهٔ نمایش حالتش را بین به‌روزرسانی‌های Livewire نگه می‌دارد — بدون Alpine، فقط $toggle روی همان آرایهٔ state.') }}
    </p>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A daily budget cap with a currency addon', 'سقف هزینهٔ روزانه با واحد چسبیده') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('A number box and its glued «تومان» addon as one composite control; the frame carries the label, the hint with the locale-formatted minimum, and the error when the cap is too low. The monthly estimate below reads the same number back in Persian digits.', 'یک ورودی عدد و برچسب چسبیدهٔ «تومان» به‌عنوان یک کنترل مرکب؛ قاب برچسب، راهنما با کمینهٔ مجاز به ارقام محلی و خطای سقفِ کم را حمل می‌کند. برآورد ماهانهٔ پایین همان عدد را با ارقام فارسی برمی‌گرداند.') }}
        </p>
    </div>
    <x-nx::field for="demo-cap" :label="$say('Daily spend cap', 'سقف هزینهٔ روزانه')" :hint="$capHint" :error="$capError">
        <div class="nx-input-group">
            <input class="nx-input" id="demo-cap" type="number" inputmode="numeric" dir="ltr"
                min="{{ $minCap }}" step="5000" placeholder="50000"
                aria-describedby="{{ $capDescribed }}" aria-invalid="{{ $capError ? 'true' : 'false' }}"
                wire:model.live.number.debounce.400ms="state.budget" />
            <span class="nx-input-addon">{{ $say('Toman', 'تومان') }}</span>
        </div>
    </x-nx::field>
    <div class="pg-row" style="justify-content: space-between">
        <p style="margin: 0; color: var(--nx-text-muted)" aria-live="polite">
            @if ($capSet && $capError === null)
                {{ $say('This month’s ceiling at that pace: ', 'بیشینهٔ هزینهٔ این ماه با همین سقف: ') }}<strong style="color: var(--nx-text)">{{ $n($budget * 30) }}</strong> {{ $say('Toman', 'تومان') }}
            @else
                {{ $say('Type a cap of '.$n($minCap).' or more to see the monthly ceiling.', 'سقفی مساوی یا بیش از '.$n($minCap).' وارد کنید تا بیشینهٔ ماه دیده شود.') }}
            @endif
        </p>
        <x-nx::button variant="primary" icon="check" :disabled="$capError !== null || ! $capSet"
            wire:click="save('{{ $say('Cap applied', 'سقف اعمال شد') }}')">
            {{ $say('Apply cap', 'اعمال سقف') }}
        </x-nx::button>
    </div>
</section>
