{{--
    Flip button's real scenarios: the step footer of an onboarding wizard (the
    quiet secondary "back" and the rolling primary "continue"), then a download
    row where each link flips to reveal what you actually get.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A wizard’s step footer', 'پایین‌بندِ مرحلهٔ ویزارد') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Hover the continue button — or Tab to it — and every part rolls a quarter turn: the words become an invitation, the arrow becomes a spark.', 'روی «ادامه» هاور کنید — یا با تب به آن بروید — تا هر پاره یک‌چهارم دور بزند: کلمات به دعوت و فلش به جرقه بدل می‌شود.') }}
        </p>
    </div>
    <div class="pg-row" style="justify-content: space-between">
        <x-nx::flip-button variant="secondary" :label="$say('Back', 'برگشت')" :hover-label="$say('Not yet', 'هنوز نه')" icon="arrow-left" hover-icon="x" size="sm"
            wire:click="ping(@js($say('Back to the checklist', 'بازگشت به چک‌لیست')))" />
        <div class="pg-row">
            <span style="color: var(--nx-text-subtle); font-size: var(--nx-text-sm)">{{ NabuXUI::formatNumber(2).' / '.NabuXUI::formatNumber(4) }}</span>
            <x-nx::flip-button :label="$say('Continue setup', 'ادامهٔ راه‌اندازی')" :hover-label="$say('Buckle up', 'بزن بریم')" icon="arrow-right" hover-icon="zap"
                wire:click="save(@js($say('Step saved', 'مرحله ذخیره شد')))" />
        </div>
    </div>
</section>

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A download row', 'ردیف دانلود') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('Real anchors whose second face tells you the size before you commit — keyboard focus flips them exactly like a hover.', 'لنگرهای واقعی که وجه دومشان حجم را قبل از تعهد لو می‌دهد — فوکوس کیبورد دقیقاً مثل هاور آنها را می‌چرخاند.') }}
        </p>
        <div class="pg-row" style="gap: 1rem">
            <x-nx::flip-button variant="secondary" :label="$say('Download PDF', 'دانلود PDF')" :hover-label="$say(NabuXUI::formatNumber(4).' MB', NabuXUI::formatNumber(4).' مگابایت')" icon="arrow-up" hover-icon="file" size="sm" href="#" />
            <x-nx::flip-button variant="secondary" :label="$say('Download SVGs', 'دانلود SVGها')" :hover-label="$say(NabuXUI::formatNumber(12).' MB', NabuXUI::formatNumber(12).' مگابایت')" icon="arrow-up" hover-icon="file" size="sm" href="#" />
        </div>
    </section>

    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Both directions', 'هر دو جهت') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('The cube rolls the same quarter turn in RTL — nothing to configure, the direction comes from the page.', 'مکعب در RTL همان یک‌چهارم دور را می‌زند — چیزی برای تنظیم نیست؛ جهت از خود صفحه می‌آید.') }}
        </p>
        <div class="pg-row" dir="rtl" lang="fa" style="gap: 1rem">
            <x-nx::flip-button label="شروع کنید" hover-label="بزن بریم" />
            <x-nx::flip-button variant="secondary" size="sm" label="راهنما" hover-label="کوتاه و مفید" icon="arrow-left" hover-icon="file" />
        </div>
    </section>
</div>
