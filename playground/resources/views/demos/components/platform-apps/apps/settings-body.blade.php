{{--
    The settings app's shared body — included as the default slot of every
    <x-nx::app-window> on the settings demo page (all four platform skins).
    The in-window navigation (a radio group styled as a rail) plus rows of
    native switches and selects: sample data only, no backend, but every
    control really works in whatever shell it lands in. Scoped under
    .pst-*; tokens only, no Blade inside the style block.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $sections = [
        ['id' => 'general', 'label' => $say('General', 'عمومی'), 'icon' => 'settings'],
        ['id' => 'alerts', 'label' => $say('Alerts', 'اعلان‌ها'), 'icon' => 'bell'],
        ['id' => 'storage', 'label' => $say('Storage', 'ذخیره‌سازی'), 'icon' => 'folder'],
    ];

    // Rows per section: a native switch (checkbox) or a select, with a hint.
    $rows = [
        'general' => [
            ['kind' => 'switch', 'label' => $say('Start with the app open', 'با باز بودن اپ شروع شود'), 'hint' => $say('Remember the last note', 'آخرین یادداشت را به یاد داشته باش'), 'on' => true],
            ['kind' => 'switch', 'label' => $say('Compact spacing', 'فاصله‌های فشرده'), 'hint' => $say('More rows on small windows', 'ردیف‌های بیشتر در پنجره‌های کوچک'), 'on' => false],
            ['kind' => 'select', 'label' => $say('Language', 'زبان'), 'hint' => $say('Interface language', 'زبان رابط'), 'options' => [$say('Persian', 'فارسی'), $say('English', 'انگلیسی')], 'picked' => $fa ? 0 : 1],
            ['kind' => 'select', 'label' => $say('Appearance', 'نمای برنامه'), 'hint' => $say('Light, dark, or follow the system', 'روشن، تیره، یا هماهنگ با سیستم'), 'options' => [$say('Light', 'روشن'), $say('Dark', 'تیره'), $say('System', 'هماهنگ با سیستم')], 'picked' => 2],
            ['kind' => 'switch', 'label' => $say('Spellcheck', 'غلط‌یاب'), 'hint' => $say('Squiggles while you type', 'خط‌های موجی حین نوشتن'), 'on' => true],
        ],
        'alerts' => [
            ['kind' => 'switch', 'label' => $say('Mentions only', 'فقط منشن‌ها'), 'hint' => $say('Skip the rest of the noise', 'بقیهٔ هیاهو را رها کن'), 'on' => true],
            ['kind' => 'switch', 'label' => $say('Quiet hours', 'ساعت‌های سکوت'), 'hint' => $say('22:00 – 08:00', '۲۲:۰۰ – ۰۸:۰۰'), 'on' => true],
            ['kind' => 'switch', 'label' => $say('Daily digest', 'چکیدهٔ روزانه'), 'hint' => $say('One email each morning', 'هر صبح یک ایمیل'), 'on' => false],
        ],
        'storage' => [
            ['kind' => 'select', 'label' => $say('Keep notes for', 'نگه‌داشت یادداشت‌ها'), 'hint' => $say('Then they archive themselves', 'بعد خودشان بایگانی می‌شوند'), 'options' => [$say('30 days', '۳۰ روز'), $say('A year', 'یک سال'), $say('Forever', 'همیشه')], 'picked' => 2],
            ['kind' => 'switch', 'label' => $say('Sync over cellular', 'همگام‌سازی با دیتای موبایل'), 'hint' => $say('Off on the road', 'در سفر خاموش'), 'on' => false],
            ['kind' => 'switch', 'label' => $say('Empty trash on exit', 'خالی‌کردن سطل هنگام خروج'), 'hint' => $say('No archaeology', 'کاوشی در کار نیست'), 'on' => true],
        ],
    ];
@endphp
<style>
    .pst-body { display: flex; flex-wrap: wrap; gap: .875rem; }
    .pst-rail { flex: 1 1 7rem; display: grid; gap: .25rem; align-content: start; }
    .pst-rail-label { margin: 0 0 .125rem; padding-inline: .3rem; font: 600 var(--nx-text-xs) / 1.4 var(--nx-font-sans);
                      letter-spacing: var(--nx-tracking-wide); color: var(--nx-text-subtle); }
    .pst-tab { display: inline-flex; align-items: center; gap: .5rem; padding: .5rem .7rem; border: 0;
               border-radius: var(--nx-radius-md); background: transparent; color: var(--nx-text-muted);
               font: 600 var(--nx-text-sm) / 1.4 var(--nx-font-sans); text-align: start; cursor: pointer;
               transition-property: scale, background-color, color; transition-duration: var(--nx-dur-fast); }
    .pst-tab:hover { background: var(--nx-surface-2); color: var(--nx-text); }
    .pst-tab:active { scale: calc(1 - 0.04 * var(--nx-motion)); }
    .pst-tab:focus-visible { outline: 2px solid var(--nx-ring); outline-offset: 2px; }
    .pst-tab[aria-pressed='true'] { background: var(--nx-accent-soft); color: var(--nx-accent-text); }
    .pst-tab .nx-icon { inline-size: 1rem; block-size: 1rem; }
    .pst-rows { flex: 3 1 14rem; display: grid; gap: .5rem; align-content: start; }
    .pst-row { display: flex; gap: .75rem; align-items: center; justify-content: space-between;
               padding: .65rem .8rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg);
               background: var(--nx-surface-2); }
    .pst-row-text { display: grid; gap: .15rem; min-inline-size: 0; }
    .pst-row-label { font: 600 var(--nx-text-sm) / 1.4 var(--nx-font-sans); color: var(--nx-text); }
    .pst-row-hint { font-size: var(--nx-text-xs); color: var(--nx-text-muted); }
    .pst-row input[type='checkbox'] { flex: none; inline-size: 1.15rem; block-size: 1.15rem; accent-color: var(--nx-accent); cursor: pointer; }
    .pst-row select { flex: none; max-inline-size: 9rem; padding: .35rem .55rem; border: 1px solid var(--nx-border);
                      border-radius: var(--nx-radius-md); background: var(--nx-surface); color: var(--nx-text);
                      font: 500 var(--nx-text-sm) / 1.4 var(--nx-font-sans); cursor: pointer; }
    .pst-row select:focus-visible, .pst-row input:focus-visible { outline: 2px solid var(--nx-ring); outline-offset: 1px; }
    .pst-count { display: flex; gap: .5rem; align-items: center; justify-content: space-between;
                 font-size: var(--nx-text-xs); color: var(--nx-text-subtle); }
    .pst-meter { flex: 1; block-size: .3rem; border-radius: var(--nx-radius-full); background: var(--nx-surface-3); overflow: clip; }
    .pst-meter span { display: block; block-size: 100%; border-radius: inherit;
                      background: linear-gradient(90deg, var(--nx-accent), var(--nx-violet-500)); }
</style>
<div class="pst-body" x-data="{ section: 'general' }">
    <div class="pst-rail" role="group" aria-label="{{ $say('Settings sections', 'بخش‌های تنظیمات') }}">
        <p class="pst-rail-label">{{ $say('Settings', 'تنظیمات') }}</p>
        @foreach ($sections as $section)
            <button type="button" class="pst-tab" @if ($loop->first) aria-pressed="true" @endif
                x-bind:aria-pressed="section === @js($section['id']) ? 'true' : 'false'"
                x-on:click="section = @js($section['id'])">
                {{ NabuXUI::icon($section['icon']) }}<span>{{ $section['label'] }}</span>
            </button>
        @endforeach
    </div>
    <div class="pst-rows">
        @foreach ($rows as $sectionId => $sectionRows)
            <div x-show="section === @js($sectionId)" @if ($sectionId !== 'general') x-cloak @endif style="display: grid; gap: .5rem">
                @foreach ($sectionRows as $row)
                    <label class="pst-row">
                        <span class="pst-row-text">
                            <span class="pst-row-label">{{ $row['label'] }}</span>
                            <span class="pst-row-hint">{{ $row['hint'] }}</span>
                        </span>
                        @if ($row['kind'] === 'switch')
                            <input type="checkbox" @if ($row['on']) checked @endif aria-label="{{ $row['label'] }}">
                        @else
                            <select aria-label="{{ $row['label'] }}">
                                @foreach ($row['options'] as $optionIndex => $option)
                                    <option value="{{ $optionIndex }}" @if ($optionIndex === (int) $row['picked']) selected @endif>{{ $option }}</option>
                                @endforeach
                            </select>
                        @endif
                    </label>
                @endforeach
                @if ($sectionId === 'storage')
                    <p class="pst-count">
                        <span>{{ $say('Cloud backup', 'پشتیبان ابری') }}</span>
                        <span class="pst-meter" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="64" aria-label="{{ $say('Backup space used', 'فضای پشتیبان استفاده‌شده') }}"><span style="inline-size: 64%"></span></span>
                        <span>{{ NabuXUI::formatNumber(64) }}٪</span>
                    </p>
                @endif
            </div>
        @endforeach
    </div>
</div>
