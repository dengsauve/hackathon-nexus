<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhaseFourteenTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_without_event_permission_can_create_event(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('manage.events.create'))
            ->assertOk()
            ->assertSee('Create event');

        $this->post(route('manage.events.store'), [
            'name' => 'Neighborhood Build Night',
            'summary' => 'A local evening hackathon.',
            'description' => 'Builders work together on practical neighborhood tools.',
            'location' => 'Oakland, CA',
            'format' => 'in-person',
            'status' => 'draft',
            'visibility' => 'private',
            'starts_at' => now()->addWeek()->format('Y-m-d H:i:s'),
            'ends_at' => now()->addWeek()->addHours(8)->format('Y-m-d H:i:s'),
            'registration_closes_at' => now()->addDays(6)->format('Y-m-d H:i:s'),
            'capacity' => 40,
        ])->assertRedirect();

        $event = Event::query()->where('name', 'Neighborhood Build Night')->firstOrFail();

        $this->assertSame($user->id, $event->owner_id);
        $this->assertSame('neighborhood-build-night', $event->slug);
        $this->assertNotNull($event->qr_code_path);
    }

    public function test_guest_must_login_before_creating_event(): void
    {
        $this->get(route('manage.events.create'))->assertRedirect(route('login'));
        $this->post(route('manage.events.store'), [])->assertRedirect(route('login'));
    }

    public function test_created_events_appear_in_portal_and_managed_event_list(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create([
            'owner_id' => $user->id,
            'name' => 'Owner Portal Sprint',
            'status' => 'draft',
            'visibility' => 'private',
        ]);

        $this->actingAs($user)
            ->get(route('portal'))
            ->assertOk()
            ->assertSee('Created events')
            ->assertSee('Owner Portal Sprint')
            ->assertSee(route('manage.events.create'), false)
            ->assertSee(route('manage.events.show', $event), false);

        $this->get(route('manage.events.index'))
            ->assertOk()
            ->assertSee('Owner Portal Sprint');
    }

    public function test_non_owner_without_management_permission_cannot_manage_created_event(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $event = Event::factory()->create(['owner_id' => $owner->id]);

        $this->actingAs($otherUser)
            ->get(route('manage.events.show', $event))
            ->assertForbidden();
    }
}
