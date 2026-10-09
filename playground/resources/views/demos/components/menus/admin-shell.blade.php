{{--
    The admin shell's real scenario: the frame of a small shop panel — sidebar,
    topbar and a content area that actually changes with the active item
    (wire:model on the root). A second frame shows the custom search/heading
    slots and the mobile drawer's menu button.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $page = $state['page'] ?? 'dashboard';
    $titles = [
        'dashboard' => [$say('Dashboard', 'داشبورد'), $say("Today's pulse", 'نبض امروز')],
        'orders' => [$say('Orders', 'سفارش‌ها'), NabuXUI::formatNumber(24).' '.$say('open', 'سفارش باز')],
        'customers' => [$say('Customers', 'مشتریان'), $say('Loyal and brand new', 'وفاداری و تازه‌وارد')],
    ];
    [$title, $subtitle] = $titles[$page] ?? [$say('Reports', 'گزارش‌ها'), $say('Coming soon', 'به‌زودی')];

    $nav = [
        ['label' => $say('Shop', 'فروشگاه'), 'items' => [
            ['id' => 'dashboard', 'label' => $say('Dashboard', 'داشبورد'), 'icon' => 'grid'],
            ['id' => 'orders', 'label' => $say('Orders', 'سفارش‌ها'), 'icon' => 'layers', 'badge' => 24],
            ['id' => 'customers', 'label' => $say('Customers', 'مشتریان'), 'icon' => 'users'],
        ]],
        ['label' => $say('System', 'سیستم'), 'items' => [
            ['id' => 'reports', 'label' => $say('Reports', 'گزارش‌ها'), 'icon' => 'chart'],
            ['id' => 'settings', 'label' => $say('Settings', 'تنظیمات'), 'icon' => 'settings'],
        ]],
    ];

    $stats = [
        ['label' => $say('Orders today', 'سفارش امروز'), 'value' => 128, 'icon' => 'layers', 'caption' => $say('+12 vs yesterday', '+۱۲ نسبت به دیروز')],
        ['label' => $say('Visits', 'بازدید'), 'value' => 9412, 'icon' => 'globe'],
        ['label' => $say('Conversion', 'نرخ تبدیل'), 'value' => 3, 'icon' => 'trend-up', 'caption' => $say('%', '٪')],
    ];

    $orders = [
        ['id' => '1042', 'customer' => $say('Emma Carter', 'آوا کریمی'), 'total' => 1280000, 'status' => 'success'],
        ['id' => '1041', 'customer' => $say('Liam Harper', 'سهیل نوری'), 'total' => 490000, 'status' => 'running'],
        ['id' => '1040', 'customer' => $say('Mia Novak', 'مونا احمدی'), 'total' => 2350000, 'status' => 'queued'],
        ['id' => '1039', 'customer' => $say('Daniel Brooks', 'کیان رجایی'), 'total' => 760000, 'status' => 'failed'],
    ];

    $customers = [
        ['name' => $say('Emma Carter', 'آوا کریمی'), 'city' => $say('Istanbul', 'استانبول'), 'orders' => 42],
        ['name' => $say('Liam Harper', 'سهیل نوری'), 'city' => $say('Dubai', 'دبی'), 'orders' => 17],
        ['name' => $say('Mia Novak', 'مونا احمدی'), 'city' => $say('Rome', 'رم'), 'orders' => 8],
    ];

    $activity = [
        ['id' => 'as1', 'actor' => ['name' => $say('Emma Carter', 'آوا کریمی')], 'text' => $say('paid order', 'سفارش را پرداخت کرد'), 'target' => '#1042', 'time' => now()->subMinutes(6), 'unread' => true],
        ['id' => 'as2', 'actor' => ['name' => $say('Daniel Brooks', 'کیان رجایی')], 'text' => $say('opened a ticket', 'تیکت باز کرد'), 'time' => now()->subHours(2), 'unread' => true],
        ['id' => 'as3', 'actor' => ['name' => $say('Mia Novak', 'مونا احمدی')], 'text' => $say('reviewed', 'نظر داد به'), 'target' => $say('wool coat', 'کتان پشمی'), 'time' => now()->subDay()],
    ];
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A whole shop panel in one component', 'کل پنل فروشگاه در یک کامپوننت') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Click around the sidebar — the active item syncs to Livewire through wire:model, the highlight springs after it, and the content area follows. The toggle at the bottom of the sidebar collapses it; shrink the window under 48rem and the topbar’s menu button opens the drawer.', 'در نوار کناری کلیک کنید — آیتم فعال با wire:model به Livewire می‌رود، هایلایت فنری دنبالش می‌آید و ناحیهٔ محتوا عوض می‌شود. دکمهٔ پایین نوار کناری آن را جمع می‌کند؛ اگر پنجره را زیر ۴۸rem ببرید، دکمهٔ منوی نوار بالایی کشو را باز می‌کند.') }}
        </p>
    </div>
    <x-nx::admin-shell brand="{{ $say('Nabu Shop', 'فروشگاه نابو') }}" :active="$page" wire:model="state.page" :groups="$nav"
        :title="$title" :subtitle="$subtitle" :user="['name' => $say('Clara Meyer', 'نگار رستمی'), 'role' => $say('Sales lead', 'مدیر فروش')]"
        :search-placeholder="$say('Search or run a command…', 'جست‌وجو یا اجرای فرمان…')" search-hint="⌘K"
        height="34rem" min-height="30rem">
        <x-slot:actions>
            <x-nx::theme-toggle />
            <x-nx::activity-dropdown :items="$activity" x-on:nx-mark-all-read="ping(@js($say('All caught up', 'همه خوانده شد')))" />
        </x-slot:actions>
        <x-slot:userMenu>
            <div class="nx-admin-user-head">
                <p class="nx-admin-user-name">{{ $say('Clara Meyer', 'نگار رستمی') }}</p>
                <p class="nx-admin-user-role">{{ $say('Sales lead', 'مدیر فروش') }}</p>
            </div>
            <div role="menu" aria-label="{{ $say('Clara Meyer', 'نگار رستمی') }}">
                <button type="button" class="nx-admin-user-item" role="menuitem" wire:click="ping(@js($say('Profile opened', 'پروفایل باز شد')))">
                    {{ \NabuXUI\NabuXUI::icon('user') }}<span>{{ $say('Your profile', 'پروفایل شما') }}</span>
                </button>
                <button type="button" class="nx-admin-user-item" role="menuitem" wire:click="save(@js($say('Receipt sent', 'رسید فرستاده شد')))">
                    {{ \NabuXUI\NabuXUI::icon('mail') }}<span>{{ $say('Email my receipts', 'رسیدها را ایمیل کن') }}</span>
                </button>
                <hr class="nx-admin-user-divider" />
                <button type="button" class="nx-admin-user-item" role="menuitem" wire:click="ping(@js($say('Signed out (not really)', 'خروج انجام شد (نه واقعاً)')))">
                    {{ \NabuXUI\NabuXUI::icon('lock') }}<span>{{ $say('Sign out', 'خروج') }}</span>
                </button>
            </div>
        </x-slot:userMenu>

        @if ($page === 'dashboard')
            <x-nx::stat-strip :label="$say('Today', 'امروز')" :stats="$stats" />
            <div class="pg-row">
                <x-nx::button variant="primary" icon="plus" wire:click="ping(@js($say('New order form opened', 'فرم سفارش تازه باز شد')))">{{ $say('New order', 'سفارش تازه') }}</x-nx::button>
                <x-nx::button variant="ghost" icon="upload" wire:click="save(@js($say('Catalog synced', 'کاتالوگ هم‌گام شد')))">{{ $say('Sync catalog', 'هم‌گام‌سازی کاتالوگ') }}</x-nx::button>
            </div>
        @elseif ($page === 'orders')
            <x-nx::data-table :caption="$say('Latest orders', 'تازه‌ترین سفارش‌ها')" :rows="$orders" :columns="[
                ['key' => 'id', 'label' => $say('Order', 'سفارش'), 'sortable' => true],
                ['key' => 'customer', 'label' => $say('Customer', 'مشتری'), 'sortable' => true],
                ['key' => 'total', 'label' => $say('Total', 'مبلغ'), 'format' => 'number', 'sortable' => true],
                ['key' => 'status', 'label' => $say('Status', 'وضعیت'), 'format' => 'status', 'sortable' => true],
            ]" />
        @elseif ($page === 'customers')
            <ul style="list-style: none; margin: 0; padding: 0; display: grid; gap: .5rem">
                @foreach ($customers as $customer)
                    <li class="pg-row" style="justify-content: space-between; padding: .75rem 1rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg)">
                        <span class="pg-row" style="gap: .75rem">
                            <x-nx::avatar :name="$customer['name']" size="sm" />
                            <strong>{{ $customer['name'] }}</strong>
                            <span style="color: var(--nx-text-muted)">{{ $customer['city'] }}</span>
                        </span>
                        <x-nx::badge tone="accent">{{ NabuXUI::formatNumber($customer['orders']).' '.$say('orders', 'سفارش') }}</x-nx::badge>
                    </li>
                @endforeach
            </ul>
        @else
            <x-nx::empty-state icon="wand" :title="$say('Nothing here yet', 'این‌جا هنوز چیزی نیست')"
                :description="$say('This corner of the demo panel is intentionally blank — a real page would fill it.', 'این گوشهٔ پنل دمو عمداً خالی است — صفحهٔ واقعی این‌جا پر می‌شود.')">
                <x-slot:actions>
                    <x-nx::button variant="primary" icon="grid" wire:click="$set('state.page', 'dashboard')">{{ $say('Back to dashboard', 'بازگشت به داشبورد') }}</x-nx::button>
                </x-slot:actions>
            </x-nx::empty-state>
        @endif
    </x-nx::admin-shell>
    <p style="margin: 0; color: var(--nx-text-muted)">
        {{ $say('Active item on the server:', 'آیتم فعال روی سرور:') }}
        <code>{{ $page }}</code>
    </p>
</section>

<section class="pg-box" style="gap: 1rem">
    <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A second frame, custom seats', 'قاب دوم، با جای‌گذاری‌های سفارشی') }}</h3>
    <p style="margin: 0; color: var(--nx-text-muted)">
        {{ $say('The search seat takes a real input through the search slot, and the heading slot replaces title/subtitle with your own markup.', 'جای جست‌وجو با اسلات search یک ورودی واقعی می‌گیرد و اسلات heading عنوان و زیرعنوان را با مارک‌آپ خودتان عوض می‌کند.') }}
    </p>
    <x-nx::admin-shell brand="Lab" brand-mark="N" active="notes" :groups="[
        ['label' => $say('Lab', 'آزمایشگاه'), 'items' => [
            ['id' => 'notes', 'label' => $say('Notes', 'یادداشت‌ها'), 'icon' => 'edit'],
            ['id' => 'runs', 'label' => $say('Runs', 'اجراها'), 'icon' => 'zap', 'badge' => 3],
        ]],
    ]" height="19rem" min-height="19rem" nav-label="{{ $say('Lab navigation', 'ناوبری آزمایشگاه') }}">
        <x-slot:heading>
            <div class="pg-row" style="gap: .5rem">
                <x-nx::badge tone="accent" dot>{{ $say('beta', 'آزمایشی') }}</x-nx::badge>
                <p class="nx-admin-title">{{ $say('Notes', 'یادداشت‌ها') }}</p>
            </div>
        </x-slot:heading>
        <x-slot:search>
            <input class="nx-input" type="search" wire:model.live="state.labQuery" placeholder="{{ $say('Filter notes…', 'پالایش یادداشت‌ها…') }}" aria-label="{{ $say('Filter notes', 'پالایش یادداشت‌ها') }}" style="inline-size: min(100%, 16rem)">
        </x-slot:search>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('Whatever you typed:', 'هرچه نوشتید:') }}
            <code>{{ $state['labQuery'] ?? '—' }}</code>
        </p>
    </x-nx::admin-shell>
</section>
