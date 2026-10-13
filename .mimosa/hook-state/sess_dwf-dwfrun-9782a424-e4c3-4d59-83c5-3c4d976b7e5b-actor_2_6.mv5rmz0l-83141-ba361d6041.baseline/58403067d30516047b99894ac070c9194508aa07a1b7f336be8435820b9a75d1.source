{{--
    The audio room as a live support hangout: the pill opens like a dynamic
    island, the host is speaking (the little equalizer dances), one listener
    is muted, and the buttons dispatch real events. The second room starts
    open, the way a room looks when you host it.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $members = [
        ['name' => $say('Ava Karimi', 'آوا کریمی'), 'role' => $say('Host', 'میزبان'), 'speaking' => true],
        ['name' => $say('Soheil Nouri', 'سهیل نوری'), 'speaking' => true],
        ['name' => $say('Mia Novak', 'مونا احمدی'), 'muted' => true],
        ['name' => $say('Kian Rajaee', 'کیان رجایی')],
        ['name' => $say('Sara Mohammadi', 'سارا محمدی'), 'muted' => true],
    ];
@endphp

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem; justify-items: center">
        <div style="justify-self: start">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The live room pill', 'قرص اتاق زنده') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('Press the pill: it grows into the room. Mute, raise a hand, leave — each control dispatches its event; we toast what you did.', 'قرص را فشار دهید: به اتاق بزرگ می‌شود. بی‌صدا کردن، بالا بردن دست، خروج — هر کنترل رویداد خودش را می‌فرستد؛ ما کاری که کردید را توست می‌کنیم.') }}
            </p>
        </div>
        <x-nx::audio-room title="{{ $say('Design crit · live', 'نقد طراحی · زنده') }}" :listeners="1284" :members="$members"
            x-on:nx-room-mute="$wire.ping($event.detail.muted ? {{ json_encode($say('You went mute', 'بی‌صدا شدید')) }} : {{ json_encode($say('You are audible', 'صدایتان می‌آید')) }})"
            x-on:nx-room-hand="$wire.ping($event.detail.raised ? {{ json_encode($say('Hand raised', 'دست بالا رفت')) }} : {{ json_encode($say('Hand lowered', 'دست پایین آمد')) }})"
            x-on:nx-room-leave="$wire.save({{ json_encode($say('You left the room — mic free', 'از اتاق خارج شدید')) }})" />
    </section>

    <section class="pg-box" style="gap: 1rem; justify-items: center">
        <div style="justify-self: start">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Starting open, like a host', 'باز شروع می‌شود، مثل میزبان') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('open="true" renders the room expanded, with your own count rolled in Persian digits:', 'با open="true" اتاق باز رندر می‌شود، با شمار شنوندهٔ خودتان که با ارقام فارسی می‌غلتد:') }}
                <strong>{{ NabuXUI::formatNumber(312) }}</strong>
            </p>
        </div>
        <x-nx::audio-room :open="true" :muted="true" :listeners="312" title="{{ $say('Nabu fan night', 'شب هواداران نابو') }}" :members="[
            ['name' => $say('You', 'شما'), 'role' => $say('Host', 'میزبان')],
            ['name' => $say('Parham Sabeti', 'پرهام ثابتی'), 'speaking' => true],
            ['name' => $say('Hana Qomi', 'حنا قمی')],
        ]" x-on:nx-room-leave="$wire.ping({{ json_encode($say('Goodnight!', 'شب بخیر!')) }})" />
    </section>
</div>
