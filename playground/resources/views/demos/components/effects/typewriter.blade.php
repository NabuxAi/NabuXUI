{{--
    Typewriter's real scenarios: an agency landing hero that cycles what it
    builds (deleting only the changing end), and an assistant's status line
    typed word by word that stops on its last phrase.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Agency hero', 'هیروی یک آژانس') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Only the end of the line changes, so only the end is deleted and retyped. Persian types a grapheme at a time — letters with their half-spaces stay whole.', 'فقط انتهای جمله عوض می‌شود، پس فقط همان پاک و دوباره تایپ می‌شود. فارسی گرافیم‌به‌گرافیم تایپ می‌شود — حرف با نیم‌فاصله‌اش یک تکه است.') }}
        </p>
    </div>
    <div style="display: grid; gap: 1rem; padding: 2.5rem 1.5rem; border-radius: var(--nx-radius-xl); background: var(--nx-surface-2)">
        <h2 style="margin: 0; font: 800 var(--nx-text-4xl) / 1.2 var(--nx-font-display); letter-spacing: var(--nx-tracking-tight); min-block-size: 2.4em">
            <x-nx::typewriter :phrases="$fa
                ? ['ما وب‌سایت می‌سازیم', 'ما اپلیکیشن می‌سازیم', 'ما برند می‌سازیم', 'ما تجربه می‌سازیم']
                : ['We build websites', 'We build mobile apps', 'We build brands', 'We build experiences']" />
        </h2>
        <p style="margin: 0; color: var(--nx-text-muted)">{{ $say('A studio of twelve in Istanbul and Berlin.', 'استودیویی دوازده‌نفره در استانبول و برلین.') }}</p>
        <div class="pg-row"><x-nx::button variant="primary" shape="pill" icon-end="arrow-right">{{ $say('See our work', 'نمونه‌کارها') }}</x-nx::button></div>
    </div>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Assistant status', 'وضعیت دستیار') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('by="word" and :loop="false": the status types word by word and rests on its last phrase with a block caret.', 'by="word" و ‎:loop="false"‎: وضعیت کلمه‌به‌کلمه تایپ می‌شود و روی عبارت آخر با نشانگر مربعی می‌ماند.') }}
        </p>
    </div>
    <div class="pg-row" style="align-items: flex-start; gap: .75rem; padding: 1rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-xl); background: var(--nx-surface)">
        <span style="display: grid; place-items: center; flex: none; inline-size: 2rem; block-size: 2rem; border-radius: var(--nx-radius-full); background: var(--nx-accent-soft); color: var(--nx-accent-text)">{{ \NabuXUI\NabuXUI::icon('sparkles') }}</span>
        <p style="margin: 0; color: var(--nx-text-muted); font-family: var(--nx-font-mono); font-size: var(--nx-text-sm); line-height: 2">
            <x-nx::typewriter by="word" :loop="false" caret="block" :type-speed="140" :hold="900" :phrases="$fa
                ? ['در حال خواندن ۳ فایل…', 'در حال مقایسهٔ قراردادها…', 'پیش‌نویس پاسخ آماده است.']
                : ['Reading 3 files…', 'Comparing the contracts…', 'Your draft reply is ready.']" />
        </p>
    </div>
</section>
