{{--
    The team cards' real scenarios: an "about us" page row where every card
    links to a profile, then the crew behind a conference.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The people behind the product', 'آدم‌های پشت محصول') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The about page as it ships: every card is a real link to the profile, the colour climbs from the bottom on hover or focus, and it is all CSS — the initials come from the names.', 'صفحهٔ «دربارهٔ ما» همان‌طور که واقعاً منتشر می‌شود: هر کارت لینک واقعی به پروفایل است، رنگ با hover یا فوکوس از پایین بالا می‌آید و همه‌اش CSS است — حرف اول نام‌ها از خود نام‌ها می‌آید.') }}
        </p>
    </div>
    <x-nx::team-cards :label="$say('The Nabu team', 'تیم نابو')" :members="[
        ['name' => $say('Niloofar Ahmadi', 'نیلوفر احمدی'), 'role' => $say('Design lead · تهران', 'رهبر طراحی · تهران'), 'color' => 'violet', 'href' => '#'],
        ['name' => $say('Kenji Sato', 'کنجی ساتو'), 'role' => $say('Motion · 東京', 'حرکت · توکیو'), 'color' => 'cyan', 'href' => '#'],
        ['name' => $say('María López', 'ماریا لوپز'), 'role' => $say('Data engineering · Madrid', 'مهندسی داده · مادرید'), 'color' => 'gold', 'href' => '#'],
        ['name' => $say('Amara Okafor', 'آمارا اوکافور'), 'role' => $say('Support · Lagos', 'پشتیبانی · لاگوس'), 'color' => 'lapis', 'href' => '#'],
        ['name' => $say('Omar Haddad', 'عمر حداد'), 'role' => $say('Frontend · عمّان', 'فرانت‌اند · عمّان'), 'color' => 'var(--nx-chart-2)', 'href' => '#'],
    ]" />
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The conference crew', 'تیم برگزارکنندهٔ همایش') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Three organisers, no links — with card-width tuned for a narrow column and chart-token colours.', 'سه سازمان‌دهنده، بدون لینک — با card-width برای ستون باریک و رنگ‌های توکن نمودار.') }}
        </p>
    </div>
    <x-nx::team-cards :label="$say('The organisers', 'سازمان‌دهندگان')" card-width="13rem" :members="[
        ['name' => $say('Priya Nair', 'پریا نایر'), 'role' => $say('Programme · प्रोग्राम', 'برنامه · همایش'), 'color' => 'var(--nx-chart-2)'],
        ['name' => $say('Lena Fischer', 'لنا فیشر'), 'role' => $say('Stage & sound', 'صحنه و صدا'), 'color' => 'var(--nx-chart-7)'],
        ['name' => $say('Haruto Sato', 'هاروتو ساتو'), 'role' => $say('Workshops · ワークショップ', 'کارگاه‌ها'), 'color' => 'lapis'],
    ]" />
</section>
