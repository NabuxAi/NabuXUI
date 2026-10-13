{{--
    Pull cord's real scenarios: the reading lamp the cord switches on, and the
    theme cord — pull it and the whole page flips. A tone row shows the armed
    glow in every accent.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<style>
    .pg-cord-stage { display: flex; gap: 1.5rem; align-items: stretch; flex-wrap: wrap; }
    .pg-cord-box { position: relative; inline-size: 11rem; min-inline-size: 9rem; border: 1px dashed var(--nx-border); border-radius: var(--nx-radius-xl); background: var(--nx-surface-2); }
    .pg-lamp {
        inline-size: 4.5rem; block-size: 4.5rem; margin-block: auto; border-radius: var(--nx-radius-full);
        border: 1px solid var(--nx-border); background: var(--nx-surface-3); display: grid; place-items: center;
        color: var(--nx-text-subtle); transition: box-shadow var(--nx-dur-slow) var(--nx-ease-out), background-color var(--nx-dur-slow) var(--nx-ease-out), color var(--nx-dur-slow) var(--nx-ease-out);
    }
    .pg-lamp svg { inline-size: 1.75rem; block-size: 1.75rem; }
    [data-lit] .pg-lamp {
        background: color-mix(in oklab, var(--nx-gold) 30%, var(--nx-surface));
        color: var(--nx-gold-text);
        box-shadow: 0 0 24px color-mix(in oklab, var(--nx-gold) 55%, transparent), 0 0 64px color-mix(in oklab, var(--nx-gold) 25%, transparent);
    }
    .pg-led { inline-size: .9rem; block-size: .9rem; border-radius: var(--nx-radius-full); background: var(--nx-surface-3); border: 1px solid var(--nx-border); transition: background-color var(--nx-dur-base) var(--nx-ease-out), box-shadow var(--nx-dur-base) var(--nx-ease-out); }
    [data-lit] .pg-led { background: var(--_led, var(--nx-accent)); box-shadow: 0 0 10px var(--_led, var(--nx-accent)); }
</style>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The reading lamp', 'چراغ مطالعه') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('A rope with real physics in a dashed box — grab the knob, pull it down until it glows, and let go. The lamp answers; the cord springs home and wobbles to a stop.', 'طنابی با فیزیک واقعی داخل کادر خط‌چین — دستگیره را بگیرید، تا وقتی درخشید پایین بکشید و رها کنید. چراغ جواب می‌دهد؛ طناب با فنر برمی‌گردد و لرزشش می‌ایستد.') }}
        </p>
    </div>
    <div class="pg-cord-stage" x-data="{ lit: false }" x-bind:data-lit="lit ? '' : null">
        <div class="pg-cord-box">
            <x-nx::pull-cord tone="gold" height="13rem" :label="$say('Reading lamp cord', 'ریسمان چراغ مطالعه')" x-on:nx-cord-pull="lit = ! lit" />
        </div>
        <div style="display: grid; align-content: center; justify-items: center; gap: .75rem; padding-inline: .5rem">
            <span class="pg-lamp">{{ \NabuXUI\NabuXUI::icon('sun') }}</span>
            <span style="color: var(--nx-text-muted); font-size: var(--nx-text-sm)" x-text="lit ? @js($say('On', 'روشن')) : @js($say('Off', 'خاموش'))">{{ $say('Off', 'خاموش') }}</span>
        </div>
    </div>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Pull to flip the theme', 'بکشید تا تم عوض شود') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The cord is just a trigger: here a release calls $nxTheme.toggle() on the whole page. Pull this one and watch the playground switch sides.', 'ریسمان فقط یک کلید است: این‌جا رها کردنش ‎$nxTheme.toggle()‎ را روی کل صفحه صدا می‌زند. این یکی را بکشید تا پلی‌گراوند ورق بزند.') }}
        </p>
    </div>
    <div class="pg-cord-stage">
        <div x-data="{ on: document.documentElement.dataset.theme === 'dark' }"
            x-bind:data-lit="on ? '' : null"
            x-on:nx-cord-pull="$nxTheme.toggle(); setTimeout(() => on = document.documentElement.dataset.theme === 'dark', 80)"
            style="display: grid; justify-items: center; gap: .5rem; --_led: var(--nx-violet-500)">
            <div class="pg-cord-box" style="inline-size: 7.5rem">
                <x-nx::pull-cord tone="violet" height="10rem" :label="$say('Theme cord', 'ریسمان تم')" />
            </div>
            <span class="pg-led" aria-hidden="true"></span>
            <span style="color: var(--nx-text-subtle); font-size: var(--nx-text-xs)" x-text="on ? @js($say('Dark', 'تیره')) : @js($say('Light', 'روشن'))">{{ $say('Light', 'روشن') }}</span>
        </div>
        @foreach (['accent' => 'var(--nx-accent)', 'gold' => 'var(--nx-gold)', 'success' => 'var(--nx-success)'] as $tone => $led)
            <div x-data="{ lit: false }" x-bind:data-lit="lit ? '' : null" x-on:nx-cord-pull="lit = ! lit" style="display: grid; justify-items: center; gap: .5rem; --_led: {{ $led }}">
                <div class="pg-cord-box" style="inline-size: 7.5rem">
                    <x-nx::pull-cord tone="{{ $tone }}" height="10rem" :label="$say('Tone cord', 'ریسمان رنگ')" />
                </div>
                <span class="pg-led" aria-hidden="true"></span>
            </div>
        @endforeach
    </div>
</section>
