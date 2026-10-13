<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\AdminPanel;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * /admin/chat — the conversations column, the open thread and the composer,
 * all over the chat block. The threads are Livewire-local state (no backend):
 * the composer's send-action appends the message here so the server render
 * agrees with the local echo, refreshes the list preview and clears the
 * unread badge. The open thread syncs through the block's x-modelable
 * (wire:model="active"); the sidebar's "5" badge is the threads' unread sum.
 */
#[Layout('layouts.admin')]
class Chat extends Component
{
    use AdminPanel;

    public string $active = 'sara';

    /** @var array<int, array<string, mixed>> */
    public array $threads = [];

    public function mount(): void
    {
        $this->rememberLocale();

        $at = fn (int $minutes) => now()->subMinutes($minutes)->getTimestamp();

        $this->threads = [
            [
                'id' => 'sara',
                'name' => __('admin.chat_person_1'),
                'role' => __('admin.chat_person_1_role'),
                'status' => 'online',
                'unread' => 0,
                'messages' => [
                    ['id' => 's1', 'side' => 'in', 'text' => __('admin.chat_msg_2_in'), 'time' => $at(6)],
                    ['id' => 's2', 'side' => 'out', 'text' => __('admin.chat_msg_2_out'), 'time' => $at(4)],
                ],
            ],
            [
                'id' => 'amir',
                'name' => __('admin.chat_person_2'),
                'role' => __('admin.chat_person_2_role'),
                'status' => 'busy',
                'unread' => 3,
                'messages' => [
                    ['id' => 'a1', 'side' => 'in', 'text' => __('admin.chat_msg_3_in'), 'time' => $at(120)],
                ],
            ],
            [
                'id' => 'support',
                'name' => __('admin.chat_person_3'),
                'role' => __('admin.chat_person_3_role'),
                'status' => 'offline',
                'unread' => 2,
                'messages' => [
                    ['id' => 'u1', 'side' => 'in', 'text' => __('admin.chat_msg_1_in'), 'time' => $at(1200)],
                    ['id' => 'u2', 'side' => 'out', 'text' => __('admin.chat_msg_1_out'), 'time' => $at(1140)],
                ],
            ],
        ];
    }

    /**
     * The composer's send-action: the block already echoed the bubble in the
     * browser; appending the same message on the server makes the morph keep
     * it, pins the thread's preview to it and clears the unread badge.
     */
    public function sendMessage(string $text, string $id): void
    {
        $text = trim($text);

        if ($text === '' || mb_strlen($text) > 2000) {
            return;
        }

        foreach ($this->threads as $i => $thread) {
            if (($thread['id'] ?? null) !== $id) {
                continue;
            }

            $this->threads[$i]['messages'][] = [
                'id' => uniqid('m', false),
                'side' => 'out',
                'text' => $text,
                'time' => now()->getTimestamp(),
            ];
            $this->threads[$i]['unread'] = 0;
            $this->active = $id;

            break;
        }
    }

    public function render()
    {
        // The block wants every thread's preview/time; the latest message says both.
        $conversations = array_map(function (array $thread) {
            $messages = array_values($thread['messages'] ?? []);
            $last = $messages[count($messages) - 1] ?? null;

            return [
                'id' => $thread['id'],
                'name' => $thread['name'],
                'role' => $thread['role'],
                'status' => $thread['status'],
                'unread' => (int) $thread['unread'],
                'preview' => (string) ($last['text'] ?? ''),
                'time' => (int) ($last['time'] ?? now()->getTimestamp()),
                'messages' => $messages,
            ];
        }, $this->threads);

        return view('livewire.admin.chat', [
            'conversations' => $conversations,
            'typing' => ['amir' => true],
        ]);
    }
}
