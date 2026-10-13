<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\AdminPanel;
use Livewire\Attributes\Layout;
use Livewire\Component;
use NabuXUI\NabuXUI;

/**
 * /admin/errors — the front door for the panel's standalone error pages.
 * The flavours themselves (/admin/errors/{404,500,maintenance}) deliberately
 * answer with their own HTTP status — a 404 that ships as 200 is a lie
 * crawlers and monitors repeat — so the sidebar and the command palette
 * link here, a plain 200, and each card opens its flavour through a GET
 * form button: still one click for a person, but a link checker that only
 * follows <a href> no longer reads the demo's honest status codes as
 * broken navigation. The flavours' addresses stay stable and deep-linkable.
 */
#[Layout('layouts.admin')]
class Errors extends Component
{
    use AdminPanel;

    public function mount(): void
    {
        $this->rememberLocale();
    }

    public function render()
    {
        $locale = str_replace('_', '-', app()->getLocale());
        $fmt = fn (int $n) => NabuXUI::formatNumber($n, 0, $locale);

        // code → [HTTP status, badge tone, icon]. The numbered flavours carry
        // the big gradient code rolled through the locale's digits; maintenance
        // leans on its icon alone, exactly like ErrorPage itself.
        $flavours = [];
        foreach ([
            '404' => [404, 'warning', 'search'],
            '500' => [500, 'danger', 'alert-triangle'],
            'maintenance' => [503, 'info', 'settings'],
        ] as $code => [$status, $tone, $icon]) {
            $flavours[] = [
                'code' => $code,
                'status' => $status,
                'statusLabel' => $fmt($status),
                'tone' => $tone,
                'icon' => $icon,
                'title' => __('admin.errors_'.$code.'_title'),
                'description' => __('admin.errors_'.$code.'_description'),
                'display' => $code === 'maintenance' ? null : $fmt((int) $code),
                'href' => route('admin.errors.'.$code),
                'path' => '/admin/errors/'.$code,
            ];
        }

        return view('livewire.admin.errors', ['flavours' => $flavours]);
    }
}
