<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->catchPhrase().' Hackathon';
        $startsAt = fake()->dateTimeBetween('+1 week', '+3 months');

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(100, 999),
            'summary' => fake()->sentence(12),
            'description' => fake()->paragraphs(3, true),
            'location' => fake()->city(),
            'format' => fake()->randomElement(['in-person', 'online', 'hybrid']),
            'status' => 'published',
            'visibility' => 'public',
            'starts_at' => $startsAt,
            'ends_at' => (clone $startsAt)->modify('+2 days'),
            'registration_closes_at' => (clone $startsAt)->modify('-2 days'),
            'capacity' => fake()->numberBetween(40, 200),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (): array => [
            'status' => 'draft',
            'visibility' => 'private',
        ]);
    }

    public function unlisted(): static
    {
        return $this->state(fn (): array => [
            'status' => 'published',
            'visibility' => 'unlisted',
        ]);
    }
}
