{{--
    The voice recorder as a support reply: mic → live pill (rolling mm:ss,
    pause, cancel) → playback chip with a scrubbable waveform. It never asks
    for the microphone — an app feeds levels by dispatching nx-record-level on
    the element. The wrapper carries the event handlers, so the messages stay
    whole; the second recorder grows from the other end, capped at 30 seconds.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $js = fn (string $php) => json_encode($php);

    $stopPrefix = $say('Voice note ready: ', 'یادداشت صوتی آماده: ');
    $stopSuffix = $say(' seconds', ' ثانیه');
    $cancelMsg = $say('Recording scrapped', 'ضبط دور انداخته شد');
    $discardMsg = $say('Take deleted', 'قطعه حذف شد');
    $cappedMsg = $say('Capped take kept', 'قطعهٔ سقف‌خورده نگه داشته شد');
@endphp

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Answering a ticket with your voice', 'پاسخ تیکت با صدا') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('Press the mic: the button becomes a recording pill — the clock rolls, pause holds it, stop hands you a playback chip you can scrub. Stop fires with the duration.', 'میکروفون را بزنید: دکمه به قرص ضبط بدل می‌شود — ساعت می‌غلتد، توقف نگهش می‌دارد، پایان ضبط تراشهٔ پخشی می‌دهد که می‌توانید در آن جابه‌جا شوید. رویداد پایان با مدت فرستاده می‌شود.') }}
            </p>
        </div>
        <div style="display: grid; gap: .875rem; padding: 1rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-xl); background: var(--nx-surface-2)"
            x-on:nx-record-stop="$wire.save({{ $js($stopPrefix) }} + $event.detail.duration + {{ $js($stopSuffix) }})"
            x-on:nx-record-cancel="$wire.ping({{ $js($cancelMsg) }})"
            x-on:nx-record-discard="$wire.ping({{ $js($discardMsg) }})">
            <div class="pg-row" style="gap: .625rem">
                <x-nx::avatar name="{{ $say('Kian Rajaee', 'کیان رجایی') }}" size="sm" />
                <span style="font-size: var(--nx-text-sm)"><strong>{{ $say('Kian Rajaee', 'کیان رجایی') }}</strong> · {{ $say('“the invoice PDF is blank on my phone”', '«فاکتور PDF روی گوشی‌ام خالی است»') }}</span>
            </div>
            <div class="pg-row">
                <x-nx::voice-recorder max-duration="120" />
            </div>
            <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
                {{ $say('No permission prompt, ever — wire your real levels in by dispatching nx-record-level on the element.', 'هیچ پرسش مجازی در کار نیست — سطح‌های واقعی‌تان را با فرستادن nx-record-level روی خود عنصر بدهید.') }}
            </p>
        </div>
    </section>

    <section class="pg-box" style="gap: 1rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A 30-second cap, growing the other way', 'سقف ۳۰ ثانیه، رشد از سوی دیگر') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('align="end" suits toolbars pinned at a row’s far end; the pill stops by itself at the cap.', 'با align="end" برای نوارهای ابزارِ انتهای ردیف مناسب است؛ قرص خودش در سقف می‌ایستد.') }}
            </p>
        </div>
        <div class="pg-row" style="justify-content: flex-end; padding: 1rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-xl); background: var(--nx-surface-2)"
            x-on:nx-record-stop="$wire.ping({{ $js($cappedMsg) }})">
            <x-nx::voice-recorder max-duration="30" align="end" :bars="18" />
        </div>
        <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
            {{ $say('bars narrows the live wave; take-bars shapes the playback scrubber.', 'با bars موج زنده باریک‌تر می‌شود؛ take-bars شکل اسکرابر پخش را تعیین می‌کند.') }}
        </p>
    </section>
</div>
