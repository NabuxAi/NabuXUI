{{--
    Blob button's real scenarios: the dark hero CTA of a contact section (the
    blob button's natural habitat), then a compact pricing footer where it sits
    beside quieter buttons.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<section class="pg-box" style="gap: 1.25rem; background:
    radial-gradient(120% 120% at 50% 0%, var(--nx-surface-2), var(--nx-surface))">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The contact hero', 'هیروی تماس') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The blobs drift on their own and gather under the pointer — a CTA that feels alive before you even press it.', 'blobها خودشان رها می‌شوند و زیر موس جمع می‌شوند — CTAای که پیش از فشردن هم زنده به نظر می‌رسد.') }}
        </p>
    </div>
    <div class="pg-row" style="align-items: center; gap: 2rem">
        <x-nx::blob-button icon="sparkles" size="lg" wire:click="save(@js($say('We usually reply within '.NabuXUI::formatNumber(2).' hours', 'معمولاً کمتر از '.NabuXUI::formatNumber(2).' ساعت جواب می‌دهیم')))">
            {{ $say('Talk to Nabu', 'گفت‌وگو با نابو') }}
        </x-nx::blob-button>
        <x-nx::blob-button size="sm" icon-end="arrow-right" href="/components" wire:navigate>
            {{ $say('Or browse the demos', 'یا دموها را ورق بزن') }}
        </x-nx::blob-button>
    </div>
</section>

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Beside quieter buttons', 'کنار دکمه‌های آرام‌تر') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('The blob button takes the star action of a row and lets the base Button stay quiet around it.', 'دکمهٔ blob نقش ستارهٔ ردیف را می‌گیرد و دکمهٔ پایه آرام کنارش می‌ماند.') }}
        </p>
        <div class="pg-row" style="align-items: center; gap: 1.5rem">
            <x-nx::blob-button wire:click="ping(@js($say('Checkout opened', 'درگاه پرداخت باز شد')))">
                {{ $say('Buy the team plan', 'خرید پلن تیمی') }}
            </x-nx::blob-button>
            <x-nx::button variant="ghost" wire:click="ping(@js($say('Invoice emailed', 'فاکتور ایمیل شد')))">{{ $say('Email me an invoice', 'فاکتور را ایمیل کن') }}</x-nx::button>
        </div>
    </section>

    <section class="pg-box" dir="rtl" lang="fa" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Right to left', 'راست‌به‌چپ') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('The blobs do not care about direction — they follow the pointer wherever it comes from.', 'blobها به جهت کاری ندارند — از هر سمتی که موس بیاید دنبالش می‌روند.') }}
        </p>
        <div class="pg-row" style="align-items: center; gap: 1.5rem">
            <x-nx::blob-button icon="message" wire:click="save(@js('پیام‌ات رسید'))">ارسال پیام</x-nx::blob-button>
            <x-nx::blob-button size="sm" icon-end="arrow-right" href="#" >پرسش‌های پرتکرار</x-nx::blob-button>
        </div>
    </section>
</div>
