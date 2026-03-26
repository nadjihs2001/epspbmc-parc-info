<?php

namespace Database\Factories;

use App\Models\Equipment;
use App\Models\EquipmentType;
use App\Models\Structure;
use Illuminate\Database\Eloquent\Factories\Factory;

class EquipmentFactory extends Factory
{
    protected $model = Equipment::class;

    public function definition(): array
    {
        return [
            'code_inventaire' => $this->faker->unique()->bothify('EQ-####'),
            'type_id' => EquipmentType::factory(),
            'structure_id' => Structure::factory(),
            'statut' => 'actif',
        ];
    }
}
