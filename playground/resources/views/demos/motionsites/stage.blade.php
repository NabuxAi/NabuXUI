{{--
    The stage of a MotionSites demo (manifest key `scene`): the standalone
    scene in a sandboxed iframe with a desktop / tablet / phone width switch,
    a reload and a full-screen link. The iframe gets ?lang= and ?theme= from
    the host so the scene follows the page's language, direction and theme.
    Below it, the original MotionSites prompt the scene was built from.
--}}
@php
    use App\Support\MotionSites;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $lang = $fa ? 'fa' : 'en';
    $src = '/motionsites/'.$scene.'.html?lang='.$lang;
    $prompt = MotionSites::prompt($scene);
    $entry = MotionSites::entry($scene);
@endphp
<style>
    .ms-stage { display: grid; gap: .75rem; }
    .ms-bar { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: .5rem; }
    .ms-seg { display: inline-flex; gap: .25rem; padding: .25rem; border-radius: 999px;
              border: 1px solid var(--nx-border); background: var(--nx-surface-2); }
    .ms-seg button { block-size: 2rem; padding-inline: .8rem; border: 0; border-radius: 999px; cursor: pointer;
                     font: 600 var(--nx-text-xs) / 1 var(--nx-font-sans); color: var(--nx-text-muted); background: transparent; }
    .ms-seg button[aria-pressed="true"] { background: var(--nx-surface); color: var(--nx-text); box-shadow: var(--nx-shadow-sm); }
    .ms-frame-wrap { display: grid; justify-items: center; padding: .75rem; border-radius: var(--nx-radius-xl);
                     border: 1px solid var(--nx-border); background:
                     repeating-linear-gradient(45deg, var(--nx-surface-2) 0 10px, transparent 10px 20px); }
    .ms-frame { inline-size: 100%; block-size: min(78vh, 820px); border: 0; border-radius: var(--nx-radius-lg);
                background: #fff; box-shadow: var(--nx-shadow-lg);
                transition: inline-size calc(.45s * var(--nx-motion, 1)) var(--nx-ease-out, ease); }
    .ms-prompt { margin: 0; max-block-size: 26rem; overflow: auto; padding: 1rem 1.25rem; white-space: pre-wrap;
                 overflow-wrap: anywhere; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg);
                 background: var(--nx-surface-2); font: 500 var(--nx-text-sm) / 1.7 var(--nx-font-mono); }
</style>

<div class="ms-stage" x-data="{
        width: '100%',
        theme: document.documentElement.dataset.theme || '',
        get frameSrc() { return @js($src) + (this.theme ? '&theme=' + this.theme : '') },
        reload() { this.$refs.frame.src = this.frameSrc },
        init() {
            new MutationObserver(() => {
                const next = document.documentElement.dataset.theme || '';
                if (next !== this.theme) { this.theme = next; this.reload() }
            }).observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });
        },
    }">
    <div class="ms-bar">
        <div class="ms-seg" role="group" aria-label="{{ $say('Preview width', 'عرض پیش‌نمایش') }}">
            <button type="button" x-on:click="width = '100%'" :aria-pressed="width === '100%'">{{ $say('Desktop', 'دسکتاپ') }}</button>
            <button type="button" x-on:click="width = '820px'" :aria-pressed="width === '820px'">{{ $say('Tablet', 'تبلت') }}</button>
            <button type="button" x-on:click="width = '390px'" :aria-pressed="width === '390px'">{{ $say('Phone', 'موبایل') }}</button>
        </div>
        <div class="pg-row" style="gap: .5rem">
            <x-nx::button size="sm" variant="ghost" icon="play" x-on:click="reload()">{{ $say('Replay', 'اجرای دوباره') }}</x-nx::button>
            <x-nx::button size="sm" variant="secondary" icon="external-link" :href="$src" target="_blank" rel="noopener">{{ $say('Full screen', 'تمام‌صفحه') }}</x-nx::button>
        </div>
    </div>
    <div class="ms-frame-wrap">
        <iframe class="ms-frame" x-ref="frame" :src="frameSrc" src="{{ $src }}" :style="{ inlineSize: width }"
            title="{{ $say('Live scene', 'صحنهٔ زنده') }}" loading="lazy"
            allow="autoplay; fullscreen; clipboard-write; accelerometer; gyroscope"
            sandbox="allow-scripts allow-same-origin allow-popups allow-forms"></iframe>
    </div>
</div>

@if ($prompt)
    <section class="pg-box" style="gap: .75rem; margin-block-start: 1.25rem" aria-labelledby="ms-prompt-title">
        <div class="pg-row" style="justify-content: space-between">
            <div>
                <h3 class="pg-title" id="ms-prompt-title" style="margin: 0">{{ $say('Original prompt', 'پرامپت اصلی') }}</h3>
                <p style="margin: .25rem 0 0; color: var(--nx-text-muted); font-size: var(--nx-text-sm)">
                    {{ $say('From the MotionSites collection', 'از مجموعهٔ موشن‌سایتس') }}@if ($entry) · {{ $entry['category'] }}@endif
                </p>
            </div>
            <x-nx::copy-button :value="$prompt" variant="secondary" size="sm">{{ $say('Copy prompt', 'کپی پرامپت') }}</x-nx::copy-button>
        </div>
        <pre class="ms-prompt" dir="ltr">{{ $prompt }}</pre>
    </section>
@endif
