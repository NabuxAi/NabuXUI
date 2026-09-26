{{--
    <x-nx::registration-card model="registration" wire:submit="register" :success="$registered" currency="EUR"
        :event="['title' => 'Nabu Summit 2026', 'date' => '12 Oct 2026', 'location' => 'Lisboa', 'badge' => 'Hybrid']"
        :tickets="[
            ['id' => 'general', 'label' => 'General', 'price' => 49, 'description' => 'Talks and workshops'],
            ['id' => 'student', 'label' => 'Student', 'price' => 0, 'max' => 1],
        ]" />

    A native <form> in three steps (details → tickets → confirm). Steps before the
    last never submit; on the last one the form submits as usual (wire:submit, or
    a plain POST to `action`). Field errors come from the server's $errors — the
    card walks back to the first step that has one. Set `success` once the
    registration went through: the check draws itself and confetti flies.
    With `model`, the fields bind to model.name, model.email and model.tickets.{id}.
--}}
@props(['event' => [], 'tickets' => [], 'currency' => 'USD', 'locale' => null, 'model' => null, 'success' => false, 'values' => []])
@php
    use NabuXUI\NabuXUI;
    $id = NabuXUI::id('nx-reg');
    $lang = substr($locale ?? app()->getLocale(), 0, 2);
    $t = fn (string $en, string $fa, string $ar) => match ($lang) { 'fa' => $fa, 'ar' => $ar, default => $en };
    $steps = [$t('Details', 'مشخصات', 'البيانات'), $t('Tickets', 'بلیت‌ها', 'التذاكر'), $t('Confirm', 'تأیید', 'تأكيد')];
    $digits = NabuXUI::digits($locale);
    $tickets = array_values(array_map(fn ($ticket) => ['id' => (string) $ticket['id'], 'label' => $ticket['label'], 'price' => (float) ($ticket['price'] ?? 0), 'max' => (int) ($ticket['max'] ?? 10), 'description' => $ticket['description'] ?? null], $tickets));
    $quantities = collect($tickets)->mapWithKeys(fn ($ticket) => [$ticket['id'] => (int) ($values['tickets'][$ticket['id']] ?? 0)])->all();
    $total = collect($tickets)->sum(fn ($ticket) => $ticket['price'] * $quantities[$ticket['id']]);
    $free = $t('Free', 'رایگان', 'مجاني');
    $money = fn (float $amount) => $amount == 0 ? $free : (class_exists(\NumberFormatter::class) ? (new \NumberFormatter($locale ?? app()->getLocale(), \NumberFormatter::CURRENCY))->formatCurrency($amount, $currency) : $currency.' '.NabuXUI::formatNumber($amount, 2, $locale));
    $field = fn (string $name) => $model ? "{$model}.{$name}" : $name;
    $nameError = NabuXUI::error($field('name'));
    $emailError = NabuXUI::error($field('email'));
    $submitTarget = null;
    foreach ($attributes->getAttributes() as $attr => $handler) {
        if (str_starts_with($attr, 'wire:submit')) {
            $submitTarget = preg_replace('/\(.*$/s', '', (string) $handler) ?: null;
        }
    }
    $glyph = fn (string $d) => new \Illuminate\Support\HtmlString('<svg class="nx-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="'.$d.'"/></svg>');
    $calendar = 'M5 6.5A1.5 1.5 0 0 1 6.5 5h11A1.5 1.5 0 0 1 19 6.5v11a1.5 1.5 0 0 1-1.5 1.5h-11A1.5 1.5 0 0 1 5 17.5zM5 10h14M9 3v4M15 3v4';
    $pin = 'M12 21s-6.5-5.6-6.5-11a6.5 6.5 0 0 1 13 0c0 5.4-6.5 11-6.5 11zM12 12.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z';
@endphp
<form {{ $attributes->class('nx-registration-card')->merge(['novalidate' => true, 'aria-labelledby' => "{$id}-title", 'data-success' => $success ? 'true' : 'false', 'data-step' => 0]) }}
    x-data="nxRegistrationCard(@js($tickets), @js($quantities), @js($currency), @js($locale), {{ count($steps) }})"
    x-on:submit.capture="submit($event)" x-bind:data-step="step" x-bind:data-done="done ? '' : null">
    <header class="nx-registration-card-head">
        <div class="nx-registration-card-eyebrow">
            @if (! empty($event['badge']))<span class="nx-badge" data-tone="gold">{{ $event['badge'] }}</span>@endif
            @if (! empty($event['date']))<span>{{ $glyph($calendar) }}{{ $event['date'] }}</span>@endif
            @if (! empty($event['location']))<span>{{ $glyph($pin) }}{{ $event['location'] }}</span>@endif
        </div>
        <h2 class="nx-registration-card-title" id="{{ $id }}-title">{{ $event['title'] ?? '' }}</h2>
    </header>
    <ol class="nx-registration-card-steps" style="--_count: {{ count($steps) }}; --_progress: 0" x-bind:style="{ '--_progress': Math.min(step, {{ count($steps) - 1 }}) / {{ count($steps) - 1 }} }">
        @foreach ($steps as $i => $stepTitle)
            <li class="nx-registration-card-stepmark" data-status="{{ $i === 0 ? 'current' : 'upcoming' }}" x-bind:data-status="status({{ $i }})" x-bind:aria-current="status({{ $i }}) === 'current' ? 'step' : null">
                <span class="nx-registration-card-marker" aria-hidden="true"><span>{{ $digits[$i + 1] ?? $i + 1 }}</span>{{ NabuXUI::icon('check') }}</span>
                <span>{{ $stepTitle }}</span>
            </li>
        @endforeach
    </ol>
    <p class="nx-visually-hidden" aria-live="polite"
        x-text="done ? @js($t('You’re in!', 'ثبت‌نام شد!', 'تم تسجيلك!')) : @js($t('Step :step of :total', 'مرحلهٔ :step از :total', 'الخطوة :step من :total')).replace(':step', step + 1).replace(':total', {{ count($steps) }}) + ': ' + @js($steps)[step]"></p>
    <div class="nx-registration-card-viewport" x-ref="viewport" wire:ignore.self>
        <div class="nx-registration-card-measure" x-ref="measure">
            <fieldset class="nx-registration-card-step" data-step="0" data-pos="current" x-bind:data-pos="pos(0)" x-bind:inert="step !== 0">
                <legend class="nx-registration-card-legend">{{ $steps[0] }}</legend>
                <div class="nx-field" @if ($nameError) data-invalid @endif>
                    <label class="nx-label" for="{{ $id }}-name">{{ $t('Full name', 'نام و نام خانوادگی', 'الاسم الكامل') }}<span class="nx-label-required" aria-hidden="true">*</span></label>
                    <input class="nx-input" id="{{ $id }}-name" name="name" data-field="name" autocomplete="name" required value="{{ $values['name'] ?? '' }}"
                        @if ($model) wire:model="{{ $model }}.name" @endif @if ($nameError) aria-invalid="true" aria-describedby="{{ $id }}-name-error" @endif>
                    @error($field('name'))<p class="nx-error" id="{{ $id }}-name-error">{{ NabuXUI::icon('alert-circle') }}{{ $message }}</p>@enderror
                </div>
                <div class="nx-field" @if ($emailError) data-invalid @endif>
                    <label class="nx-label" for="{{ $id }}-email">{{ $t('Email', 'ایمیل', 'البريد الإلكتروني') }}<span class="nx-label-required" aria-hidden="true">*</span></label>
                    <input class="nx-input" id="{{ $id }}-email" type="email" name="email" data-field="email" dir="ltr" autocomplete="email" required value="{{ $values['email'] ?? '' }}"
                        @if ($model) wire:model="{{ $model }}.email" @endif @if ($emailError) aria-invalid="true" aria-describedby="{{ $id }}-email-error" @endif>
                    @error($field('email'))<p class="nx-error" id="{{ $id }}-email-error">{{ NabuXUI::icon('alert-circle') }}{{ $message }}</p>@enderror
                </div>
            </fieldset>
            <fieldset class="nx-registration-card-step" data-step="1" data-pos="after" x-bind:data-pos="pos(1)" x-bind:inert="step !== 1" inert>
                <legend class="nx-registration-card-legend">{{ $steps[1] }}</legend>
                <ul class="nx-registration-card-tickets">
                    @foreach ($tickets as $ticket)
                        @php $qty = $quantities[$ticket['id']]; @endphp
                        <li class="nx-registration-card-ticket" @if ($qty) data-picked @endif x-bind:data-picked="(quantities[@js($ticket['id'])] ?? 0) ? '' : null">
                            <span class="nx-registration-card-ticket-text">
                                <span class="nx-registration-card-ticket-name">{{ $ticket['label'] }}</span>
                                @if ($ticket['description'])<span class="nx-registration-card-ticket-note">{{ $ticket['description'] }}</span>@endif
                                <span class="nx-registration-card-price">{{ $money($ticket['price']) }}</span>
                            </span>
                            <span class="nx-registration-card-stepper">
                                <button type="button" class="nx-registration-card-stepbtn" aria-label="{{ str_replace(':name', $ticket['label'], $t('Remove one :name', 'کم کردن یک :name', 'إزالة :name واحدة')) }}"
                                    @disabled($qty === 0) x-bind:disabled="! (quantities[@js($ticket['id'])] ?? 0)" x-on:click="qty(@js($ticket['id']), -1)">{{ NabuXUI::icon('minus') }}</button>
                                <output class="nx-registration-card-qty" aria-live="polite" aria-label="{{ $ticket['label'] }}"><x-nx::number data-qty="{{ $ticket['id'] }}" :value="$qty" :locale="$locale" :reveal="false" wire:ignore /></output>
                                <button type="button" class="nx-registration-card-stepbtn" aria-label="{{ str_replace(':name', $ticket['label'], $t('Add one :name', 'افزودن یک :name', 'إضافة :name واحدة')) }}"
                                    @disabled($qty >= $ticket['max']) x-bind:disabled="(quantities[@js($ticket['id'])] ?? 0) >= {{ $ticket['max'] }}" x-on:click="qty(@js($ticket['id']), 1)">{{ NabuXUI::icon('plus') }}</button>
                            </span>
                            <input type="hidden" name="tickets[{{ $ticket['id'] }}]" data-ticket="{{ $ticket['id'] }}" value="{{ $qty }}" x-bind:value="quantities[@js($ticket['id'])] ?? 0"
                                @if ($model) wire:model="{{ $model }}.tickets.{{ $ticket['id'] }}" @endif>
                        </li>
                    @endforeach
                </ul>
                <p class="nx-error" role="alert" x-show="needTicket" style="display: none">{{ NabuXUI::icon('alert-circle') }}{{ $t('Choose at least one ticket', 'دست‌کم یک بلیت انتخاب کنید', 'اختر تذكرة واحدة على الأقل') }}</p>
                @error($field('tickets'))<p class="nx-error" role="alert">{{ NabuXUI::icon('alert-circle') }}{{ $message }}</p>@enderror
            </fieldset>
            <fieldset class="nx-registration-card-step" data-step="2" data-pos="after" x-bind:data-pos="pos(2)" x-bind:inert="step !== 2" inert>
                <legend class="nx-registration-card-legend">{{ $steps[2] }}</legend>
                <dl class="nx-registration-card-summary">
                    <div><dt>{{ $t('Full name', 'نام و نام خانوادگی', 'الاسم الكامل') }}</dt><dd x-text="name"></dd></div>
                    <div><dt>{{ $t('Email', 'ایمیل', 'البريد الإلكتروني') }}</dt><dd dir="ltr" x-text="email"></dd></div>
                    <template x-for="ticket in picked" :key="ticket.id">
                        <div><dt x-text="number(ticket.qty) + ' × ' + ticket.label"></dt><dd x-text="money(ticket.qty * ticket.price, @js($free))"></dd></div>
                    </template>
                    <div class="nx-registration-card-total">
                        <dt>{{ $t('Total', 'جمع', 'الإجمالي') }}</dt>
                        <dd><x-nx::number x-ref="total" :value="$total" :decimals="2" :locale="$locale" :reveal="false" wire:ignore /></dd>
                    </div>
                </dl>
            </fieldset>
            <div class="nx-registration-card-success" data-pos="after" x-bind:data-pos="done ? 'current' : 'after'">
                <span class="nx-registration-card-check" x-ref="check" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 12.5l4.5 4.5L19 7.5" pathLength="1"/></svg></span>
                <h3 class="nx-registration-card-success-title" tabindex="-1">{{ $t('You’re in!', 'ثبت‌نام شد!', 'تم تسجيلك!') }}</h3>
                <p x-text="@js($t('Your tickets are on their way to :email.', 'بلیت‌ها به :email فرستاده شد.', 'أُرسلت تذاكرك إلى :email.')).replace(':email', email)"></p>
            </div>
        </div>
    </div>
    <footer class="nx-registration-card-foot">
        <x-nx::button variant="ghost" icon="arrow-left" x-show="step > 0 && ! done" style="display: none" x-on:click="back()">{{ __('nabuxui::ui.back') }}</x-nx::button>
        <x-nx::button type="submit" variant="primary" icon-end="arrow-right" :target="$submitTarget">
            <span x-show="step < {{ count($steps) - 1 }}">{{ $t('Continue', 'ادامه', 'متابعة') }}</span><span x-show="step >= {{ count($steps) - 1 }}" style="display: none">{{ $t('Register', 'ثبت‌نام', 'تسجيل') }}</span>
        </x-nx::button>
    </footer>
</form>
