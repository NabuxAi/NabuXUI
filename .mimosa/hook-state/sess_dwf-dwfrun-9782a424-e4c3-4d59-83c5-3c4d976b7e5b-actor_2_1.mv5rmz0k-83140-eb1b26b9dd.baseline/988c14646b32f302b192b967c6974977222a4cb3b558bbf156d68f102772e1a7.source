<?php

namespace App\Livewire\Auth;

use App\Livewire\Concerns\SwitchesDemoLocale;
use Livewire\Attributes\Layout;
use Livewire\Component;
use NabuXUI\Livewire\WithToasts;

/**
 * /login · /register · /forgot-password — the auth-card demo gate. UI only:
 * "signing in" just sets a demo session flag and lands on /admin; the card's
 * client-side mode switches are mirrored into $mode via nx-mode-change so the
 * one submit action knows which pane sent it.
 */
#[Layout('layouts.admin')]
class Gate extends Component
{
    use SwitchesDemoLocale;
    use WithToasts;

    public string $mode = 'login';

    /** @var array<string, mixed> */
    public array $form = ['name' => '', 'email' => '', 'password' => '', 'remember' => true];

    public function mount(string $mode = 'login'): void
    {
        $this->mode = in_array($mode, ['login', 'register', 'forgot'], true) ? $mode : 'login';
        $this->rememberLocale();
    }

    /** wire:submit — branches on the pane that is currently showing. */
    public function submit(): void
    {
        if ($this->mode === 'forgot') {
            $this->toast(__('admin.auth_reset_sent'), tone: 'info');

            return;
        }

        // Login and sign-up both "open" the panel for this demo session.
        session(['admin_auth' => true]);
        $this->toastAfterRedirect(
            $this->mode === 'register' ? __('admin.auth_register_done') : __('admin.auth_login_done'),
            tone: 'success',
        );

        $this->redirect(route('admin.dashboard'));
    }

    public function render()
    {
        return view('livewire.auth.gate');
    }
}
