{{-- Livewire demos: Text & hero motion. --}}
@php
    $languages = ['English', 'Español', 'Français', 'Deutsch', '日本語', '中文', 'العربية', 'فارسی', 'हिन्दी', 'Português', '한국어', 'Türkçe'];
    $stage = 'position: relative; isolation: isolate; overflow: clip; place-content: center; justify-items: center; text-align: center';
@endphp

<section class="pg-box" style="padding: 0; overflow: clip">
    <x-nx::text-hero as="h2" title="Write in every language" highlight="every language"
        subtitle="Hola · Bonjour · こんにちは · سلام · नमस्ते — one headline, and every letter springs into place."
        :stickers="[
            ['shape' => 'star', 'position' => 'top-start'],
            ['shape' => 'sparkle', 'position' => 'top-end'],
            ['shape' => 'smiley', 'position' => 'bottom-start'],
            ['shape' => 'heart', 'position' => 'bottom-end'],
            ['shape' => 'arrow', 'position' => 'start'],
        ]">
        <x-slot:actions>
            <x-nx::button variant="primary" shape="pill" icon-end="arrow-right" wire:click="ping('Writing in every language')">Start writing</x-nx::button>
            <x-nx::button variant="ghost" shape="pill">See the scripts</x-nx::button>
        </x-slot:actions>
    </x-nx::text-hero>
</section>

<div class="pg-grid">
    <section class="pg-box" dir="rtl" lang="ar" style="padding: 0; overflow: clip">
        <x-nx::text-hero as="h2" title="اكتب بكل لغة" highlight="بكل لغة" style="padding-block: 4rem"
            subtitle="العربية والفارسية تنهضان كلمةً كلمة، فتبقى حروفهما متصلة."
            :stickers="[['shape' => 'bolt', 'position' => 'top-end'], ['shape' => 'heart', 'position' => 'bottom-start', 'tone' => 'violet']]" />
    </section>

    <section class="pg-box" style="{{ $stage }}; min-block-size: 26rem">
        <x-nx::dither-backdrop />
        <x-nx::badge tone="accent" dot>Now in 12 languages</x-nx::badge>
        <h2 class="pg-title" style="font-size: var(--nx-text-4xl); max-inline-size: 18ch">Support that speaks your customer’s language</h2>
        <p style="margin: 0; color: var(--nx-text-muted)">Agents that answer in Español, Français, 日本語 and فارسی — in seconds.</p>
        <div class="pg-row" style="justify-content: center">
            <x-nx::button variant="primary" shape="pill">Start free</x-nx::button>
            <x-nx::button shape="pill">Book a demo</x-nx::button>
        </div>
    </section>
</div>

<section class="pg-box" style="padding: 0; overflow: clip">
    <x-nx::scroll-text-reveal text="Every word finds its place" eyebrow="Scroll · Desplázate · スクロール" height="220vh" />
</section>

<section class="pg-box">
    <h2 class="pg-title">Hover reveal</h2>
    <x-nx::hover-reveal :items="[
        ['label' => 'Design · Diseño · デザイン', 'href' => '#', 'description' => 'Interfaces with a sense of motion'],
        ['label' => 'Motion · Mouvement · Bewegung', 'href' => '#', 'description' => 'Springs, not durations'],
        ['label' => 'Language · لغة · زبان', 'href' => '#', 'description' => 'Right to left from the first line'],
        ['label' => 'Build · Construir · 만들기', 'href' => '#', 'description' => 'Livewire, Inertia and React'],
    ]" />
</section>

<section class="pg-box" dir="rtl" lang="fa">
    <h2 class="pg-title">پیوندهای درخشان · راست‌به‌چپ</h2>
    <x-nx::hover-reveal :items="[
        ['label' => 'طراحی', 'href' => '#', 'description' => 'رابط‌هایی که حس حرکت دارند'],
        ['label' => 'حرکت · Motion', 'href' => '#'],
        ['label' => 'ساختن', 'href' => '#', 'description' => 'Livewire، Inertia و React'],
    ]" />
</section>

<section class="pg-box" style="padding: 0; overflow: clip">
    <x-nx::scroll-scramble title="Decoding every script" :items="$languages" />
</section>

<section class="pg-box" dir="rtl" lang="fa" style="padding: 0; overflow: clip">
    <x-nx::scroll-scramble title="رمزگشایی هر خط" :items="['فارسی', 'العربية', 'English', '日本語', 'हिन्दी']" height="200vh" />
</section>

<div class="pg-grid">
    <section class="pg-box">
        <h2 class="pg-title">Roll text</h2>
        <nav aria-label="Demo menu" class="pg-row" style="gap: 0.5rem 1.5rem; font-size: var(--nx-text-2xl); font-weight: 700">
            <x-nx::roll-text href="#">Work</x-nx::roll-text>
            <x-nx::roll-text href="#">Studio</x-nx::roll-text>
            <x-nx::roll-text href="#">Journal</x-nx::roll-text>
            <x-nx::roll-text href="#">Kontakt</x-nx::roll-text>
            <x-nx::roll-text href="#">お問い合わせ</x-nx::roll-text>
            <x-nx::roll-text href="#">تماس با ما</x-nx::roll-text>
        </nav>
        <div class="pg-row">
            <x-nx::button variant="primary" shape="pill" icon-end="arrow-right"><x-nx::roll-text>Get in touch</x-nx::roll-text></x-nx::button>
            <x-nx::roll-text as="button" style="font-weight: 600" wire:click="ping('Menu')">Menu · Menú · メニュー</x-nx::roll-text>
        </div>
    </section>

    <section class="pg-box">
        <h2 class="pg-title">Pulse button</h2>
        <div class="pg-row" style="justify-content: center; align-items: start; gap: 2.5rem">
            <x-nx::pulse-button size="sm" aria-label="Next" wire:click="ping('Next')" />
            <x-nx::pulse-button icon="play" tone="gold">Écouter</x-nx::pulse-button>
            <x-nx::pulse-button icon="sparkles" tone="inverse" size="lg">Try it · 試す</x-nx::pulse-button>
        </div>
    </section>
</div>

<section class="pg-box" style="{{ $stage }}; min-block-size: 30rem">
    <x-nx::backdrop variant="mesh" />
    <p style="margin: 0; color: var(--nx-text-subtle); font-size: var(--nx-text-xs); font-weight: 600">Mesh backdrop and pulse button</p>
    <h2 class="pg-title" style="font-size: var(--nx-text-5xl); max-inline-size: 18ch">Light, in every colour of the language</h2>
    <p style="margin: 0; color: var(--nx-text-muted)">Luz · Lumière · Licht · 光 · نور — a mesh of lights over a wireframe floor.</p>
    <x-nx::pulse-button icon="play" size="lg" href="#">Play the reel</x-nx::pulse-button>
</section>

<section class="pg-box" style="{{ $stage }}; min-block-size: 24rem">
    <x-nx::backdrop variant="stripes" />
    <p style="margin: 0; color: var(--nx-text-subtle); font-size: var(--nx-text-xs); font-weight: 600">Stripes backdrop</p>
    <h2 class="pg-title" style="font-size: var(--nx-text-5xl); max-inline-size: 18ch">Signals on every line</h2>
    <p style="margin: 0; color: var(--nx-text-muted)">Señales · Signaux · Signale · 信号 — pulses of light, each at its own speed.</p>
    <div class="pg-row" style="justify-content: center; font-size: var(--nx-text-lg); font-weight: 600">
        <x-nx::roll-text href="#">Changelog</x-nx::roll-text>
        <x-nx::roll-text href="#">Docs</x-nx::roll-text>
        <x-nx::roll-text href="#">Status</x-nx::roll-text>
    </div>
</section>
