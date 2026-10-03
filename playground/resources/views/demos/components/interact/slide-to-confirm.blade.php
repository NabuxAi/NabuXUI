{{--
    Slide to confirm's real scenarios: a checkout that pays when the thumb
    reaches the end (the server's toast is the receipt), ending a live
    session in red, and the RTL track that runs right to left.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Pay at the end of the track', 'پرداخت در انتهای مسیر') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Drag the thumb: the label fades as you go and a release short of the end springs back. Past 90% (or a quick flick past half) it confirms, the arrow turns into a check and the server answers. Tab to the thumb and use the arrows, or End.', 'دستگیره را بکشید: برچسب با پیشروی محو می‌شود و رهاکردن پیش از انتها با فنر برمی‌گردد. از ۹۰٪ به بعد (یا پرتاب سریع از نیمه) تأیید می‌شود، فلش تیک می‌شود و سرور جواب می‌دهد. با Tab روی دستگیره بروید و جهت‌ها یا End را بزنید.') }}
        </p>
    </div>
    <div style="display: grid; gap: 1rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-xl); padding: 1.25rem; max-inline-size: 24rem">
        <div class="pg-row" style="justify-content: space-between">
            <span style="color: var(--nx-text-muted)">{{ $say('Nabu Pro · yearly', 'نابو حرفه‌ای · سالانه') }}</span>
            <strong style="font: 700 var(--nx-text-lg) / 1 var(--nx-font-display)">{{ NabuXUI::formatNumber(49) }} $</strong>
        </div>
        <x-nx::slide-to-confirm :label="$say('Slide to pay $49', 'بکشید تا ۴۹ دلار پرداخت شود')" :done-label="$say('Paid', 'پرداخت شد')"
            :reset-after="3500" x-on:nx-confirm="$wire.save({{ \Illuminate\Support\Js::from($say('Receipt sent to your inbox', 'رسید به صندوق‌تان آمد')) }})" />
    </div>
</section>

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('End a live session', 'پایان جلسهٔ زنده') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('Danger tone for the action that drops 214 viewers at once; the reset button rewinds it from Alpine.', 'رنگ خطر برای کاری که ۲۱۴ بیننده را یک‌جا قطع می‌کند؛ دکمهٔ بازنشانی از Alpine آن را برمی‌گرداند.') }}
        </p>
        <div x-data style="display: grid; gap: .75rem">
            <x-nx::slide-to-confirm tone="danger" icon="stop" x-ref="end" :label="$say('Slide to end for everyone', 'بکشید تا برای همه تمام شود')" :done-label="$say('Session ended', 'جلسه تمام شد')"
                x-on:nx-confirm="$wire.ping({{ \Illuminate\Support\Js::from($say('Session ended for 214 viewers', 'جلسه برای ۲۱۴ بیننده تمام شد')) }})" />
            <div><x-nx::button size="sm" variant="ghost" x-on:click="$refs.end.dispatchEvent(new CustomEvent('nx-reset'))">{{ $say('Reset', 'بازنشانی') }}</x-nx::button></div>
        </div>
    </section>

    <section class="pg-box" dir="rtl" lang="fa" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Right to left', 'راست‌به‌چپ') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('The track runs right to left: drag toward the left, and the arrow keys follow the reading direction.', 'مسیر از راست به چپ است: به سمت چپ بکشید؛ کلیدهای جهت هم از جهت خواندن پیروی می‌کنند.') }}
        </p>
        <x-nx::slide-to-confirm tone="success" label="بکشید تا سفارش ثبت شود" done-label="سفارش ثبت شد" :reset-after="3000" />
    </section>
</div>
