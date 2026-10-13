{{--
    Tabs doing tab work: an account-settings switchboard bound to Livewire
    state, and an underline file browser where each tab carries a count badge.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $tab = (string) ($state['settingsTab'] ?? 'profile');
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Account settings, one switchboard', 'تنظیمات حساب، یک تابلوی کنترل') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The active tab lives in the URL’s component state — reload, walk away, come back: it remembers. Arrow keys roam, Home and End jump.', 'تب فعال در وضعیت کامپوننت می‌ماند — رفرش کنید، بروید، برگردید: یادش می‌ماند. کلیدهای جهت می‌گردند و Home و End می‌پرند.') }}
        </p>
    </div>
    <x-nx::tabs wire:model="state.settingsTab" :label="$say('Account settings', 'تنظیمات حساب')" :items="[
        'profile' => ['label' => $say('Profile', 'پروفایل'), 'icon' => 'user'],
        'security' => ['label' => $say('Security', 'امنیت'), 'icon' => 'shield'],
        'notifications' => ['label' => $say('Notifications', 'اعلان‌ها'), 'icon' => 'bell', 'badge' => NabuXUI::formatNumber(3)],
    ]" :value="$tab">
        <x-slot:profile>
            <div class="pg-grid" style="margin-block-start: .5rem">
                <x-nx::input :label="$say('Display name', 'نام نمایشی')" icon="user" wire:model.blur="state.name" />
                <x-nx::input :label="$say('Email', 'ایمیل')" type="email" icon="mail" wire:model.blur="state.billingEmail" />
            </div>
        </x-slot:profile>
        <x-slot:security>
            <div style="display: grid; gap: 1rem; margin-block-start: .5rem">
                <x-nx::switch :label="$say('Two-factor authentication', 'احراز هویت دو مرحله‌ای')" :description="$say('A code by SMS at every login.', 'هر ورود، یک کد با پیامک.')" wire:model="state.mfa" />
                <div class="pg-row">
                    <x-nx::button variant="secondary" icon="lock" wire:click="save('{{ $say('All sessions signed out', 'همهٔ نشست‌ها بسته شد') }}')">{{ $say('Sign out everywhere', 'خروج از همه‌جا') }}</x-nx::button>
                </div>
            </div>
        </x-slot:security>
        <x-slot:notifications>
            <div style="display: grid; gap: 1rem; margin-block-start: .5rem">
                <x-nx::switch :label="$say('Weekly digest', 'خلاصهٔ هفتگی')" :description="$say('Every Thursday, products only.', 'هر پنجشنبه، فقط محصول.')" wire:model="state.digest" />
                <x-nx::switch :label="$say('Mentions only', 'فقط منشن‌ها')" wire:model="state.mentions" />
                <x-nx::switch :label="$say('Everything, immediately', 'همه‌چیز، همان لحظه')" :description="$say('Brave choice.', 'انتخاب شجاعانه.')" wire:model="state.allMail" />
            </div>
        </x-slot:notifications>
    </x-nx::tabs>
</section>

<section class="pg-box" style="gap: 1rem">
    <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A file browser with counts', 'مرورگر فایل با شمارنده') }}</h3>
    <p style="margin: 0; color: var(--nx-text-muted)">
        {{ $say('Underline tabs with a badge on each — the pill follows the active tab with a spring.', 'تب‌های زیرخط‌دار با یک نشان روی هرکدام — pill با فنر دنبال تب فعال می‌آید.') }}
    </p>
    <x-nx::tabs variant="underline" :label="$say('Files', 'فایل‌ها')" :items="[
        'all' => ['label' => $say('All files', 'همه'), 'badge' => NabuXUI::formatNumber(18)],
        'shared' => ['label' => $say('Shared', 'مشترک'), 'badge' => NabuXUI::formatNumber(4)],
        'trash' => ['label' => $say('Trash', 'زباله'), 'badge' => NabuXUI::formatNumber(2)],
        'locked' => ['label' => $say('Locked', 'قفل'), 'disabled' => true],
    ]">
        <x-slot:all>
            <ul style="list-style: none; margin: .5rem 0 0; padding: 0; display: grid; gap: .5rem">
                <li class="pg-row" style="gap: .6rem"><x-nx::icon name="file" />{{ $say('Contract — Nabu 1404.pdf', 'قرارداد — نابو ۱۴۰۴.pdf') }}</li>
                <li class="pg-row" style="gap: .6rem"><x-nx::icon name="image" />brand-plate.png</li>
                <li class="pg-row" style="gap: .6rem"><x-nx::icon name="music" />{{ $say('launch-theme.mp3', 'تم-راه‌اندازی.mp3') }}</li>
            </ul>
        </x-slot:all>
        <x-slot:shared>
            <p style="margin: .5rem 0 0; color: var(--nx-text-muted)">{{ $say('Four files shared with the design guild.', 'چهار فایل با گروه طراحی مشترک است.') }}</p>
        </x-slot:shared>
        <x-slot:trash>
            <div class="pg-row" style="margin-block-start: .5rem; justify-content: space-between">
                <p style="margin: 0; color: var(--nx-text-muted)">{{ $say('Empties itself after 30 days.', 'بعد از ۳۰ روز خودش خالی می‌شود.') }}</p>
                <x-nx::button variant="ghost" icon="trash" wire:click="ping('{{ $say('Trash emptied', 'زباله خالی شد') }}')">{{ $say('Empty now', 'همین حالا خالی کن') }}</x-nx::button>
            </div>
        </x-slot:trash>
    </x-nx::tabs>
</section>
