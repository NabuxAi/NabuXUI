{{--
    The switch as it lives in real products: a shop's notification channels
    (form semantics — the toggles ride along with the save button), the
    storefront's vacation mode (live semantics — the panel reacts the moment
    it flips, beside a locked switch that cannot move) and the members
    table's access column (bare sm switches named through aria-label).
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $channels = [
        ['key' => 'sms', 'label' => $say('Order SMS', 'پیامک سفارش'), 'description' => $say('Every new order, to the owner’s number.', 'هر سفارش تازه، به شمارهٔ صاحب فروشگاه.')],
        ['key' => 'lowStock', 'label' => $say('Low-stock alert', 'هشدار کم‌موجودی'), 'description' => $say('When an item falls below 5 units.', 'وقتی موجودی کالا زیر ۵ عدد برود.')],
        ['key' => 'digest', 'label' => $say('Weekly digest', 'گزارش هفتگی'), 'description' => $say('Saturday mornings, in the panel inbox.', 'صبح شنبه‌ها، در صندوق پنل.')],
    ];
    $on = count(array_filter($channels, fn ($channel) => (bool) ($state['notify'][$channel['key']] ?? false)));

    $vacation = (bool) ($state['vacation'] ?? false);

    $members = [
        ['key' => 'maryam', 'name' => 'مریم صادقی', 'role' => $say('Product designer', 'طراح محصول')],
        ['key' => 'saman', 'name' => 'سامان دهقان', 'role' => $say('Fulfillment lead', 'سرپرست انبار')],
        ['key' => 'hoda', 'name' => 'هدی کریمی', 'role' => $say('Support agent', 'کارشناس پشتیبانی')],
    ];
    $allowed = count(array_filter($members, fn ($member) => (bool) ($state['access'][$member['key']] ?? false)));
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('How the shop hears about orders', 'فروشگاه چطور از سفارش‌ها باخبر می‌شود') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The switches bind with wire:model, so they ride along with the save — until then nothing is committed, and the count below shows what the shop has actually saved.', 'سوییچ‌ها با wire:model وصل‌اند و همراهِ دکمهٔ ذخیره می‌روند — تا آن لحظه چیزی ثبت نمی‌شود و شمارندهٔ پایین همان چیزی است که واقعاً ذخیره شده.') }}
        </p>
    </div>
    <div style="display: grid; gap: 1rem">
        @foreach ($channels as $channel)
            <x-nx::switch :label="$channel['label']" :description="$channel['description']" wire:model="state.notify.{{ $channel['key'] }}" />
        @endforeach
    </div>
    <div class="pg-row" style="justify-content: space-between">
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ NabuXUI::formatNumber($on).' '.$say('of 3 channels saved as on.', 'کانال از ۳ کانال، ذخیره‌شده روی روشن.') }}
        </p>
        <x-nx::button variant="primary" icon="check" wire:click="save('{{ $say('Notification settings saved', 'تنظیمات اطلاع‌رسانی ذخیره شد') }}')">
            {{ $say('Save settings', 'ذخیرهٔ تنظیمات') }}
        </x-nx::button>
    </div>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The storefront’s vacation mode', 'حالت تعطیلی ویترین') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('A bare size="lg" switch in its own row, named through aria-label; wire:model.live posts the flip as it happens and the panel answers — no save button anywhere.', 'کلیدی تنها با size="lg" در ردیف خودش، نام‌گذاری‌شده با aria-label؛ با wire:model.live همان لحظه که می‌چرخد به سرور می‌رود و پنل جواب می‌دهد — نه دکمهٔ ذخیره‌ای در کار است.') }}
        </p>
    </div>
    <div class="pg-row" style="justify-content: space-between; padding: .9rem 1.1rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg)">
        <span style="display: grid; gap: .15rem">
            <strong style="font-weight: 600">{{ $say('Pause the storefront', 'توقف ویترین') }}</strong>
            <span style="color: var(--nx-text-muted)">{{ $say('Guests see the away note; orders wait in the panel.', 'میهمان‌ها پیام تعطیلی را می‌بینند؛ سفارش‌ها در پنل می‌مانند.') }}</span>
        </span>
        <x-nx::switch size="lg" wire:model.live="state.vacation" aria-label="{{ $say('Pause the storefront', 'توقف ویترین') }}" />
    </div>
    @if ($vacation)
        <x-nx::alert tone="warning" :title="$say('The storefront is paused', 'ویترین متوقف است')">
            {{ $say('The “back soon” note is up and checkout is closed. Flip the switch to open again.', 'پیام «به‌زودی برمی‌گردیم» بالا است و پرداخت بسته است. برای بازگشایی، کلید را برگردانید.') }}
        </x-nx::alert>
    @else
        <x-nx::alert tone="success" :title="$say('The storefront is open', 'ویترین باز است')">
            {{ $say('Orders come through and checkout is live.', 'سفارش‌ها می‌رسند و پرداخت روشن است.') }}
        </x-nx::alert>
    @endif
    <x-nx::switch :label="$say('Payment gateway', 'درگاه پرداخت')" :description="$say('Locked while a payout is unsettled; support can reopen it.', 'تا وقتی تسویه‌ای باز است قفل است؛ پشتیبانی می‌تواند باز کند.')" checked disabled />
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The access column in the members table', 'ستون دسترسی در جدول اعضا') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Bare size="sm" switches in their own column — each named through aria-label so it says itself to a screen reader, while the badge beside it restates the state in colour and words.', 'سوییچ‌های تنها با size="sm" در ستون خودشان — هرکدام با aria-label نام‌گذاری شده تا برای صفحه‌خوان خودش را معرفی کند و نشان کنارش همان وضعیت را با رنگ و واژه تکرار می‌کند.') }}
        </p>
    </div>
    <ul style="list-style: none; margin: 0; padding: 0; display: grid; gap: .5rem">
        @foreach ($members as $member)
            @php
                $memberAllowed = (bool) ($state['access'][$member['key']] ?? false);
            @endphp
            <li class="pg-row" style="justify-content: space-between; padding: .6rem .9rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg)">
                <span class="pg-row" style="gap: .6rem">
                    <x-nx::avatar :name="$member['name']" size="sm" />
                    <strong style="font-weight: 600">{{ $member['name'] }}</strong>
                </span>
                <span class="pg-row" style="gap: .75rem">
                    <span style="color: var(--nx-text-muted)">{{ $member['role'] }}</span>
                    @if ($memberAllowed)
                        <x-nx::badge tone="success">{{ $say('Panel access', 'دسترسی پنل') }}</x-nx::badge>
                    @else
                        <x-nx::badge tone="neutral">{{ $say('No access', 'بدون دسترسی') }}</x-nx::badge>
                    @endif
                    <x-nx::switch size="sm" wire:model.live="state.access.{{ $member['key'] }}" aria-label="{{ $say('Panel access for', 'دسترسی پنل برای') }} {{ $member['name'] }}" />
                </span>
            </li>
        @endforeach
    </ul>
    <p style="margin: 0; color: var(--nx-text-muted)">
        {{ NabuXUI::formatNumber($allowed).' '.$say('of 3 members can enter the panel.', 'نفر از ۳ عضو می‌تواند وارد پنل شود.') }}
    </p>
</section>
