<?php

namespace Database\Factories;

use App\Models\SensorData;
use App\Models\Sensor;
use Illuminate\Database\Eloquent\Factories\Factory;

class SensorDataFactory extends Factory
{
    protected $model = SensorData::class;

    public function definition()
    {
        return [
            'sensor_id' => Sensor::factory(),
            'value' => $this->faker->randomFloat(2, 0, 100),
        ];
    }
}
