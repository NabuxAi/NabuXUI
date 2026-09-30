{{--
    The stacked scroll cards' real scenarios: a product's step-by-step setup
    guide (with a custom artwork slot on the last card), then a compact
    two-card stack with custom pin positions.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    // Covers are gradients from the palette tokens: no hotlinked images.
    $art = [
        'lapis' => 'radial-gradient(120% 90% at 12% 0%, var(--nx-lapis-300), transparent 58%), linear-gradient(140deg, var(--nx-lapis-600), var(--nx-violet-700))',
        'violet' => 'radial-gradient(110% 80% at 90% 10%, var(--nx-violet-300), transparent 55%), linear-gradient(160deg, var(--nx-violet-600), var(--nx-lapis-950))',
        'cyan' => 'radial-gradient(100% 80% at 15% 15%, var(--nx-cyan-300), transparent 58%), linear-gradient(200deg, var(--nx-cyan-600), var(--nx-lapis-800))',
        'gold' => 'radial-gradient(100% 90% at 80% 100%, var(--nx-gold-300), transparent 60%), linear-gradient(160deg, var(--nx-gold-500), var(--nx-violet-700))',
    ];
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The four-step setup guide', 'راهنمای راه‌اندازی در چهار گام') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Scroll: each card sticks over the previous one — the covered card shrinks, leans back and dims, so the reader always knows how far they have come. The last card’s artwork is a slot.', 'اسکرول کنید: هر کارت روی قبلی سنجاق می‌شود — کارتِ زیرین کوچک‌تر می‌شود، به عقب خم می‌شود و کم‌رنگ می‌شود؛ خواننده همیشه می‌فهمد کجای راه است. تصویر کارت آخر یک اسلات است.') }}
        </p>
    </div>
    <x-nx::stacked-scroll-cards top="1.5rem" height="min(22rem, 60svh)" :items="[
        'invite' => ['title' => $say('Invite the team', 'دعوت از تیم'), 'description' => $say('One link, any email — teammates land in the right workspace with their roles already set.', 'یک لینک، هر ایمیلی — هم‌تیمی‌ها با نقش‌های از پیش تعیین‌شده در ورک‌اسپیس درست فرود می‌آیند.'), 'tone' => 'lapis', 'cover' => $art['lapis']],
        'connect' => ['title' => $say('Connect the channels', 'اتصال کانال‌ها'), 'description' => $say('WhatsApp, Telegram and the web chat join the same inbox in a couple of clicks.', 'واتساپ، تلگرام و گفت‌وگوی وب با چند کلیک به یک صندوق ورودی می‌پیوندند.'), 'tone' => 'cyan', 'cover' => $art['cyan']],
        'train' => ['title' => $say('Teach the agent', 'آموزش ایجنت'), 'description' => $say('Drop in your docs and past replies; the agent learns the tone and the answers.', 'سندها و پاسخ‌های قبلی‌تان را بدهید؛ ایجنت لحن و جواب‌ها را یاد می‌گیرد.'), 'tone' => 'gold', 'cover' => $art['gold']],
        'live' => ['title' => $say('Go live', 'روشن کردن'), 'description' => $say('Flip the switch — from this message on, Nabu answers first and a human is one click away.', 'کلید را بزنید — از این پیام به بعد نابو اول جواب می‌دهد و انسان یک کلیک دورتر است.'), 'tone' => 'violet', 'cover' => $art['violet']],
    ]">
        <x-slot:live>
            <div style="display: grid; place-items: center; block-size: 100%; color: var(--nx-ink-50); font: 700 var(--nx-text-5xl) / 1 var(--nx-font-display); gap: .5rem">
                <span aria-hidden="true">{{ $say('Go', 'بزن‌بریم') }}</span>
            </div>
        </x-slot:live>
    </x-nx::stacked-scroll-cards>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A compact two-card stack', 'دستهٔ فشردهٔ دوکارتی') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('top, offset and height decide where the first card sticks, how much lower each next one sits and how tall they are — here a shorter pair for a page fold.', 'top و offset و height تعیین می‌کنند کارت اول کجا بچسبد، هر کارت بعدی چقدر پایین‌تر بنشیند و کارت‌ها چقدر بلند باشند — اینجا جفتِ کوتاه‌تر برای یک تاخوردگی صفحه.') }}
        </p>
    </div>
    <x-nx::stacked-scroll-cards top="3.5rem" offset="2.75rem" height="min(15rem, 44svh)" :items="[
        'q1' => ['title' => $say('What counts as a conversation?', 'یک گفت‌وگو چه حساب می‌شود؟'), 'description' => $say('Every thread that reaches an answer — the counter rolls as they do.', 'هر رشته‌ای که به پاسخ برسد — شمارنده همراهش می‌غلتد.'), 'tone' => 'lapis', 'cover' => $art['lapis']],
        'q2' => ['title' => $say('Can I pay yearly?', 'می‌شود سالانه پرداخت کرد؟'), 'description' => $say('Yes — two months on the house. The invoice block even prints.', 'بله — دو ماه مهمان ما هستید. بلوک فاکتور حتی چاپ هم می‌شود.'), 'tone' => 'gold', 'cover' => $art['gold']],
    ]" />
</section>
