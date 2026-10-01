<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\SwitchesDemoLocale;
use Illuminate\Http\Response;
use Livewire\Attributes\Layout;
use Livewire\Component;
use NabuXUI\NabuXUI;

/**
 * /admin/errors/{404,500,maintenance} — the panel's standalone minimal error
 * pages: no admin shell, just the empty-state block centred on the plain
 * skeleton (the same one the auth gate uses). The code rides in through the
 * route's defaults(), exactly like the auth gate's mode; the numbered
 * flavours carry the big code in gradient display type — rolled through the
 * locale's digits — and every word comes from admin.errors_* keys. Each
 * flavour answers with its own HTTP status (404/500/503) through Livewire's
 * ->response() hook on the page component's view.
 */
#[Layout('layouts.admin')]
class ErrorPage extends Component
{
    use SwitchesDemoLocale;

    /** The page's flavour: '404' | '500' | 'maintenance'. */
    public string $code = '404';

    public function mount(string $code = '404'): void
    {
        $this->code = in_array($code, ['404', '500', 'maintenance'], true) ? $code : '404';
        $this->rememberLocale();
    }

    public function render()
    {
        return view('livewire.admin.error-page', [
            'code' => $this->code,
            'title' => __('admin.errors_'.$this->code.'_title'),
            'description' => __('admin.errors_'.$this->code.'_description'),
            // The big gradient code only exists for the numbered flavours; the
            // maintenance page leans on its plate alone.
            'display' => $this->code === 'maintenance' ? null
                : NabuXUI::formatNumber((int) $this->code, 0, str_replace('_', '-', app()->getLocale())),
            'icon' => match ($this->code) {
                '404' => 'search',
                '500' => 'alert-triangle',
                default => 'settings',
            },
        ])
            // The page also answers with the flavour's own status — a 404 that
            // ships as 200 is a lie crawlers and monitors repeat.
            ->response(fn (Response $response) => $response->setStatusCode(
                ['404' => 404, '500' => 500][$this->code] ?? 503,
            ));
    }
}
