{{--
    <x-nx::file-drop wire:model="attachments" multiple hint="PDF up to 20 MB" />
    Dropping files feeds them to the input, so Livewire uploads them; the list shows its progress.
--}}
@props(['title' => null, 'hint' => null])
<div x-data="nxFileDrop()" {{ $attributes->only(['class', 'style']) }}>
    <label class="nx-filedrop" x-bind:data-dragging="dragging ? '' : null" @dragenter="enter($event)" @dragover.prevent @dragleave="leave()" @drop="drop($event)">
        <input type="file" x-ref="input" {{ $attributes->except(['class', 'style'])->class('nx-visually-hidden') }}>
        <span class="nx-filedrop-icon" aria-hidden="true">{{ \NabuXUI\NabuXUI::icon('upload') }}</span>
        <span class="nx-filedrop-title">
            @if ($title){{ $title }}@else
                @php [$before, $after] = array_pad(explode(':browse', __('nabuxui::ui.dropFiles')), 2, ''); @endphp
                {{ $before }}<u>{{ __('nabuxui::ui.browse') }}</u>{{ $after }}
            @endif
        </span>
        @if ($hint)<span class="nx-filedrop-hint">{{ $hint }}</span>@endif
    </label>
    <ul class="nx-file-list" x-show="files.length" x-cloak>
        <template x-for="(file, i) in files" :key="file.name + i">
            <li class="nx-file" x-bind:data-status="file.status" x-bind:style="`--nx-i: ${i}`">
                {{ \NabuXUI\NabuXUI::icon('file') }}
                <span class="nx-file-name" x-text="file.name"></span>
                <span class="nx-file-state">
                    <template x-if="file.status === 'done'"><span>{{ \NabuXUI\NabuXUI::icon('check-circle', '', __('nabuxui::ui.uploaded')) }}</span></template>
                    <template x-if="file.status === 'error'"><span>{{ \NabuXUI\NabuXUI::icon('alert-circle', '', __('nabuxui::ui.failed')) }}</span></template>
                </span>
                <span class="nx-file-meta" x-text="size(file.size)"></span>
                <template x-if="file.status === 'uploading'">
                    <progress class="nx-progress" max="100" x-bind:value="file.progress" x-bind:style="`--nx-value: ${file.progress}`" aria-label="{{ __('nabuxui::ui.uploading') }}"></progress>
                </template>
            </li>
        </template>
    </ul>
</div>
