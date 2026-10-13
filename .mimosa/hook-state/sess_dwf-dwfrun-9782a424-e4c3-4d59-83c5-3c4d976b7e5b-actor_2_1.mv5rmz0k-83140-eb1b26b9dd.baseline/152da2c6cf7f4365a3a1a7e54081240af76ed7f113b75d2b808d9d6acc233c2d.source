<?php

namespace Database\Factories;

use App\Models\Email;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Email>
 */
class EmailFactory extends Factory
{
    /**
     * Plausible email subjects so seed data reads like a real inbox
     * (deterministic across re-seeds, cycling with modulo).
     *
     * @var list<string>
     */
    public const SUBJECTS = [
        'Your weekly analytics report is ready',
        'Invoice #2841 has been paid',
        'Meeting notes from Tuesday sync',
        'New comment on your pull request',
        'Security alert: new sign-in from Chrome',
        'Q4 roadmap review — slides attached',
        'Password reset requested',
        'Shipping confirmation for order ORD-1004',
        'Invitation: design critique on Friday',
        'Your trial ends in three days',
        'Contract renewal — action required',
        'Deployment finished for staging',
        'Feedback on the onboarding flow',
        'Team standup moved to 09:30',
    ];

    /**
     * @var list<array{name: string, email: string}>
     */
    protected const SENDERS = [
        ['name' => 'Nora Blake', 'email' => 'nora.blake@acmecorp.com'],
        ['name' => 'Jonas Weber', 'email' => 'jonas@devmail.io'],
        ['name' => 'Priya Nair', 'email' => 'priya.nair@cloudsuite.com'],
        ['name' => 'Marcus Lee', 'email' => 'marcus@ledgerhq.com'],
        ['name' => 'Billing Team', 'email' => 'billing@stripe-mail.com'],
        ['name' => 'Ana Duarte', 'email' => 'ana.duarte@pixelforge.co'],
    ];

    protected static int $cursor = 0;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $index = self::$cursor % count(self::SUBJECTS);
        self::$cursor++;
        $sender = self::SENDERS[$index % count(self::SENDERS)];

        return [
            'subject' => self::SUBJECTS[$index],
            'sender_name' => $sender['name'],
            'sender_email' => $sender['email'],
            'body' => 'Hi there,'."\n\n".fake()->paragraph()."\n\nBest regards,\n".$sender['name'],
            'folder' => $index % 5 === 4 ? 'archive' : 'inbox',
            'is_read' => true,
            'received_at' => now()->subHours($index + 1),
        ];
    }
}
