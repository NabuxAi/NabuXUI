<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\AdminPanel;
use App\Support\Panel;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * /admin/settings — the workspace form (name, default role), the security and
 * notification toggles, the appearance rows (the theme-switch and the fa/en
 * language-menu, the same stores the topbar writes to) and the danger zone:
 * "delete workspace" opens a dialog (native <dialog> under the block) whose
 * confirm only toasts — nothing here touches real data, it is all demo
 * state kept in $form.
 */
#[Layout('layouts.admin')]
class Settings extends Component
{
    use AdminPanel;

    /** @var array<string, mixed> */
    public array $form = [
        'workspace' => '',
        'defaultRole' => 'editor',
        'twoFactor' => true,
        'signinAlert' => true,
        'emailDigest' => true,
        'productUpdates' => false,
    ];

    public bool $confirmingDelete = false;

    public function mount(): void
    {
        $this->rememberLocale();
        $this->form['workspace'] = __('admin.brand');
    }

    /** The form's save (wire:submit) — a toast in this demo. */
    public function save(): void
    {
        $this->toast(__('admin.settings_saved'), tone: 'success');
    }

    /** The danger dialog's confirm: close it, then own up to being a demo. */
    public function deleteWorkspace(): void
    {
        $this->confirmingDelete = false;
        $this->toast(__('admin.settings_danger_toast'), tone: 'warning');
    }

    public function render()
    {
        return view('livewire.admin.settings', [
            'roles' => [
                'admin' => __('admin.users_role_admin'),
                'editor' => __('admin.users_role_editor'),
                'viewer' => __('admin.users_role_viewer'),
            ],
            'languages' => Panel::languages(),
        ]);
    }
}
