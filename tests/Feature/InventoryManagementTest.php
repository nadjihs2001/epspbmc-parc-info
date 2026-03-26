<?php

namespace Tests\Feature;

use App\Livewire\Inventory\InventoryIndex;
use App\Livewire\Maintenance\MaintenancePlanIndex;
use App\Models\Structure;
use App\Models\User;
use Database\Seeders\Core\StructuresSeeder;
use Database\Seeders\Permissions\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class InventoryManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_it_can_access_inventory_and_maintenance_modules(): void
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
            ->test(InventoryIndex::class)
            ->assertSee('Inventaire');

        Livewire::actingAs($admin)
            ->test(MaintenancePlanIndex::class)
            ->assertSee('Maintenance preventive');
    }
}
