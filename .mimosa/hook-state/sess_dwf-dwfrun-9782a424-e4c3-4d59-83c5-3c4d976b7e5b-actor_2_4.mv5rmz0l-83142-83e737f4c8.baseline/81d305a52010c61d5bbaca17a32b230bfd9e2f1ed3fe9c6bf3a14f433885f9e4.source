{{--
    The depth carousel's real scenarios: customer stories the reader swipes
    through (the slide riding a live Livewire property), then a product tour
    where one slide is a slot.
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

    $stories = [
        'bakery' => ['title' => $say('The bakery that never misses an order', 'نانوایی که هیچ سفارشی را از دست نمی‌دهد'), 'description' => $say('Direct messages, one inbox, zero lost loaves.', 'پیام‌های دایرکت، یک صندوق، هیچ نانِ گم‌شده‌ای.'), 'cover' => $art['gold'], 'href' => '#'],
        'clinic' => ['title' => $say('A clinic with calmer phones', 'مطب با تلفن‌های آرام‌تر'), 'description' => $say('Appointments book themselves in three languages.', 'نوبت‌ها به سه زبان خودشان ثبت می‌شوند.'), 'cover' => $art['lapis'], 'href' => '#'],
        'fashion' => ['title' => $say('Returns handled while they sleep', 'مرجوعی‌ها وقتی خواب‌اند حل می‌شوند'), 'description' => $say('The agent drafts, the team approves in the morning.', 'ایجنت پیش‌نویس می‌زند، تیم صبح تأیید می‌کند.'), 'cover' => $art['rose'], 'href' => '#'],
        'school' => ['title' => $say('A school answering every parent', 'مدرسه‌ای که به همهٔ والدین جواب می‌دهد'), 'description' => $say('Two thousand families, one patient reply at a time.', 'دو هزار خانواده، یک پاسخ صبور در هر بار.'), 'cover' => $art['cyan'], 'href' => '#'],
    ];
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Customer stories', 'قصه‌های مشتریان') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Drag the front card sideways, use the dots, the buttons or the arrow keys — neighbours fall back and soften. The current slide rides a live Livewire property.', 'کارت جلو را کنار بکشید، از نقطه‌ها یا دکمه‌ها یا فلش‌های کیبورد استفاده کنید — همسایه‌ها عقب می‌افتند و نرم می‌شوند. اسلاید جاری با یک پراپرتی زندهٔ Livewire می‌ماند.') }}
        </p>
    </div>
    <x-nx::depth-carousel :label="$say('Customer stories', 'قصه‌های مشتریان')" :items="$stories" wire:model.live="state.slide" />
    <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
        {{ $say('Slide on the server (wire:model.live):', 'اسلاید روی سرور (wire:model.live):') }}
        <code>{{ $stories[array_keys($stories)[$state['slide'] ?? 0]]['title'] ?? '—' }}</code>
    </p>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A product tour with a custom slide', 'تور محصول با یک اسلاید سفارشی') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('loop wraps past the last card, and the slide keyed “demo” is a slot — any content you like, not just a cover.', 'loop از آخرین کارت دور می‌زند و اسلایدی که کلیدش «demo» است یک اسلات است — هر محتوایی، نه فقط یک جلد.') }}
        </p>
    </div>
    <x-nx::depth-carousel :label="$say('Product tour', 'تور محصول')" :items="[
        'capture' => ['title' => $say('Capture', 'ثبت'), 'description' => $say('Email in, chat in, everything in.', 'ایمیل، گفت‌وگو، همه‌چیز داخل.'), 'cover' => $art['cyan']],
        'demo' => ['title' => $say('The live demo', 'دموی زنده')],
        'ship' => ['title' => $say('Ship', 'انتشار'), 'description' => $say('From inbox to answered in a minute.', 'از صندوق ورودی تا پاسخ، در یک دقیقه.'), 'cover' => $art['gold']],
    ]">
        <x-slot:demo>
            <div style="display: grid; place-items: center; gap: .5rem; block-size: 100%; color: var(--nx-accent-text); background: var(--nx-surface-2)">
                <span style="font: 800 var(--nx-text-4xl) / 1 var(--nx-font-display)">۳۹٪</span>
                <span style="font-size: var(--nx-text-sm); color: var(--nx-text-muted)">{{ $say('faster first reply, this quarter', 'نخستین پاسخ سریع‌تر، در این فصل') }}</span>
            </div>
        </x-slot:demo>
    </x-nx::depth-carousel>
</section>
