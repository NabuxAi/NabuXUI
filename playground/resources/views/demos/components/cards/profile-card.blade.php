{{--
    The profile card's real scenarios: the author page as it ships (cover and
    avatar, the verified seal, rolling stats, the follow morph and the tabs),
    the experts directory (one card already followed — the server's truth —
    beside one that is not), and the lean on-call specialist: initials from
    the name over the brand gradient, no tabs, the message button a link.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    // Portrait and cover art as inline SVG data URIs — no external assets; the
    // avatar fills its circle and the cover its banner (object-fit: cover).
    $svg = fn (string $w, string $h, string $body) => 'data:image/svg+xml,' . rawurlencode(
        '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ' . $w . ' ' . $h . '">' . $body . '</svg>'
    );
    $art = [
        'cover' => $svg('600', '200', '<rect width="600" height="200" fill="#312e81"/><circle cx="90" cy="14" r="110" fill="#4f46e5" opacity=".6"/><circle cx="330" cy="214" r="150" fill="#0d9488" opacity=".5"/><circle cx="545" cy="26" r="92" fill="#f59e0b" opacity=".3"/>'),
        'negar' => $svg('200', '200', '<rect width="200" height="200" fill="#efe9fd"/><circle cx="100" cy="80" r="36" fill="#7c5cd6"/><path d="M38 200a62 62 0 0 1 124 0" fill="#5b3fc4"/>'),
        'omar' => $svg('200', '200', '<rect width="200" height="200" fill="#e0f5f6"/><circle cx="100" cy="80" r="36" fill="#0e7490"/><path d="M38 200a62 62 0 0 1 124 0" fill="#155e75"/>'),
        'lena' => $svg('200', '200', '<rect width="200" height="200" fill="#fbf0d9"/><circle cx="100" cy="80" r="36" fill="#b45309"/><path d="M38 200a62 62 0 0 1 124 0" fill="#92400e"/>'),
    ];
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The author’s page, as it ships', 'صفحهٔ نویسنده، همان‌طور که منتشر می‌شود') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The avatar rides the cover/body seam with the online dot breathing on its rim, the name carries the verified seal, and every stat rolls its digits in the page’s own numerals. The follow button morphs — icon swap, label crossfade, recolour — announces itself to screen readers, and rolls the follower count ±1 until the server’s own number lands; here each move also reports through a toast (the nx-follow / nx-unfollow / nx-message events riding the card). The small tabs carry a springing indicator and answer the arrow keys.', 'آواتار روی درز کاور و بدنه می‌نشیند و نقطهٔ «آنلاین» روی لبه‌اش نفس می‌کشد، نام مهر تأیید را دارد و همهٔ آمارها ارقام‌شان را با ارقام خود صفحه می‌غلتانند. دکمهٔ دنبال‌کردن مورف می‌شود — تعویض آیکون، کراس‌فید برچسب، تغییر رنگ — خودش را به صفحه‌خوان‌ها اعلام می‌کند و شمار دنبال‌کننده را تا رسیدن عددِ خود سرور ±۱ می‌غلتاند؛ اینجا هر حرکت با یک توست هم خبر می‌دهد (رویدادهای nx-follow / nx-unfollow / nx-message روی خود کارت). تب‌های کوچک نشانگر فنری می‌آورند و به فلش‌های کیبورد جواب می‌دهند.') }}
        </p>
    </div>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 24rem), 1fr)); gap: var(--nx-space-4); max-inline-size: 30rem">
        <x-nx::profile-card
            :name="$say('Negar Rostami', 'نگار رستمی')"
            :role="$say('Writer & teacher of Persian typography', 'نویسنده و مدرس تایپوگرافی فارسی')"
            handle="@negar-types" href="#" status="online" verified
            :avatar="['src' => $art['negar'], 'alt' => '']"
            :cover="['src' => $art['cover'], 'alt' => '']"
            :stats="[
                ['label' => $say('Followers', 'دنبال‌کننده'), 'value' => 12840],
                ['label' => $say('Following', 'دنبال‌شده'), 'value' => 812],
                ['label' => $say('Essays', 'نوشته'), 'value' => 146],
            ]"
            :followers-stat="0"
            :tabs="['about' => $say('About', 'درباره'), 'activity' => $say('Activity', 'فعالیت')]"
            x-on:nx-follow="$wire.ping('{{ $say('Now following', 'دنبال شد') }}: ' + $event.detail.name)"
            x-on:nx-unfollow="$wire.ping('{{ $say('Unfollowed', 'دنبال کردن لغو شد') }}: ' + $event.detail.name)"
            x-on:nx-message="$wire.ping('{{ $say('Message to', 'پیام به') }}: ' + $event.detail.name)">
            <x-slot:about>
                <p>{{ $say('Fourteen years of setting Persian type for screens; the newsletter arrives every other Tuesday — one kata, one critique.', 'چهارده سال حروف‌چینی فارسی برای صفحه‌نمایش‌ها؛ خبرنامه هر دو هفته یک‌بار می‌رسد — یک تمرین، یک نقد.') }}</p>
            </x-slot:about>
            <x-slot:activity>
                <ul style="margin: 0; padding-inline-start: 1.125rem; display: grid; gap: .375rem">
                    <li>{{ $say('Published “Kerning in Persian” — ۲ days ago', '«فاصله‌گذاری در فارسی» را منتشر کرد — ۲ روز پیش') }}</li>
                    <li>{{ $say('Reviewed the type-scale proposal — ۵ days ago', 'پیشنهاد مقیاس حروف را بازبینی کرد — ۵ روز پیش') }}</li>
                    <li>{{ $say('Opened the workshop seats — ۱ week ago', 'جاهای کارگاه را باز کرد — ۱ هفته پیش') }}</li>
                </ul>
            </x-slot:activity>
        </x-nx::profile-card>
    </div>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The experts directory', 'فهرست کارشناسان') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Omar arrives with following already true — the server rendered him that way, so the button lands in its settled state and unfollowing rolls his followers −۱. Lena is not followed yet. Neither passes follow-action, so the state stays in the browser and the nx-follow / nx-unfollow events bubble for you; name a Livewire method instead and it is called after the optimistic flip while the button holds still for the round-trip.', 'عمر با following از قبل روشن می‌رسد — سرور او را همین‌طور رندر کرده — پس دکمه در حالت settled فرود می‌آید و لغو دنبال‌کردن، دنبال‌کننده‌هایش را −۱ می‌غلتاند. لنا هنوز دنبال نشده است. هیچ‌کدام follow-action نمی‌گیرند، پس حالت در مرورگر می‌ماند و رویدادهای nx-follow و nx-unfollow حباب می‌کنند؛ به‌جایش نام یک متد لایووایر بدهید تا پس از چرخش خوش‌بینانه صدا زده شود و دکمه تا پایان رفت‌وبرگشت درنگ کند.') }}
        </p>
    </div>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 22rem), 1fr)); gap: var(--nx-space-4)">
        <x-nx::profile-card
            :name="$say('Omar Haddad', 'عمر حداد')"
            :role="$say('Senior infrastructure engineer', 'مهندس ارشد زیرساخت')"
            handle="@omar-infra" status="busy"
            :avatar="['src' => $art['omar'], 'alt' => '']"
            following
            :stats="[
                ['label' => $say('Followers', 'دنبال‌کننده'), 'value' => 248],
                ['label' => $say('Answers', 'پاسخ'), 'value' => 1932],
                ['label' => $say('Runbooks', 'دفترچهٔ اجرا'), 'value' => 54],
            ]"
            :followers-stat="0"
            x-on:nx-follow="$wire.ping('{{ $say('Now following', 'دنبال شد') }}: ' + $event.detail.name)"
            x-on:nx-unfollow="$wire.ping('{{ $say('Unfollowed', 'دنبال کردن لغو شد') }}: ' + $event.detail.name)" />
        <x-nx::profile-card
            :name="$say('Lena Fischer', 'لنا فیشر')"
            :role="$say('Data engineer', 'مهندس داده')"
            handle="@lena-data" status="away"
            :avatar="['src' => $art['lena'], 'alt' => '']"
            :stats="[
                ['label' => $say('Followers', 'دنبال‌کننده'), 'value' => 186],
                ['label' => $say('Datasets', 'مجموعه‌داده'), 'value' => 42],
                ['label' => $say('Notebooks', 'دفترچه'), 'value' => 28],
            ]"
            :followers-stat="0"
            x-on:nx-follow="$wire.ping('{{ $say('Now following', 'دنبال شد') }}: ' + $event.detail.name)"
            x-on:nx-unfollow="$wire.ping('{{ $say('Unfollowed', 'دنبال کردن لغو شد') }}: ' + $event.detail.name)" />
    </div>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The on-call specialist', 'کارشناسِ شیفت آنلاین') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The lean edge: no photo anywhere — the initials come from the name over the brand gradient — no tabs, and the message button has become a plain link (message-href), so it navigates instead of dispatching. The score still arrives with its fraction (۴٫۹) in the page’s own digits.', 'حالت ماشه‌ای و باریک: هیچ عکسی در کار نیست — حرف‌های اول نام، روی گرادیان برند می‌نشینند — تب هم ندارد و دکمهٔ پیام با message-href به یک لینک ساده تبدیل شده، پس به‌جای رویداد، پیمایش می‌کند. امتیاز هنوز با کسرش (۴٫۹) و با ارقام خود صفحه می‌رسد.') }}
        </p>
    </div>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 20rem), 1fr)); gap: var(--nx-space-4); max-inline-size: 26rem">
        <x-nx::profile-card
            :name="$say('Maryam Ghasemi', 'مریم قاسمی')"
            :role="$say('Support specialist · morning shift', 'کارشناس پشتیبانی · شیفت صبح')"
            status="online"
            message-href="#tickets"
            :stats="[
                ['label' => $say('Tickets closed', 'تیکت بسته‌شده'), 'value' => 342],
                ['label' => $say('Score', 'امتیاز'), 'value' => 4.9],
                ['label' => $say('First reply (min)', 'نخستین پاسخ (دقیقه)'), 'value' => 8],
            ]" />
    </div>
    <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
        {{ $say('The follow button is always there; without a followers-stat nothing rolls, the button only morphs.', 'دکمهٔ دنبال‌کردن هم هست؛ بدون followers-stat چیزی نمی‌غلتد و دکمه فقط مورف می‌شود.') }}
    </p>
</section>
