{{--
    The status badge's real scenarios: a job list speaking Persian through the
    labels map, then a deployment monitor that morphs a live badge from
    Livewire buttons.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $words = $fa
        ? ['running' => 'در حال اجرا', 'success' => 'موفق', 'failed' => 'ناموفق', 'queued' => 'در صف', 'canceled' => 'لغو‌شده']
        : [];
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The nightly jobs, named in your words', 'کارهای شبانه، با واژه‌های خودتان') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Five states, one pill each — the labels map swaps the built-in words for yours, and every pill’s width settles with its own label.', 'پنج وضعیت، هرکدام یک قرص — فرهنگ labels واژه‌های ساخته‌شده را با واژه‌های شما عوض می‌کند و عرض هر قرص با برچسب خودش می‌ایستد.') }}
        </p>
    </div>
    <ul style="list-style: none; margin: 0; padding: 0; display: grid; gap: .6rem">
        @foreach ([
            ['queued', $say('Ingest today’s tickets', 'دریافت تیکت‌های امروز')],
            ['running', $say('Re-embed the knowledge base', 'بازجاسازی پایهٔ دانش')],
            ['success', $say('Nightly database backup', 'پشتیبان‌گیری شبانهٔ پایگاه‌داده')],
            ['failed', $say('Exchange rates refresh', 'به‌روزرسانی نرخ ارز')],
            ['canceled', $say('Stale-report cleanup', 'پاک‌سازی گزارش‌های قدیمی')],
        ] as [$status, $job])
            <li class="pg-row" style="justify-content: space-between; padding: .65rem 1rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg); background: var(--nx-surface-2)">
                <span style="font-weight: 550">{{ $job }}</span>
                <x-nx::status-badge :status="$status" :labels="$words" />
            </li>
        @endforeach
    </ul>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A deployment, as it happens', 'یک استقرار، همان لحظه که رخ می‌دهد') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('live gives the pill role="status" so each change is spoken; the buttons flip a Livewire property and the morphing badge follows it — no page turn, the pill simply reshapes.', 'live به قرص role="status" می‌دهد تا هر تغییر خوانده شود؛ دکمه‌ها یک پراپرتی Livewire را می‌چرخانند و نشانِ مورف‌شونده دنبالش می‌رود — بدون برگشتن صفحه، قرص فقط شکل عوض می‌کند.') }}
        </p>
    </div>
    <div class="pg-row">
        <span style="font-size: var(--nx-text-sm); color: var(--nx-text-muted)">{{ $say('Release v2.5.0', 'انتشار نسخهٔ ۲٫۵٫۰') }}</span>
        <x-nx::status-badge :status="$state['job'] ?? 'queued'" live :labels="$words" size="lg" />
        <span style="flex: 1"></span>
        <x-nx::button size="xs" variant="ghost" wire:click="$set('state.job', 'running')">{{ $say('Run', 'اجرا') }}</x-nx::button>
        <x-nx::button size="xs" variant="ghost" wire:click="$set('state.job', 'success')">{{ $say('Succeed', 'موفق') }}</x-nx::button>
        <x-nx::button size="xs" variant="ghost" wire:click="$set('state.job', 'failed')">{{ $say('Fail', 'شکست') }}</x-nx:button>
        <x-nx::button size="xs" variant="ghost" wire:click="$set('state.job', 'canceled')">{{ $say('Cancel', 'لغو') }}</x-nx:button>
        <x-nx::button size="xs" variant="ghost" wire:click="$set('state.job', 'queued'); ping(@js($say('Back in the queue', 'دوباره در صف')))">{{ $say('Requeue', 'بازصف') }}</x-nx:button>
    </div>
</section>
