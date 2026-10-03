{{--
    A coding agent asks before touching production config: the change is previewed as
    a diff, answered with the buttons or Y / N / A, and the decision is bound to
    Livewire (wire:model) — the badge below reads it back. The second card asks to
    send email on the user's behalf and allows Undo.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $decision = $state['decision'] ?? 'pending';
    $labels = [
        'approve' => $say('Approve', 'تأیید'), 'deny' => $say('Deny', 'رد'), 'always' => $say('Always allow', 'همیشه مجاز'),
        'approved' => $say('Approved', 'تأیید شد'), 'denied' => $say('Denied', 'رد شد'), 'alwaysAllowed' => $say('Always allowed', 'همیشه مجاز شد'), 'undo' => $say('Undo', 'برگرداندن'),
    ];
    $before = "return [\n    'default' => env('CACHE_STORE', 'file'),\n\n    'stores' => [\n        'file' => [\n            'driver' => 'file',\n            'path' => storage_path('framework/cache/data'),\n        ],\n    ],\n\n    'prefix' => 'nabux_cache_',\n];";
    $after = "return [\n    'default' => env('CACHE_STORE', 'redis'),\n\n    'stores' => [\n        'file' => [\n            'driver' => 'file',\n            'path' => storage_path('framework/cache/data'),\n        ],\n        'redis' => [\n            'driver' => 'redis',\n            'connection' => 'cache',\n        ],\n    ],\n\n    'prefix' => 'nabux_cache_',\n];";
@endphp
<div style="display: grid; gap: 1.25rem; max-inline-size: 44rem">
    <x-nx::approval-card wire:model.live="state.decision" :labels="$labels" autofocus
        :title="$say('Switch the production cache to Redis?', 'کش محیط اصلی به Redis تغییر کند؟')"
        :summary="$say('Edits config/cache.php and restarts the queue workers. Sessions are not affected.', 'فایل config/cache.php را ویرایش و صف‌ها را دوباره راه‌اندازی می‌کند. نشست‌ها تغییری نمی‌کنند.')"
        tool="edit_file · config/cache.php">
        <x-slot:meta><span class="nx-approval-chip">{{ \NabuXUI\NabuXUI::icon('globe') }}production</span></x-slot:meta>
        <x-nx::diff-viewer filename="config/cache.php" :old-text="$before" :new-text="$after" :context="2" max-height="16rem" :labels="['unified' => $say('Unified', 'یکپارچه'), 'split' => $say('Split', 'دوستونه'), 'expand' => $say('Expand :count unchanged lines', ':count خط بدون تغییر را باز کن')]" />
    </x-nx::approval-card>
    <div class="pg-row">
        <span style="color: var(--nx-text-muted); font-size: var(--nx-text-sm)">{{ $say('Livewire sees:', 'Livewire می‌بیند:') }}</span>
        <x-nx::badge :tone="match ($decision) { 'approved', 'always' => 'success', 'denied' => 'danger', default => 'neutral' }">{{ $decision }}</x-nx::badge>
        @if ($decision !== 'pending')
            <x-nx::button size="sm" variant="ghost" wire:click="$set('state.decision', 'pending')">{{ $say('Ask again', 'دوباره بپرس') }}</x-nx::button>
        @endif
    </div>

    <x-nx::approval-card :labels="$labels" undoable :allow-always="false"
        :title="$say('Send the follow-up email to 14 customers?', 'ایمیل پیگیری برای ۱۴ مشتری ارسال شود؟')"
        :summary="$say('Subject: “Your order is on its way”. Sent from support@ on your behalf.', 'موضوع: «سفارش شما در راه است». از طرف شما با support@ ارسال می‌شود.')"
        tool="send_email × 14" />
</div>
