{{--
    File drop's real scenarios: the attachment field of a KYC form (plain POST
    — the dropzone feeds the native input), and a single-file avatar picker
    with its own invitation text. The queue under the field is the component's
    own; live per-file progress arrives when Livewire uploads drive the input
    (wire:model + WithFileUploads).
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A document checklist', 'چک‌لیست مدارک') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('Drop the files anywhere on the field — they land in the queue with their size; the drag state lights the rim. This form posts them natively.', 'فایل‌ها را هرجای فیلد رها کنید — در صف با حجمشان می‌نشینند و حالت کشیدن rim را روشن می‌کند. این فرم آنها را بومی پست می‌کند.') }}
        </p>
    </div>
    <form wire:submit.prevent="save(@js($say('Documents submitted for review', 'مدارک برای بررسی فرستاده شد')))" style="display: grid; gap: 1rem">
        <x-nx::file-drop multiple name="documents[]" :hint="$say('PDF or images, up to '.NabuXUI::formatNumber(20).' MB each', 'PDF یا تصویر، هرکدام تا '.NabuXUI::formatNumber(20).' مگابایت')" />
        <div class="pg-row" style="justify-content: space-between">
            <p style="margin: 0; color: var(--nx-text-subtle); font-size: var(--nx-text-sm)">
                {{ $say('With wire:model + WithFileUploads the same queue shows live progress per file.', 'با wire:model و WithFileUploads همین صف پیشرفت زندهٔ هر فایل را نشان می‌دهد.') }}
            </p>
            <x-nx::button variant="primary" icon="check" type="submit">{{ $say('Submit for review', 'ارسال برای بررسی') }}</x-nx::button>
        </div>
    </form>
</section>

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('One avatar, its own words', 'یک آواتار، با واژه‌های خودش') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('title replaces the built-in invitation and accept filters what the file picker offers — single file, no multiple.', 'با title دعوت پیش‌فرض عوض می‌شود و accept فیلتر دیالوگ فایل را تعیین می‌کند — تک‌فایل، بدون multiple.') }}
        </p>
        <x-nx::file-drop accept="image/*" name="avatar"
            :title="$say('Drop your photo here, or tap to browse', 'عکست را همین‌جا رها کن، یا برای انتخاب بزن')"
            :hint="$say('Square reads best — we crop to a circle.', 'مربع بهتر دیده می‌شود — دایره‌ای برش می‌خوریم.')" />
    </section>

    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Accessibility', 'دسترس‌پذیری') }}</h3>
        <ul role="list" style="margin: 0; padding-inline-start: 1.25rem; display: grid; gap: .5rem; color: var(--nx-text-muted)">
            <li>{{ $say('The whole zone is a real <label> wrapping a native file input — keyboard and screen readers work out of the box.', 'کل ناحیه یک <label> واقعی دور یک input فایلِ بومی است — کیبورد و صفحه‌خوان از همان ابتدا کار می‌کنند.') }}</li>
            <li>{{ $say('Dropping simply forwards to the input, so your validation sees one source of truth.', 'رهاکردن فقط به input پاس می‌دهد، پس اعتبارسنجی‌تان یک منبع حقیقت می‌بیند.') }}</li>
        </ul>
    </section>
</div>
