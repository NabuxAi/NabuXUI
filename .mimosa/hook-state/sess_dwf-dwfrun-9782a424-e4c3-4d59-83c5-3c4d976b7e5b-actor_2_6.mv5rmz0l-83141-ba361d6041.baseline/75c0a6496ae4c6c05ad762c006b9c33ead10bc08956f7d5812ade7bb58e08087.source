{{--
    The infinite grid's real scenarios: a hero section over the drifting dot
    field, then a denser panel with the cell size tuned.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A hero that never stands still', 'قهرمانی که هیچ‌وقت نمی‌ایستد') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Drop it inside any positioned container and put your heading in the slot. Move the pointer: the dots it touches light up; the stepper in the corner tightens or loosens the mesh.', 'آن را داخل هر ظرفِ position دار بیندازید و تیترتان را در اسلات بگذارید. نشانگر را حرکت بدهید: نقطه‌هایی که لمس می‌کند روشن می‌شوند؛ پلهٔ گوشه شبکه را سفت یا شل می‌کند.') }}
        </p>
    </div>
    <div style="position: relative; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-2xl); overflow: clip">
        <x-nx::infinite-grid>
            <div style="display: grid; gap: .75rem; padding: 3.5rem max(1.5rem, 6cqi); text-align: start; max-inline-size: 38rem">
                <h2 style="margin: 0; font: 700 var(--nx-text-4xl) / 1.15 var(--nx-font-display)">{{ $say('Infrastructure that never says “later”', 'زیرسازی‌ای که هیچ‌وقت «بعداً» نمی‌گوید') }}</h2>
                <p style="margin: 0; color: var(--nx-text-muted)">
                    {{ $say('The same dots in light and dark, left-to-right and right-to-left — the field drifts, your content stays put.', 'همان نقطه‌ها در روشن و تیره، چپ‌به‌راست و راست‌به‌چپ — میدان می‌راند، محتوایتان سرِ جایش می‌ماند.') }}
                </p>
                <div class="pg-row">
                    <x-nx::button variant="primary" icon="arrow-right" wire:click="ping(@js($say('Welcome aboard', 'خوش آمدید')))">{{ $say('Start building', 'شروع کنید') }}</x-nx::button>
                    <x-nx::button variant="ghost" wire:click="ping(@js($say('Docs are one page, promise', 'مستندات فقط یک صفحه‌ست، قول')))">{{ $say('Read the one-pager', 'یک‌صفحه‌ای را بخوانید') }}</x-nx:button>
                </div>
            </div>
        </x-nx::infinite-grid>
    </div>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A denser field, a smaller cell', 'میدانِ متراکم‌تر، سلولِ کوچک‌تر') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('cell sets the starting mesh (16 px here) and min / max / step keep the corner stepper within your taste.', 'cell شبکهٔ آغازین را تعیین می‌کند (اینجا ۱۶ پیکسل) و min / max / step پلهٔ گوشه را در سلیقهٔ شما نگه می‌دارد.') }}
        </p>
    </div>
    <div style="position: relative; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-xl); overflow: clip">
        <x-nx::infinite-grid :cell="16" :min="8" :max="32" :step="2">
            <p style="margin: 0; padding: 2.5rem 1.5rem; font: 600 var(--nx-text-lg) / 1.6 var(--nx-font-display)">
                {{ $say('“It feels like the page is breathing under my text.”', '«انگار صفحه زیر متن من نفس می‌کشد.»') }}
            </p>
        </x-nx::infinite-grid>
    </div>
</section>
