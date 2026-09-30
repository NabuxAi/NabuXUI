{{--
    The toaster demoed from both sides: the browser fires tones straight at it
    with the $nxToast magic, and one button goes through Livewire — the exact
    call every component in this catalog makes.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Fire them yourself', 'خودتان شلیک کنید') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Toasts stack up in the corner; hover them and the timers pause, swipe one away and it goes. The danger ones outstay the rest on purpose.', 'توست‌ها در گوشه تلر می‌شوند؛ با hover زمان‌ها می‌ایستند و سوایپ یکی را می‌برد. danger عمداً بیشتر می‌ماند.') }}
        </p>
    </div>
    <div class="pg-row" x-data>
        <x-nx::button variant="primary" icon="check" @click="$nxToast.success('{{ $say('Invoice 1404 paid', 'فاکتور ۱۴۰۴ پرداخت شد') }}')">{{ $say('Success', 'موفق') }}</x-nx::button>
        <x-nx::button variant="secondary" icon="info" @click="$nxToast.info('{{ $say('Maintenance at 02:00', 'سرویس ساعت ۰۲:۰۰') }}', '{{ $say('About 4 minutes.', 'حدود ۴ دقیقه.') }}')">{{ $say('Info', 'خبر') }}</x-nx::button>
        <x-nx::button variant="secondary" icon="alert-triangle" @click="$nxToast.warning('{{ $say('Storage at 91%', 'فضای ذخیره در ۹۱٪') }}')">{{ $say('Warning', 'هشدار') }}</x-nx::button>
        <x-nx::button variant="danger" icon="alert-circle" @click="$nxToast.error('{{ $say('Deploy failed on checkout', 'دپلوی روی checkout شکست خورد') }}')">{{ $say('Danger', 'خطر') }}</x-nx::button>
        <x-nx::button variant="ghost" icon="bell" @click="$nxToast('{{ $say('مریم mentioned you', 'مریم شما را منشن کرد') }}')">{{ $say('Neutral', 'خنثی') }}</x-nx::button>
    </div>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('From the server side', 'از سمت سرور') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The same stack, fed by Livewire: this button runs the slow action every demo here uses, and the toast is its receipt — description and all.', 'همان تلر، این بار از Livewire: این دکمه همان اکشن کند همهٔ دموهای این‌جاست و توست رسیدش است — با توضیح و تمام.') }}
        </p>
    </div>
    <div class="pg-row">
        <x-nx::button variant="primary" icon="upload" wire:click="save('{{ $say('Backup taken', 'پشتیبان گرفته شد') }}')">{{ $say('Run a slow job', 'یک کار کند اجرا کن') }}</x-nx::button>
        <x-nx::button variant="ghost" wire:click="ping('{{ $say('Nothing sent', 'چیزی ارسال نشد') }}')">{{ $say('A quiet ping', 'یک پینگ آرام') }}</x-nx::button>
    </div>
</section>

<section class="pg-box" style="gap: 1rem">
    <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The one-liner you will actually write', 'همان یک‌خطی که واقعاً می‌نویسید') }}</h3>
    <p style="margin: 0; color: var(--nx-text-muted)">{{ $say('One component in the layout, one call anywhere. That is the whole integration.', 'یک کامپوننت در layout، یک فراخوانی هرجا. تمامِ اتصال همین است.') }}</p>
    <pre dir="ltr" style="margin: 0; padding: 1rem 1.25rem; overflow-x: auto; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg); background: var(--nx-surface-2); font: 500 var(--nx-text-sm) / 1.7 var(--nx-font-mono)"><code>// layout — once
&lt;x-nx::toaster /&gt;

// any Livewire component
$this-&gt;toast('پرداخت شد', 'فاکتور ۱۴۰۴ بسته شد', tone: 'success');

// any browser code
NabuXUI.toast.info('Maintenance at 02:00');</code></pre>
</section>
