<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\AdminPanel;
use App\Support\Locales;
use Livewire\Attributes\Layout;
use Livewire\Component;
use NabuXUI\NabuXUI;

/**
 * /admin/email — the shop's correspondence desk on the nx-email block: the
 * built-in folder rail, the message list and the reading pane with its reply
 * composer (replies round-trip through sendReply for their toast). The
 * header carries the inbox's unread tally (it agrees with the sidebar badge)
 * and the compose dialog — to / subject / body, send or park as a draft.
 * Message data comes from the admin.email_* keys; the block's own words
 * (folders, star, reply) live in the core i18n table.
 */
#[Layout('layouts.admin')]
class Email extends Component
{
    use AdminPanel;

    /** The compose dialog (x-nx::dialog wire:model). */
    public bool $composing = false;

    public string $composeTo = '';

    public string $composeSubject = '';

    public string $composeBody = '';

    /**
     * The shared trait's updated() hook types its value ?string, which the
     * composing bool crashes against — same behaviour for the locale switch,
     * but through a wider door. (Class methods win over the trait's.)
     */
    public function updated(string $name, mixed $value): void
    {
        if ($name === 'locale' && is_string($value) && in_array($value, Locales::codes(), true)) {
            session(['locale' => $value]);
            $this->redirect($this->path);
        }
    }

    public function mount(): void
    {
        $this->rememberLocale();
    }

    /** The reading pane's reply composer: the block clears itself, the toast says it shipped. */
    public function sendReply(string $text, string $messageId): void
    {
        $this->toast(__('admin.email_sent_toast'), tone: 'success');
    }

    /** The compose dialog's send. */
    public function send(): void
    {
        $this->composing = false;
        $this->reset('composeTo', 'composeSubject', 'composeBody');
        $this->toast(__('admin.email_sent_toast'), tone: 'success');
    }

    /** The compose dialog's “park it in drafts”. */
    public function saveDraft(): void
    {
        $this->composing = false;
        $this->reset('composeTo', 'composeSubject', 'composeBody');
        $this->toast(__('admin.email_draft_toast'), tone: 'info');
    }

    public function render()
    {
        $locale = str_replace('_', '-', app()->getLocale());
        $me = __('admin.user_name');

        // The correspondence desk: three unread in the inbox (the sidebar's
        // badge says the same), one read, plus one sent, one draft parked on
        // its snippet and the archived elder of the invoice thread. The trash
        // stays empty on purpose — the block's own empty line covers it.
        $messages = [
            ['id' => 'm1', 'from' => ['name' => __('admin.email_person_1'), 'email' => 'maryam@nabu.studio'],
                'subject' => __('admin.email_subject_1'), 'body' => __('admin.email_body_1'),
                'time' => now()->subMinutes(18), 'unread' => true, 'starred' => true],
            ['id' => 'm2', 'from' => ['name' => __('admin.email_person_2'), 'email' => 'saman@nabu.studio'],
                'subject' => __('admin.email_subject_2'), 'body' => __('admin.email_body_2'),
                'time' => now()->subHours(2), 'unread' => true],
            ['id' => 'm3', 'from' => ['name' => __('admin.email_person_3'), 'email' => 'support@adamkoo.io'],
                'subject' => __('admin.email_subject_4'), 'body' => __('admin.email_body_4'),
                'time' => now()->subHours(6), 'unread' => true],
            ['id' => 'm4', 'from' => ['name' => __('admin.email_person_1'), 'email' => 'maryam@nabu.studio'],
                'subject' => __('admin.email_subject_3'), 'body' => __('admin.email_body_3'),
                'time' => now()->subDay()],
            ['id' => 'm5', 'from' => ['name' => $me, 'email' => 'hussein@nabu.studio'], 'to' => __('admin.email_person_2'),
                'subject' => __('admin.email_subject_2'), 'body' => __('admin.email_snippet_2'),
                'time' => now()->subDays(2), 'folder' => 'sent'],
            ['id' => 'm6', 'from' => ['name' => $me, 'email' => 'hussein@nabu.studio'],
                'subject' => __('admin.email_subject_1'), 'body' => __('admin.email_snippet_1'),
                'time' => now()->subDay(), 'folder' => 'drafts'],
            ['id' => 'm7', 'from' => ['name' => __('admin.email_person_1'), 'email' => 'maryam@nabu.studio'],
                'subject' => __('admin.email_subject_1'), 'body' => __('admin.email_body_1'),
                'time' => now()->subDays(6), 'folder' => 'archive'],
        ];

        $unread = count(array_filter($messages, fn (array $message) => ($message['folder'] ?? 'inbox') === 'inbox' && ! empty($message['unread'])));

        return view('livewire.admin.email', [
            'messages' => $messages,
            'unreadText' => str_replace(':count', NabuXUI::formatNumber($unread, 0, $locale), ':count '.__('admin.email_unread')),
            'locale' => $locale,
        ]);
    }
}
