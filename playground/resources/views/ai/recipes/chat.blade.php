{{-- chat: a support thread — the bubbles, an agent note, and the reply row. --}}
@php $fa = app()->getLocale() === 'fa'; $say = fn (string $e, string $f) => $fa ? $f : $e; @endphp
<div style="max-inline-size: 34rem; margin-inline: auto; display: grid; gap: .9rem">
    <div class="aist-recipe-bubbles">
        <div class="aist-bubble aist-bubble-them">{{ $say('Hi! My order #1248 says shipped but tracking is empty.', 'سلام! سفارش ۱۲۴۸ می‌گوید ارسال شده ولی رهگیری خالی است.') }}</div>
        <div class="aist-bubble aist-bubble-me">{{ $say('Checking the courier feed now — one moment.', 'همین حالا خوراک پیک را چک می‌کنم — یک لحظه.') }}</div>
        <div class="aist-bubble aist-bubble-me">{{ $say('Found it: the label reprints in ~10 minutes. Here is the new link.', 'پیدا شد: برچسب تا ۱۰ دقیقه دیگر چاپ می‌شود. لینک تازه این است.') }}</div>
    </div>
    <div class="aist-recipe-row" style="gap: .5rem">
        <x-nx::input :label="''" name="ai-chat-reply" style="flex: 1" :placeholder="$say('Write a reply…', 'پاسخ بنویسید…')" />
        <x-nx::button variant="primary" icon="send">{{ $say('Send', 'ارسال') }}</x-nx::button>
    </div>
    <x-nx::alert tone="info" :title="$say('AI draft ready', 'پیش‌نویس هوشمند آماده')" icon="sparkles">
        {{ $say('NabuGate suggested two quick replies for this thread.', 'نابوگیت برای این گفت‌وگو دو پاسخ سریع پیشنهاد کرد.') }}
    </x-nx::alert>
</div>
