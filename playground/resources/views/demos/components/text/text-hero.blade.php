{{--
    The text hero's real scenarios: the launch hero of a marketing page (with
    its CTA row), then a start-aligned secondary hero living inside a card —
    the way a changelog or an onboarding panel uses it.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<section class="pg-box" style="padding: 0; overflow: clip">
    <x-nx::text-hero as="h1"
        :title="$say('Ship in every language your users speak', 'با هر زبانی که کاربرت می‌فهمد منتشر کن')"
        :highlight="$say('every language', 'هر زبانی')"
        :subtitle="$say('One design system, five frameworks, two directions — Persian settles word by word, Latin letter by letter.', 'یک سیستم طراحی، پنج فریم‌ورک، دو جهت — فارسی کلمه‌به‌کلمه و لاتین حرف‌به‌حرف سرِ جای خود می‌نشیند.')"
        :stickers="[
            ['shape' => 'star', 'position' => 'top-start'],
            ['shape' => 'sparkle', 'position' => 'top-end'],
            ['shape' => 'heart', 'position' => 'bottom-end', 'tone' => 'pink'],
            ['shape' => 'smiley', 'position' => 'bottom-start'],
        ]">
        <x-slot:actions>
            <x-nx::button variant="primary" shape="pill" icon-end="arrow-right" wire:click="save(@js($say('Workspace is ready', 'ورک‌اسپیس آماده شد')))">
                {{ $say('Start free', 'شروع رایگان') }}
            </x-nx::button>
            <x-nx::button variant="ghost" shape="pill" href="/components" wire:navigate>
                {{ $say('See the components', 'دیدن کامپوننت‌ها') }}
            </x-nx::button>
        </x-slot:actions>
    </x-nx::text-hero>
</section>

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A secondary hero inside a card', 'هیروی فرعی داخل کارت') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('align="start" and as="h2" — the card that opens the onboarding flow; the stickers frame the smaller title just the same.', 'با align="start" و as="h2" — کارتی که جریان راه‌اندازی را باز می‌کند؛ برچسب‌ها دور تیترِ کوچک‌تر هم همین‌طور می‌ایستند.') }}
        </p>
        <div style="border: 1px solid var(--nx-border); border-radius: var(--nx-radius-xl); padding: 1.5rem">
            <x-nx::text-hero as="h2" align="start"
                :title="$say('Set up your workspace', 'ورک‌اسپیس خودت را بساز')"
                :highlight="$say('workspace', 'بساز')"
                :subtitle="$say(NabuXUI::formatNumber(2).' minutes, no credit card.', NabuXUI::formatNumber(2).' دقیقه، بدون کارت بانکی.')"
                :stickers="[['shape' => 'bolt', 'position' => 'top-end'], ['shape' => 'arrow', 'position' => 'bottom-start']]">
                <x-slot:actions>
                    <x-nx::button variant="secondary" icon-end="arrow-right" wire:click="ping(@js($say('Setup checklist opened', 'چک‌لیست راه‌اندازی باز شد')))">
                        {{ $say('Open the checklist', 'بازکردن چک‌لیست') }}
                    </x-nx::button>
                </x-slot:actions>
            </x-nx::text-hero>
        </div>
    </section>

    <section class="pg-box" dir="rtl" lang="fa" style="padding: 0; overflow: clip">
        <h3 class="pg-title" style="padding: 1.5rem 1.5rem 0; font-size: var(--nx-text-xl)">{{ $say('The other direction, natively', 'جهت دیگر، بومی') }}</h3>
        <x-nx::text-hero as="h2" title="راست‌به‌چپ از همان خط اول" highlight="همان خط" style="padding-block: 3rem"
            subtitle="فارسی و عربی کلمه‌به‌کلمه می‌نشینند تا حروف نچسبنده نمانند."
            :stickers="[['shape' => 'heart', 'position' => 'top-end', 'tone' => 'violet'], ['shape' => 'sparkle', 'position' => 'bottom-start']]" />
    </section>
</div>
