{{--
    The status badge where it was born: a deploy pipeline. The ledger shows all
    five states side by side; below, one row is wired to Livewire so you can
    drive it yourself and watch the icon and width morph.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $labels = [
        'fa' => ['running' => 'در حال دپلوی', 'success' => 'موفق', 'failed' => 'ناموفق', 'queued' => 'در صف', 'canceled' => 'لغو شد'],
        'en' => ['running' => 'Deploying', 'success' => 'Succeeded', 'failed' => 'Failed', 'queued' => 'Queued', 'canceled' => 'Canceled'],
    ][$fa ? 'fa' : 'en'];

    $pipelines = [
        ['name' => 'nabu-web', 'branch' => 'main', 'status' => 'success', 'ago' => $say('18 min ago', '۱۸ دقیقه پیش')],
        ['name' => 'nabu-api', 'branch' => 'main', 'status' => 'running', 'ago' => $say('started 40s ago', 'شروع ۴۰ ثانیه پیش')],
        ['name' => 'checkout', 'branch' => 'feat/gift-cards', 'status' => 'failed', 'ago' => $say('1 h ago', '۱ ساعت پیش')],
        ['name' => 'docs', 'branch' => 'main', 'status' => 'queued', 'ago' => $say('waiting for runner', 'در انتظار رانر')],
        ['name' => 'mobile', 'branch' => 'release/4', 'status' => 'canceled', 'ago' => $say('by مریم', 'توسط مریم')],
    ];

    $pipeline = (string) ($state['pipeline'] ?? 'running');
    $allowed = ['running', 'success', 'failed', 'queued', 'canceled'];
    $pipeline = in_array($pipeline, $allowed, true) ? $pipeline : 'running';
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The deploy ledger', 'دفتر دپلوی‌ها') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('All five states in one honest list — labels localize with the page, and the running one announces itself politely.', 'هر پنج وضعیت در یک فهرست صادق — برچسب‌ها با زبان صفحه می‌آیند و «در حال اجرا» مؤدبانه خودش را معرفی می‌کند.') }}
        </p>
    </div>
    <ul style="list-style: none; margin: 0; padding: 0; display: grid; gap: .5rem">
        @foreach ($pipelines as $job)
            <li class="pg-row" style="justify-content: space-between; padding: .6rem .9rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg)">
                <span class="pg-row" style="gap: .75rem">
                    <x-nx::icon name="grid" />
                    <strong style="font-weight: 600" dir="ltr">{{ $job['name'] }}</strong>
                    <code dir="ltr" style="font: 500 var(--nx-text-xs) var(--nx-font-mono); color: var(--nx-text-muted)">{{ $job['branch'] }}</code>
                </span>
                <span class="pg-row" style="gap: .75rem">
                    <span style="color: var(--nx-text-subtle); font-size: var(--nx-text-sm)">{{ $job['ago'] }}</span>
                    <x-nx::status-badge :status="$job['status']" :labels="$labels" :live="$job['status'] === 'running'" />
                </span>
            </li>
        @endforeach
    </ul>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Drive one yourself', 'یکی را خودتان برانید') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The badge follows its data-status attribute, so a Livewire re-render is all the choreography it needs — the icon swaps, the width settles on the new label.', 'نشان دنبال data-status می‌رود، پس یک رندر دوبارهٔ Livewire تمام رقصش است — آیکون عوض می‌شود و عرض روی برچسب تازه جا می‌گیرد.') }}
        </p>
    </div>
    <div class="pg-row" style="justify-content: space-between; padding: .9rem; border: 1px dashed var(--nx-border-strong); border-radius: var(--nx-radius-lg)">
        <span class="pg-row" style="gap: .75rem">
            <strong style="font-weight: 600" dir="ltr">release/5</strong>
            <x-nx::status-badge :status="$pipeline" :labels="$labels" live />
        </span>
        <span class="pg-row">
            <x-nx::button size="sm" variant="secondary" icon="play" wire:click="$set('state.pipeline', 'running')">{{ $say('Run', 'اجرا') }}</x-nx::button>
            <x-nx::button size="sm" variant="secondary" icon="check" wire:click="$set('state.pipeline', 'success')">{{ $say('Pass', 'موفق') }}</x-nx::button>
            <x-nx::button size="sm" variant="secondary" icon="x" wire:click="$set('state.pipeline', 'failed')">{{ $say('Fail', 'شکست') }}</x-nx::button>
            <x-nx::button size="sm" variant="ghost" wire:click="$set('state.pipeline', 'canceled')">{{ $say('Cancel', 'لغو') }}</x-nx::button>
        </span>
    </div>
</section>
