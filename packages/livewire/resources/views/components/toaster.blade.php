{{--
    Once per layout: <x-nx::toaster />. Shows toasts from $this->toast() (Livewire),
    NabuXUI::flashToast() (after a redirect), and $nxToast() / window.NabuXUI.toast() in the browser.
--}}
@props(['position' => 'bottom-end'])
<section {{ $attributes->class('nx-toaster')->merge(['data-position' => $position, 'aria-label' => __('nabuxui::ui.notifications'), 'popover' => 'manual']) }}
    x-data="nxToaster(@js(\NabuXUI\NabuXUI::flashedToasts()))" wire:ignore data-navigate-persist
    @nx-toast.window="push($event.detail)" @pointerenter="pause()" @pointerleave="resume()" @focusin="pause()"
    @focusout="if (! $root.contains($event.relatedTarget)) resume()">
    <ol class="nx-toast-list" x-ref="list" aria-live="polite">
        <template x-for="item in items" :key="item.id">
            <li class="nx-toast" x-bind:data-id="item.id" x-bind:data-tone="item.tone" x-bind:data-state="item.state" x-bind:role="item.tone === 'danger' ? 'alert' : null" x-init="mount($el, item)">
                <span class="nx-toast-icon" x-html="icon(item.tone)"></span>
                <div class="nx-toast-body">
                    <p class="nx-toast-title" x-text="item.title"></p>
                    <template x-if="item.description"><p class="nx-toast-description" x-text="item.description"></p></template>
                </div>
                <template x-if="item.action && item.action.href">
                    <a class="nx-button nx-toast-action" data-variant="secondary" data-size="xs" x-bind:href="item.action.href"><span class="nx-button-label" x-text="item.action.label"></span></a>
                </template>
                <button type="button" class="nx-toast-close" aria-label="{{ __('nabuxui::ui.dismiss') }}" @click="dismiss(item.id)">{{ \NabuXUI\NabuXUI::icon('x') }}</button>
                <template x-if="item.duration > 0 && item.state === 'open'">
                    <span class="nx-toast-timer" aria-hidden="true" x-bind:style="`--nx-duration: ${item.duration}ms`"></span>
                </template>
            </li>
        </template>
    </ol>
</section>
