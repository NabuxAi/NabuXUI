{{--
    The expandable stack's real scenarios: a brand library that fans out into
    a grid (state riding a Livewire property), then a campaign archive that
    starts open.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $art = [
        'lapis' => 'radial-gradient(120% 90% at 12% 0%, var(--nx-lapis-300), transparent 58%), linear-gradient(140deg, var(--nx-lapis-600), var(--nx-violet-700))',
        'violet' => 'radial-gradient(110% 80% at 90% 10%, var(--nx-violet-300), transparent 55%), linear-gradient(160deg, var(--nx-violet-600), var(--nx-lapis-950))',
        'cyan' => 'radial-gradient(100% 80% at 15% 15%, var(--nx-cyan-300), transparent 58%), linear-gradient(200deg, var(--nx-cyan-600), var(--nx-lapis-800))',
        'gold' => 'radial-gradient(100% 90% at 80% 100%, var(--nx-gold-300), transparent 60%), linear-gradient(160deg, var(--nx-gold-500), var(--nx-violet-700))',
        'rose' => 'radial-gradient(100% 80% at 20% 0%, color-mix(in oklab, var(--nx-chart-2) 60%, var(--nx-ink-50)), transparent 60%), linear-gradient(150deg, var(--nx-chart-2), var(--nx-violet-700))',
    ];
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The brand library, in one row', 'کتابخانهٔ برند، در یک ردیف') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Five assets live in the footprint of one card; “Show all” flies them out into a grid on the gentle spring and back again. The open/closed state rides a Livewire property.', 'پنج دارایی در جایِ یک کارت جا می‌شوند؛ «نمایش همه» آن‌ها با فنر ملایم به گرید می‌پراند و برمی‌گرداند. باز/بسته بودن با یک پراپرتی Livewire می‌ماند.') }}
        </p>
    </div>
    <x-nx::expandable-stack
        wire:model="state.open"
        :expand-label="$say('Show all five', 'نمایش هر پنج دارایی')"
        :collapse-label="$say('Stack them back', 'پشت هم')"
        :items="[
            'logo' => ['title' => $say('Logotype', 'لوگوتایپ'), 'description' => $say('The wordmark, the monogram and the clear-space rules.', 'نشانهٔ نوشتاری، مونوگرام و قواعد فاصلهٔ امن.'), 'cover' => $art['lapis']],
            'color' => ['title' => $say('Colour', 'رنگ'), 'description' => $say('Two palettes, both themes, and when the gold may appear.', 'دو پالت، هر دو تم، و این‌که طلایی کی حق ظهور دارد.'), 'cover' => $art['rose']],
            'type' => ['title' => $say('Typography', 'تایپوگرافی'), 'description' => $say('Vazirmatn for Persian, Inter for Latin, the mono for code.', 'وزیرمتن برای فارسی، اینتر برای لاتین، مونو برای کد.'), 'cover' => $art['gold']],
            'photo' => ['title' => $say('Photography', 'عکاسی'), 'description' => $say('Natural light, real hands, no stock smiles.', 'نور طبیعی، دست‌های واقعی، بدون لبخندِ بانکی.'), 'cover' => $art['cyan']],
            'icon' => ['title' => $say('Iconography', 'شمایل‌ها'), 'description' => $say('Two pixels of stroke, one grid, every glyph.', 'دو پیکسل ضخامت، یک شبکه، برای همهٔ نشانه‌ها.'), 'cover' => $art['violet']],
        ]" />
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The campaign archive', 'بایگانی کمپین') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('expanded opens it on arrival — the three seasonal campaigns of last year, already spread out.', 'expanded آن را از همان ورود باز می‌کند — سه کمپین فصلی پارسال، از همین حالا پهن.') }}
        </p>
    </div>
    <x-nx::expandable-stack expanded :expand-label="$say('Open the archive', 'بایگانی را باز کن')" :collapse-label="$say('Close', 'بستن')" :items="[
        'spring' => ['title' => $say('Spring · «نوروز»', 'بهار · «نوروز»'), 'description' => $say('Tea, mirrors and seven days of stories.', 'چای، آینه و هفت روز روایت.'), 'cover' => $art['rose']],
        'summer' => ['title' => $say('Summer · «کنار دریا»', 'تابستان · «کنار دریا»'), 'description' => $say('The Caspian coast, shot in one week.', 'ساحل خزر، یک‌هفته عکاسی.'), 'cover' => $art['cyan']],
        'fall' => ['title' => $say('Fall · «بازار»', 'پاییز · «بازار»'), 'description' => $say('Grand Bazaar at 5 a.m., before the crowd.', 'بازار بزرگ ساعت ۵ صبح، پیش از ازدحام.'), 'cover' => $art['gold']],
    ]" />
</section>
