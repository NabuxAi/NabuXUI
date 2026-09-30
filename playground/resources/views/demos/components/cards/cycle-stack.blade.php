{{--
    The cycle stack's real scenarios: a notification centre in the footprint
    of one card, where the front card's index rides a Livewire property.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $events = [
        'deploy' => ['icon' => 'zap', 'tone' => 'cyan', 'title' => $say('Deploy finished', 'استقرار تمام شد'), 'description' => $say('v2.4 is live in all regions.', 'نسخهٔ ۲٫۴ در همهٔ منطقه‌ها زنده است.'), 'meta' => $say('2 min ago', '۲ دقیقه پیش')],
        'pay' => ['icon' => 'check-circle', 'tone' => 'gold', 'title' => $say('Payment received', 'پرداخت دریافت شد'), 'description' => $say('Invoice #1043 paid by Estudio Sol.', 'فاکتور ۱۰۴۳ توسط استودیو سُل پرداخت شد.'), 'meta' => $say('5 min ago', '۵ دقیقه پیش')],
        'comment' => ['icon' => 'message', 'tone' => 'violet', 'title' => $say('New comment', 'دیدگاه تازه'), 'description' => $say('“Keep the blue version?”', '«نسخهٔ آبی را نگه داریم؟»'), 'meta' => $say('12 min ago', '۱۲ دقیقه پیش')],
        'review' => ['icon' => 'star', 'tone' => 'lapis', 'title' => $say('A new five-star review', 'نقد پنج‌ستارهٔ تازه'), 'description' => $say('“So easy to use, even the night shift.”', '«آن‌قدر ساده که حتی شیفت شب هم راحت است.»'), 'meta' => $say('1 h ago', '۱ ساعت پیش')],
        'alert' => ['icon' => 'bell', 'tone' => 'gold', 'title' => $say('Usage at 90%', 'مصرف به ۹۰٪ رسید'), 'description' => $say('Agents pause at 100% — consider an upgrade.', 'ایجنت‌ها در ۱۰۰٪ می‌ایستند — ارتقا را ببینید.'), 'meta' => $say('2 h ago', '۲ ساعت پیش')],
    ];
    $front = (int) ($state['card'] ?? 0);
    $frontTitle = $events[array_keys($events)[$front]]['title'] ?? '—';
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The notification centre', 'مرکز اعلان‌ها') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Five events in the space of one: drag the top card down, click it or press the button and it tucks behind the deck. The front card’s index rides a Livewire property.', 'پنج رویداد در جایِ یک کارت: کارت رویی را پایین بکشید، کلیکش کنید یا دکمه را بزنید تا پشت دسته پنهان شود. اندیس کارت جلو با یک پراپرتی Livewire می‌ماند.') }}
        </p>
    </div>
    <div class="pg-grid" style="grid-template-columns: repeat(auto-fit, minmax(min(100%, 22rem), 1fr))">
        <x-nx::cycle-stack :next-label="$say('Next event', 'رویداد بعدی')" wire:model="state.card" :items="$events" />
        <div class="pg-box" style="padding: 0; border: 0; gap: .75rem; align-content: center">
            <h4 class="pg-title" style="margin: 0; font-size: var(--nx-text-lg)">{{ $say('What the server knows', 'چیزی که سرور می‌داند') }}</h4>
            <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
                {{ $say('wire:model sends the front index with the next request:', 'wire:model اندیس کارت جلو را با درخواست بعدی می‌فرستد:') }}
                <code>{{ $frontTitle }}</code> · {{ $say('index', 'اندیس') }} <code>{{ NabuXUI::formatNumber($front) }}</code>
            </p>
            <div class="pg-row">
                <x-nx::button size="sm" variant="ghost" icon="check" wire:click="save(@js($say('All caught up — 5 events read', 'همه خوانده شد — ۵ رویداد')))">{{ $say('Mark all as read', 'خواندن همه') }}</x-nx::button>
            </div>
        </div>
    </div>
</section>
