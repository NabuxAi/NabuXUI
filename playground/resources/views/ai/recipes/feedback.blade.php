{{-- feedback: the tell-the-user set — alert, ring, toast row, confirm button. --}}
@php $fa = app()->getLocale() === 'fa'; $say = fn (string $e, string $f) => $fa ? $f : $e; @endphp
<div style="max-inline-size: 34rem; margin-inline: auto; display: grid; gap: 1rem">
    <x-nx::alert tone="success" :title="$say('Backup finished', 'پشتیبان‌گیری تمام شد')" icon="check-circle" dismissible>
        {{ $say('1.2 GB copied to the EU bucket in 42 seconds.', '۱٫۲ گیگابایت در ۴۲ ثانیه به بکت اروپا کپی شد.') }}
    </x-nx::alert>
    <x-nx::alert tone="warning" :title="$say('Quota at 80%', 'سهمیه روی ۸۰٪')" icon="alert-triangle">
        {{ $say('Upgrade before the 1st to keep the nightly jobs.', 'برای برجا‌ماندن کارهای شبانه، پیش از اول ارتقا دهید.') }}
    </x-nx::alert>
    <div class="aist-recipe-grid" style="align-items: center">
        <div class="aist-recipe-row" style="gap: .8rem">
            <x-nx::progress-ring :value="72" />
            <div style="display: grid; gap: .15rem">
                <b style="font-size: var(--nx-text-sm)">{{ $say('Uploading', 'در حال بارگذاری') }}</b>
                <span style="font-size: var(--nx-text-xs); color: var(--nx-text-muted)">q4-report.mp4</span>
            </div>
        </div>
        <div class="aist-recipe-row" style="justify-content: end">
            <x-nx::button-status kind="spinner" :label="$say('Saving…', 'در حال ذخیره…')" busy />
            <x-nx::button variant="primary" icon="check">{{ $say('Done', 'تمام') }}</x-nx::button>
        </div>
    </div>
</div>
