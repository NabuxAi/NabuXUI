{{--
    Steps twice: a horizontal onboarding wizard you can actually walk through
    (state in Livewire), and a vertical order tracker with descriptions.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $step = max(0, min(3, (int) ($state['step'] ?? 0)));

    $wizard = [
        ['title' => $say('Workspace', 'ورک‌اسپیس'), 'description' => $say('Name and address', 'نام و آدرس')],
        ['title' => $say('Invite', 'دعوت'), 'description' => $say('Optional — 3 seats', 'اختیاری — ۳ صندلی')],
        ['title' => $say('Payment', 'پرداخت'), 'description' => $say('Card or transfer', 'کارت یا انتقال')],
        ['title' => $say('Done', 'آماده'), 'description' => $say('Land on the board', 'فرود روی برد')],
    ];
    $stepCopy = [
        $say('Pick the name your teammates will see every morning.', 'نامی را بردارید که هم‌تیمی‌ها هر صبح می‌بینند.'),
        $say('Emails can wait — you can invite from settings any time.', 'ایمیل‌ها می‌توانند صبر کنند — از تنظیمات هر وقت خواستید دعوت کنید.'),
        $say('The card is only charged after the 14-day trial.', 'کارت فقط بعد از آزمایشیِ ۱۴ روزه برداشت می‌شود.'),
        $say('The board opens with three samples waiting.', 'برد با سه نمونهٔ آماده باز می‌شود.'),
    ];
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Onboarding, walkable', 'راه‌اندازی اولیه، قابل قدم‌زدن') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Finished steps take the check, the current one is announced with aria-current — go back and forth, the markers keep up.', 'مرحله‌های تمام‌شده تیک می‌گیرند، مرحلهٔ فعلی با aria-current اعلام می‌شود — جلو و عقب بروید، نشانگرها همراه می‌آیند.') }}
        </p>
    </div>
    <x-nx::steps :steps="$wizard" :current="$step" />
    <div class="pg-row" style="justify-content: space-between">
        <p style="margin: 0; color: var(--nx-text-muted)">{{ $stepCopy[$step] }}</p>
        <span class="pg-row">
            <x-nx::button variant="ghost" icon="arrow-left" :disabled="$step === 0 ? 'disabled' : null" wire:click="$set('state.step', {{ $step > 0 ? $step - 1 : 0 }})">{{ $say('Back', 'قبلی') }}</x-nx:button>
            @if ($step < 3)
                <x-nx::button variant="primary" icon-end="arrow-right" wire:click="$set('state.step', {{ $step + 1 }})">{{ $say('Continue', 'ادامه') }}</x-nx::button>
            @else
                <x-nx::button variant="primary" icon="sparkles" wire:click="save('{{ $say('Workspace is live', 'ورک‌اسپیس بالا آمد') }}')">{{ $say('Open the board', 'باز کردن برد') }}</x-nx::button>
            @endif
        </span>
    </div>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Where the parcel is, vertically', 'بسته کجاست، به‌صورت عمودی') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The same markers stacked for order tracking — descriptions carry the timestamps so the page needs no table.', 'همان نشانگرها، روی‌هم برای رهگیری سفارش — توضیح‌ها زمان‌ها را می‌آورند تا صفحه جدول نخواهد.') }}
        </p>
    </div>
    <div class="pg-grid">
        <x-nx::steps orientation="vertical" :current="1" :steps="[
            ['title' => $say('Packed', 'بسته‌بندی شد'), 'description' => $say('Istanbul atelier — 09:12', 'کارگاه استانبول — ۰۹:۱۲')],
            ['title' => $say('On the road', 'در راه است'), 'description' => $say('Courier 4471 — picked up 11:40', 'پیک ۴۴۷۱ — تحویل ۱۱:۴۰')],
            ['title' => $say('Delivered', 'تحویل شد'), 'description' => $say('Expected today by 18:00', 'امروز تا ۱۸:۰۰')],
        ]" />
        <div style="display: grid; gap: .75rem; align-content: start">
            <x-nx::alert tone="info" :title="$say('One more stop before you', 'یک ایستگاه دیگر تا شما')">{{ $say('Leave a note for the courier if the bell is broken.', 'اگر زنگ خراب است برای پیک یادداشت بگذارید.') }}</x-nx::alert>
            <x-nx::button variant="secondary" icon="copy" wire:click="ping('{{ $say('Tracking code copied', 'کد رهگیری کپی شد') }}')">{{ $say('Copy tracking code', 'کپی کد رهگیری') }}</x-nx::button>
        </div>
    </div>
</section>
