<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FoundationTest extends TestCase
{
    use RefreshDatabase;

    public function test_role_permission_model_supports_authorization_checks(): void
    {
        $role = Role::factory()->create(['name' => 'organizer']);
        $permission = Permission::factory()->create(['name' => 'events.manage']);

        $role->permissions()->attach($permission);

        $user = User::factory()->create(['role_id' => $role->id]);

        $this->assertTrue($user->hasPermission('events.manage'));
        $this->assertFalse($user->hasPermission('events.delete'));
    }
}
