{{--
    تنظیمات — the body of App\Filament\Pages\Settings::content(Schema):
    a Filament form of four nx fields (toggle, segmented, select, slider)
    with a «ذخیره» action that writes storage/app/settings.json. The fields
    bind through wire:model and dispatch no events, so nothing here needs
    wiring back to Livewire beyond the form's own submit handler.
--}}
<x-filament-panels::page>
    <div class="nx-settings">
        {!! $this->content->toEmbeddedHtml() !!}
    </div>
</x-filament-panels::page>

<style>
    /* Keep the form on a single readable column; the save button rides the footer. */
    .nx-settings {
        max-inline-size: 44rem;
    }
</style>
