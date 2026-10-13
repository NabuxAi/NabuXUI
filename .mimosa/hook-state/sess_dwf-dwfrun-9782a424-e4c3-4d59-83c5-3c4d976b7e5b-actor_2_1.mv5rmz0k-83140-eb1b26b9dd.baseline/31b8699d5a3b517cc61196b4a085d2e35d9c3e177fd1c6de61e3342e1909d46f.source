<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use NabuXUI\Livewire\WithToasts;

#[Layout('layouts.app')]
class Showcase extends Component
{
    use WithFileUploads;
    use WithToasts;

    #[Validate('required|email')]
    public string $email = 'nabu@';

    public string $message = '';

    public string $tab = 'chat';

    public string $billing = 'monthly';

    public int $rating = 4;

    public string $code = '';

    public float $temperature = 0.7;

    public bool $showInvite = false;

    public bool $notify = true;

    public string $plan = 'monthly';

    public int $count = 12840;

    public bool $streaming = false;

    public array $attachments = [];

    public function save(): void
    {
        $this->validate();
        $this->toast('Saved', "We'll write to {$this->email}.", tone: 'success');
    }

    public function pay(): void
    {
        usleep(900_000);
        $this->toast('Payment received', 'Receipt sent · 領収書を送りました', tone: 'success');
    }

    public function ask(): void
    {
        $this->streaming = true;
        $this->toast('Nabu is answering', $this->message ?: '…', tone: 'accent');
        $this->message = '';
    }

    public function stop(): void
    {
        $this->streaming = false;
    }

    public function bump(): void
    {
        $this->count += random_int(500, 4000);
    }

    public function invite(): void
    {
        $this->showInvite = false;
        $this->toast('Invitation sent', tone: 'success');
    }

    public function render()
    {
        return view('livewire.showcase');
    }
}
