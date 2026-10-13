<?php

namespace Database\Factories;

use App\Models\ChatMessage;
use App\Models\Conversation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ChatMessage>
 */
class ChatMessageFactory extends Factory
{
    protected static int $cursor = 0;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $index = self::$cursor;
        self::$cursor++;

        return [
            'conversation_id' => Conversation::factory(),
            'is_mine' => $index % 2 === 1,
            'body' => fake()->sentence(),
            'sent_at' => now()->subMinutes(120 - $index * 7),
        ];
    }
}
