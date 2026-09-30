{{--
    The orbit showcase's real scenarios: a product family orbiting the brand
    mark (a custom centre slot), then a slower showcase with fewer screens.
--}}
@php
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

    $apps = [
        ['title' => $say('Nabu Desk', 'میز نابو'), 'subtitle' => $say('The shared inbox', 'صندوق ورودی مشترک'), 'cover' => $art['lapis'], 'href' => '#'],
        ['title' => $say('Nabu Voice', 'نابو صوت'), 'subtitle' => $say('Answers that speak', 'پاسخ‌هایی که حرف می‌زنند'), 'cover' => $art['gold'], 'href' => '#'],
        ['title' => $say('Nabu Docs', 'نابو سند'), 'subtitle' => $say('Knowledge that writes itself', 'دانشی که خودش را می‌نویسد'), 'cover' => $art['cyan'], 'href' => '#'],
        ['title' => $say('Nabu Pay', 'نابو پرداخت'), 'subtitle' => $say('Invoices in any currency', 'فاکتور به هر ارزی'), 'cover' => $art['violet'], 'href' => '#'],
        ['title' => $say('Nabu Insight', 'نابو بینش'), 'subtitle' => $say('What your customers mean', 'مقصود مشتری‌تان چه بود'), 'cover' => $art['green'], 'href' => '#'],
        ['title' => $say('Nabu Studio', 'استودیو نابو'), 'subtitle' => $say('Design the conversation', 'گفت‌وگو را طراحی کنید'), 'cover' => $art['rose'], 'href' => '#'],
    ];
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The product family, in orbit', 'خانوادهٔ محصول، در مدار') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Six apps circle the brand mark; hovering pauses the ring and clicking a screen walks it to the front. The centre orb is a slot — here it holds the mark itself.', 'شش اپ دور نشانهٔ برند می‌چرخند؛ مکث روی حلقه آن را نگه می‌دارد و کلیک روی هر صفحه آن را جلو می‌آورد. گوی مرکز یک اسلات است — اینجا خودِ نشانه در آن نشسته.') }}
        </p>
    </div>
    <x-nx::orbit-showcase :items="$apps">
        <x-slot:center>
            <span style="display: grid; place-items: center; inline-size: 5rem; aspect-ratio: 1; border-radius: var(--nx-radius-full); border: 1px solid var(--nx-border-strong); background: var(--nx-surface); color: var(--nx-accent-text); font: 800 var(--nx-text-2xl) / 1 var(--nx-font-display); box-shadow: var(--nx-shadow-glow)" aria-hidden="true">N</span>
        </x-slot:center>
    </x-nx::orbit-showcase>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A slower, quieter tour', 'توری آرام‌تر و کندتر') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('period sets the seconds per turn — 55 here. With reduced motion the ring simply holds still and every screen stays reachable.', 'period ثانیه‌های هر دور را تعیین می‌کند — اینجا ۵۵. با حرکت کم حلقه فقط ساکن می‌ماند و همهٔ صفحه‌ها در دسترس می‌مانند.') }}
        </p>
    </div>
    <x-nx::orbit-showcase :period="55" :controls="false" :items="[
        ['title' => $say('Tokyo office', 'دفتر توکیو'), 'subtitle' => $say('Support from 9 to 9', 'پشتیبانی ۹ تا ۹'), 'cover' => $art['rose'], 'href' => '#'],
        ['title' => $say('Berlin office', 'دفتر برلین'), 'subtitle' => $say('Where it started', 'همه‌چیز از این‌جا شروع شد'), 'cover' => $art['lapis'], 'href' => '#'],
        ['title' => $say('São Paulo office', 'دفتر سائوپائولو'), 'subtitle' => $say('Portuguese, around the clock', 'پرتغالی، شبانه‌روزی'), 'cover' => $art['gold'], 'href' => '#'],
    ]" />
</section>
