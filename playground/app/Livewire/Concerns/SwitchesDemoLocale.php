<?php

namespace App\Livewire\Concerns;

/**
 * fa/en switching for the demo pages. The language menu binds `locale`
 * (wire:model.live); we keep the choice in the session — the
 * SetLocaleFromSession middleware applies it to every next request — then
 * reload the page so the layout's lang/dir flip together with the language.
 */
trait SwitchesDemoLocale
{
    public string $locale = 'en';

    /** Where the switch happened, so the reload lands back on the same demo. */
    public string $path = '/components';

    /** Call from mount(): sync the menu with the active locale and note the path. */
    protected function rememberLocale(): void
    {
        // ?lang=fa|en also switches (a plain link can set it); the session
        // keeps the choice for every following request via the middleware.
        $lang = request()->query('lang');
        if (in_array($lang, ['fa', 'en'], true)) {
            session(['locale' => $lang]);
            app()->setLocale($lang);
        }

        $this->locale = app()->getLocale();
        $this->path = request()->getPathInfo();
    }

    /** The language menu lands here (property hook on `locale`). */
    public function updated(string $name, ?string $value): void
    {
        if ($name === 'locale' && in_array($value, ['fa', 'en'], true)) {
            session(['locale' => $value]);
            $this->redirect($this->path);
        }
    }
}
