{{--
    Carbon's AI label (slug) pinned to a generated support reply: the gradient
    badge pops a popover with model metadata, copy and revert actions — revert
    really swaps the AI draft back to the human one, and regenerate brings the
    AI back. The second card shows the inline kind inside a heading, and the
    variants row compares the three sizes.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;
@endphp
<style>
    .cbai-root {
        --cbai-accent: #0f62fe; --cbai-accent-hover: #0353e9;
        --cbai-text: #161616; --cbai-text-secondary: #525252;
        --cbai-border: #e0e0e0; --cbai-border-strong: #8d8d8d;
        --cbai-layer: #f4f4f4; --cbai-layer-hover: #e8e8e8;
        --cbai-ai: linear-gradient(90deg, #8a3ffc, #d02670, #1192e8);
        --cbai-font: 'IBM Plex Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        font-family: var(--cbai-font);
        display: grid; gap: 2rem; justify-items: center;
    }
    html[data-theme="dark"] .cbai-root {
        --cbai-accent: #4589ff; --cbai-accent-hover: #78a9ff;
        --cbai-text: #f4f4f4; --cbai-text-secondary: #c6c6c6;
        --cbai-border: #393939; --cbai-border-strong: #8d8d8d;
        --cbai-layer: #262626; --cbai-layer-hover: #333333;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .cbai-root {
            --cbai-accent: #4589ff; --cbai-accent-hover: #78a9ff;
            --cbai-text: #f4f4f4; --cbai-text-secondary: #c6c6c6;
            --cbai-border: #393939; --cbai-border-strong: #8d8d8d;
            --cbai-layer: #262626; --cbai-layer-hover: #333333;
        }
    }
    .cbai-draft { inline-size: min(100%, 34rem); background: var(--cbai-layer); }
    .cbai-draft-head { display: flex; align-items: center; gap: .625rem; padding: .75rem 1rem; border-block-end: 1px solid var(--cbai-border); }
    .cbai-draft-head b { font-weight: 600; font-size: .875rem; color: var(--cbai-text); }
    .cbai-draft-head small { margin-inline-start: auto; font-size: .75rem; color: var(--cbai-text-secondary); }
    .cbai-draft-body { padding: 1rem; font-size: .875rem; line-height: 1.6; color: var(--cbai-text); min-block-size: 7.5rem; }
    .cbai-draft-body[data-ai='true'] { border-inline-start: 3px solid transparent; border-image: var(--cbai-ai) 1; }
    .cbai-draft-foot { display: flex; flex-wrap: wrap; gap: .5rem; align-items: center; padding: .625rem 1rem; border-block-start: 1px solid var(--cbai-border); }
    .cbai-draft-foot small { font-size: .75rem; color: var(--cbai-text-secondary); margin-inline-end: auto; }
    .cbai-act { block-size: 2rem; padding-inline: .875rem; border: none; background: transparent; color: var(--cbai-accent);
                font: 400 .8125rem/1 var(--cbai-font); cursor: pointer; display: inline-flex; align-items: center; gap: .375rem; }
    .cbai-act:hover { text-decoration: underline; }
    .cbai-act:focus-visible { outline: 2px solid var(--cbai-accent); outline-offset: 1px; }
    .cbai-act[data-primary] { background: var(--cbai-accent); color: #fff; }
    .cbai-act[data-primary]:hover { background: var(--cbai-accent-hover); text-decoration: none; }
    .cbai-slug { position: relative; display: inline-grid; place-items: center; border: none; padding: 0; border-radius: 50%;
                 color: #fff; cursor: pointer; font: 600 .6875rem/1 var(--cbai-font); letter-spacing: .2px;
                 background: var(--cbai-ai); transition: box-shadow .11s ease-in; }
    .cbai-slug:hover { box-shadow: 0 0 0 3px color-mix(in srgb, #8a3ffc 25%, transparent); }
    .cbai-slug:focus-visible { outline: 2px solid var(--cbai-accent); outline-offset: 2px; }
    .cbai-slug[data-size='xs'] { inline-size: 1.25rem; block-size: 1.25rem; font-size: .5rem; }
    .cbai-slug[data-size='sm'] { inline-size: 1.5rem; block-size: 1.5rem; font-size: .625rem; }
    .cbai-slug[data-size='md'] { inline-size: 2rem; block-size: 2rem; font-size: .8125rem; }
    .cbai-pop { position: absolute; inset-block-start: calc(100% + 8px); inset-inline-end: 0; z-index: 7; inline-size: min(17rem, 76vw);
                padding: 1rem; background: var(--cbai-layer); box-shadow: 0 2px 6px rgba(0, 0, 0, .25); }
    .cbai-pop[data-open='false'] { display: none; }
    .cbai-pop-head { display: flex; align-items: center; gap: .5rem; padding-block-end: .5rem; border-block-end: 1px solid var(--cbai-border); }
    .cbai-pop-head b { font-weight: 600; font-size: .875rem; color: var(--cbai-text); }
    .cbai-pop dl { display: grid; gap: 0; margin: .5rem 0; font-size: .75rem; }
    .cbai-pop div { display: flex; justify-content: space-between; gap: 1rem; padding-block: .375rem; border-block-end: 1px solid var(--cbai-border); }
    .cbai-pop dt { color: var(--cbai-text-secondary); }
    .cbai-pop dd { margin: 0; font-weight: 600; font-family: 'IBM Plex Mono', Menlo, Consolas, monospace; color: var(--cbai-text); }
    .cbai-pop-foot { display: flex; gap: .5rem; padding-block-start: .625rem; }
    .cbai-inline { position: relative; display: inline-flex; align-items: center; gap: .5rem; }
    .cbai-inline .cbai-slug { vertical-align: middle; }
    .cbai-summary { inline-size: min(100%, 34rem); padding: 1rem; background: var(--cbai-layer); font-size: .875rem; line-height: 1.6; color: var(--cbai-text); }
    .cbai-summary h4 { margin: 0 0 .5rem; font: 600 1rem/1.3 var(--cbai-font); display: flex; align-items: center; gap: .5rem; flex-wrap: wrap; }
    .cbai-spec { display: grid; gap: 1.5rem; justify-items: center; }
    .cbai-spec-row { display: flex; flex-wrap: wrap; gap: 2rem; justify-content: center; align-items: flex-start; }
    .cbai-spec-cell { display: grid; gap: .5rem; justify-items: center; }
    .cbai-spec-cell > small { font-size: .72rem; color: var(--nx-text-muted); }
    :where(.nx-js) .pg:has(.cbai-root) .nx-data-table[data-nx-reveal] tbody tr { opacity: 1; translate: none; }
    @media (max-width: 480px) {
        .pg:has(.cbai-root) .nx-data-table :is(th, td) { white-space: normal; padding-inline: .5rem; overflow-wrap: break-word; }
        .cbai-pop { inset-inline-end: auto; inset-inline-start: 0; }
    }
    @media (prefers-reduced-motion: reduce) {
        .cbai-root * { transition-duration: .01ms !important; animation-duration: .01ms !important; }
    }
</style>

<div class="cbai-root">
    <section class="pg-box" style="justify-items: center"
        x-data="{
            pop: false,
            mode: 'ai',
            copied: false,
            ai: '{{ $say('Hello Hussein, yes — the October sales report was ready by noon today. The reporting worker briefly stopped responding, which we have fixed; the CSV export is attached to the ticket. If you need another column, tell me and I will add it today. — Nabu support', 'سلام حسین، بله — گزارش فروش آبان تا ظهر امروز آماده شد. سرور گزارش‌گیر موقتاً پاسخ نمی‌داد که رفع کردیم؛ خروجی CSV هم پیوست تیکت است. اگر ستون دیگری لازم دارید بگویید تا همین امروز اضافه کنم. — تیم پشتیبانی نابو') }}',
            human: '{{ $say('You are right — the report did land a little late and it is attached to this ticket now. Thanks for flagging it. — Nabu support', 'سلام، درست می‌فرمایید. گزارش با کمی تأخیر آماده شد و به همین تیکت پیوست شده است. ممنون که اطلاع دادید — تیم پشتیبانی نابو') }}',
            get text() { return this.mode === 'ai' ? this.ai : this.human },
            revert() { this.mode = 'human'; this.pop = false },
            regenerate() { this.mode = 'ai'; this.copied = false },
            copy() { navigator.clipboard?.writeText(this.text).catch(() => {}); this.copied = true; setTimeout(() => this.copied = false, 2000) },
        }">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Who wrote this? The badge knows', 'این را چه کسی نوشته؟ نشان می‌داند') }}</h3>
            <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
                {{ $say('The gradient badge is pinned to AI-generated content: click it to see which model wrote the draft, with what confidence — then copy it, or revert in one click to the human version. Regenerate brings the AI back.', 'نشانِ گرادیانی به محتوای تولیدی هوش مصنوعی می‌چسبد: با کلیک ببینید کدام مدل با چه اطمینانی پیش‌نویس را نوشته — بعد کپی کنید یا با یک کلیک به نسخهٔ انسانی واگرد کنید. «بازتولید» هوش مصنوعی را برمی‌گرداند.') }}
            </p>
        </div>

        <div class="cbai-draft">
            <div class="cbai-draft-head">
                <b>{{ $say('Ticket #۲۸۴۱ — draft reply', 'تیکت ۲۸۴۱ — پیش‌نویس پاسخ') }}</b>
                <div class="cbai-inline" x-data="{ pop: false }" x-on:keydown.escape.window="pop = false" x-on:click.outside="pop = false">
                    <button type="button" class="cbai-slug" data-size="sm" x-show="mode === 'ai'" x-cloak
                        x-on:click="pop = !pop" x-bind:aria-expanded="pop.toString()"
                        aria-label="{{ $say('Generated with AI — details', 'تولیدشده با هوش مصنوعی — جزئیات') }}">AI</button>
                    <div class="cbai-pop" role="dialog" aria-label="{{ $say('AI details', 'جزئیات هوش مصنوعی') }}" x-bind:data-open="pop.toString()">
                        <div class="cbai-pop-head">
                            <span aria-hidden="true" style="inline-size: .75rem; block-size: .75rem; background: var(--cbai-ai)"></span>
                            <b>{{ $say('AI-generated content', 'محتوای تولیدشده با هوش مصنوعی') }}</b>
                        </div>
                        <dl>
                            <div><dt>{{ $say('Model', 'مدل') }}</dt><dd>nabu-reply-3</dd></div>
                            <div><dt>{{ $say('Confidence', 'اطمینان') }}</dt><dd>{{ $num('92') }}٪</dd></div>
                            <div><dt>{{ $say('Input tokens', 'توکن ورودی') }}</dt><dd>{{ $num('1,940') }}</dd></div>
                            <div><dt>{{ $say('Reviewed by', 'بازبینی') }}</dt><dd>{{ $say('not yet', 'نشده') }}</dd></div>
                        </dl>
                        <div class="cbai-pop-foot">
                            <button type="button" class="cbai-act" x-on:click="copy(); pop = false">{{ $say('Copy', 'کپی') }}</button>
                            <button type="button" class="cbai-act" x-on:click="revert()">{{ $say('Revert to human draft', 'واگرد به پیش‌نویس انسانی') }}</button>
                        </div>
                    </div>
                </div>
                <small x-text="mode === 'ai' ? '{{ $say('suggested by nabu-reply-3', 'پیشنهاد nabu-reply-3') }}' : '{{ $say('human draft', 'پیش‌نویس انسانی') }}'"></small>
            </div>
            <div class="cbai-draft-body" x-bind:data-ai="(mode === 'ai').toString()" x-text="text" data-ai="true"></div>
            <div class="cbai-draft-foot">
                <small x-show="mode === 'ai'">{{ $say('Sending an AI draft without review is logged on the ticket.', 'ارسال پیش‌نویس هوش مصنوعی بی‌بازبینی در تیکت ثبت می‌شود.') }}</small>
                <small x-show="mode === 'human'" x-cloak>{{ $say('Back on the human draft — the badge is gone with it.', 'برگشتیم به پیش‌نویس انسانی — نشان هم با آن رفت.') }}</small>
                <button type="button" class="cbai-act" x-show="mode === 'human'" x-cloak x-on:click="regenerate()">{{ $say('Regenerate with AI', 'بازتولید با هوش مصنوعی') }}</button>
                <button type="button" class="cbai-act" x-on:click="copy()">{{ $say('Copy draft', 'کپی پیش‌نویس') }}</button>
                <button type="button" class="cbai-act" data-primary>{{ $say('Send reply', 'ارسال پاسخ') }}</button>
            </div>
        </div>
    </section>

    <section class="pg-box" style="justify-items: center"
        x-data="{ pop: false }" x-on:keydown.escape.window="pop = false" x-on:click.outside="pop = false">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('Inline, inside the heading', 'درون‌خطی، داخل سرخط') }}</h3>
            <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
                {{ $say('The inline kind rides along inside headings and copy — same popover, same actions, smaller footprint.', 'گونهٔ درون‌خطی داخل سرخط‌ها و متن‌ها سوار می‌شود — همان پاپ‌آپ، همان اکشن‌ها، جای‌گیر کمتر.') }}
            </p>
        </div>
        <div class="cbai-summary">
            <h4>
                {{ $say('Conversation summary', 'خلاصهٔ گفت‌وگو') }}
                <span class="cbai-inline">
                    <button type="button" class="cbai-slug" data-size="xs" x-on:click="pop = !pop" x-bind:aria-expanded="pop.toString()"
                        aria-label="{{ $say('AI summary — details', 'خلاصهٔ هوش مصنوعی — جزئیات') }}">AI</button>
                    <div class="cbai-pop" role="dialog" aria-label="{{ $say('AI details', 'جزئیات هوش مصنوعی') }}" x-bind:data-open="pop.toString()">
                        <div class="cbai-pop-head">
                            <span aria-hidden="true" style="inline-size: .75rem; block-size: .75rem; background: var(--cbai-ai)"></span>
                            <b>{{ $say('AI-generated summary', 'خلاصهٔ تولیدشده با هوش مصنوعی') }}</b>
                        </div>
                        <dl>
                            <div><dt>{{ $say('Model', 'مدل') }}</dt><dd>nabu-sum-2</dd></div>
                            <div><dt>{{ $say('Messages read', 'پیام‌های خوانده‌شده') }}</dt><dd>{{ $num('64') }}</dd></div>
                        </dl>
                        <div class="cbai-pop-foot">
                            <button type="button" class="cbai-act" x-on:click="pop = false">{{ $say('Close', 'بستن') }}</button>
                        </div>
                    </div>
                </span>
            </h4>
            {{ $say('The customer reported a delayed sales report; the agent confirmed the delay, attached the CSV export and fixed the reporting worker. One follow-up question about extra columns is still open.', 'مشک از تأخیر گزارش فروش گفت؛ کارشناس تأخیر را تأیید کرد، خروجی CSV را پیوست کرد و ورکر گزارش‌گیر را درست کرد. یک پرسش دربارهٔ ستون‌های بیشتر هنوز باز است.') }}
        </div>
    </section>
</div>

<section class="pg-box" style="justify-items: center">
    <div class="cbai-root" style="inline-size: 100%">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('The whole family', 'همهٔ خانواده') }}</h3>
            <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
                {{ $say('Three official sizes — xs to hug running text, md to mark an open surface.', 'سه اندازهٔ رسمی — xs برای چسبیدن به متن روان، md برای نشانه‌گذاری سطح باز.') }}
            </p>
        </div>
        <div class="cbai-spec">
        <div class="cbai-spec-row">
            <div class="cbai-spec-cell"><button type="button" class="cbai-slug" data-size="xs" aria-label="AI extra small">AI</button><small>xs · {{ $num('20') }}px</small></div>
            <div class="cbai-spec-cell"><button type="button" class="cbai-slug" data-size="sm" aria-label="AI small">AI</button><small>sm · {{ $num('24') }}px</small></div>
            <div class="cbai-spec-cell"><button type="button" class="cbai-slug" data-size="md" aria-label="AI medium">AI</button><small>md · {{ $num('32') }}px</small></div>
        </div>
        </div>
    </div>
</section>
