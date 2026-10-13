{{--
    Swipe actions' real scenarios: an inbox whose rows swipe to mark read or
    to archive/delete (a full swipe fires the primary action, the server
    toasts it), and a Persian task list where the sides mirror.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $mails = [
        ['id' => 1, 'from' => $say('Emma Carter', 'سارا احمدی'), 'subject' => $say('Contract for the Istanbul branch', 'قرارداد شعبهٔ استانبول'), 'time' => '09:41'],
        ['id' => 2, 'from' => $say('Billing', 'صورت‌حساب'), 'subject' => $say('Invoice #1043 is ready', 'فاکتور ۱۰۴۳ آماده است'), 'time' => '08:15'],
        ['id' => 3, 'from' => $say('Reza Karimi', 'رضا کریمی'), 'subject' => $say('Photos from the Nowruz shoot', 'عکس‌های عکاسی نوروز'), 'time' => $say('Yesterday', 'دیروز')],
    ];
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('An inbox on a phone', 'صندوق ورودی روی گوشی') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Swipe a row toward the end to reveal “Read”, toward the start for “Archive” and “Delete”. Past about 60% of the row the primary action fires on release; a quick flick opens a side even when short. Keyboard: Tab reaches every action (the row opens to show it), Escape closes.', 'سطری را به سمت انتها بکشید تا «خوانده» پیدا شود و به سمت ابتدا برای «بایگانی» و «حذف». از حدود ۶۰٪ عرض به بعد، با رهاکردن کنش اصلی اجرا می‌شود؛ یک پرتاب سریع حتی کوتاه هم یک سمت را باز می‌کند. کیبورد: Tab به همهٔ کنش‌ها می‌رسد (سطر باز می‌شود تا نشانش دهد)، Escape می‌بندد.') }}
        </p>
    </div>
    <ul x-data="{ gone: [] }" style="display: grid; gap: .5rem; margin: 0; padding: 0; list-style: none; max-inline-size: 30rem">
        @foreach ($mails as $mail)
            <li x-show="! gone.includes({{ $mail['id'] }})" x-transition.opacity.duration.150ms>
                <x-nx::swipe-actions
                    :start="[['id' => 'read', 'label' => $say('Read', 'خوانده'), 'icon' => 'check', 'tone' => 'accent', 'primary' => true, 'click' => 'ping('.json_encode($say('Marked as read', 'خوانده شد')).')']]"
                    :end="[
                        ['id' => 'archive', 'label' => $say('Archive', 'بایگانی'), 'icon' => 'folder', 'tone' => 'warning', 'click' => 'ping('.json_encode($say('Archived', 'بایگانی شد')).')'],
                        ['id' => 'delete', 'label' => $say('Delete', 'حذف'), 'icon' => 'trash', 'tone' => 'danger', 'primary' => true, 'click' => 'ping('.json_encode($say('Deleted', 'حذف شد')).')'],
                    ]"
                    x-on:nx-action="if (['archive', 'delete'].includes($event.detail)) setTimeout(() => gone.push({{ $mail['id'] }}), 320)">
                    <a href="#" x-on:click.prevent style="display: flex; gap: .75rem; align-items: center; padding: .85rem 1rem; color: inherit; text-decoration: none">
                        <x-nx::avatar :name="$mail['from']" size="sm" />
                        <span style="display: grid; flex: 1; min-inline-size: 0">
                            <strong>{{ $mail['from'] }}</strong>
                            <span style="color: var(--nx-text-muted); font-size: var(--nx-text-sm); white-space: nowrap; overflow: hidden; text-overflow: ellipsis">{{ $mail['subject'] }}</span>
                        </span>
                        <span style="color: var(--nx-text-subtle); font-size: var(--nx-text-xs)">{{ $mail['time'] }}</span>
                    </a>
                </x-nx::swipe-actions>
            </li>
        @endforeach
        <li x-show="gone.length === {{ count($mails) }}" style="padding: 1rem; color: var(--nx-text-muted)">{{ $say('All clear.', 'همه‌چیز مرتب است.') }}</li>
    </ul>
</section>

<div class="pg-grid">
    <section class="pg-box" dir="rtl" lang="fa" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Right to left', 'راست‌به‌چپ') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('The start side is on the right here: swipe left to reveal it.', 'این‌جا سمت ابتدا راست است: برای دیدنش به چپ بکشید.') }}
        </p>
        <x-nx::swipe-actions
            :start="[['id' => 'done', 'label' => 'انجام شد', 'icon' => 'check', 'tone' => 'success', 'primary' => true]]"
            :end="[['id' => 'later', 'label' => 'بعداً', 'icon' => 'bell', 'tone' => 'gold', 'primary' => true]]">
            <div style="padding: .9rem 1rem">
                <strong>تماس با تأمین‌کنندهٔ کاغذ</strong>
                <div style="color: var(--nx-text-muted); font-size: var(--nx-text-sm)">امروز، ساعت ۱۵</div>
            </div>
        </x-nx::swipe-actions>
    </section>

    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Only one side', 'فقط یک سمت') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('No start actions: dragging that way only rubber-bands. full-swipe is off, so the end side just opens.', 'بدون کنش در ابتدا: کشیدن به آن سو فقط کش می‌آید. کشیدن کامل خاموش است، پس سمت انتها فقط باز می‌شود.') }}
        </p>
        <x-nx::swipe-actions :full-swipe="false" :end="[
            ['id' => 'mute', 'label' => $say('Mute', 'بی‌صدا'), 'icon' => 'bell'],
            ['id' => 'pin', 'label' => $say('Pin', 'سنجاق'), 'icon' => 'star', 'tone' => 'gold'],
        ]">
            <div style="padding: .9rem 1rem">
                <strong>{{ $say('#design-review', '#بازبینی-طراحی') }}</strong>
                <div style="color: var(--nx-text-muted); font-size: var(--nx-text-sm)">{{ $say('12 new messages', '۱۲ پیام تازه') }}</div>
            </div>
        </x-nx::swipe-actions>
    </section>
</div>
