<?php

namespace Database\Seeders;

use App\Models\Sensor_data;
use Illuminate\Database\Seeder;
use App\Models\Sensor;

class SensorSeeder extends Seeder
{
    public function run()
    {
        $thermometer = Sensor::create(['name' => 'термометр', 'parameter_type' => 'T']);
        $barometer = Sensor::create(['name' => 'барометр', 'parameter_type' => 'P']);
        $speedometer = Sensor::create(['name' => 'спидометр', 'parameter_type' => 'v']);

        for ($i = 0; $i < 10; $i++) {
            Sensor_data::create(['sensor_id' => $thermometer->id, 'value' => rand(20, 30)]); // Пример значений для термометра
            Sensor_data::create(['sensor_id' => $barometer->id, 'value' => rand(600, 800)]); // Пример значений для барометра
            Sensor_data::create(['sensor_id' => $speedometer->id, 'value' => rand(0, 120)]); // Пример значений для спидометра
        }
    }
}
