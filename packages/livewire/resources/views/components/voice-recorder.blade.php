{{--
    <x-nx::voice-recorder max-duration="120" x-on:nx-record-stop="$wire.ping('Voice note: ' + $event.detail.duration + 's')" />

    A mic that becomes a recording pill (live bars, rolling mm:ss, pause, stop,
    cancel), then a playback chip. It never asks for the microphone: the bars
    follow levels the app sends with $dispatch('nx-record-level', 0.42) on the
    element, and a believable simulation otherwise. Browser events:
    nx-record-start, nx-record-pause, nx-record-resume,
    nx-record-stop { duration, levels }, nx-record-cancel, nx-record-discard.
--}}
@props(['maxDuration' => 0, 'align' => 'start', 'locale' => null, 'bars' => 26, 'takeBars' => 30])
@php
    use NabuXUI\NabuXUI;
    $lang = substr($locale ?? app()->getLocale(), 0, 2);
    $t = fn (string $en, string $fa, string $ar) => match ($lang) { 'fa' => $fa, 'ar' => $ar, default => $en };
    $digits = NabuXUI::digits($locale);
    $zero = $digits[0].$digits[0].':'.$digits[0].$digits[0];
    $words = [
        'record' => $t('Record a voice message', 'ضبط پیام صوتی', 'تسجيل رسالة صوتية'),
        'recording' => $t('Recording', 'در حال ضبط', 'جارٍ التسجيل'),
        'paused' => $t('Paused', 'متوقف', 'متوقف مؤقتًا'),
        'pause' => __('nabuxui::ui.pause'),
        'resume' => $t('Resume', 'ادامهٔ ضبط', 'استئناف'),
        'stop' => $t('Stop recording', 'پایان ضبط', 'إيقاف التسجيل'),
        'cancel' => $t('Cancel', 'لغو', 'إلغاء'),
        'play' => __('nabuxui::ui.play'),
        'seek' => $t('Seek', 'جابه‌جایی در صدا', 'التنقل في الصوت'),
        'discard' => $t('Delete recording', 'حذف صدا', 'حذف التسجيل'),
        'message' => $t('Voice message', 'پیام صوتی', 'رسالة صوتية'),
    ];
@endphp
@php
    // A rolling mm:ss: four digit columns around a colon (the live face and the chip each have one).
    $clock = function (string $ref) use ($digits, $zero) {
        $column = '<span class="nx-digit" style="--d: 0"><span class="nx-digit-track">'.implode('', array_map(fn ($d) => '<span>'.e($d).'</span>', $digits)).'</span></span>';

        return new \Illuminate\Support\HtmlString(
            '<span class="nx-number" x-ref="'.$ref.'"><span class="nx-visually-hidden">'.e($zero).'</span><span class="nx-number-roll" aria-hidden="true">'
            .$column.$column.'<span class="nx-number-sep">:</span>'.$column.$column.'</span></span>'
        );
    };
@endphp
<div wire:ignore.self {{ $attributes->class('nx-voice-recorder')->merge(['data-align' => $align === 'end' ? 'end' : null]) }}
    x-data="nxVoiceRecorder({{ (float) $maxDuration }}, @js($locale))" data-state="idle" x-bind:data-state="state">
    <div class="nx-voice-recorder-measure" x-ref="measure" wire:ignore>
        <div class="nx-voice-recorder-face" data-face="idle" data-active x-bind:data-active="state === 'idle' ? '' : null">
            <button type="button" class="nx-voice-recorder-mic" data-focus="mic" aria-label="{{ $words['record'] }}" x-bind:tabindex="state === 'idle' ? 0 : -1" x-on:click="start()">{{ NabuXUI::icon('mic') }}</button>
        </div>
        <div class="nx-voice-recorder-face" data-face="live" role="group" aria-label="{{ $words['recording'] }}"
            x-bind:data-active="state === 'recording' || state === 'paused' ? '' : null" x-bind:aria-label="state === 'paused' ? @js($words['paused']) : @js($words['recording'])">
            <button type="button" class="nx-voice-recorder-btn" data-tone="ghost" aria-label="{{ $words['cancel'] }}" tabindex="-1" x-bind:tabindex="state === 'recording' || state === 'paused' ? 0 : -1" x-on:click="cancel()">{{ NabuXUI::icon('x') }}</button>
            <span class="nx-voice-recorder-dot" aria-hidden="true"></span>
            <span class="nx-voice-recorder-wave" x-ref="wave" aria-hidden="true">@for ($i = 0; $i < (int) $bars; $i++)<i></i>@endfor</span>
            <span class="nx-voice-recorder-time" role="timer">{{ $clock('liveTime') }}</span>
            <button type="button" class="nx-voice-recorder-btn" tabindex="-1" x-bind:tabindex="state === 'recording' || state === 'paused' ? 0 : -1"
                x-bind:data-swapped="state === 'paused' ? '' : null" aria-label="{{ $words['pause'] }}" x-bind:aria-label="state === 'paused' ? @js($words['resume']) : @js($words['pause'])" x-on:click="togglePause()">
                <span class="nx-voice-recorder-swap" aria-hidden="true">{{ NabuXUI::icon('pause') }}{{ NabuXUI::icon('mic') }}</span>
            </button>
            <button type="button" class="nx-voice-recorder-btn" data-tone="danger" data-focus="stop" aria-label="{{ $words['stop'] }}" tabindex="-1" x-bind:tabindex="state === 'recording' || state === 'paused' ? 0 : -1" x-on:click="stop()">{{ NabuXUI::icon('stop') }}</button>
        </div>
        <div class="nx-voice-recorder-face" data-face="playback" role="group" aria-label="{{ $words['message'] }}" x-bind:data-active="state === 'stopped' ? '' : null">
            <button type="button" class="nx-voice-recorder-btn" data-tone="accent" data-focus="play" tabindex="-1" x-bind:tabindex="state === 'stopped' ? 0 : -1"
                x-bind:data-swapped="playing ? '' : null" aria-label="{{ $words['play'] }}" x-bind:aria-label="playing ? @js($words['pause']) : @js($words['play'])" x-on:click="togglePlay()">
                <span class="nx-voice-recorder-swap" aria-hidden="true">{{ NabuXUI::icon('play') }}{{ NabuXUI::icon('pause') }}</span>
            </button>
            <span class="nx-voice-recorder-take" x-ref="take" style="--_bars: {{ (int) $takeBars }}" x-bind:style="{ '--_p': duration ? position / duration : 0 }">
                @for ($i = 0; $i < (int) $takeBars; $i++)<i style="--_i: {{ $i }}"></i>@endfor
                <input type="range" class="nx-voice-recorder-seek" min="0" step="0.1" value="0" tabindex="-1" aria-label="{{ $words['seek'] }}"
                    x-bind:max="Math.max(duration, 0.1)" x-bind:value="Math.min(position, duration)" x-bind:tabindex="state === 'stopped' ? 0 : -1"
                    x-bind:aria-valuetext="clockText(position) + ' / ' + clockText(duration)" x-on:input="seek($event.target.value)">
            </span>
            <span class="nx-voice-recorder-time">{{ $clock('playTime') }}</span>
            <button type="button" class="nx-voice-recorder-btn" data-tone="ghost" aria-label="{{ $words['discard'] }}" tabindex="-1" x-bind:tabindex="state === 'stopped' ? 0 : -1" x-on:click="discard()">{{ NabuXUI::icon('trash') }}</button>
        </div>
    </div>
</div>
