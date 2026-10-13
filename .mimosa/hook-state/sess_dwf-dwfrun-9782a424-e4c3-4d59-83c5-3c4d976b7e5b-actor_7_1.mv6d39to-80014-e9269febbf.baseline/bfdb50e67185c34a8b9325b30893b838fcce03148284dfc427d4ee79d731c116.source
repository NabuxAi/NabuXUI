{{--
    Border button's real scenarios: the footer of a document editor (save via
    Livewire — the dashes spin while the request runs — and publish through a
    client-side state machine), then the same button driven from the server.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A document editor’s footer', 'پایین‌بندِ ویرایشگر سند') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Save draft goes to the server (wire:loading spins the dashes, then a toast lands); Publish runs the full machine locally: loading → success or error → back, and every other press fails.', '«ذخیرهٔ پیش‌نویس» به سرور می‌رود (wire:loading نقطه‌چین‌ها را می‌چرخاند و بعد توست می‌آید)؛ «انتشار» کل ماشین‌حالت را محلی اجرا می‌کند: لودینگ ← موفقیت یا خطا ← برگشت، و هر بار دیگری خطا می‌دهد.') }}
        </p>
    </div>
    <div class="pg-row" style="justify-content: space-between">
        <div class="pg-row">
            <x-nx::border-button icon="file" wire:click="save(@js($say('Draft saved', 'پیش‌نویس ذخیره شد')))" :success-label="$say('Saved', 'ذخیره شد')">
                {{ $say('Save draft', 'ذخیرهٔ پیش‌نویس') }}
            </x-nx::border-button>
            <div x-data="{ status: null, fail: false }">
                <x-nx::border-button :success-label="$say('Published', 'منتشر شد')" :error-label="$say('Try again', 'دوباره امتحان کنید')" wire:ignore.self
                    x-bind:data-status="status" x-bind:aria-busy="status === 'loading' ? 'true' : null"
                    x-on:click="if (status) return; status = 'loading'; setTimeout(() => { status = fail ? 'error' : 'success'; fail = ! fail }, 1400); setTimeout(() => status = null, 3400)">
                    {{ $say('Publish', 'انتشار') }}
                </x-nx::border-button>
            </div>
        </div>
        <x-nx::border-button size="sm" icon="trash" wire:click="ping(@js($say('Moved to trash', 'به زباله رفت')))">
            {{ $say('Delete', 'حذف') }}
        </x-nx::border-button>
    </div>
</section>

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Driven from the server', 'هدایت از سرور') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('The same button with :status from a Livewire property — sync endpoints that finish on their own schedule.', 'همان دکمه با :status از یک پراپرتی Livewire — برای همگام‌سازی‌هایی که در زمان خودشان تمام می‌شوند.') }}
        </p>
        <div class="pg-row">
            <x-nx::border-button :status="$state['sync'] ?? null" :success-label="$say('Synced', 'همگام شد')">{{ $say('Sync with GitHub', 'همگام‌سازی با گیت‌هاب') }}</x-nx::border-button>
        </div>
        <div class="pg-row">
            <x-nx::button size="sm" variant="ghost" wire:click="$set('state.sync', 'success')">{{ $say('Finish well', 'موفق تمام شود') }}</x-nx::button>
            <x-nx::button size="sm" variant="ghost" wire:click="$set('state.sync', 'error')">{{ $say('Fail', 'خطا بدهد') }}</x-nx::button>
            <x-nx::button size="sm" variant="ghost" wire:click="$set('state.sync', null)">{{ $say('Reset', 'بازنشانی') }}</x-nx::button>
        </div>
    </section>

    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Reading the states', 'خواندن حالت‌ها') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('Nothing is motion-only: while it works the dashes spin, when it lands the ring closes into a check, and when it fails the frame shakes and reddens — with the label read out politely.', 'هیچ چیزی فقط حرکت نیست: حین کار نقطه‌چین‌ها می‌چرخند، در موفقیت حلقه با تیک بسته می‌شود و در خطا قاب می‌لرزد و سرخ می‌شود — و برچسب مؤدبانه خوانده می‌شود.') }}
        </p>
        <div class="pg-row" style="gap: 1.5rem">
            <x-nx::border-button status="loading" wire:ignore.self>{{ $say('Working…', 'در حال کار…') }}</x-nx::border-button>
            <x-nx::border-button status="success" :success-label="$say('Done', 'انجام شد')">{{ $say('Done', 'انجام شد') }}</x-nx::border-button>
            <x-nx::border-button status="error" :error-label="$say('Failed', 'ناموفق')">{{ $say('Failed', 'ناموفق') }}</x-nx::border-button>
        </div>
    </section>
</div>
