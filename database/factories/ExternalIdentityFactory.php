<?php

namespace Database\Factories;

use App\Models\ExternalIdentity;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ExternalIdentity>
 */
class ExternalIdentityFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'provider' => fake()->randomElement(['google', 'github']),
            'provider_user_id' => fake()->uuid(),
            'nickname' => fake()->userName(),
            'avatar_url' => fake()->imageUrl(),
        ];
    }
}
