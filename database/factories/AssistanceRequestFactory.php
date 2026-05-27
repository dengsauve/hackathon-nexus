<?php

namespace Database\Factories;

use App\Models\AssistanceRequest;
use App\Models\Event;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AssistanceRequest>
 */
class AssistanceRequestFactory extends Factory
{
    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'team_id' => Team::factory(),
            'requested_by' => User::factory(),
            'subject' => fake()->sentence(5),
            'message' => fake()->paragraph(),
            'status' => 'open',
        ];
    }
}
