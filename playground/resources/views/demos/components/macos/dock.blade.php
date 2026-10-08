{{--
    The authentic macOS Dock on a desktop stage: a glass tray of gradient
    tiles whose icons grow by Gaussian distance from the pointer and shove
    their neighbours, 4px running dots under two open apps, a name bubble
    after a 400ms hover, the middle separator and the trash at the end.
    Clicking an app bounces it (keyframes, reduced-motion safe).
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $apps = [
        ['name' => 'Finder', 'emoji' => '🙂', 'grad' => 'linear-gradient(160deg, #CFE8FF 0%, #7FB6F2 50%, #1E6FD9 100%)'],
        ['name' => 'Mail', 'emoji' => '✉️', 'grad' => 'linear-gradient(160deg, #6EC6FF, #0A63C9)', 'running' => true],
        ['name' => 'Music', 'emoji' => '🎵', 'grad' => 'linear-gradient(160deg, #FC6076, #E0245E)', 'running' => true],
        ['name' => 'Photos', 'emoji' => '🌸', 'grad' => 'linear-gradient(160deg, #FFFFFF, #EDEDF0)'],
        ['name' => 'Notes', 'emoji' => '📝', 'grad' => 'linear-gradient(160deg, #FFFFFF 0%, #FFFFFF 34%, #FFE873 34%, #F0C21B 100%)'],
        ['name' => 'Maps', 'emoji' => '🗺️', 'grad' => 'linear-gradient(160deg, #A8E6A3, #4CAF7D)'],
        ['name' => 'Calculator', 'emoji' => '🧮', 'grad' => 'linear-gradient(160deg, #5A5A5E, #26262A)'],
    ];
@endphp
<style>
    .mcdock-root {
        --mcdock-text: #1E1E1E; --mcdock-text2: #6D6D72; --mcdock-accent: #007AFF;
        --mcdock-hair: rgba(0, 0, 0, .15);
        --mcdock-tray: rgba(255, 255, 255, .35);
        --mcdock-tray-line: rgba(255, 255, 255, .55);
        --mcdock-base: 46px; --mcdock-grow: 26px;
        font-family: system-ui, -apple-system, "Vazirmatn", sans-serif;
    }
    html[data-theme="dark"] .mcdock-root {
        --mcdock-text: #F5F5F5; --mcdock-text2: #A5A5AA; --mcdock-accent: #0A84FF;
        --mcdock-hair: rgba(255, 255, 255, .15);
        --mcdock-tray: rgba(40, 40, 40, .42);
        --mcdock-tray-line: rgba(255, 255, 255, .28);
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .mcdock-root {
            --mcdock-text: #F5F5F5; --mcdock-text2: #A5A5AA; --mcdock-accent: #0A84FF;
            --mcdock-hair: rgba(255, 255, 255, .15);
            --mcdock-tray: rgba(40, 40, 40, .42);
            --mcdock-tray-line: rgba(255, 255, 255, .28);
        }
    }

    .mcdock-stage {
        position: relative; overflow: clip; min-block-size: 26rem; border-radius: var(--nx-radius-2xl);
        background: linear-gradient(140deg, #1D2B53 0%, #6E4B7E 55%, #E8956D 100%);
    }
    .mcdock-wall { position: absolute; inset: 0; }
    .mcdock-wall i { position: absolute; inline-size: 48%; aspect-ratio: 1; border-radius: 50%; filter: blur(50px); opacity: .65; animation: mcdock-drift 20s ease-in-out infinite alternate; }
    .mcdock-wall i:nth-child(1) { inset-block-start: -12%; inset-inline-start: -8%; background: #5AC8FA; }
    .mcdock-wall i:nth-child(2) { inset-block-end: -20%; inset-inline-end: -10%; background: #FF9F0A; animation-delay: -9s; }
    @keyframes mcdock-drift { to { translate: 7% -9%; scale: 1.15; } }
    @media (prefers-reduced-motion: reduce) { .mcdock-wall i { animation: none; } }

    .mcdock-hint {
        position: absolute; inset-block-start: 18px; inset-inline: 0; margin-inline: auto; inline-size: max-content;
        color: rgba(255, 255, 255, .85); text-shadow: 0 1px 10px rgba(0, 0, 0, .35);
        font: 500 13px/1.6 system-ui, -apple-system, "Vazirmatn", sans-serif;
    }

    .mcdock-bubble {
        position: absolute; inset-block-end: 118px; inset-inline: 0; margin-inline: auto;
        inline-size: max-content; z-index: 5; padding: 4px 11px; border-radius: 999px;
        color: #fff; background: rgba(30, 30, 30, .78); backdrop-filter: blur(20px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, .3), inset 0 0 0 .5px rgba(255, 255, 255, .22);
        font: 500 12px/1.5 system-ui, -apple-system, "Vazirmatn", sans-serif;
        pointer-events: none;
    }

    .mcdock-tray {
        position: absolute; inset-block-end: 14px; inset-inline: 0; margin-inline: auto;
        inline-size: max-content; max-inline-size: calc(100% - 16px); z-index: 4;
        display: flex; align-items: end; gap: 6px; padding: 7px;
        border-radius: 21px; background: var(--mcdock-tray);
        backdrop-filter: blur(24px) saturate(1.6);
        box-shadow: inset 0 0 0 .5px var(--mcdock-tray-line), 0 18px 42px rgba(0, 0, 0, .32);
    }
    .mcdock-slot { display: grid; grid-template-columns: minmax(0, 1fr); gap: 4px; justify-items: center; min-inline-size: 0; }
    .mcdock-app {
        padding: 0; border: 0; background: transparent; cursor: pointer;
        inline-size: var(--mcdock-base); max-inline-size: 100%; container-type: inline-size; aspect-ratio: 1; transition: inline-size .1s ease-out;
        font: 400 26px/1 system-ui, -apple-system, sans-serif;
    }
    .mcdock-tile {
        display: grid; place-items: center; inline-size: 100%; aspect-ratio: 1;
        border-radius: 22%; overflow: clip; font-size: 56cqi;
        box-shadow: inset 0 0 0 .5px rgba(255, 255, 255, .3), 0 4px 10px rgba(0, 0, 0, .25);
    }
    .mcdock-app[data-bounce] { animation: mcdock-bounce .65s ease; }
    @keyframes mcdock-bounce {
        0%, 100% { translate: 0 0; } 30% { translate: 0 -24px; } 55% { translate: 0 0; } 75% { translate: 0 -9px; }
    }
    @media (prefers-reduced-motion: reduce) { .mcdock-app[data-bounce] { animation: none; } .mcdock-app { transition: none; } }
    .mcdock-dot { inline-size: 4px; aspect-ratio: 1; border-radius: 50%; background: transparent; }
    .mcdock-dot[data-on] { background: currentColor; color: var(--mcdock-text); }
    .mcdock-sep { align-self: stretch; margin-block: 10px; inline-size: 1px; background: var(--mcdock-tray-line); }
    .mcdock-app[data-trash] .mcdock-tile { font-size: 52cqi; background: linear-gradient(160deg, rgba(220, 228, 236, .55), rgba(130, 142, 156, .55)); backdrop-filter: blur(8px); }
    .mcdock-root button:focus-visible { outline: 2px solid #fff; outline-offset: 2px; border-radius: 22%; }
    @media (max-width: 640px) {
        .mcdock-tray { --mcdock-base: 34px; --mcdock-grow: 16px; gap: 3px; padding: 5px; border-radius: 17px; }
    }

    /* The wrapper (livewire/component-demo) renders this demo's props table and
       snippet around the partial, and at narrow widths their nowrap cells and long
       code lines clip at the edge. Scoped to this demo's page only through
       .mcdock-root — this partial is its only source — let them wrap instead. */
    @media (max-width: 768px) {
        .pg:has(.mcdock-root) .nx-data-table th,
        .pg:has(.mcdock-root) .nx-data-table td { white-space: normal; padding-inline: .6rem; }
        .pg:has(.mcdock-root) .nx-data-table td:last-child { overflow-wrap: anywhere; }
        .pg:has(.mcdock-root) section:has(#demo-snippet-title) pre code { white-space: pre-wrap; overflow-wrap: anywhere; }
    }
</style>

<section class="pg-box mcdock-root">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The Dock', 'داک') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Sweep the pointer across the tray: each tile swells by its Gaussian distance from the pointer and pushes its neighbours aside. Two apps are running; click one to bounce it.', 'اشاره‌گر را روی سینی بکشید: هر کاشی بر اساس فاصلهٔ گاوسی از اشاره‌گر پهن می‌شود و همسایه‌هایش را هل می‌دهد. دو برنامه در حال اجراست؛ برای پرش، روی یکی بزنید.') }}
        </p>
    </div>

    <div class="mcdock-stage"
         x-data="{
                label: '', bounce: null, hoverT: null, bounceT: null,
                mag(e) {
                    const tray = this.$refs.tray;
                    if (!tray) return;
                    const tr = tray.getBoundingClientRect();
                    const px = e.clientX - tr.left;
                    const cs = getComputedStyle(tray);
                    const base = parseFloat(cs.getPropertyValue('--mcdock-base')) || 46;
                    const grow = parseFloat(cs.getPropertyValue('--mcdock-grow')) || 26;
                    for (const el of tray.querySelectorAll('.mcdock-app')) {
                        const r = el.getBoundingClientRect();
                        const d = Math.abs(px - (r.left + r.width / 2 - tr.left));
                        const s = Math.exp(-Math.pow(d / 55, 2));
                        el.style.setProperty('inline-size', (base + grow * s).toFixed(1) + 'px');
                    }
                },
                calm() { for (const el of this.$refs.tray.querySelectorAll('.mcdock-app')) el.style.removeProperty('inline-size'); },
                peek(name) { clearTimeout(this.hoverT); this.hoverT = setTimeout(() => { this.label = name }, 400); },
                hide() { clearTimeout(this.hoverT); this.label = ''; },
                hop(name) { this.bounce = name; clearTimeout(this.bounceT); this.bounceT = setTimeout(() => { this.bounce = null }, 700); },
            }"
         x-on:pointermove="mag($event)"
         x-on:pointerleave="calm(); hide()">
        <div class="mcdock-wall" aria-hidden="true"><i></i><i></i></div>
        <p class="mcdock-hint" aria-hidden="true">{{ $say('Pasargadae — dusk', 'پاسارگاد — غروب') }}</p>

        <span class="mcdock-bubble" x-cloak x-show="label" x-text="label" aria-hidden="true"></span>

        <div class="mcdock-tray" x-ref="tray" role="toolbar" aria-label="{{ $say('Dock', 'داک') }}">
            @foreach ($apps as $app)
                <div class="mcdock-slot">
                    <button type="button" class="mcdock-app"
                            :data-bounce="bounce === '{{ $app['name'] }}' ? '' : null"
                            aria-label="{{ $app['name'] }}"
                            x-on:pointerenter="peek('{{ $app['name'] }}')"
                            x-on:click="hop('{{ $app['name'] }}')">
                        <span class="mcdock-tile" style="background: {{ $app['grad'] }}" aria-hidden="true">{{ $app['emoji'] }}</span>
                    </button>
                    <i class="mcdock-dot" {{ ($app['running'] ?? false) ? 'data-on' : '' }} aria-hidden="true"></i>
                </div>
            @endforeach
            <span class="mcdock-sep" aria-hidden="true"></span>
            <div class="mcdock-slot">
                <button type="button" class="mcdock-app" data-trash
                        :data-bounce="bounce === 'Trash' ? '' : null"
                        aria-label="{{ $say('Trash', 'سطل زباله') }}"
                        x-on:pointerenter="peek('{{ $say('Trash', 'سطل زباله') }}')"
                        x-on:click="hop('Trash')">
                    <span class="mcdock-tile" aria-hidden="true">🗑️</span>
                </button>
                <i class="mcdock-dot" aria-hidden="true"></i>
            </div>
        </div>
    </div>
</section>
