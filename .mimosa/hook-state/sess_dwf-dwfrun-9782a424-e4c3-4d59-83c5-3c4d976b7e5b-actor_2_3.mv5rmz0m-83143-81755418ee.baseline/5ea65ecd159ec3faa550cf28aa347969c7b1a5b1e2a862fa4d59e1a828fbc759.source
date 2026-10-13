{{--
    Image reveal's real scenarios: an AI image generator whose result resolves
    out of the noise as the progress reaches 100, and a lazy gallery whose
    photos dissolve in as they arrive.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    // A generated landscape (sky, sun, three ridges) as an SVG data URI — the demo's "photo".
    $art = function (array $sky, string $sun, array $hills): string {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 600" preserveAspectRatio="xMidYMid slice">'
            .'<defs><linearGradient id="s" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="'.$sky[0].'"/><stop offset="1" stop-color="'.$sky[1].'"/></linearGradient></defs>'
            .'<rect width="800" height="600" fill="url(#s)"/><circle cx="560" cy="250" r="70" fill="'.$sun.'"/>'
            .'<path d="M0 380 Q150 300 300 360 T600 330 T800 350 V600 H0Z" fill="'.$hills[0].'"/>'
            .'<path d="M0 450 Q200 380 380 440 T800 420 V600 H0Z" fill="'.$hills[1].'"/>'
            .'<path d="M0 520 Q250 470 480 520 T800 500 V600 H0Z" fill="'.$hills[2].'"/></svg>';

        return 'data:image/svg+xml;charset=utf-8,'.rawurlencode($svg);
    };
    $dusk = $art(['#1b1f4b', '#f08a5d'], '#ffd27a', ['#3b2f63', '#2a2350', '#171433']);
    $gallery = [
        ['src' => $art(['#9fd3ff', '#e9f6ff'], '#fff3b0', ['#7cbf8e', '#4f9a6a', '#2f6b48']), 'alt' => $say('Green hills under a pale morning sky', 'تپه‌های سبز زیر آسمان رنگ‌پریدهٔ صبح')],
        ['src' => $art(['#2b1055', '#d53369'], '#ffb199', ['#4b1d52', '#33133d', '#1d0b24']), 'alt' => $say('Purple ridges at sunset', 'رشته‌کوه‌های بنفش در غروب')],
        ['src' => $art(['#0f2027', '#2c5364'], '#e0f7fa', ['#20404c', '#16323b', '#0b1d22']), 'alt' => $say('Night valley with a full moon', 'دره‌ای در شب با ماه کامل')],
    ];
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('AI image generator', 'سازندهٔ تصویر با هوش مصنوعی') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('While the model works, pixel noise churns and gets finer with the progress; at 100% the noise dissolves outward and the image sharpens out of a blur.', 'تا مدل کار می‌کند، نویز پیکسلی می‌جوشد و با پیشرفت ریزتر می‌شود؛ در ۱۰۰٪ نویز از مرکز کنار می‌رود و تصویر از دل بلور واضح می‌شود.') }}
        </p>
    </div>
    <div x-data="{
            progress: 0, busy: false, timer: null,
            push(detail) { this.$root.querySelector('.nx-image-reveal').dispatchEvent(new CustomEvent('nx-image-update', { detail })) },
            generate() {
                clearInterval(this.timer);
                this.busy = true; this.progress = 0;
                this.push({ progress: 0, src: null });
                this.timer = setInterval(() => {
                    this.progress = Math.min(100, this.progress + 3 + Math.round(Math.random() * 6));
                    this.push(this.progress >= 100 ? { progress: 100, src: @js($dusk) } : { progress: this.progress });
                    if (this.progress >= 100) { clearInterval(this.timer); this.busy = false; }
                }, 220);
            },
        }" style="display: grid; gap: 1rem; max-inline-size: 36rem">
        <div class="pg-row" style="flex-wrap: nowrap">
            <x-nx::input :aria-label="$say('Prompt', 'پرامپت')" :value="$say('A lighthouse at dusk, wide shot, warm light', 'فانوس دریایی در غروب، نمای باز، نور گرم')" style="flex: 1" />
            <x-nx::button variant="primary" icon="sparkles" x-on:click="generate()" x-bind:disabled="busy">
                <span x-text="busy ? @js($say('Generating…', 'در حال ساخت…')) : @js($say('Generate', 'بساز'))">{{ $say('Generate', 'بساز') }}</span>
            </x-nx::button>
        </div>
        <x-nx::image-reveal ratio="16 / 9" :progress="0" :alt="$say('A lighthouse at dusk', 'فانوس دریایی در غروب')" :label="$say('Generating', 'در حال ساخت')" :error-label="$say('Generation failed', 'ساخت ناموفق بود')" />
    </div>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A lazy gallery', 'گالری تنبل') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('No progress here: each frame waits for its image and resolves the moment it has loaded. Press the button to let them arrive one after another.', 'این‌جا پیشرفتی در کار نیست: هر قاب منتظر تصویرش می‌ماند و همان لحظهٔ بارگذاری آشکار می‌شود. دکمه را بزنید تا یکی‌یکی برسند.') }}
        </p>
    </div>
    <div x-data="{ load() { [...$el.querySelectorAll('.nx-image-reveal')].forEach((frame, i) => setTimeout(() => frame.dispatchEvent(new CustomEvent('nx-image-update', { detail: { src: frame.dataset.later } })), 350 + i * 450)) } }" style="display: grid; gap: 1rem">
        <div style="display: grid; gap: .75rem; grid-template-columns: repeat(auto-fit, minmax(10rem, 1fr))">
            @foreach ($gallery as $photo)
                <x-nx::image-reveal ratio="1" :alt="$photo['alt']" :data-later="$photo['src']" :label="$say('Loading', 'در حال بارگذاری')" />
            @endforeach
        </div>
        <div class="pg-row"><x-nx::button variant="secondary" icon="image" x-on:click="load()">{{ $say('Load the photos', 'بارگذاری عکس‌ها') }}</x-nx::button></div>
    </div>
</section>
