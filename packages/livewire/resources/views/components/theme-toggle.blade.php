<button type="button" {{ $attributes->class('nx-button nx-theme-toggle')->merge(['data-variant' => 'ghost', 'data-icon-only' => '', 'aria-label' => __('nabuxui::ui.darkMode')]) }}
    x-data="nxTheme()" x-bind:aria-pressed="dark ? 'true' : 'false'" x-bind:title="dark ? @js(__('nabuxui::ui.toLight')) : @js(__('nabuxui::ui.toDark'))" @click="toggle()">
    <span class="nx-button-label"><span class="nx-theme-icons" aria-hidden="true">{{ \NabuXUI\NabuXUI::icon('sun', 'nx-theme-sun') }}{{ \NabuXUI\NabuXUI::icon('moon', 'nx-theme-moon') }}</span></span>
</button>
