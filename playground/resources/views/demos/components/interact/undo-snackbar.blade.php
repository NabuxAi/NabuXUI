{{--
    Undo snackbar's real scenarios: an inbox where archiving a thread is
    instant and Undo puts it back (all in the browser, through nx-undo /
    nx-undone), and a server action whose Undo calls a Livewire method.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $threads = [
        ['id' => 't1', 'from' => $say('Sara Ahmadi', 'سارا احمدی'), 'subject' => $say('Contract for the Tabriz branch', 'قرارداد شعبهٔ تبریز')],
        ['id' => 't2', 'from' => $say('Billing', 'صورت‌حساب'), 'subject' => $say('Invoice #1043 is ready', 'فاکتور ۱۰۴۳ آماده است')],
        ['id' => 't3', 'from' => $say('Reza Karimi', 'رضا کریمی'), 'subject' => $say('Photos from the Nowruz shoot', 'عکس‌های عکاسی نوروز')],
        ['id' => 't4', 'from' => $say('GitHub', 'گیت‌هاب'), 'subject' => $say('3 new review requests', '۳ درخواست بازبینی تازه')],
    ];
@endphp

<x-nx::undo-snackbar :undo-label="$say('Undo', 'بازگردانی')" :label="$say('Undo notices', 'اعلان‌های بازگردانی')" />

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Archive now, regret later', 'الان بایگانی، بعد پشیمانی') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Archiving is instant — no confirm dialog. Each one stacks a snackbar whose thin timer drains over six seconds; hover or Tab into the stack and every timer stops. Undo brings the thread back where it was.', 'بایگانی فوری است — بی‌دیالوگ تأیید. هر کدام اسنک‌باری روی هم می‌گذارد که تایمر نازکش در شش ثانیه خالی می‌شود؛ روی پشته هاور کنید یا با Tab واردش شوید تا همهٔ تایمرها بایستند. بازگردانی رشته را سر جایش برمی‌گرداند.') }}
        </p>
    </div>
    <ul x-data="{ threads: {{ \Illuminate\Support\Js::from($threads) }}, hidden: [] }"
        x-on:nx-undone.window="hidden = hidden.filter((id) => id !== $event.detail.params[0])"
        style="display: grid; gap: .5rem; margin: 0; padding: 0; list-style: none; max-inline-size: 36rem">
        <template x-for="thread in threads.filter((t) => ! hidden.includes(t.id))" x-bind:key="thread.id">
            <li style="display: flex; align-items: center; gap: .75rem; padding: .75rem 1rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg); background: var(--nx-surface)">
                <span style="display: grid; flex: 1; min-inline-size: 0">
                    <strong x-text="thread.from"></strong>
                    <span style="color: var(--nx-text-muted); font-size: var(--nx-text-sm)" x-text="thread.subject"></span>
                </span>
                <x-nx::button size="sm" variant="ghost" icon="folder"
                    x-on:click="hidden.push(thread.id); $dispatch('nx-undo', { message: {{ \Illuminate\Support\Js::from($say('Archived: ', 'بایگانی شد: ')) }} + thread.subject, params: [thread.id], icon: 'folder' })">
                    {{ $say('Archive', 'بایگانی') }}
                </x-nx::button>
            </li>
        </template>
        <li x-show="hidden.length === threads.length" style="padding: 1rem; color: var(--nx-text-muted)">{{ $say('Inbox zero. Undo any of them below.', 'صندوق خالی شد. هر کدام را از پایین برگردانید.') }}</li>
    </ul>
</section>

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Undo through the server', 'بازگردانی از راه سرور') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('Here Undo calls a Livewire method with params — in your app, $this->dispatch(\'nx-undo\', message: …, undo: \'restore\', params: [$id]). The server answers with a toast.', 'این‌جا بازگردانی یک متد Livewire را با پارامترها صدا می‌زند — در برنامهٔ شما: $this->dispatch(\'nx-undo\', message: …, undo: \'restore\', params: [$id]). سرور با توست جواب می‌دهد.') }}
        </p>
        <div class="pg-row">
            <x-nx::button size="sm" variant="danger" icon="trash" x-data
                x-on:click="$dispatch('nx-undo', { message: {{ \Illuminate\Support\Js::from($say('Customer “Ali Rezaei” deleted', 'مشتری «علی رضایی» حذف شد')) }}, undo: 'ping', params: [{{ \Illuminate\Support\Js::from($say('Customer restored', 'مشتری برگردانده شد')) }}], icon: 'user', duration: 8000 })">
                {{ $say('Delete customer', 'حذف مشتری') }}
            </x-nx::button>
            <x-nx::button size="sm" variant="ghost" icon="check" x-data
                x-on:click="$dispatch('nx-undo', { message: {{ \Illuminate\Support\Js::from($say('Settings saved', 'تنظیمات ذخیره شد')) }}, undoable: false, icon: 'check-circle', duration: 3000 })">
                {{ $say('A notice without Undo', 'اعلانی بی‌بازگردانی') }}
            </x-nx::button>
        </div>
    </section>

    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Why not the toaster?', 'چرا توستر نه؟') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('Toasts report; snackbars hold a decision open. The stack sits bottom-centre, keeps at most three on screen, and the drain bar is never the only cue — the message and the Undo button say it too.', 'توست گزارش می‌دهد؛ اسنک‌بار یک تصمیم را باز نگه می‌دارد. پشته پایینِ وسط می‌نشیند، حداکثر سه تا را نشان می‌دهد و نوار تخلیه هرگز تنها نشانه نیست — پیام و دکمهٔ بازگردانی هم همین را می‌گویند.') }}
        </p>
    </section>
</div>
