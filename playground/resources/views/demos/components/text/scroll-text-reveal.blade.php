{{--
    Scroll text reveal's real scenarios: a brand manifesto pinned mid-page, and
    a shorter second statement with its own seed so the two never scatter the
    same way. Both are tall by design — the scroll drives the gather.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<section class="pg-box" style="padding: 0; overflow: clip">
    <x-nx::scroll-text-reveal
        :text="$say('Every word finds its place', 'هر کلمه جای خودش را پیدا می‌کند')"
        :eyebrow="$say('Scroll · Manifesto', 'اسکرول کنید · مانیفست')"
        height="200vh" />
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A second statement, scattered differently', 'جملهٔ دوم، با پراکندگی دیگر') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Same component, seed=7 and a shorter height — the letters start from a different spread and gather a little faster. Both directions read correctly.', 'همان کامپوننت با seed=7 و ارتفاع کمتر — حروف از پراکندگی دیگری شروع می‌کنند و کمی زودتر جمع می‌شوند. هر دو جهت درست خوانده می‌شوند.') }}
        </p>
    </div>
</section>

<section class="pg-box" style="padding: 0; overflow: clip">
    <x-nx::scroll-text-reveal
        :text="$say('Interfaces that respect both directions', 'رابط‌هایی که به هر دو جهت احترام می‌گذارند')"
        :eyebrow="$say('Scroll again', 'دوباره اسکرول کنید')"
        height="160vh"
        :seed="7" />
</section>
