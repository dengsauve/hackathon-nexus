<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortalDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_sees_dashboard_data(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create([
            'name' => 'Civic Tech Sprint',
            'summary' => 'Build with public data.',
        ]);
        $managedTeam = Team::factory()->create([
            'owner_id' => $user->id,
            'name' => 'Nexus Builders',
        ]);
        $joinedTeam = Team::factory()->create([
            'name' => 'Prototype Guild',
        ]);

        $user->events()->attach($event->id, ['status' => 'joined']);
        $managedTeam->members()->attach($user->id, ['role' => 'owner']);
        $joinedTeam->members()->attach($user->id, ['role' => 'member']);

        $this->actingAs($user)
            ->get('/portal')
            ->assertOk()
            ->assertSee('Upcoming joined events')
            ->assertSee('Civic Tech Sprint')
            ->assertSee('Nexus Builders')
            ->assertSee(route('teams.show', $managedTeam), false)
            ->assertSee('Manage')
            ->assertSee('Prototype Guild')
            ->assertSee('Quick actions');
    }

    public function test_new_user_sees_dashboard_empty_states(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/portal')
            ->assertOk()
            ->assertSee('No joined events yet')
            ->assertSee('You are not managing any teams yet')
            ->assertSee('You have not joined a team yet');
    }

    public function test_guest_cannot_view_dashboard(): void
    {
        $this->get('/portal')->assertRedirect('/login');
    }
}
