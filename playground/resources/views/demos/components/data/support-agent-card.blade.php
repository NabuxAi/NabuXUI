{{--
    The support agent card's real scenarios: the wall of on-shift operators —
    one taking tickets through a live slot action, one link-only, one away
    and running out of hours.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The shift wall', 'دیوار شیفت') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Bars fill in turn as each card scrolls into view. Amara takes tickets through a live button (watch the spinner); Kenji’s card is a plain link; Layla is away and her quota is nearly spent.', 'نوارها با رسیدن هر کارت به دید، به‌نوبت پر می‌شوند. آمارا با یک دکمهٔ زنده تیکت می‌گیرد (اسپینر را ببینید)؛ کارت کنجی فقط لینک است؛ لیلا غایب است و سهمیه‌اش تقریباً تمام.') }}
        </p>
    </div>
    <div class="pg-grid" style="grid-template-columns: repeat(auto-fit, minmax(min(100%, 19rem), 1fr))">
        <x-nx::support-agent-card name="آمارا اوکافور" :role="$say('Billing · Lagos', 'مالی · لاگوس')" status="online"
            :metrics="[
                ['label' => $say('Tickets resolved', 'تیکت حل‌شده'), 'value' => 342, 'max' => 400, 'display' => $fa ? '۳۴۲ / ۴۰۰' : '342 / 400'],
                ['label' => 'CSAT', 'value' => 96, 'display' => $fa ? '۹۶٪' : '96%', 'tone' => 'success'],
                ['label' => $say('First response', 'نخستین پاسخ'), 'value' => 72, 'display' => $fa ? '۱د ۴۲ث' : '1m 42s', 'tone' => 'info'],
            ]"
            :trend="[12, 18, 14, 22, 26, 24, 31, 29, 35, 33, 41, 44]"
            :trend-label="$say('Resolved, 12 weeks', 'حل‌شده، ۱۲ هفته')" trend-value="{{ $fa ? '۳۲۹' : '329' }}">
            <x-slot:actions>
                <x-nx::button variant="primary" block icon="arrow-right" wire:click="save(@js($say('Ticket #4821 assigned to Amara', 'تیکت ۴۸۲۱ به آمارا سپرده شد')))">
                    {{ $say('Assign ticket #4821', 'سپردن تیکت ۴۸۲۱') }}
                </x-nx::button>
            </x-slot:actions>
        </x-nx::support-agent-card>

        <x-nx::support-agent-card name="Kenji Sato" :role="$say('Onboarding · Tokyo', 'رهاسازی · توکیو')" status="online"
            :metrics="[
                ['label' => $say('Tickets resolved', 'تیکت حل‌شده'), 'value' => 214, 'max' => 400, 'display' => $fa ? '۲۱۴ / ۴۰۰' : '214 / 400'],
                ['label' => 'CSAT', 'value' => 91, 'display' => $fa ? '۹۱٪' : '91%', 'tone' => 'success'],
                ['label' => $say('First response', 'نخستین پاسخ'), 'value' => 48, 'display' => $fa ? '۳د ۰۵ث' : '3m 05s', 'tone' => 'warning'],
            ]"
            :trend="[30, 28, 31, 27, 25, 26, 22, 24, 21, 23, 20, 19]"
            :trend-label="$say('Resolved, 12 weeks', 'حل‌شده، ۱۲ هفته')" trend-value="{{ $fa ? '۲۹۶' : '296' }}"
            :action="['label' => $say('Message Kenji', 'پیام به کنجی'), 'icon' => 'message', 'href' => '#']" />

        <x-nx::support-agent-card name="لیلا حداد" :role="$say('Escalations · Dubai', 'تشدیدها · دبی')" status="away"
            :metrics="[
                ['label' => $say('Tickets resolved', 'تیکت حل‌شده'), 'value' => 388, 'max' => 400, 'display' => $fa ? '۳۸۸ / ۴۰۰' : '388 / 400', 'tone' => 'warning'],
                ['label' => 'CSAT', 'value' => 89, 'display' => $fa ? '۸۹٪' : '89%', 'tone' => 'success'],
            ]"
            :trend="[9, 14, 11, 18, 24, 21, 26, 31, 28, 34, 37, 38]"
            :trend-label="$say('Resolved, 12 weeks', 'حل‌شده، ۱۲ هفته')" trend-value="{{ $fa ? '۲۶۱' : '261' }}" />
    </div>
</section>
