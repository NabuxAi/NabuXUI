<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\AdminPanel;
use App\Support\Panel;
use Livewire\Attributes\Layout;
use Livewire\Component;
use NabuXUI\NabuXUI;

/**
 * /admin/profile — the account card (avatar, role badge, email) beside three
 * tabs: the overview's fact sheet, the activity stream over the timeline-feed
 * block, and the personal preferences (time zone, theme, language). The
 * preferences are Livewire-local; the language row reuses the topbar's
 * registry-driven switch (SwitchesDemoLocale) and the theme row writes the core theme
 * store, so both land exactly where the rest of the panel reads them.
 */
#[Layout('layouts.admin')]
class Profile extends Component
{
    use AdminPanel;

    public string $tab = 'overview';

    public string $timezone = 'Europe/Berlin';

    public function mount(): void
    {
        $this->rememberLocale();
    }

    /** The preferences tab's save button — a toast in this demo. */
    public function save(): void
    {
        $this->toast(__('admin.settings_saved'), tone: 'success');
    }

    public function render()
    {
        $fmt = fn (int $n) => NabuXUI::formatNumber($n, 0, app()->getLocale());
        $say = fn (string $key, array $replace = []) => __($key, $replace);
        // Zone ids are their own labels; the select wants a value => label map.
        $zones = ['Europe/Berlin', 'Europe/Istanbul', 'Europe/London', 'Asia/Dubai', 'America/New_York', 'UTC'];

        return view('livewire.admin.profile', [
            'user' => Panel::user(),
            'email' => __('admin.invoice_from_line_2'),
            'timezones' => array_combine($zones, $zones),
            'timeline' => [
                ['id' => 'p1', 'actor' => __('admin.person_1'), 'text' => $say('admin.activity_confirmed', ['target' => '#'.$fmt(1248)]), 'time' => now()->subMinutes(3), 'tone' => 'success', 'href' => route('admin.invoice')],
                ['id' => 'p2', 'actor' => __('admin.person_3'), 'text' => __('admin.activity_joined'), 'time' => now()->subHours(1), 'tone' => 'info', 'href' => route('admin.users')],
                ['id' => 'p3', 'icon' => 'upload', 'text' => __('admin.activity_backup'), 'time' => now()->subHours(5), 'tone' => 'info'],
                ['id' => 'p4', 'actor' => __('admin.person_2'), 'text' => $say('admin.activity_paid', ['target' => '#'.$fmt(9801)]), 'time' => now()->subDays(1), 'tone' => 'success', 'href' => route('admin.invoice')],
                ['id' => 'p5', 'actor' => __('admin.person_2'), 'text' => $say('admin.activity_shipped', ['target' => $fmt(201)]), 'time' => now()->subDays(2), 'tone' => 'info', 'href' => route('admin.kanban')],
                ['id' => 'p6', 'actor' => __('admin.person_1'), 'text' => $say('admin.activity_rejected', ['target' => '#'.$fmt(9802)]), 'time' => now()->subDays(3), 'tone' => 'warning'],
            ],
        ]);
    }
}
