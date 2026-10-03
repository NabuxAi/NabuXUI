{{--
    Border beam's real scenarios: the recommended plan in a pricing row, and an
    AI prompt box that wears the beam (the bare .nx-beam class) only while it works.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The plan we recommend', 'پلنی که پیشنهاد می‌کنیم') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Three plans side by side; only the middle one carries a slow gold beam, so the eye lands there without a shouting badge.', 'سه پلن کنار هم؛ فقط پلن وسط پرتو طلایی آرامی دارد تا چشم بی‌آن‌که برچسب پرسروصدایی لازم باشد، همان‌جا بنشیند.') }}
        </p>
    </div>
    <div style="display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(13rem, 1fr)); align-items: stretch">
        @foreach ([
            ['name' => $say('Starter', 'پایه'), 'price' => $say('Free', 'رایگان'), 'note' => $say('For trying things out', 'برای امتحان کردن'), 'beam' => false],
            ['name' => $say('Studio', 'استودیو'), 'price' => $say('$19 / mo', '۴۹۰ هزار تومان / ماه'), 'note' => $say('For small teams shipping weekly', 'برای تیم‌های کوچکی که هر هفته منتشر می‌کنند'), 'beam' => true],
            ['name' => $say('Enterprise', 'سازمانی'), 'price' => $say('Talk to us', 'تماس بگیرید'), 'note' => $say('SSO, audit log, SLA', 'ورود یکپارچه، گزارش ممیزی، SLA'), 'beam' => false],
        ] as $plan)
            @php
                $inner = '<div style="display: grid; gap: .75rem; block-size: 100%; padding: 1.25rem; border: 1px solid var(--nx-border); border-radius: inherit; background: var(--nx-surface)">'
                    .'<strong style="font: 700 var(--nx-text-lg) / 1.2 var(--nx-font-display)">'.e($plan['name']).'</strong>'
                    .'<span style="font: 700 var(--nx-text-2xl) / 1 var(--nx-font-display)">'.e($plan['price']).'</span>'
                    .'<span style="color: var(--nx-text-muted); font-size: var(--nx-text-sm)">'.e($plan['note']).'</span></div>';
            @endphp
            @if ($plan['beam'])
                <x-nx::border-beam tone="gold" radius="var(--nx-radius-xl)" :duration="7" :size="90">
                    <div style="display: grid; gap: .75rem; block-size: 100%; padding: 1.25rem; border: 1px solid var(--nx-gold-soft); border-radius: inherit; background: var(--nx-surface)">
                        <div class="pg-row" style="justify-content: space-between">
                            <strong style="font: 700 var(--nx-text-lg) / 1.2 var(--nx-font-display)">{{ $plan['name'] }}</strong>
                            <x-nx::badge tone="gold">{{ $say('Recommended', 'پیشنهادی') }}</x-nx::badge>
                        </div>
                        <span style="font: 700 var(--nx-text-2xl) / 1 var(--nx-font-display)">{{ $plan['price'] }}</span>
                        <span style="color: var(--nx-text-muted); font-size: var(--nx-text-sm)">{{ $plan['note'] }}</span>
                        <x-nx::button variant="primary" block>{{ $say('Start 14-day trial', 'شروع آزمایش ۱۴ روزه') }}</x-nx::button>
                    </div>
                </x-nx::border-beam>
            @else
                <div style="border-radius: var(--nx-radius-xl)">{!! $inner !!}</div>
            @endif
        @endforeach
    </div>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A prompt box at work', 'جعبهٔ پرامپت در حال کار') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The bare .nx-beam class is toggled on while the request runs — the beam says “working” and the label says it too.', 'کلاس خالص ‎.nx-beam فقط تا وقتی درخواست در جریان است روشن می‌ماند — پرتو می‌گوید «در حال کار» و برچسب هم همین را می‌گوید.') }}
        </p>
    </div>
    <form x-data="{ busy: false, run() { this.busy = true; setTimeout(() => this.busy = false, 3200) } }" x-on:submit.prevent="run()"
        style="display: grid; gap: .75rem; padding: .75rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-xl); background: var(--nx-surface)"
        x-bind:class="{ 'nx-beam': busy }" data-tone="violet">
        <label for="fx-beam-prompt" class="nx-visually-hidden">{{ $say('Prompt', 'پرامپت') }}</label>
        <textarea id="fx-beam-prompt" rows="3" class="nx-input" style="resize: none; border: 0; background: transparent"
            placeholder="{{ $say('Summarise last week’s support tickets…', 'تیکت‌های پشتیبانی هفتهٔ پیش را خلاصه کن…') }}"></textarea>
        <div class="pg-row" style="justify-content: space-between">
            <span style="color: var(--nx-text-muted); font-size: var(--nx-text-sm)" x-text="busy ? @js($say('Reading 214 tickets…', 'در حال خواندن ۲۱۴ تیکت…')) : @js($say('Ready', 'آماده'))">{{ $say('Ready', 'آماده') }}</span>
            <x-nx::button type="submit" variant="primary" icon="sparkles" x-bind:disabled="busy">{{ $say('Generate', 'بساز') }}</x-nx::button>
        </div>
    </form>
</section>
