{{-- landing: the pitch — hero line, proof chips, and the two calls. --}}
@php $fa = app()->getLocale() === 'fa'; $say = fn (string $e, string $f) => $fa ? $f : $e; @endphp
<div style="max-inline-size: 40rem; margin-inline: auto; display: grid; gap: 1.1rem; text-align: center; padding-block: .5rem">
    <x-nx::badge tone="accent" style="justify-self: center">{{ $say('New — v2 is out', 'تازه — نسخهٔ ۲ رسید') }}</x-nx::badge>
    <h3 style="margin: 0; font: 800 clamp(1.6rem, 4.5vw, 2.4rem) / 1.15 var(--nx-font-display); letter-spacing: var(--nx-tracking-tight)">
        {{ $say('Ship faster than your roadmap', 'سریع‌تر از نقشهٔ راه‌تان منتشر کنید') }}
    </h3>
    <p style="margin: 0 auto; max-inline-size: 34rem; color: var(--nx-text-muted)">
        {{ $say('One design system, five frameworks, two directions — the pieces snap together so the team ships the same week.', 'یک سیستم طراحی، پنج فریم‌ورک، دو جهت — قطعه‌ها همان‌طور به هم می‌خورند که تیم همان هفته منتشر کند.') }}
    </p>
    <div class="aist-recipe-row" style="justify-content: center">
        <x-nx::button variant="primary" size="lg" icon-end="arrow-right">{{ $say('Start free', 'شروع رایگان') }}</x-nx::button>
        <x-nx::button variant="ghost" size="lg" icon="play">{{ $say('Watch the tour', 'تماشای تور') }}</x-nx::button>
    </div>
    <div class="aist-recipe-row" style="justify-content: center; gap: 1.4rem; font-size: var(--nx-text-xs); color: var(--nx-text-muted)">
        <span>★ {{ $say('4.9 on G2', '۴٫۹ در G2') }}</span>
        <span>{{ $say('12k teams', '۱۲هزار تیم') }}</span>
        <span>{{ $say('SOC 2', 'ساک ۲') }}</span>
    </div>
</div>
