{{--
    The workspace shell's real scenarios: a support inbox where the current
    view rides a live Livewire property, with a header, a footer and a
    prompt of its own.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $view = $state['view'] ?? 'inbox';
    $viewNames = [
        'inbox' => $say('Inbox', 'صندوق ورودی'),
        'agents' => $say('Agents', 'ایجنت‌ها'),
        'knowledge' => $say('Knowledge', 'دانش'),
        'analytics' => $say('Analytics', 'تحلیل'),
        'settings' => $say('Settings', 'تنظیمات'),
    ];
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Nabu Desk, as a customer sees it', 'میز نابو، همان‌طور که مشتری می‌بیند') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Sidebar items without href are buttons that make themselves current — the id rides a live Livewire property, so the badge and the header answer from the server. The prompt slot holds a prompt of our own.', 'آیتم‌های بدون href دکمه‌هایی‌اند که خودشان را جاری می‌کنند — شناسه روی یک پراپرتی زندهٔ Livewire می‌رود پس نشان و هدر از سرور جواب می‌گیرند. اسلات پرامپت هم یک پرامپت خودمانی دارد.') }}
        </p>
    </div>
    <x-nx::workspace-shell
        brand="{{ $say('Nabu Desk', 'میز نابو') }}"
        :label="$say('Workspace', 'ورک‌اسپیس')"
        height="34rem"
        :active="$view"
        wire:model.live="state.view"
        :items="[
            ['id' => 'inbox', 'label' => $viewNames['inbox'], 'icon' => 'message', 'badge' => 12],
            ['id' => 'agents', 'label' => $viewNames['agents'], 'icon' => 'sparkles'],
            ['id' => 'knowledge', 'label' => $viewNames['knowledge'], 'icon' => 'layers'],
            ['id' => 'analytics', 'label' => $viewNames['analytics'], 'icon' => 'chart'],
            ['id' => 'settings', 'label' => $viewNames['settings'], 'icon' => 'sliders'],
        ]">
        <x-slot:header>
            <strong style="font-size: var(--nx-text-md)">{{ $viewNames[$view] ?? $viewNames['inbox'] }}</strong>
            <x-nx::badge tone="success" pulse>{{ $say('Live', 'زنده') }}</x-nx::badge>
            <span style="margin-inline-start: auto">
                <x-nx::avatar-group size="sm" :label="$say('On shift', 'در شیفت')" :people="[['name' => 'نیلوفر احمدی'], ['name' => 'Kenji Sato'], ['name' => 'María López'], ['name' => 'Layla Haddad']]" />
            </span>
        </x-slot:header>

        @if ($view === 'analytics')
            <div class="pg-box" style="padding: var(--nx-space-4); gap: var(--nx-space-2)">
                <span style="font-weight: 600">{{ $say('Median first reply', 'میانهٔ نخستین پاسخ') }}</span>
                <span style="font: 700 var(--nx-text-2xl) / 1 var(--nx-font-display)">{{ NabuXUI::formatNumber(142) }} {{ $say('seconds · 18% faster than last week', 'ثانیه · ۱۸٪ بهتر از هفتهٔ پیش') }}</span>
            </div>
        @else
            <div style="display: grid; gap: var(--nx-space-3)">
                @foreach ([
                    [$say('Olá! Has order #4821 shipped?', 'سلام! سفارش ۴۸۲۱ ارسال شد؟'), $say('Beatriz · WhatsApp', 'باتریس · واتساپ')],
                    [$say('How do I rotate my API key?', 'کلید API را چطور عوض کنم؟'), $say('Sam · Web', 'سم · وب')],
                    [$say('¿Tienen factura en euros?', 'فاکتور به یورو دارید؟'), $say('Carmen · Telegram', 'کارمن · تلگرام')],
                    [$say('返品の手続きを教えてください', 'رویهٔ مرجوع کردن را بگویید'), $say('Haruto · LINE', 'هاروتو · لاین')],
                    [$say('هل يمكنني تغيير خطتي؟', 'می‌توانم پلنم را عوض کنم؟'), $say('Omar · WhatsApp', 'عمر · واتساپ')],
                    [$say('Rechnung für September?', 'صورت‌حساب سپتامبر؟'), $say('Lena · E-mail', 'لنا · ایمیل')],
                ] as [$text, $who])
                    <div class="pg-box" style="padding: var(--nx-space-4); gap: var(--nx-space-1)">
                        <span style="font-weight: 600">{{ $text }}</span>
                        <span style="font-size: var(--nx-text-xs); color: var(--nx-text-muted)">{{ $who }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        <x-slot:footer>
            <div style="display: flex; align-items: center; gap: 0.75rem; min-block-size: 2.5rem; padding-inline: 0.75rem; font-size: var(--nx-text-sm); font-weight: 500; white-space: nowrap">
                <x-nx::avatar name="Hussein" size="xs" status="online" />
                <span>Hussein</span>
            </div>
        </x-slot:footer>
        <x-slot:prompt>
            <x-nx::prompt :placeholder="$say('Ask Nabu anything — in any language…', 'هرچه می‌خواهی بپرس — به هر زبان…')" x-on:submit.prevent="$wire.ping(@js($say('Nabu is on it', 'نابو دست به کار شد')))" />
        </x-slot:prompt>
    </x-nx::workspace-shell>
</section>
