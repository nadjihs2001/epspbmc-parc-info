<?php

namespace Tests\Feature;

use App\Models\Equipment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EquipmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_equipment_can_be_created(): void
    {
        $equipment = Equipment::factory()->create([
            'code_inventaire' => 'EQ-0001',
        ]);

        $this->assertDatabaseHas('equipments', [
            'code_inventaire' => 'EQ-0001',
        ]);
    }
}
