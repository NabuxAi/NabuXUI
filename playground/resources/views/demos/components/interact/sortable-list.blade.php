{{--
    Sortable list's real scenarios: a release checklist whose order goes to
    the server (the toast reports it), a playlist reordered only in the
    browser with the order shown live, and Persian announcements.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $steps = [
        ['id' => 'freeze', 'label' => $say('Code freeze', 'توقف کد'), 'description' => $say('main is locked for merges', 'ادغام در main بسته است'), 'meta' => $say('Mon', 'دوشنبه'), 'icon' => 'lock'],
        ['id' => 'qa', 'label' => $say('QA pass', 'آزمون کیفیت'), 'description' => $say('Regression suite on staging', 'مجموعهٔ رگرسیون روی استیجینگ'), 'meta' => $say('Tue', 'سه‌شنبه'), 'icon' => 'shield'],
        ['id' => 'notes', 'label' => $say('Release notes', 'یادداشت انتشار'), 'description' => $say('Changelog and screenshots', 'تغییرات و تصاویر'), 'meta' => $say('Wed', 'چهارشنبه'), 'icon' => 'file'],
        ['id' => 'ship', 'label' => $say('Ship to production', 'انتشار روی تولید'), 'description' => $say('Blue-green switch', 'سوییچ آبی-سبز'), 'meta' => $say('Thu', 'پنجشنبه'), 'icon' => 'zap'],
        ['id' => 'announce', 'label' => $say('Announce', 'اطلاع‌رسانی'), 'description' => $say('Email and social', 'ایمیل و شبکه‌های اجتماعی'), 'meta' => $say('Thu', 'پنجشنبه'), 'icon' => 'bell'],
    ];
    $messages = $fa ? [
        'grabbed' => '{name} برداشته شد. جایگاه {position} از {total}. با جهت‌ها جابه‌جا کنید، Space برای گذاشتن، Escape برای لغو.',
        'moved' => '{name} به جایگاه {position} از {total} رفت.',
        'dropped' => '{name} در جایگاه {position} از {total} گذاشته شد.',
        'cancelled' => 'جابه‌جایی لغو شد. {name} به جایگاه {position} از {total} برگشت.',
    ] : [];
    $handle = $say('Reorder {name}', 'جابه‌جایی {name}');
    $instructions = $say('Press Space to pick up, the arrow keys to move, Space again to drop, Escape to cancel.', 'Space برای برداشتن، جهت‌ها برای جابه‌جایی، دوباره Space برای گذاشتن، Escape برای لغو.');
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Plan the release', 'برنامهٔ انتشار') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Drag a row by its handle: the others step aside as it passes, a pull past the ends rubber-bands, and the drop springs everything into place. From the keyboard: Tab to a handle, Space, arrows, Space. The new order is reported to the server.', 'سطری را از دستگیره‌اش بکشید: بقیه هنگام عبورش کنار می‌روند، کشیدن از دو سر کش می‌آید و رهاکردن همه را با فنر سر جایشان می‌نشاند. با کیبورد: Tab تا دستگیره، Space، جهت‌ها، Space. ترتیب تازه به سرور گزارش می‌شود.') }}
        </p>
    </div>
    <div style="max-inline-size: 36rem">
        <x-nx::sortable-list :items="$steps" :label="$say('Release checklist', 'چک‌لیست انتشار')" :messages="$messages" :handle-label="$handle" :instructions="$instructions"
            x-on:nx-sort="$wire.ping({{ \Illuminate\Support\Js::from($say('New order saved', 'ترتیب تازه ذخیره شد')) }})" />
    </div>
</section>

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem" x-data="{ order: {{ \Illuminate\Support\Js::from(['intro', 'track', 'bridge', 'outro']) }} }">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A playlist, in the browser', 'یک پلی‌لیست، در مرورگر') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('No server here: the nx-sort event hands Alpine the ids in their new order.', 'این‌جا سروری نیست: رویداد nx-sort شناسه‌ها را به ترتیب تازه به Alpine می‌دهد.') }}
        </p>
        <x-nx::sortable-list x-on:nx-sort="order = $event.detail" :messages="$messages" :handle-label="$handle" :instructions="$instructions" :label="$say('Playlist', 'پلی‌لیست')" :items="[
            ['id' => 'intro', 'label' => $say('Morning in Shiraz', 'صبح شیراز'), 'meta' => '3:12', 'icon' => 'music'],
            ['id' => 'track', 'label' => $say('Caravan', 'کاروان'), 'meta' => '4:48', 'icon' => 'music'],
            ['id' => 'bridge', 'label' => $say('Rain on the bazaar', 'باران روی بازار'), 'meta' => '2:57', 'icon' => 'music'],
            ['id' => 'outro', 'label' => $say('Night train', 'قطار شب'), 'meta' => '5:05', 'icon' => 'music'],
        ]" />
        <code dir="ltr" style="color: var(--nx-text-muted); font-size: var(--nx-text-sm)" x-text="order.join(' → ')"></code>
    </section>

    <section class="pg-box" dir="rtl" lang="fa" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Right to left', 'راست‌به‌چپ') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('Persian announcements with Persian position numbers; the handle sits at the inline start.', 'اعلام‌های فارسی با شماره‌جایگاه فارسی؛ دستگیره در ابتدای سطر می‌نشیند.') }}
        </p>
        <x-nx::sortable-list label="اولویت‌ها" handle-label="جابه‌جایی {name}" instructions="Space برای برداشتن، جهت‌ها برای جابه‌جایی، دوباره Space برای گذاشتن، Escape برای لغو." :messages="[
            'grabbed' => '{name} برداشته شد. جایگاه {position} از {total}.',
            'moved' => '{name} به جایگاه {position} از {total} رفت.',
            'dropped' => '{name} در جایگاه {position} از {total} گذاشته شد.',
            'cancelled' => 'لغو شد. {name} در جایگاه {position} از {total} ماند.',
        ]" :items="[
            ['id' => 'a', 'label' => 'پاسخ به تیکت‌ها'],
            ['id' => 'b', 'label' => 'بازبینی کد'],
            ['id' => 'c', 'label' => 'جلسهٔ هفتگی'],
        ]" />
    </section>
</div>
