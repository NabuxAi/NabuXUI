{{--
    Backdrop's real scenarios: the marketing hero, then four small scenes — the
    same panel in different moods. Every backdrop is the first child of a
    positioned section; the content sits after it.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $scene = 'position: relative; isolation: isolate; overflow: clip; place-content: center; justify-items: center; text-align: center; gap: .75rem';
@endphp

<section class="pg-box" style="{{ $scene }}; min-block-size: 22rem">
    <x-nx::backdrop variant="aurora" />
    <p style="position: relative; margin: 0; color: var(--nx-text-subtle); font-size: var(--nx-text-xs); font-weight: 600">aurora</p>
    <h3 class="pg-title" style="position: relative; margin: 0; font-size: var(--nx-text-3xl); max-inline-size: 20ch">
        {{ $say('The launch hero', 'هیروی انتشار') }}
    </h3>
    <p style="position: relative; margin: 0; color: var(--nx-text-muted); max-inline-size: 44ch">
        {{ $say('One decorative div and the section is dressed — no image, no gradient CSS of your own.', 'یک div تزئینی و بخش آماده است — نه تصویر، نه گرادیان دست‌ساز.') }}
    </p>
    <x-nx::button variant="primary" shape="pill" icon-end="arrow-right" wire:click="save(@js($say('Welcome aboard', 'خوش آمدی')))" style="position: relative">
        {{ $say('Start building', 'شروع ساختن') }}
    </x-nx::button>
</section>

<div class="pg-grid">
    <section class="pg-box" style="{{ $scene }}; min-block-size: 14rem">
        <x-nx::backdrop variant="grid" />
        <h3 class="pg-title" style="position: relative; margin: 0; font-size: var(--nx-text-xl)">{{ $say('Your dashboard awaits', 'داشبوردت منتظر است') }}</h3>
        <x-nx::button variant="secondary" size="sm" wire:click="ping(@js($say('First project created', 'نخستین پروژه ساخته شد')))" style="position: relative">
            {{ $say('Create a project', 'ساخت پروژه') }}
        </x-nx::button>
        <p style="position: absolute; inset-block-end: .75rem; inset-inline-start: 1rem; margin: 0; font-size: var(--nx-text-xs); color: var(--nx-text-subtle)">grid</p>
    </section>

    <section class="pg-box" style="{{ $scene }}; min-block-size: 14rem">
        <x-nx::backdrop variant="stars" />
        <h3 class="pg-title" style="position: relative; margin: 0; font-size: var(--nx-text-xl)">{{ $say('Night shift', 'شیفت شب') }}</h3>
        <x-nx::button variant="secondary" size="sm" wire:click="ping(@js($say('Dark mode stays in sync', 'تم تیره همگام ماند')))" style="position: relative">
            {{ $say('Flip the theme', 'گرداندن تم') }}
        </x-nx::button>
        <p style="position: absolute; inset-block-end: .75rem; inset-inline-start: 1rem; margin: 0; font-size: var(--nx-text-xs); color: var(--nx-text-subtle)">stars</p>
    </section>

    <section class="pg-box" style="{{ $scene }}; min-block-size: 14rem">
        <x-nx::backdrop variant="beams" />
        <h3 class="pg-title" style="position: relative; margin: 0; font-size: var(--nx-text-xl)">{{ $say('Release week', 'هفتهٔ انتشار') }}</h3>
        <x-nx::button variant="secondary" size="sm" href="/components" wire:navigate style="position: relative">
            {{ $say('See the changelog', 'دیدن تغییرات') }}
        </x-nx::button>
        <p style="position: absolute; inset-block-end: .75rem; inset-inline-start: 1rem; margin: 0; font-size: var(--nx-text-xs); color: var(--nx-text-subtle)">beams</p>
    </section>

    <section class="pg-box" style="{{ $scene }}; min-block-size: 14rem">
        <x-nx::backdrop variant="dots" />
        <h3 class="pg-title" style="position: relative; margin: 0; font-size: var(--nx-text-xl)">{{ $say('Playful corners', 'گوشه‌های بازیگوش') }}</h3>
        <x-nx::button variant="secondary" size="sm" wire:click="ping(@js($say('Hello from the dots', 'سلام از نقطه‌ها')))" style="position: relative">
            {{ $say('Say hi', 'سلام کن') }}
        </x-nx::button>
        <p style="position: absolute; inset-block-end: .75rem; inset-inline-start: 1rem; margin: 0; font-size: var(--nx-text-xs); color: var(--nx-text-subtle)">dots</p>
    </section>

    <section class="pg-box" style="{{ $scene }}; min-block-size: 14rem">
        <x-nx::backdrop variant="mesh" />
        <h3 class="pg-title" style="position: relative; margin: 0; font-size: var(--nx-text-xl)">{{ $say('Light on a wireframe', 'نور روی توری') }}</h3>
        <x-nx::button variant="secondary" size="sm" wire:click="ping(@js($say('Mesh vibes only', 'همه‌چیز حال‌وهوای مش است')))" style="position: relative">
            {{ $say('Feel it', 'حسش کن') }}
        </x-nx::button>
        <p style="position: absolute; inset-block-end: .75rem; inset-inline-start: 1rem; margin: 0; font-size: var(--nx-text-xs); color: var(--nx-text-subtle)">mesh</p>
    </section>

    <section class="pg-box" style="{{ $scene }}; min-block-size: 14rem">
        <x-nx::backdrop variant="stripes" />
        <h3 class="pg-title" style="position: relative; margin: 0; font-size: var(--nx-text-xl)">{{ $say('Signals on every line', 'سیگنال روی هر خط') }}</h3>
        <x-nx::button variant="secondary" size="sm" wire:click="ping(@js($say('Beep beep', 'بوق بوق')))" style="position: relative">
            {{ $say('Transmit', 'ارسال') }}
        </x-nx::button>
        <p style="position: absolute; inset-block-end: .75rem; inset-inline-start: 1rem; margin: 0; font-size: var(--nx-text-xs); color: var(--nx-text-subtle)">stripes</p>
    </section>
</div>
