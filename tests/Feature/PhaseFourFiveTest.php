<?php

namespace Tests\Feature;

use App\Models\AssistanceRequest;
use App\Models\Event;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\User;
use App\Notifications\TeamInvitationNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PhaseFourFiveTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_manage_invite_and_register_team_for_event(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $event = Event::factory()->create([
            'registration_closes_at' => now()->addDay(),
            'capacity' => 2,
        ]);

        $this->actingAs($user)
            ->post('/teams', [
                'name' => 'Nexus Builders',
                'description' => 'A focused hackathon team.',
            ])
            ->assertRedirect();

        $team = Team::query()->where('name', 'Nexus Builders')->firstOrFail();

        $this->assertTrue($team->isManagedBy($user));

        $this->post(route('teams.invitations.store', $team), [
            'email' => 'invitee@example.com',
            'role' => 'member',
            'github_handle' => 'invitee',
        ])->assertRedirect(route('teams.show', $team));

        $invitation = TeamInvitation::query()->where('email', 'invitee@example.com')->firstOrFail();
        Notification::assertSentTo($invitation, TeamInvitationNotification::class);

        $invitee = User::factory()->create(['email' => 'invitee@example.com']);

        $this->post(route('team-invitations.accept', $invitation->token))->assertRedirect(route('teams.show', $team));

        $this->assertDatabaseHas('team_user', [
            'team_id' => $team->id,
            'user_id' => $invitee->id,
            'role' => 'member',
        ]);

        $this->actingAs($user)
            ->post(route('events.teams.store', $event), ['team_id' => $team->id])
            ->assertRedirect(route('events.show', $event));

        $this->assertDatabaseHas('event_team', [
            'event_id' => $event->id,
            'team_id' => $team->id,
        ]);
    }

    public function test_organizer_can_create_event_manage_lifecycle_and_assistance_requests(): void
    {
        $organizer = $this->userWithPermission('events.create', 'events.manage');

        $this->actingAs($organizer)
            ->post(route('manage.events.store'), [
                'name' => 'Organizer Sprint',
                'summary' => 'A managed event.',
                'description' => 'Full event details.',
                'location' => 'Online',
                'format' => 'online',
                'status' => 'draft',
                'visibility' => 'private',
                'starts_at' => now()->addWeek()->format('Y-m-d H:i:s'),
                'ends_at' => now()->addWeek()->addDay()->format('Y-m-d H:i:s'),
                'registration_closes_at' => now()->addDays(6)->format('Y-m-d H:i:s'),
                'capacity' => 10,
            ])
            ->assertRedirect();

        $event = Event::query()->where('name', 'Organizer Sprint')->firstOrFail();

        $this->assertSame($organizer->id, $event->owner_id);
        $this->assertNotNull($event->qr_code_path);

        $this->post(route('manage.events.publish', $event))->assertSessionHas('status');
        $this->post(route('manage.events.start', $event))->assertSessionHas('status');
        $this->post(route('manage.events.end', $event))->assertSessionHas('status');

        $requester = User::factory()->create();
        $request = AssistanceRequest::factory()->create([
            'event_id' => $event->id,
            'requested_by' => $requester->id,
            'status' => AssistanceRequest::OPEN,
        ]);

        $this->patch(route('assistance-requests.update', $request), [
            'status' => AssistanceRequest::RESOLVED,
        ])->assertSessionHas('status');

        $this->assertSame(AssistanceRequest::RESOLVED, $request->fresh()->status);
        $this->assertNotNull($request->fresh()->responded_at);
    }

    private function userWithPermission(string ...$permissions): User
    {
        $role = Role::factory()->create();
        foreach ($permissions as $permission) {
            $role->permissions()->attach(Permission::factory()->create(['name' => $permission]));
        }

        return User::factory()->create(['role_id' => $role->id]);
    }
}
