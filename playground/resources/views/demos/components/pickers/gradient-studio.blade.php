{{--
    Gradient studio's scenario: the little design-tool bench — a live preview,
    colour stops you add, move and remove, a draggable angle and a CSS output
    that copies in one click. Everything is composed from the package's own
    parts (slider, buttons, copy feedback); Alpine owns the state.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<style>
    .pg-studio { display: grid; gap: 1.25rem; grid-template-columns: minmax(0, 1.2fr) minmax(16rem, 1fr); align-items: start; }
    @media (max-width: 52rem) { .pg-studio { grid-template-columns: minmax(0, 1fr); } }
    .pg-studio-preview {
        block-size: 11rem; border-radius: var(--nx-radius-xl); border: 1px solid var(--nx-border);
        box-shadow: inset 0 1px 0 color-mix(in oklab, white 10%, transparent), var(--nx-shadow-sm);
    }
    .pg-studio-css {
        margin: .75rem 0 0; padding: .6rem .75rem; border-radius: var(--nx-radius-md);
        border: 1px solid var(--nx-border); background: var(--nx-surface-2); overflow-wrap: anywhere;
        font: 500 var(--nx-text-xs) / 1.6 var(--nx-font-mono); color: var(--nx-text-muted); direction: ltr; text-align: left;
    }
    .pg-studio-kind { display: flex; gap: .4rem; }
    .pg-studio-kind button {
        padding: .3rem .7rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-full);
        background: transparent; color: var(--nx-text-muted); font: 600 var(--nx-text-xs) / 1.4 var(--nx-font-sans);
        cursor: pointer; transition: background-color var(--nx-dur-base) var(--nx-ease-out), color var(--nx-dur-base) var(--nx-ease-out), border-color var(--nx-dur-base) var(--nx-ease-out);
    }
    .pg-studio-kind button[data-active] { background: color-mix(in oklab, var(--nx-accent) 14%, transparent); border-color: color-mix(in oklab, var(--nx-accent) 45%, var(--nx-border)); color: var(--nx-accent-text); }
    .pg-stop-row { display: grid; grid-template-columns: 2.4rem minmax(0, 1fr) 2rem; gap: .6rem; align-items: center; }
    /* The stop sliders ride the .nx-slider structure for its custom properties. */
    .pg-stop-row .nx-slider { padding-block: 0; }
    .pg-stop-row input[type='color'] {
        inline-size: 2.4rem; block-size: 2.2rem; padding: .15rem; border: 1px solid var(--nx-border);
        border-radius: var(--nx-radius-sm); background: var(--nx-surface); cursor: pointer;
    }
    .pg-stop-row input[type='range'] { inline-size: 100%; }    .pg-stop-remove {
        inline-size: 2rem; block-size: 2rem; display: grid; place-items: center; padding: 0;
        border: 1px solid transparent; border-radius: var(--nx-radius-full); background: transparent;
        color: var(--nx-text-subtle); cursor: pointer; transition: color var(--nx-dur-base) var(--nx-ease-out), background-color var(--nx-dur-base) var(--nx-ease-out);
    }
    .pg-stop-remove:hover { color: var(--nx-danger); background: color-mix(in oklab, var(--nx-danger) 12%, transparent); }
    .pg-stop-remove svg { inline-size: .85rem; block-size: .85rem; }
</style>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The gradient bench', 'میز کار گرادیان') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Design-tool chrome, built only from this package: the precision slider, buttons and copy feedback. Stops sort themselves by position; the CSS follows every move and copies on click.', 'کروم ابزار طراحی، فقط با اجزای همین پکیج: اسلایدر دقت، دکمه‌ها و بازخورد کپی. توقف‌ها خودشان بر اساس جای مرتب می‌شوند؛ خروجی CSS هر حرکت را دنبال می‌کند و با کلیک کپی می‌شود.') }}
        </p>
    </div>

    <div x-data="{
        kind: 'linear',
        angle: 135,
        seq: 3,
        copied: false,
        stops: [
            { id: 1, color: '#5647e6', pos: 0 },
            { id: 2, color: '#22d3ee', pos: 55 },
            { id: 3, color: '#f4a93c', pos: 100 },
        ],
        get sorted() { return [...this.stops].sort((a, b) => a.pos - b.pos) },
        get css() {
            const list = this.sorted.map(s => `${s.color.toLowerCase()} ${s.pos}%`).join(', ');
            if (this.kind === 'radial') return `radial-gradient(circle, ${list})`;
            if (this.kind === 'conic') return `conic-gradient(from ${this.angle}deg, ${list})`;
            return `linear-gradient(${this.angle}deg, ${list})`;
        },
        add() { this.seq += 1; this.stops.push({ id: this.seq, color: '#ffffff', pos: 50 }); },
        remove(id) { if (this.stops.length > 2) this.stops = this.stops.filter(s => s.id !== id); },
        copy() {
            navigator.clipboard?.writeText(`background: ${this.css};`);
            this.copied = true;
            clearTimeout(this.copiedTimer);
            this.copiedTimer = setTimeout(() => this.copied = false, 1600);
        },
    }">
        <div class="pg-studio">
            <div style="display: grid; gap: .75rem; align-content: start">
                <div class="pg-studio-preview" x-bind:style="`background: ${css}`" role="img"
                    x-bind:aria-label="css"></div>
                <code class="pg-studio-css" x-text="`background: ${css};`">background: linear-gradient(135deg, #5647e6 0%, #22d3ee 55%, #f4a93c 100%);</code>
                <div style="display: flex; gap: .5rem; align-items: center">
                    <x-nx::button size="sm" variant="secondary" icon="copy" x-on:click="copy()">
                        <span x-text="copied ? @js($say('Copied', 'کپی شد')) : @js($say('Copy CSS', 'کپی CSS'))">{{ $say('Copy CSS', 'کپی CSS') }}</span>
                    </x-nx::button>
                    <x-nx::button size="sm" variant="ghost" icon="plus" x-on:click="add()">{{ $say('Add stop', 'افزودن توقف') }}</x-nx::button>
                </div>
            </div>

            <div style="display: grid; gap: 1rem; align-content: start">
                <div class="pg-row" style="justify-content: space-between">
                    <span style="font: 600 var(--nx-text-sm) / 1.4 var(--nx-font-sans)">{{ $say('Shape', 'شکل') }}</span>
                    <div class="pg-studio-kind" role="group" x-bind:aria-label="$say('Shape', 'شکل')">
                        @foreach (['linear' => $say('Linear', 'خطی'), 'radial' => $say('Radial', 'شعاعی'), 'conic' => $say('Conic', 'مخروطی')] as $kind => $word)
                            <button type="button" x-on:click="kind = @js($kind)" x-bind:data-active="kind === @js($kind) ? '' : null">{{ $word }}</button>
                        @endforeach
                    </div>
                </div>

                <div class="pg-row" style="justify-content: space-between">
                    <span style="font: 600 var(--nx-text-sm) / 1.4 var(--nx-font-sans)">{{ $say('Angle', 'زاویه') }}</span>
                    <span style="font: 600 var(--nx-text-xs) / 1.4 var(--nx-font-mono); color: var(--nx-text-muted)" x-text="`${angle}°`">135°</span>
                </div>
                <div class="nx-slider" x-bind:style="`--nx-pct: ${(angle / 360) * 100}%`">
                    <input type="range" min="0" max="360" step="1" x-model.number="angle" class="nx-slider-input"
                        x-bind:aria-label="$say('Angle', 'زاویه')">
                </div>

                <div style="display: grid; gap: .6rem">
                    <template x-for="stop in sorted" :key="stop.id">
                        <div class="pg-stop-row">
                            <input type="color" x-model="stop.color" x-bind:aria-label="$say('Stop colour', 'رنگ توقف')">
                            <div class="nx-slider" x-bind:style="`--nx-pct: ${stop.pos}%`">
                                <input type="range" min="0" max="100" step="1" x-model.number="stop.pos" class="nx-slider-input"
                                    x-bind:aria-label="$say('Stop position', 'جای توقف')">
                            </div>
                            <button type="button" class="pg-stop-remove" x-on:click="remove(stop.id)"
                                x-bind:disabled="stops.length <= 2"
                                x-bind:aria-label="$say('Remove stop', 'حذف توقف')">
                                {{ \NabuXUI\NabuXUI::icon('x') }}
                            </button>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</section>
