<?php

namespace Tests\Feature;

use App\Models\Structure;
use App\Models\User;
use Database\Seeders\Core\StructuresSeeder;
use Database\Seeders\Permissions\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Livewire\User\UserIndex;
use Livewire\Livewire;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_it_can_access_user_management(): void
    {
        $this->seed([
            StructuresSeeder::class,
            RolesAndPermissionsSeeder::class,
        ]);

        $admin = User::factory()->create([
            'structure_id' => Structure::query()->value('id'),
        ]);
        $admin->assignRole('Admin IT');

        Livewire::actingAs($admin)
            ->test(UserIndex::class)
            ->assertSee('Utilisateurs');
    }
}
