{{--
    صفحهٔ «فایل‌ها» — App\Filament\Pages\FileManager. یک درخت نمونهٔ
    پوشه/فایل (آرایهٔ ساده در PHP، بدون دیتابیس) داخل <x-nx::file-manager>.
    جابه‌جایی پوشه‌ها و نان‌ریز مسیر با Alpine داخلی خود کامپوننت کار
    می‌کند؛ چهار رویدادی که بلوک بروی صفحه dispatch می‌کند
    (nx-navigate، nx-open، nx-view، nx-upload) به اکشن‌های Livewire همین
    صفحه وصل‌اند و کارت پایین ثبت‌شان می‌کند.
--}}
<x-filament-panels::page>
    <div class="fm-page">
        <p class="fm-page-intro">
            درخت نمونهٔ همین صفحه — سه سطح عمق («پروژه‌ها / پروژهٔ استانبول / مودبورد»)،
            ۲ پوشهٔ ریشه و ۲ پوشهٔ تو در تو، با پیش‌نمایش تصویری، صدا و ویدئو — بدون دیتابیس،
            از یک آرایهٔ ساده در PHP. کلیک روی پوشه مسیر را با نان‌ریزهای بالای برد باز می‌کند
            (کار Alpine داخلی کامپوننت است) و هر رویدادش به اکشن‌های Livewire همین صفحه خبر می‌دهد.
        </p>

        <x-nx::file-manager
            :items="$this->tree()"
            :current="$currentFolder"
            :view="$viewMode"
            label="فضای کاری استودیو"
            search-placeholder="جست‌وجو در همین پوشه…"
            empty-text="این پوشه خالی است — نخستین فایل را با دکمهٔ بارگذاری بگذارید."
            height="34rem"
            x-on:nx-navigate="$wire.trackFolder($event.detail.folder)"
            x-on:nx-open="$wire.trackFile($event.detail.entry)"
            x-on:nx-view="$wire.trackView($event.detail.view)"
            x-on:nx-upload="$wire.noteUpload($event.detail.files.length)"
        />

        <x-nx::card
            title="وضعیت زندهٔ صفحه"
            description="نان‌ریز و جابه‌جایی پوشه خودِ کامپوننت را می‌برد؛ این کارت فقط ثبت می‌کند که رویدادها به Livewire رسیده‌اند."
        >
            <dl class="fm-page-status">
                <div>
                    <dt>پوشهٔ جاری</dt>
                    <dd>{{ $this->folderTrail() }}</dd>
                </div>
                <div>
                    <dt>فایل باز (کشوی جزئیات)</dt>
                    <dd>{{ $this->fileName($selectedFile) }}</dd>
                </div>
                <div>
                    <dt>نمای فعلی</dt>
                    <dd>{{ $viewMode === 'list' ? 'فهرستی' : 'گرید' }}</dd>
                </div>
                <div>
                    <dt>آخرین انتخاب بارگذاری</dt>
                    <dd>{{ $pickedFiles === null ? '—' : \NabuXUI\NabuXUI::formatNumber($pickedFiles, 0).' فایل' }}</dd>
                </div>
            </dl>
        </x-nx::card>
    </div>
</x-filament-panels::page>

<style>
    .fm-page { display: grid; gap: var(--nx-space-4); }
    .fm-page-intro { margin: 0; max-inline-size: 48rem; color: var(--nx-text-muted); }
    .fm-page-status {
        margin: 0;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(min(100%, 16rem), 1fr));
        gap: var(--nx-space-3);
    }
    .fm-page-status dt { font: 500 var(--nx-text-sm) / 1.4 var(--nx-font-body); color: var(--nx-text-muted); }
    .fm-page-status dd { margin: .25rem 0 0; font: 600 var(--nx-text-base) / 1.5 var(--nx-font-body); color: var(--nx-text); overflow-wrap: anywhere; }
</style>
