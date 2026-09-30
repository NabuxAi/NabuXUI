{{--
    The progress bar as an upload manager: known percentages, a failed one in
    danger red, an indeterminate "preparing" — and a re-check that swaps in an
    indeterminate bar for the duration of the round trip.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $uploads = [
        ['name' => 'launch-teaser.mp4', 'pct' => 100, 'tone' => 'success', 'note' => $say('Ready — 214 MB', 'آماده — ۲۱۴ مگابایت')],
        ['name' => 'brand-plate.png', 'pct' => 67, 'tone' => null, 'note' => $say('4.1 of 6.1 MB', '۴٫۱ از ۶٫۱ مگابایت')],
        ['name' => 'voice-over.wav', 'pct' => 34, 'tone' => 'danger', 'note' => $say('Connection lost at 34%', 'اتصال در ۳۴٪ قطع شد')],
    ];
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The upload tray', 'سینی آپلود') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Done reads green, a stalled one wears danger, and the newest file has no number yet — an indeterminate bar tells the truth better than a fake 3%.', 'تمام‌شده سبز است، ناتمامِ گیرکرده danger می‌پوشد و تازه‌ترین فایل هنوز عددی ندارد — نوار نامعین از یک ۳٪ ساختگی صادق‌تر است.') }}
        </p>
    </div>
    <ul style="list-style: none; margin: 0; padding: 0; display: grid; gap: 1rem">
        @foreach ($uploads as $upload)
            <li style="display: grid; gap: .4rem">
                <div class="pg-row" style="justify-content: space-between">
                    <span class="pg-row" style="gap: .6rem">
                        <x-nx::icon name="upload" />
                        <strong dir="ltr" style="font-weight: 600; font: 500 var(--nx-text-sm) var(--nx-font-mono)">{{ $upload['name'] }}</strong>
                    </span>
                    <span class="pg-row" style="gap: .75rem">
                        <span style="color: var(--nx-text-muted); font-size: var(--nx-text-sm)">{{ $upload['note'] }}</span>
                        @if ($upload['tone'] === 'danger')
                            <x-nx::button size="xs" variant="ghost" icon="play" wire:click="save('{{ $say('Retrying voice-over.wav', 'تلاش دوباره برای voice-over.wav') }}')">{{ $say('Retry', 'دوباره') }}</x-nx::button>
                        @endif
                    </span>
                </div>
                <x-nx::progress :value="$upload['pct']" :tone="$upload['tone']" :label="$upload['name']" />
            </li>
        @endforeach
        <li style="display: grid; gap: .4rem">
            <div class="pg-row" style="justify-content: space-between">
                <span class="pg-row" style="gap: .6rem">
                    <x-nx::icon name="file" />
                    <strong dir="ltr" style="font: 500 var(--nx-text-sm) var(--nx-font-mono)">poster-final.pdf</strong>
                </span>
                <span style="color: var(--nx-text-muted); font-size: var(--nx-text-sm)">{{ $say('Preparing…', 'در حال آماده‌سازی…') }}</span>
            </div>
            <x-nx::progress :label="$say('Preparing poster-final.pdf', 'آماده‌سازی poster-final.pdf')" />
        </li>
    </ul>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Quota bars, and a re-check in flight', 'نوارهای سهمیه، و بازبینیِ در جریان') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Tones carry meaning here: brand blue for the team plan, warning at 80% — and while the server recounts, an indeterminate bar stands in.', 'رنگ‌ها معنا دارند: آبیِ برند برای پلن تیم، هشدار در ۸۰٪ — و تا سرور دوباره بشمارد، نوار نامعین جایش می‌ایستد.') }}
        </p>
    </div>
    <div style="display: grid; gap: 1rem">
        <div style="display: grid; gap: .4rem">
            <div class="pg-row" style="justify-content: space-between">
                <span>{{ $say('Storage', 'فضای ذخیره') }}</span>
                <span style="color: var(--nx-text-muted); font-size: var(--nx-text-sm)">{{ NabuXUI::formatNumber(41.2, 1).' '.($fa ? 'از ۶۰ گیگابایت' : 'of 60 GB') }}</span>
            </div>
            <x-nx::progress :value="69" tone="brand" label="{{ $say('Storage', 'فضای ذخیره') }}" />
        </div>
        <div style="display: grid; gap: .4rem">
            <div class="pg-row" style="justify-content: space-between">
                <span>{{ $say('Build minutes', 'دقیقه‌های بیلد') }}</span>
                <span style="color: var(--nx-text-warning-text); font-size: var(--nx-text-sm)">{{ NabuXUI::formatNumber(1610).' '.($fa ? 'از ۲۰۰۰ دقیقه' : 'of 2,000 min') }}</span>
            </div>
            <x-nx::progress :value="80" tone="warning" label="{{ $say('Build minutes', 'دقیقه‌های بیلد') }}" />
        </div>
    </div>
    <div class="pg-row" style="justify-content: space-between">
        <div style="display: grid; gap: .4rem; flex: 1">
            <x-nx::progress wire:loading wire:target="save" :label="$say('Recounting usage', 'شمارش دوبارهٔ مصرف')" />
            <p wire:loading.remove wire:target="save" style="margin: 0; color: var(--nx-text-subtle); font-size: var(--nx-text-sm)">{{ $say('Numbers as of the last visit.', 'اعداد از آخرین بازدید.') }}</p>
        </div>
        <x-nx::button variant="secondary" icon="play" wire:click="save('{{ $say('Usage recounted', 'مصرف دوباره شمرده شد') }}')">{{ $say('Re-check now', 'همین حالا بشمار') }}</x-nx::button>
    </div>
</section>
