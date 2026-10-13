<?php

namespace Database\Seeders;

use App\Models\CalendarEvent;
use App\Models\ChatMessage;
use App\Models\Conversation;
use App\Models\Email;
use App\Models\Order;
use App\Models\Product;
use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NabuxSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed demo data for the Filament admin: 12 products, 18 orders,
     * 24 tasks, 9 emails (2 unread), 2 chat conversations and 7
     * calendar events. Idempotent — always wipes its own tables first.
     */
    public function run(): void
    {
        // Children first: they hold the FKs.
        ChatMessage::query()->delete();
        Conversation::query()->delete();
        Order::query()->delete();
        Task::query()->delete();
        Product::query()->delete();
        Email::query()->delete();
        CalendarEvent::query()->delete();

        $products = Product::factory(12)->create();

        for ($i = 1; $i <= 18; $i++) {
            $product = $products[$i % $products->count()];
            $quantity = 1 + $i % 3;

            Order::factory()->create([
                'number' => 'ORD-'.(1000 + $i),
                'product_id' => $product->id,
                'total' => round($product->price * $quantity, 2),
            ]);
        }

        // Keep three paid orders inside the last three days so the recent-sales
        // charts always have bars to draw.
        Order::where('status', 'paid')->oldest('id')->take(3)->get()->values()->each(function ($order, $i): void {
            $order->update(['ordered_at' => now()->subDays($i)->setTimeFromTimeString(sprintf('%02d:15:00', 9 + $i))]);
        });

        Task::factory(24)->create();

        $this->seedEmails();
        $this->seedConversations();
        $this->seedCalendarEvents();
    }

    /**
     * Nine inbox emails, exactly two unread, spread over the last
     * three days. Deterministic rows so widgets always render well.
     */
    private function seedEmails(): void
    {
        $rows = [
            ['Q4 roadmap review — slides attached', 'Nora Blake', 'nora.blake@acmecorp.com', 'inbox', false, 2],
            ['Your weekly analytics report is ready', 'Jonas Weber', 'jonas@devmail.io', 'inbox', true, 7],
            ['Invoice #2841 has been paid', 'Billing Team', 'billing@ledgerhq.com', 'inbox', true, 20],
            ['New comment on your pull request', 'Priya Nair', 'priya.nair@cloudsuite.com', 'inbox', true, 26],
            ['Deployment finished for staging', 'CI Bot', 'ci@devmail.io', 'inbox', true, 30],
            ['Security alert: new sign-in from Chrome', 'Marcus Lee', 'marcus@ledgerhq.com', 'inbox', false, 44],
            ['Feedback on the onboarding flow', 'Ana Duarte', 'ana.duarte@pixelforge.co', 'archive', true, 49],
            ['Meeting notes from Tuesday sync', 'Sara Ahmadi', 'sara.ahmadi@acmecorp.com', 'archive', true, 55],
            ['Shipping confirmation for order ORD-1004', 'Store Notices', 'notices@shopmail.com', 'archive', true, 71],
        ];

        foreach ($rows as [$subject, $name, $address, $folder, $isRead, $hoursAgo]) {
            Email::create([
                'subject' => $subject,
                'sender_name' => $name,
                'sender_email' => $address,
                'body' => "Hi there,\n\n".$this->emailBody($subject)."\n\nBest regards,\n".$name,
                'folder' => $folder,
                'is_read' => $isRead,
                'received_at' => now()->subHours($hoursAgo),
            ]);
        }
    }

    private function emailBody(string $subject): string
    {
        return match (true) {
            str_contains($subject, 'Invoice') => 'We are happy to confirm that invoice #2841 was settled this morning. The receipt is attached for your records.',
            str_contains($subject, 'Security') => 'We noticed a new sign-in to your account from Chrome on macOS. If this was you, no action is needed.',
            str_contains($subject, 'roadmap') => 'Slides for the Q4 roadmap review are attached. Please skim them before Thursday so we can dive straight into open questions.',
            default => 'Quick heads-up regarding "'.$subject.'". Let us know if you have any questions or want to change how we handle this.',
        };
    }

    /**
     * Two chat conversations: five and four messages, alternating
     * sides like a real thread.
     */
    private function seedConversations(): void
    {
        $conversations = [
            [
                'title' => 'Sprint 14 planning',
                'participant_name' => 'Daniel Wu',
                'avatar_color' => 'blue',
                'messages' => [
                    ['mine' => false, 'body' => 'Morning! Did you get a chance to look at the sprint scope?', 'minutes_ago' => 95],
                    ['mine' => true, 'body' => 'Yes — looks tight but doable. The checkout fix is the only risky one.', 'minutes_ago' => 88],
                    ['mine' => false, 'body' => 'Agreed. I can take the fix off your plate if reviews pile up.', 'minutes_ago' => 80],
                    ['mine' => true, 'body' => 'That would help a lot. Let me finish the failing test first.', 'minutes_ago' => 74],
                    ['mine' => false, 'body' => 'Perfect. Standup at 10:30 then.', 'minutes_ago' => 65],
                ],
            ],
            [
                'title' => 'Marketing site copy',
                'participant_name' => 'Lena Fischer',
                'avatar_color' => 'orange',
                'messages' => [
                    ['mine' => false, 'body' => 'First draft of the landing page copy is in the doc.', 'minutes_ago' => 240],
                    ['mine' => true, 'body' => 'Read it — the hero line is much stronger now.', 'minutes_ago' => 232],
                    ['mine' => false, 'body' => 'Great. I only rewrote the pricing section, rest is minor polish.', 'minutes_ago' => 225],
                    ['mine' => true, 'body' => 'Ship it after one more pass from Tom.', 'minutes_ago' => 210],
                ],
            ],
        ];

        foreach ($conversations as $conversation) {
            $thread = Conversation::create([
                'title' => $conversation['title'],
                'participant_name' => $conversation['participant_name'],
                'avatar_color' => $conversation['avatar_color'],
            ]);

            foreach ($conversation['messages'] as $message) {
                ChatMessage::create([
                    'conversation_id' => $thread->id,
                    'is_mine' => $message['mine'],
                    'body' => $message['body'],
                    'sent_at' => now()->subMinutes($message['minutes_ago']),
                ]);
            }
        }
    }

    /**
     * Seven calendar events spread across the current week
     * (Monday through Sunday), colors cycling through the palette.
     */
    private function seedCalendarEvents(): void
    {
        $weekStart = now()->startOfWeek();

        $events = [
            // [title, day offset within week, time, color]
            ['Sprint planning', 0, '09:30', 'blue'],
            ['Design critique', 1, '14:00', 'indigo'],
            ['Customer onboarding call', 2, '10:30', 'green'],
            ['1:1 with product', 3, '11:00', 'orange'],
            ['Roadmap review', 4, '15:30', 'blue'],
            ['Team retro', 5, '16:00', 'green'],
            ['Release cut & QA pass', 6, '13:00', 'orange'],
        ];

        foreach ($events as [$title, $dayOffset, $time, $color]) {
            CalendarEvent::create([
                'title' => $title,
                'event_date' => $weekStart->copy()->addDays($dayOffset)->toDateString(),
                'event_time' => $time,
                'color' => $color,
            ]);
        }
    }
}
