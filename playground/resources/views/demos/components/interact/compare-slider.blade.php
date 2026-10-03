{{--
    Compare slider's real scenarios: a photo editor's before/after with the
    split position kept in Livewire state, a vertical split for a redesign
    review, and the RTL frame where "before" sits on the right.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $scene = fn (string $body) => 'data:image/svg+xml,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 750">' . $body . '</svg>');

    $landscape = fn (array $c) => $scene(
        '<rect width="1200" height="750" fill="'.$c[0].'"/><circle cx="900" cy="190" r="80" fill="'.$c[1].'"/>'
        .'<path d="M0 520 L240 280 L430 470 L640 230 L900 520 L1200 360 V750 H0Z" fill="'.$c[2].'"/>'
        .'<path d="M0 600 Q600 520 1200 610 V750 H0Z" fill="'.$c[3].'"/><path d="M0 680 Q500 640 1200 690 V750 H0Z" fill="'.$c[4].'"/>'
    );
    $raw = $landscape(['#9aa3ad', '#d8d8d8', '#6f757c', '#7b8077', '#5c605a']);
    $graded = $landscape(['#ffcf8f', '#fff2c4', '#5b5ea6', '#2f9e6e', '#1f6f50']);

    $ui = fn (bool $new) => $scene(
        '<rect width="1200" height="750" fill="'.($new ? '#f4f5fb' : '#ececec').'"/>'
        .'<rect x="0" y="0" width="1200" height="84" fill="'.($new ? '#ffffff' : '#3b5998').'"/>'
        .'<rect x="48" y="28" width="160" height="28" rx="'.($new ? 14 : 0).'" fill="'.($new ? '#5647e6' : '#ffffff').'"/>'
        .'<rect x="48" y="140" width="'.($new ? 520 : 700).'" height="'.($new ? 56 : 40).'" rx="'.($new ? 10 : 0).'" fill="'.($new ? '#0a0c17' : '#333333').'"/>'
        .'<rect x="48" y="220" width="460" height="22" rx="'.($new ? 11 : 0).'" fill="'.($new ? '#8a8fa8' : '#777777').'"/>'
        .'<rect x="48" y="290" width="'.($new ? 200 : 140).'" height="56" rx="'.($new ? 28 : 2).'" fill="'.($new ? '#5647e6' : '#4caf50').'"/>'
        .'<rect x="'.($new ? 680 : 60).'" y="'.($new ? 130 : 400).'" width="'.($new ? 470 : 1080).'" height="'.($new ? 560 : 300).'" rx="'.($new ? 28 : 0).'" fill="'.($new ? '#e3e6ff' : '#d0d0d0').'"/>'
    );
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A photo editor’s before/after', 'قبل و بعدِ یک ویرایشگر عکس') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Drag anywhere on the photo (or Tab to it and use the arrows, Home, End). Underneath is a real range input, so wire:model keeps the split in Livewire state.', 'هر جای عکس را بکشید (یا با Tab بروید و جهت‌ها، Home، End را بزنید). زیرش یک range واقعی است، پس wire:model جای برش را در وضعیت Livewire نگه می‌دارد.') }}
        </p>
    </div>
    <div style="max-inline-size: 44rem; display: grid; gap: .75rem">
        <x-nx::compare-slider wire:model.live.debounce.250ms="state.split" :value="$this->state['split'] ?? 50"
            :before="['src' => $raw, 'alt' => $say('The raw photo, flat and grey', 'عکس خام، تخت و خاکستری')]"
            :after="['src' => $graded, 'alt' => $say('The colour-graded photo, warm sky and green hills', 'عکسِ اصلاح‌رنگ‌شده، آسمان گرم و تپه‌های سبز')]"
            :before-label="$say('Raw', 'خام')" :after-label="$say('Graded', 'اصلاح‌شده')" :label="$say('Split position', 'جای برش')" />
        <span style="color: var(--nx-text-muted); font-size: var(--nx-text-sm)">{{ $say('Split on the server:', 'برش روی سرور:') }} {{ \NabuXUI\NabuXUI::formatNumber((float) ($this->state['split'] ?? 50)) }}{{ $say('%', '٪') }}</span>
    </div>
</section>

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Redesign review, top to bottom', 'بازبینی طراحی تازه، بالا به پایین') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('orientation="vertical": the old landing page above the line, the new one below.', 'orientation="vertical": صفحهٔ قدیمی بالای خط و تازه پایینش.') }}
        </p>
        <x-nx::compare-slider orientation="vertical" ratio="16 / 10" :value="40"
            :before="['src' => $ui(false), 'alt' => $say('The 2019 landing page', 'صفحهٔ فرود ۲۰۱۹')]"
            :after="['src' => $ui(true), 'alt' => $say('The redesigned landing page', 'صفحهٔ فرودِ بازطراحی‌شده')]"
            :before-label="$say('2019', '۲۰۱۹')" :after-label="$say('Now', 'اکنون')" :label="$say('Split position', 'جای برش')" />
    </section>

    <section class="pg-box" dir="rtl" lang="fa" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Right to left', 'راست‌به‌چپ') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('“Before” sits at the inline start — the right — and the range maps right to left natively.', '«قبل» در ابتدای سطر — سمت راست — می‌نشیند و range به‌طور بومی از راست به چپ نگاشت می‌شود.') }}
        </p>
        <x-nx::compare-slider before-label="قبل" after-label="بعد" label="جای برش" :value="35"
            :before="['src' => $raw, 'alt' => 'عکس خام']" :after="['src' => $graded, 'alt' => 'عکس اصلاح‌شده']" />
    </section>
</div>
