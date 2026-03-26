<?php

namespace Database\Factories;

use App\Models\Structure;
use Illuminate\Database\Eloquent\Factories\Factory;

class StructureFactory extends Factory
{
    protected $model = Structure::class;

    public function definition(): array
    {
        return [
            'nom' => $this->faker->company(),
            'type' => 'polyclinique',
            'code' => $this->faker->unique()->bothify('ST-##'),
            'adresse' => $this->faker->address(),
            'actif' => true,
        ];
    }
}
