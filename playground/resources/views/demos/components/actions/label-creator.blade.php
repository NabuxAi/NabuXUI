{{--
    Label creator's real scenarios: the labels field of an issue tracker (bound
    to Livewire, a toast on each create), and a plain-form variant with its own
    palette that posts the list as JSON.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<section class="pg-box" style="gap: 1.25rem" x-data x-init="Array.isArray($wire.get('state.labels')) || $wire.set('state.labels', @js($state['labels'] ?? []), false)">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Labelling an issue', 'برچسب‌زدن به یک مسئله') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Type a name, pick a colour, press Enter: a tiny loader spins on the create button, the chip pops in with its dot, and the server hears it through nx-label-created.', 'یک نام بنویسید، رنگ بگیرید و Enter بزنید: روی دکمهٔ ساخت لودری می‌چرخد، چیپ با نقطه‌اش می‌نشیند و سرور از راه nx-label-created می‌شنود.') }}
        </p>
    </div>
    <x-nx::label-creator wire:model.live="state.labels" :labels="$state['labels'] ?? []"
        :label="$say('Labels', 'برچسب‌ها')" :placeholder="$say('Add a label…', 'برچسب تازه…')"
        :create-text="$say('Create “:name”', 'ساخت «:name»')" :color-label="$say('Colour', 'رنگ')"
        x-on:nx-label-created="$wire.ping(@js($say('Label created: ', 'برچسب ساخته شد: ')) + $event.detail.name)" />
    <div class="pg-row" style="justify-content: space-between">
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say(NabuXUI::formatNumber(count($state['labels'] ?? [])).' labels live on the server', NabuXUI::formatNumber(count($state['labels'] ?? [])).' برچسب در سرور زنده است') }}
        </p>
        <x-nx::button variant="secondary" size="sm" wire:click="save(@js($say('Labels saved to the issue', 'برچسب‌ها روی مسئله ذخیره شد')))">
            {{ $say('Save labels', 'ذخیرهٔ برچسب‌ها') }}
        </x-nx::button>
    </div>
</section>

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A plain form with its own palette', 'فرم ساده با پیکر رنگ خودش') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('Without Livewire the list posts as JSON under the name you give — here with a three-swatch palette to match a brand.', 'بدون Livewire لیست به‌شکل JSON با نامی که می‌دهید پست می‌شود — اینجا با پیکر سه‌رنگِ هماهنگ با برند.') }}
        </p>
        <form wire:submit.prevent="ping(@js($say('Labels posted as JSON', 'برچسب‌ها به‌شکل JSON پست شد')))" style="display: grid; gap: 1rem">
            <x-nx::label-creator name="labels" :label="$say('Project tags', 'برچسب‌های پروژه')" :placeholder="$say('New tag…', 'برچسب تازه…')"
                :create-text="$say('Create “:name”', 'ساخت «:name»')" :color-label="$say('Colour', 'رنگ')"
                :colors="[
                    ['value' => 'brand', 'label' => $say('Brand', 'برند'), 'color' => 'var(--nx-accent)'],
                    ['value' => 'night', 'label' => $say('Night', 'شب'), 'color' => 'var(--nx-chart-6)'],
                    ['value' => 'meadow', 'label' => $say('Meadow', 'چمنزار'), 'color' => 'var(--nx-chart-4)'],
                ]"
                :labels="[
                    ['name' => $say('Launch', 'انتشار'), 'color' => 'brand'],
                    ['name' => $say('Client work', 'کار مشتری'), 'color' => 'night'],
                ]" />
            <x-nx::button variant="secondary" size="sm" type="submit">{{ $say('Post the form', 'ارسال فرم') }}</x-nx::button>
        </form>
    </section>

    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Small print', 'نکته‌های ریز') }}</h3>
        <ul role="list" style="margin: 0; padding-inline-start: 1.25rem; display: grid; gap: .5rem; color: var(--nx-text-muted)">
            <li>{{ $say('Enter creates, Escape clears what you typed, Backspace removes the last chip.', 'Enter می‌سازد، Escape تایپ‌شده را پاک می‌کند و Backspace آخرین چیپ را برمی‌دارد.') }}</li>
            <li>{{ $say('Every chip is removable with a real button and a per-chip aria-label.', 'هر چیپ با یک دکمهٔ واقعی و aria-label مخصوص خودش برداشته می‌شود.') }}</li>
        </ul>
    </section>
</div>
