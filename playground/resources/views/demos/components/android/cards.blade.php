{{--
    The three Material 3 card families staged as a playlist picker: elevated
    with its level-1 shadow, filled on its tinted surface and outlined with
    its stroke — each with media, headline, supporting text and footer text
    buttons at the exact 12 px radius. Pure CSS, as the manifest promises.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp
<style>
    .m3card-root {
        --m3card-primary: #6750A4; --m3card-on-primary: #FFFFFF;
        --m3card-primary-container: #EADDFF; --m3card-on-primary-container: #21005D;
        --m3card-secondary-container: #E8DEF8; --m3card-on-secondary-container: #1D192B;
        --m3card-surface: #FEF7FF; --m3card-surface-container: #F3EDF7;
        --m3card-surface-container-high: #ECE6F0; --m3card-surface-container-highest: #E6E0E9;
        --m3card-on-surface: #1D1B20; --m3card-on-surface-variant: #49454F;
        --m3card-outline-variant: #CAC4D0;
        --m3card-radius: 12px;
        --m3card-ease: cubic-bezier(.2, 0, 0, 1);
        font-family: Roboto, system-ui, sans-serif;
        display: grid; gap: 1.5rem;
    }
    html[data-theme="dark"] .m3card-root {
        --m3card-primary: #D0BCFF; --m3card-on-primary: #381E72;
        --m3card-primary-container: #4F378B; --m3card-on-primary-container: #EADDFF;
        --m3card-secondary-container: #4A4458; --m3card-on-secondary-container: #E8DEF8;
        --m3card-surface: #141218; --m3card-surface-container: #211F26;
        --m3card-surface-container-high: #2B2930; --m3card-surface-container-highest: #36343B;
        --m3card-on-surface: #E6E0E9; --m3card-on-surface-variant: #CAC4D0;
        --m3card-outline-variant: #49454F;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .m3card-root {
            --m3card-primary: #D0BCFF; --m3card-on-primary: #381E72;
            --m3card-primary-container: #4F378B; --m3card-on-primary-container: #EADDFF;
            --m3card-secondary-container: #4A4458; --m3card-on-secondary-container: #E8DEF8;
            --m3card-surface: #141218; --m3card-surface-container: #211F26;
            --m3card-surface-container-high: #2B2930; --m3card-surface-container-highest: #36343B;
            --m3card-on-surface: #E6E0E9; --m3card-on-surface-variant: #CAC4D0;
            --m3card-outline-variant: #49454F;
        }
    }
    .m3card-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(13.5rem, 1fr)); gap: 1rem; }
    .m3card { display: grid; gap: 0; overflow: clip; border-radius: var(--m3card-radius); background: var(--m3card-surface-container); color: var(--m3card-on-surface); font-family: Roboto, system-ui, sans-serif; }
    .m3card[data-variant='elevated'] { background: var(--m3card-surface-container-high); box-shadow: 0 1px 2px rgba(0, 0, 0, .3), 0 1px 3px 1px rgba(0, 0, 0, .15); transition: box-shadow .25s var(--m3card-ease), translate .25s var(--m3card-ease); }
    .m3card[data-variant='elevated']:hover { translate: 0 -2px; box-shadow: 0 2px 6px 2px rgba(0, 0, 0, .15), 0 1px 2px rgba(0, 0, 0, .3); }
    .m3card[data-variant='filled'] { background: var(--m3card-surface-container-highest); }
    .m3card[data-variant='outlined'] { background: var(--m3card-surface); border: 1px solid var(--m3card-outline-variant); }
    .m3card-media { position: relative; aspect-ratio: 16 / 9; }
    .m3card-media figcaption { position: absolute; inset-block-end: .5rem; inset-inline-start: .75rem; display: inline-flex; align-items: center; gap: .4rem; padding: .25rem .6rem; border-radius: 999px; background: rgba(0, 0, 0, .45); color: #ffffff; font: 500 .7rem/1 Roboto, system-ui, sans-serif; backdrop-filter: blur(6px); }
    .m3card-body { display: grid; gap: .35rem; padding: 1rem 1rem .25rem; }
    .m3card-body h3 { margin: 0; font: 500 1.05rem/1.3 Roboto, system-ui, sans-serif; }
    .m3card-body p { margin: 0; font: 400 .82rem/1.5 Roboto, system-ui, sans-serif; color: var(--m3card-on-surface-variant); }
    .m3card-actions { display: flex; gap: .25rem; padding: .6rem .5rem .5rem; }
    .m3card-btn { position: relative; overflow: clip; border: none; border-radius: 999px; background: transparent; color: var(--m3card-primary); padding: .5rem .875rem; cursor: pointer; font: 500 .82rem/1 Roboto, system-ui, sans-serif; -webkit-tap-highlight-color: transparent; }
    .m3card-btn::before { content: ''; position: absolute; inset: 0; border-radius: inherit; background: currentColor; opacity: 0; transition: opacity .15s; pointer-events: none; }
    .m3card-btn:hover::before { opacity: .08; }
    .m3card-btn:active::before { opacity: .12; }
    .m3card-btn:focus-visible { outline: 2px solid var(--m3card-primary); outline-offset: 2px; }
    .m3card-label { display: flex; align-items: center; gap: .5rem; margin: 0; font: 500 .72rem/1 Roboto, system-ui, sans-serif; color: var(--nx-text-muted); }
    .m3card-label i { inline-size: .6rem; aspect-ratio: 1; border-radius: 3px; }
    .m3card-note { margin: 0; font: 400 .8rem/1.5 Roboto, system-ui, sans-serif; color: var(--nx-text-muted); max-width: 56ch; }
    /* The «Important props» table on this page is rendered by the demo template,
       not by this partial, and hides its rows until a scroll observer marks it
       (data-nx-revealed). A full-page screenshot never scrolls, so the body was
       captured empty in both themes. Show the rows unconditionally; on narrow
       screens let the cells wrap with tighter padding so the fourth column is
       not clipped at the edge. */
    .nx-js .nx-data-table[data-nx-reveal]:not([data-nx-revealed]) tbody tr { opacity: 1; translate: none; }
    @media (max-width: 48rem) {
        .nx-data-table thead th, .nx-data-table tbody td { white-space: normal; padding-inline: .5rem; }
    }
    @media (prefers-reduced-motion: reduce) {
        .m3card-root * { animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important; }
    }
</style>

<section class="pg-box">
    <div style="display: grid; gap: .35rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Pick a playlist card', 'کارت لیست پخش را انتخاب کنید') }}</h3>
        <p style="margin: 0; max-width: 60ch; color: var(--nx-text-muted)">
            {{ $say('Same content, three containers: the elevated card floats on its shadow, the filled card rests on the highest tinted surface, and the outlined card keeps a quiet stroke. Hover the elevated one.', 'همان محتوا، سه محفظه: کارت برجسته روی سایه‌اش شناور است، کارت پرشده روی تُن‌دارترین سطح می‌نشیند و کارت خط‌دار به یک خطِ آرام قناعت می‌کند. کارت برجسته را با موس دنبال کنید.') }}
        </p>
    </div>

    <div class="m3card-root">
        <div class="m3card-grid">
            <figure class="m3card" data-variant="elevated" style="margin: 0">
                <div class="m3card-media" style="background: linear-gradient(140deg, #a5b4fc, #4f46e5)">
                    <figcaption>{{ $say('24 tracks', '۲۴ قطعه') }}</figcaption>
                </div>
                <div class="m3card-body">
                    <h3>{{ $say('Albums of the year', 'آلبوم‌های امسال') }}</h3>
                    <p>{{ $say('24 fresh albums landed in your library this week.', '۲۴ آلبوم تازه این هفته به کتابخانهٔ شما رسیده است.') }}</p>
                </div>
                <div class="m3card-actions">
                    <button type="button" class="m3card-btn">{{ $say('Listen', 'گوش دادن') }}</button>
                    <button type="button" class="m3card-btn">{{ $say('Queue', 'صف پخش') }}</button>
                </div>
            </figure>

            <figure class="m3card" data-variant="filled" style="margin: 0">
                <div class="m3card-media" style="background: linear-gradient(140deg, #fcd34d, #d97706)">
                    <figcaption>{{ $say('18 tracks', '۱۸ قطعه') }}</figcaption>
                </div>
                <div class="m3card-body">
                    <h3>{{ $say('Rainy-tea afternoons', 'عصرهای بارانیِ چای') }}</h3>
                    <p>{{ $say('Piano, tar and rain on the window — for slow evenings.', 'پیانو، تار و بارانِ پشت پنجره — برای عصرهای آرام.') }}</p>
                </div>
                <div class="m3card-actions">
                    <button type="button" class="m3card-btn">{{ $say('Listen', 'گوش دادن') }}</button>
                    <button type="button" class="m3card-btn">{{ $say('Queue', 'صف پخش') }}</button>
                </div>
            </figure>

            <figure class="m3card" data-variant="outlined" style="margin: 0">
                <div class="m3card-media" style="background: linear-gradient(140deg, #6ee7b7, #047857)">
                    <figcaption>{{ $say('31 tracks', '۳۱ قطعه') }}</figcaption>
                </div>
                <div class="m3card-body">
                    <h3>{{ $say('The road to the north', 'جادهٔ شمال') }}</h3>
                    <p>{{ $say('Made for the 6 a.m. bus to Hirkanic forests.', 'ساخته برای اتوبوس ساعت ۶ صبح به سمت جنگل هیرکانی.') }}</p>
                </div>
                <div class="m3card-actions">
                    <button type="button" class="m3card-btn">{{ $say('Listen', 'گوش دادن') }}</button>
                    <button type="button" class="m3card-btn">{{ $say('Share', 'هم‌رسانی') }}</button>
                </div>
            </figure>
        </div>

        <div class="pg-row">
            <span class="m3card-label"><i style="background: var(--m3card-surface-container-high); box-shadow: 0 1px 2px rgba(0,0,0,.3), 0 1px 3px 1px rgba(0,0,0,.15)"></i>elevated</span>
            <span class="m3card-label"><i style="background: var(--m3card-surface-container-highest)"></i>filled</span>
            <span class="m3card-label"><i style="background: var(--m3card-surface); border: 1px solid var(--m3card-outline-variant)"></i>outlined</span>
            <p class="m3card-note">{{ $say('All at the official 12 px radius; the media edge is clipped by the card itself.', 'هر سه با گردی رسمی ۱۲ پیکسل؛ لبهٔ تصویر خودبه‌خود با گردی کارت بریده می‌شود.') }}</p>
        </div>
    </div>
</section>
