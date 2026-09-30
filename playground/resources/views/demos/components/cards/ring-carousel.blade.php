{{--
    The ring carousel's real scenarios: a studio's work picked by dragging a
    ring (the front project rides a Livewire property), then a short
    case-study ring that links out.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $art = [
        'lapis' => 'radial-gradient(120% 90% at 12% 0%, var(--nx-lapis-300), transparent 58%), linear-gradient(140deg, var(--nx-lapis-600), var(--nx-violet-700))',
        'violet' => 'radial-gradient(110% 80% at 90% 10%, var(--nx-violet-300), transparent 55%), linear-gradient(160deg, var(--nx-violet-600), var(--nx-lapis-950))',
        'cyan' => 'radial-gradient(100% 80% at 15% 15%, var(--nx-cyan-300), transparent 58%), linear-gradient(200deg, var(--nx-cyan-600), var(--nx-lapis-800))',
        'gold' => 'radial-gradient(100% 90% at 80% 100%, var(--nx-gold-300), transparent 60%), linear-gradient(160deg, var(--nx-gold-500), var(--nx-violet-700))',
        'green' => 'radial-gradient(90% 80% at 80% 20%, color-mix(in oklab, var(--nx-chart-7) 55%, var(--nx-ink-50)), transparent 60%), linear-gradient(170deg, var(--nx-chart-7), var(--nx-cyan-700))',
        'rose' => 'radial-gradient(100% 80% at 20% 0%, color-mix(in oklab, var(--nx-chart-2) 60%, var(--nx-ink-50)), transparent 60%), linear-gradient(150deg, var(--nx-chart-2), var(--nx-violet-700))',
    ];

    $projects = [
        ['title' => $say('Fajr Banking App', 'اپ بانکی فجر'), 'subtitle' => $say('Redesign · 14 screens', 'طراحی دوباره · ۱۴ صفحه'), 'cover' => $art['lapis'], 'href' => '#'],
        ['title' => $say('Simorgh Airlines', 'هوابردی سیمرغ'), 'subtitle' => $say('Booking flow · +32% conversion', 'مسیر رزرو · ۳۲٪ نرخ تبدیل بیشتر'), 'cover' => $art['gold'], 'href' => '#'],
        ['title' => $say('Dena Market', 'دنه‌مارکت'), 'subtitle' => $say('Storefront · 6 languages', 'ویترین فروشگاه · ۶ زبان'), 'cover' => $art['cyan'], 'href' => '#'],
        ['title' => $say('Alborz Health', 'سلامت البرز'), 'subtitle' => $say('Patient portal · RTL first', 'پورتال بیمار · راست‌به‌چپ در درجهٔ اول'), 'cover' => $art['violet'], 'href' => '#'],
        ['title' => $say('Caspian Logistics', 'لجستیک خزر'), 'subtitle' => $say('Fleet dashboard · live map', 'داشبورد ناوگان · نقشهٔ زنده'), 'cover' => $art['green'], 'href' => '#'],
        ['title' => $say('Villa Rose Hotel', 'هتل رزویلا'), 'subtitle' => $say('Booking · 3 locales', 'رزرو · ۳ زبان'), 'cover' => $art['rose'], 'href' => '#'],
    ];

    $front = (int) ($state['ring'] ?? 0);
    $frontTitle = $projects[$front]['title'] ?? '—';
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The studio’s picked work', 'نمونه‌کارهای برگزیدهٔ استودیو') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Drag the ring and let go — it snaps to the nearest card. The arrow keys and the side buttons work too, and the front card’s index rides a Livewire property.', 'حلقه را بکشید و رها کنید — روی نزدیک‌ترین کارت می‌ایستد. فلش‌های کیبورد و دکمه‌های دو طرف هم کار می‌کنند و اندیس کارت جلو با یک پراپرتی Livewire می‌ماند.') }}
        </p>
    </div>
    <x-nx::ring-carousel :label="$say('Selected work', 'نمونه‌کارهای برگزیده')" :items="$projects" wire:model="state.ring" />
    <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
        {{ $say('Front card on the server (wire:model, sent with the next request):', 'کارت جلو روی سرور (wire:model، با درخواست بعدی فرستاده می‌شود):') }}
        <code>{{ $frontTitle }}</code> · {{ $say('index', 'اندیس') }} <code>{{ NabuXUI::formatNumber($front) }}</code>
    </p>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Case studies that link out', 'مطالعات موردی که لینک می‌شوند') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Three quieter cards with custom sizes — each one a real anchor to its case study.', 'سه کارت آرام‌تر با اندازهٔ سفارشی — هرکدام لینک واقعی به مطالعهٔ موردی خودشان.') }}
        </p>
    </div>
    <x-nx::ring-carousel :label="$say('Case studies', 'مطالعات موردی')" card-width="10.5rem" card-height="13rem" :items="[
        ['title' => $say('Cutting first response by half', 'نصف‌کردن زمان نخستین پاسخ'), 'subtitle' => $say('Support · 40 agents', 'پشتیبانی · ۴۰ اپراتور'), 'cover' => $art['lapis'], 'href' => '#'],
        ['title' => $say('A checkout that speaks six languages', 'یک پرداخت که شش زبان حرف می‌زند'), 'subtitle' => $say('Commerce · i18n', 'تجارت · چندزبانه'), 'cover' => $art['gold'], 'href' => '#'],
        ['title' => $say('Onboarding without a single email', 'رهاسازی بدون حتی یک ایمیل'), 'subtitle' => $say('Product · activation', 'محصول · فعال‌سازی'), 'cover' => $art['cyan'], 'href' => '#'],
    ]" />
</section>
