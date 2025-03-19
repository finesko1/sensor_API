<?php

namespace Database\Factories;

use App\Models\Sensor;
use Illuminate\Database\Eloquent\Factories\Factory;

class SensorFactory extends Factory
{
    protected $model = Sensor::class;

    public function definition()
    {
        return [
            'name' => $this->faker->randomElement(['термометр', 'барометр', 'спидометр']),
            'parameter_type' => $this->faker->randomElement(['T', 'P', 'v']),
        ];
    }
}
