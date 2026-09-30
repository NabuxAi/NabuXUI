{{--
    The branch connector's real scenarios: an inbound message forking to
    language agents and a human queue, then a CI pipeline fanning a build
    out to its deploy targets.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('One message, four ways out', 'یک پیام، چهار راه خروج') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The router at the heart of Nabu: pulses run along the active links; the human handoff sits idle until an agent calls for it. The paths are measured from the page and redraw as it resizes.', 'مسیریابِ قلب نابو: پالس روی لینک‌های فعال می‌دود؛ واگذار به انسان idle است تا وقتی ایجنت بخواهد. مسیرها از صفحه اندازه‌گیری می‌شوند و با تغییر اندازه دوباره کشیده می‌شوند.') }}
        </p>
    </div>
    <x-nx::branch-connector
        :source="['label' => $say('Inbound message', 'پیام ورودی'), 'description' => $say('WhatsApp · Telegram · Web', 'واتساپ · تلگرام · وب'), 'icon' => 'message']"
        :targets-label="$say('Routed to', 'مسیر به')"
        :targets="[
            ['label' => $say('Nabu agent · فارسی', 'ایجنت نابو · فارسی'), 'description' => $say('Answers natively', 'پاسخ بومی'), 'icon' => 'sparkles'],
            ['label' => $say('Agente Nabu · Español', 'ایجنت نابو · اسپانیایی'), 'description' => $say('Español · Português', 'اسپانیایی · پرتغالی'), 'icon' => 'globe'],
            ['label' => $say('Nabu エージェント · 日本語', 'ایجنت نابو · ژاپنی'), 'description' => $say('日本語 · 한국어', 'ژاپنی · کره‌ای'), 'icon' => 'cpu'],
            ['label' => $say('Human handoff', 'واگذار به انسان'), 'description' => $say('Queue: billing', 'صف: مالی'), 'icon' => 'users', 'state' => 'idle'],
        ]" />
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A build fanning out', 'یک بیلد که پهن می‌شود') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The same block for a deploy pipeline — narrow the window and watch it stack itself: source above, targets below.', 'همان بلوک برای خط لولهٔ انتشار — پنجره را تنگ کنید تا خودش را پشته کند: منبع بالا، هدف‌ها پایین.') }}
        </p>
    </div>
    <x-nx::branch-connector
        :source="['label' => $say('main @ 9a13f2c', 'main @ 9a13f2c'), 'description' => $say('All checks green', 'همهٔ بررسی‌ها سبز'), 'icon' => 'check-circle']"
        :targets="[
            ['label' => $say('Preview', 'پیش‌نمایش'), 'description' => $say('pr-4821.nabu.app', 'pr-4821.nabu.app'), 'icon' => 'globe'],
            ['label' => $say('Staging', 'استیجینگ'), 'description' => $say('Auto, on merge', 'خودکار، هنگام ادغام'), 'icon' => 'layers'],
            ['label' => $say('Production', 'تولید'), 'description' => $say('Holds for a human', 'منتظر انسان'), 'icon' => 'shield', 'state' => 'idle'],
        ]" />
</section>
