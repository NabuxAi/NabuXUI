<?php

namespace Database\Factories;

use App\Models\Conversation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Conversation>
 */
class ConversationFactory extends Factory
{
    /**
     * @var list<array{title: string, name: string}>
     */
    protected const THREADS = [
        ['title' => 'Sprint 14 planning', 'name' => 'Daniel Wu'],
        ['title' => 'Checkout bug triage', 'name' => 'Sara Ahmadi'],
        ['title' => 'Marketing site copy', 'name' => 'Lena Fischer'],
        ['title' => 'API contract review', 'name' => 'Omar Reyes'],
    ];

    protected static int $cursor = 0;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $index = self::$cursor % count(self::THREADS);
        self::$cursor++;

        return [
            'title' => self::THREADS[$index]['title'],
            'participant_name' => self::THREADS[$index]['name'],
            'avatar_color' => Conversation::COLORS[$index % count(Conversation::COLORS)],
        ];
    }
}
