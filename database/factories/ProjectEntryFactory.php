<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\ProjectEntry;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProjectEntry>
 */
class ProjectEntryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'team_id' => Team::factory(),
            'created_by' => User::factory(),
            'title' => fake()->catchPhrase(),
            'idea' => fake()->sentence(12),
            'description' => fake()->paragraph(),
            'goal_statement' => fake()->sentence(14),
            'github_repository' => 'https://github.com/example/project',
            'status' => 'draft',
        ];
    }
}
