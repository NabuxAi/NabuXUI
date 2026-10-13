{{--
    The admin topbar alone: a page heading with a real command-search seat and
    a user menu whose items are our own wire:click buttons, then a second bar
    where the heading and search slots carry custom markup.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $activity = [
        ['id' => 'at1', 'actor' => ['name' => $say('Ava Karimi', 'آوا کریمی')], 'text' => $say('commented on', 'روی نظر داد'), 'target' => $say('spring campaign', 'کمپین بهار'), 'time' => now()->subMinutes(11), 'unread' => true],
        ['id' => 'at2', 'actor' => ['name' => $say('Soheil Nouri', 'سهیل نوری')], 'text' => $say('approved', 'تأیید کرد'), 'target' => $say('the refund', 'بازپرداخت را'), 'time' => now()->subHours(4)],
    ];
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The orders page, top row', 'ردیف بالای صفحهٔ سفارش‌ها') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The avatar opens a native-popover menu — here filled through the userMenu slot with our own buttons. Pressing the search seat dispatches nx-search, so a command palette can listen for it.', 'آواتار یک منوی popover بومی باز می‌کند — این‌جا با اسلات userMenu و دکمه‌های خودمان پر شده. فشردن جای جست‌وجو رویداد nx-search می‌فرستد تا پالت فرمان به آن گوش دهد.') }}
        </p>
    </div>
    <div style="border: 1px solid var(--nx-border); border-radius: var(--nx-radius-xl); overflow: clip">
        <x-nx::admin-topbar title="{{ $say('Orders', 'سفارش‌ها') }}" :subtitle="NabuXUI::formatNumber(24).' '.$say('open right now', 'سفارش همین حالا باز است')"
            search-placeholder="{{ $say('Search orders, customers…', 'جست‌وجوی سفارش، مشتری…') }}" search-hint="⌘K"
            :user="['name' => $say('Negar Rostami', 'نگار رستمی'), 'role' => $say('Sales lead', 'مدیر فروش')]"
            x-on:nx-search="ping(@js($say('Command palette would open here', 'پالت فرمان این‌جا باز می‌شد')))">
            <x-slot:actions>
                <x-nx::activity-dropdown :items="$activity" x-on:nx-mark-all-read="ping(@js($say('All caught up', 'همه خوانده شد')))" />
                <x-nx::theme-toggle />
                <x-nx::button variant="primary" size="sm" icon="plus" wire:click="ping(@js($say('New order form opened', 'فرم سفارش تازه باز شد')))">{{ $say('New order', 'سفارش تازه') }}</x-nx::button>
            </x-slot:actions>
            <x-slot:userMenu>
                <div class="nx-admin-user-head">
                    <p class="nx-admin-user-name">{{ $say('Negar Rostami', 'نگار رستمی') }}</p>
                    <p class="nx-admin-user-role">{{ $say('Sales lead', 'مدیر فروش') }}</p>
                </div>
                <div role="menu" aria-label="{{ $say('Negar Rostami', 'نگار رستمی') }}">
                    <button type="button" class="nx-admin-user-item" role="menuitem" wire:click="ping(@js($say('Team calendar opened', 'تقویم تیم باز شد')))">
                        {{ \NabuXUI\NabuXUI::icon('chart') }}<span>{{ $say('Team calendar', 'تقویم تیم') }}</span>
                    </button>
                    <hr class="nx-admin-user-divider" />
                    <button type="button" class="nx-admin-user-item" role="menuitem" wire:click="ping(@js($say('Signed out (not really)', 'خروج انجام شد (نه واقعاً)')))">
                        {{ \NabuXUI\NabuXUI::icon('lock') }}<span>{{ $say('Sign out', 'خروج') }}</span>
                    </button>
                </div>
            </x-slot:userMenu>
        </x-nx::admin-topbar>
        <div style="padding: 1.5rem; border-block-start: 1px solid var(--nx-border); color: var(--nx-text-muted)">
            {{ $say('…the page body would start here.', '…بدنهٔ صفحه از این‌جا شروع می‌شد.') }}
        </div>
    </div>
</section>

<section class="pg-box" style="gap: 1rem">
    <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Custom heading and a live search input', 'عنوان سفارشی و ورودی جست‌وجوی زنده') }}</h3>
    <p style="margin: 0; color: var(--nx-text-muted)">
        {{ $say('The heading slot carries breadcrumbs and badges; the search slot takes any control — here a plain nx-input bound to Livewire.', 'اسلات heading مسیر و نشان‌ها را می‌گیرد؛ اسلات search هر کنترلی می‌پذیرد — این‌جا یک nx-input ساده به Livewire بسته است.') }}
    </p>
    <div style="border: 1px solid var(--nx-border); border-radius: var(--nx-radius-xl); overflow: clip">
        <x-nx::admin-topbar :user="['name' => $say('Kian Rajaee', 'کیان رجایی')]">
            <x-slot:heading>
                <nav class="pg-row" style="gap: .5rem; font-size: var(--nx-text-sm)" aria-label="{{ $say('Trail', 'مسیر') }}">
                    <span style="color: var(--nx-text-muted)">{{ $say('Panel', 'پنل') }}</span>
                    <span aria-hidden="true">/</span>
                    <span style="color: var(--nx-text-muted)">{{ $say('Customers', 'مشتریان') }}</span>
                    <span aria-hidden="true">/</span>
                    <strong>{{ $say('Ava Karimi', 'آوا کریمی') }}</strong>
                    <x-nx::badge tone="success" dot>{{ $say('active', 'فعال') }}</x-nx::badge>
                </nav>
            </x-slot:heading>
            <x-slot:search>
                <input class="nx-input" type="search" wire:model.live="state.topbarQuery" placeholder="{{ $say('Search this customer…', 'جست‌وجوی این مشتری…') }}" aria-label="{{ $say('Search', 'جست‌وجو') }}" style="inline-size: min(100%, 15rem)">
            </x-slot:search>
            <x-slot:actions>
                <x-nx::button size="sm" variant="ghost" icon="copy" wire:click="ping(@js($say('Customer link copied', 'لینک مشتری کپی شد')))">{{ $say('Copy link', 'کپی لینک') }}</x-nx::button>
            </x-slot:actions>
        </x-nx::admin-topbar>
        <div style="padding: 1.25rem 1.5rem; border-block-start: 1px solid var(--nx-border); color: var(--nx-text-muted)">
            {{ $say('Query so far:', 'تا این‌جا جست‌وجو شده:') }}
            <code>{{ $state['topbarQuery'] ?? '—' }}</code>
        </div>
    </div>
</section>
