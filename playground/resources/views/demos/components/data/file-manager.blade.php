{{--
    The file manager's real scenarios: the shop's media library — folders,
    previews, the details drawer, the search — then a legal archive in list
    view opening deep inside its tree, and a client delivery room whose
    empty folder and upload button carry the hand-off.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    // A self-contained thumbnail (data URI, no network) for the drawer previews.
    $thumb = fn (string $word) => 'data:image/svg+xml;charset=utf-8,'.rawurlencode(
        '<svg xmlns="http://www.w3.org/2000/svg" width="480" height="320" viewBox="0 0 480 320">'
        .'<rect width="480" height="320" fill="#e8eaf0"/><circle cx="240" cy="130" r="54" fill="#c7cede"/>'
        .'<path d="M158 250h164l-30-44H188z" fill="#b6c0d4"/>'
        .'<text x="240" y="302" text-anchor="middle" font-family="system-ui, sans-serif" font-size="22" fill="#5c667a">'.$word.'</text>'
        .'</svg>'
    );

    $media = [
        ['id' => 'campaign', 'name' => $say('Norooz campaign', 'کمپین نوروز'), 'kind' => 'folder', 'items' => 6, 'modified' => now()->subDays(3)],
        ['id' => 'products', 'name' => $say('Product photos', 'عکس محصول'), 'kind' => 'folder', 'items' => 14, 'modified' => now()->subDay()],
        ['id' => 'tutorials', 'name' => $say('Tutorials', 'ویدئوهای آموزشی'), 'kind' => 'folder', 'items' => 3, 'modified' => now()->subDays(9)],
        ['id' => 'hero', 'name' => 'hero-noruz.jpg', 'size' => 2411724, 'modified' => now()->subDays(3),
            'preview' => $thumb($say('hero banner', 'بنر اصلی')),
            'details' => [
                ['label' => $say('Dimensions', 'ابعاد'), 'value' => '۲۴۰۰×۱۲۰۰'],
                ['label' => $say('Designer', 'طراح'), 'value' => 'نگار رستمی'],
            ]],
        ['id' => 'jingle', 'name' => 'jingle-noruz.mp3', 'size' => 3145728, 'modified' => now()->subDays(4),
            'details' => [['label' => $say('Length', 'مدت'), 'value' => '۰:۳۰']]],

        ['id' => 'story', 'name' => 'story-9x16.jpg', 'parent' => 'campaign', 'size' => 891289, 'modified' => now()->subDays(3),
            'preview' => $thumb($say('story', 'استوری')),
            'details' => [['label' => $say('Dimensions', 'ابعاد'), 'value' => '۱۰۸۰×۱۹۲۰']]],
        ['id' => 'teaser', 'name' => 'teaser.mp4', 'parent' => 'campaign', 'size' => 48234496, 'modified' => now()->subDays(5),
            'details' => [['label' => $say('Length', 'مدت'), 'value' => '۰:۴۵']]],

        ['id' => 'shoe-front', 'name' => $say('ahar shoe — front.jpg', 'کفش اهر — نمای جلو.jpg'), 'parent' => 'products', 'size' => 1843200, 'modified' => now()->subDay(),
            'preview' => $thumb($say('cream', 'کرم')),
            'details' => [['label' => $say('Colour', 'رنگ'), 'value' => $say('Cream', 'کرم')]]],
        ['id' => 'shoe-back', 'name' => $say('ahar shoe — back.jpg', 'کفش اهر — نمای پشت.jpg'), 'parent' => 'products', 'size' => 1761280, 'modified' => now()->subDay()],
        ['id' => 'shoe-stitch', 'name' => $say('ahar shoe — stitching.jpg', 'کفش اهر — دوخت.jpg'), 'parent' => 'products', 'size' => 1507328, 'modified' => now()->subDays(2)],

        ['id' => 'sizing', 'name' => 'sizing-guide.mov', 'parent' => 'tutorials', 'size' => 128849018, 'modified' => now()->subDays(9)],
    ];

    $docs = [
        ['id' => 'contracts', 'name' => $say('Contracts', 'قراردادها'), 'kind' => 'folder', 'items' => 9, 'modified' => now()->subDays(2)],
        ['id' => 'minutes', 'name' => $say('Board minutes', 'صورت‌جلسه‌ها'), 'kind' => 'folder', 'items' => 4, 'modified' => now()->subDays(11)],
        ['id' => 'y1404', 'name' => '۱۴۰۴', 'parent' => 'contracts', 'kind' => 'folder', 'items' => 3, 'modified' => now()->subDays(2)],
        ['id' => 'y1403', 'name' => '۱۴۰۳', 'parent' => 'contracts', 'kind' => 'folder', 'items' => 6, 'modified' => now()->subDays(214)],
        ['id' => 'supply', 'name' => $say('supply agreement.pdf', 'قرارداد تأمین.pdf'), 'parent' => 'y1404', 'size' => 286720, 'modified' => now()->subDays(2), 'href' => '#',
            'details' => [
                ['label' => $say('Number', 'شماره'), 'value' => 'ن-۱۴۰۴/۲۱'],
                ['label' => $say('Owner', 'واحد مسئول'), 'value' => $say('Legal — procurement', 'حقوقی — تدارکات')],
            ]],
        ['id' => 'amendment', 'name' => $say('amendment 1.pdf', 'الحاقیه ۱.pdf'), 'parent' => 'y1404', 'size' => 92160, 'modified' => now()->subDays(6), 'href' => '#'],
        ['id' => 'support', 'name' => $say('support agreement.pdf', 'قرارداد پشتیبانی.pdf'), 'parent' => 'y1404', 'size' => 204800, 'modified' => now()->subWeeks(3), 'href' => '#'],
        ['id' => 'agm', 'name' => $say('AGM minutes.xlsx', 'صورت‌جلسه مجمع.xlsx'), 'parent' => 'minutes', 'size' => 47104, 'modified' => now()->subDays(11), 'href' => '#'],
    ];

    $delivery = [
        ['id' => 'brand', 'name' => $say('Brand book', 'کتاب برند'), 'kind' => 'folder', 'items' => 2, 'modified' => now()->subDays(4)],
        ['id' => 'final', 'name' => $say('Final cuts', 'نسخه‌های نهایی'), 'kind' => 'folder', 'items' => 0, 'modified' => now()->subHours(5)],
        ['id' => 'logo-svg', 'name' => 'nabu-logo.svg', 'parent' => 'brand', 'size' => 18432, 'modified' => now()->subDays(4),
            'preview' => $thumb($say('logo', 'لوگو')),
            'details' => [['label' => $say('Use', 'کاربرد'), 'value' => $say('letterhead & invoices', 'سربرگ و فاکتور')]]],
        ['id' => 'logo-2x', 'name' => 'nabu-logo@2x.png', 'parent' => 'brand', 'size' => 45114, 'modified' => now()->subDays(4)],
    ];
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The shop’s media library', 'کتابخانهٔ رسانهٔ فروشگاه') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Click a folder card and the trail opens along the breadcrumbs; the search filters only the folder you are in (Persian and Arabic letter variants fold together — open «عکس محصول» and type «کفش»), a file card slides its details drawer in from the inline end — Escape or the scrim closes it — and the toolbar’s segmented flips grid to list. Sizes and dates arrive in the reader’s digits and calendar.', 'کارتِ پوشه را بزنید تا مسیر با نان‌ریزها باز شود؛ جست‌وجو فقط همان پوشه‌ای را صافی می‌زند که داخلش هستید (نویسه‌های ی/ک فارسی و عربی هم‌ارز خوانده می‌شوند — «عکس محصول» را باز کنید و «کفش» را بنویسید)، کارتِ فایل کشوی جزئیات را از کنارِ صفحه می‌آورد — Esc یا پردهٔ تار می‌بنددش — و سگمنتِ نوار ابزار گرید را به فهرست برمی‌گرداند. اندازه‌ها و تاریخ‌ها با ارقام و تقویم زبان خواننده‌اند.') }}
        </p>
    </div>
    <x-nx::file-manager
        :items="$media"
        :label="$say('Media library', 'کتابخانهٔ رسانه')"
        :search-placeholder="$say('Search this folder…', 'جست‌وجو در همین پوشه…')"
        height="30rem"
        x-on:nx-open="$wire.ping('{{ $say('Details opened', 'جزئیات باز شد') }}: ' + $event.detail.entry)" />
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The legal archive, in list view', 'آرشیو اسناد حقوقی، در نمای فهرستی') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('current opens the board already inside «قراردادها / ۱۴۰۴», so the breadcrumb trail is there from the first paint. Dates come straight from Carbon and the Intl calendar formats them (Jalali for Persian); entries with an href grow an Open button in the drawer, and every folder move reports through the nx-navigate event — the toast below rides it.', 'current="y1404" برد را همان‌جا داخل «قراردادها / ۱۴۰۴» باز می‌کند، پس نان‌ریزهای مسیر از همان رندر اول سر جایشان‌اند. تاریخ‌ها مستقیم از Carbon می‌آیند و IntlCalendar آن‌ها را قالب می‌زند (برای فارسی، جلالی)؛ مدخل‌های href دار در کشو دکمهٔ «باز کردن» می‌گیرند و هر جابه‌جایی پوشه با رویداد nx-navigate خبر می‌دهد — توست پایین همین‌جا سوارش است.') }}
        </p>
    </div>
    <x-nx::file-manager
        :items="$docs"
        current="y1404"
        view="list"
        :label="$say('Legal archive', 'آرشیو اسناد')"
        height="24rem"
        x-on:nx-navigate="$wire.ping('{{ $say('Entered', 'ورود به') }} ' + ($event.detail.folder ?? '{{ $say('the root', 'ریشه') }}'))" />
</section>

<section class="pg-box" style="gap: 1.25rem; max-inline-size: 40rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The project delivery room', 'اتاق تحویل پروژه') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The «Final cuts» folder is empty — its empty text arrives from outside, like the folder a client just got. The upload button opens the native picker: unwired, the nx-upload event hands you the FileList (the toast counts it); put a wire:model on the component instead and the very same picker binds to Livewire’s uploads.', 'پوشهٔ «نسخه‌های نهایی» خالی است — متن حالت خالی‌اش مثل هر پوشه‌ای که تازه به مشتری داده‌اید از بیرون می‌آید. دکمهٔ بارگذاری انتخاب‌گر بومی را باز می‌کند: بدون اتصال، رویداد nx-upload همان FileList را می‌دهد (توست می‌شماردش)؛ و اگر به‌جایش wire:model روی خود کامپوننت بگذارید، همین انتخاب‌گر به بارگذاری Livewire وصل می‌شود.') }}
        </p>
    </div>
    <x-nx::file-manager
        :items="$delivery"
        :label="$say('Delivery room', 'اتاق تحویل')"
        :empty-text="$say('Nothing delivered yet — send the first file with the upload button.', 'هنوز چیزی تحویل نشده — نخستین فایل را با دکمهٔ بارگذاری بفرستید.')"
        height="20rem"
        x-on:nx-upload="$wire.ping($event.detail.files.length + ' {{ $say('file(s) picked for upload', 'فایل برای بارگذاری انتخاب شد') }}')" />
</section>
