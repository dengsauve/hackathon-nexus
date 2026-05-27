<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Permission;
use App\Models\ProjectEntry;
use App\Models\Role;
use App\Models\ScoringRubric;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RemainingPhasesTest extends TestCase
{
    use RefreshDatabase;

    public function test_team_can_create_upload_and_submit_project_entry(): void
    {
        Storage::fake();

        $user = User::factory()->create();
        $team = Team::factory()->create(['owner_id' => $user->id]);
        $team->members()->attach($user->id, ['role' => 'owner']);
        $event = Event::factory()->create(['ends_at' => now()->addDay()]);
        $event->teams()->attach($team->id, ['registered_by' => $user->id]);

        $this->actingAs($user)
            ->post(route('entries.store', [$team, $event]), [
                'title' => 'Helpful Prototype',
                'idea' => 'An idea',
                'description' => 'A useful description',
                'goal_statement' => 'Make something useful',
                'github_repository' => 'https://github.com/example/repo',
                'assets' => [UploadedFile::fake()->create('deck.pdf', 128, 'application/pdf')],
            ])
            ->assertRedirect();

        $entry = ProjectEntry::query()->where('title', 'Helpful Prototype')->firstOrFail();
        $this->assertDatabaseHas('entry_assets', ['project_entry_id' => $entry->id, 'original_name' => 'deck.pdf']);

        $this->post(route('entries.submit', $entry))->assertRedirect('/portal');
        $this->assertSame(ProjectEntry::SUBMITTED, $entry->fresh()->status);
    }

    public function test_judge_can_score_and_admin_can_moderate_with_audit_log(): void
    {
        $admin = $this->userWithPermission('events.manage', 'admin.access');
        $judge = User::factory()->create();
        $event = Event::factory()->create(['owner_id' => $admin->id, 'status' => 'published']);
        $team = Team::factory()->create();
        $entry = ProjectEntry::factory()->create([
            'event_id' => $event->id,
            'team_id' => $team->id,
            'status' => ProjectEntry::SUBMITTED,
            'submitted_at' => now(),
        ]);
        $rubric = ScoringRubric::query()->create([
            'event_id' => $event->id,
            'name' => 'Impact',
            'max_score' => 10,
        ]);
        $event->judgeAssignments()->create(['judge_id' => $judge->id]);

        $this->actingAs($judge)
            ->post(route('judging.score', $entry), [
                'scoring_rubric_id' => $rubric->id,
                'score' => 8,
                'notes' => 'Strong entry',
            ])
            ->assertSessionHas('status');

        $this->assertSame(8, $entry->fresh()->totalScore());

        $this->actingAs($admin)
            ->post(route('manage.events.judging.finalize', $event))
            ->assertSessionHas('status');

        $this->assertNotNull($event->fresh()->judging_finalized_at);

        $this->actingAs($admin)
            ->patch(route('admin.events.moderate', $event), ['visibility' => 'private'])
            ->assertSessionHas('status');

        $this->assertDatabaseHas('audit_logs', ['action' => 'event.moderated']);

        $this->patch(route('admin.users.moderate', $judge), ['status' => 'suspended'])
            ->assertSessionHas('status');

        $this->assertSame('suspended', $judge->fresh()->status);
        $this->assertDatabaseHas('audit_logs', ['action' => 'user.moderated']);
    }

    public function test_user_can_manage_notification_preferences(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->put(route('notifications.update'), [
                'event_reminders' => '1',
                'submission_confirmations' => '1',
            ])
            ->assertSessionHas('status');

        $this->assertTrue($user->fresh()->notification_preferences['event_reminders']);
        $this->assertFalse($user->fresh()->notification_preferences['assistance_updates']);
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
