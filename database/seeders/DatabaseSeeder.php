<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $participant = Role::query()->create([
            'name' => 'participant',
            'label' => 'Participant',
        ]);
        $organizer = Role::query()->create([
            'name' => 'organizer',
            'label' => 'Organizer',
        ]);
        $admin = Role::query()->create([
            'name' => 'admin',
            'label' => 'Administrator',
        ]);

        $permissions = collect([
            ['name' => 'events.create', 'label' => 'Create events'],
            ['name' => 'events.manage', 'label' => 'Manage events'],
            ['name' => 'teams.create', 'label' => 'Create teams'],
            ['name' => 'teams.manage', 'label' => 'Manage teams'],
            ['name' => 'admin.access', 'label' => 'Access admin dashboard'],
        ])->map(fn (array $attributes) => Permission::query()->create($attributes));

        $participant->permissions()->attach(
            $permissions->whereIn('name', ['teams.create'])->pluck('id'),
        );
        $organizer->permissions()->attach(
            $permissions->whereIn('name', ['events.create', 'events.manage', 'teams.create', 'teams.manage'])->pluck('id'),
        );
        $admin->permissions()->attach($permissions->pluck('id'));

        $civicSprint = Event::factory()->create([
            'name' => 'Civic Tech Sprint',
            'slug' => 'civic-tech-sprint',
            'summary' => 'A weekend build focused on practical city services, open data, and resident access.',
            'description' => 'Teams will prototype tools that make public services easier to discover, understand, and improve. Mentors from product, design, and engineering will be available throughout the event.',
            'location' => 'San Francisco, CA',
            'format' => 'in-person',
            'starts_at' => now()->addWeeks(3)->setTime(9, 0),
            'ends_at' => now()->addWeeks(3)->addDays(2)->setTime(17, 0),
            'registration_closes_at' => now()->addWeeks(2)->setTime(23, 59),
            'capacity' => 120,
        ]);

        $aiWeekend = Event::factory()->create([
            'name' => 'AI Builder Weekend',
            'slug' => 'ai-builder-weekend',
            'summary' => 'Ship useful AI workflows for teams, classrooms, and community organizations.',
            'description' => 'This online event helps builders move from idea to working prototype with focused checkpoints, demo reviews, and lightweight support from mentors.',
            'location' => 'Online',
            'format' => 'online',
            'starts_at' => now()->addWeeks(5)->setTime(10, 0),
            'ends_at' => now()->addWeeks(5)->addDays(1)->setTime(16, 0),
            'registration_closes_at' => now()->addWeeks(4)->setTime(23, 59),
            'capacity' => 180,
        ]);

        $climateChallenge = Event::factory()->create([
            'name' => 'Climate Data Challenge',
            'slug' => 'climate-data-challenge',
            'summary' => 'A hybrid hackathon for visualizing climate risk and resilience opportunities.',
            'description' => 'Participants will work with curated datasets and expert mentors to turn climate information into clear decision support for local groups.',
            'location' => 'Hybrid',
            'format' => 'hybrid',
            'starts_at' => now()->addWeeks(7)->setTime(8, 30),
            'ends_at' => now()->addWeeks(7)->addDays(2)->setTime(15, 30),
            'registration_closes_at' => now()->addWeeks(6)->setTime(23, 59),
            'capacity' => 150,
        ]);

        $user = User::factory()->create([
            'role_id' => $admin->id,
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $managedTeam = Team::factory()->create([
            'owner_id' => $user->id,
            'name' => 'Nexus Builders',
            'slug' => 'nexus-builders',
            'description' => 'A seeded team for testing the authenticated portal dashboard.',
        ]);
        $memberTeam = Team::factory()->create([
            'name' => 'Prototype Guild',
            'slug' => 'prototype-guild',
            'description' => 'A collaborator team that appears in the joined team list.',
        ]);

        $managedTeam->members()->attach($user->id, ['role' => 'owner']);
        $memberTeam->members()->attach($user->id, ['role' => 'member']);

        $user->events()->attach($civicSprint->id, ['status' => 'joined']);
        $user->events()->attach($aiWeekend->id, ['status' => 'joined']);

        unset($climateChallenge);
    }
}
