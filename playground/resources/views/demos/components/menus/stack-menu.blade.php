{{--
    The stack menu twice: a workspace-settings tree you push into level by
    level (Back/← pops, the search spans every depth), and a jump-to picker
    whose items carry keyboard shortcuts and English keywords — so typing
    "dash" finds the Persian label too.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Workspace settings, level by level', 'تنظیمات ورک‌اسپیس، لایه به لایه') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('Items with children push a new level; the back row pops it. Choosing a leaf dispatches nx-select — we toast the label — and the search finds items at any depth, trail included.', 'آیتم‌های دارای فرزند لایهٔ بعد را push می‌کنند؛ ردیفِ بازگشت آن را pop می‌کند. انتخاب یک برگ nx-select می‌فرستد — برچسبش را توست می‌کنیم — و جست‌وجو آیتم‌ها را در هر عمقی با ردِ مسیرشان پیدا می‌کند.') }}
            </p>
        </div>
        <div class="pg-row">
            <x-nx::stack-menu title="{{ $say('Workspace settings', 'تنظیمات ورک‌اسپیس') }}" x-on:nx-select="$wire.ping($event.detail.label)" :items="[
                ['id' => 'general', 'label' => $say('General', 'عمومی'), 'icon' => 'settings', 'children' => [
                    ['id' => 'name', 'label' => $say('Workspace name', 'نام ورک‌اسپیس'), 'icon' => 'edit', 'description' => $say('Shown to teammates', 'به هم‌کاران نشان داده می‌شود')],
                    ['id' => 'region', 'label' => $say('Region', 'منطقه'), 'icon' => 'globe', 'children' => [
                        ['id' => 'tehran', 'label' => $say('Tehran', 'تهران')],
                        ['id' => 'frankfurt', 'label' => $say('Frankfurt', 'فرانکفورت')],
                        ['id' => 'tokyo', 'label' => $say('Tokyo', 'توکیو')],
                    ]],
                ]],
                ['id' => 'members', 'label' => $say('Members', 'اعضا'), 'icon' => 'users', 'description' => $say('Seats, invites, roles', 'صندلی، دعوت، نقش'), 'shortcut' => '⌘M'],
                ['id' => 'billing', 'label' => $say('Billing', 'صورتحساب'), 'icon' => 'chart', 'keywords' => 'invoice payment فاکتور پرداخت'],
                ['id' => 'signout', 'label' => $say('Sign out', 'خروج'), 'icon' => 'lock', 'tone' => 'danger'],
            ]">
                <x-slot:trigger>
                    <x-nx::button icon="sliders">{{ $say('Workspace settings', 'تنظیمات ورک‌اسپیس') }}</x-nx::button>
                </x-slot:trigger>
            </x-nx::stack-menu>
        </div>
    </section>

    <section class="pg-box" style="gap: 1rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A jump-to menu with shortcuts', 'منوی پرش سریع با میان‌بر') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('Open it and type — the keywords make English finds Persian labels. Shortcuts sit on the right like a command palette’s.', 'بازش کنید و تایپ کنید — کلیدواژه‌ها جست‌وجوی انگلیسی را به برچسب فارسی می‌رسانند. میان‌برها مثل پالت فرمان سمت راست می‌نشینند.') }}
            </p>
        </div>
        <div class="pg-row">
            <x-nx::stack-menu title="{{ $say('Jump to', 'پرش به') }}" x-on:nx-select="$wire.ping($event.detail.label)" :items="[
                ['id' => 'dash', 'label' => $say('Dashboard', 'داشبورد'), 'icon' => 'grid', 'shortcut' => '⌘1', 'keywords' => 'dashboard home'],
                ['id' => 'inbox', 'label' => $say('Inbox', 'صندوق ورودی'), 'icon' => 'mail', 'shortcut' => '⌘2', 'keywords' => 'inbox mail'],
                ['id' => 'reports', 'label' => $say('Reports', 'گزارش‌ها'), 'icon' => 'chart', 'shortcut' => '⌘3', 'keywords' => 'report analytics'],
                ['id' => 'team', 'label' => $say('Team', 'تیم'), 'icon' => 'users', 'shortcut' => '⌘4', 'keywords' => 'team people'],
            ]">
                <x-slot:trigger>
                    <x-nx::button variant="secondary" icon="command" icon-end="chevron-down">{{ $say('Jump to…', 'پرش به…') }}</x-nx::button>
                </x-slot:trigger>
            </x-nx::stack-menu>
        </div>
    </section>
</div>
