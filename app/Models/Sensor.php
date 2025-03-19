<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * `Модель` для работы с таблицей `sensors`
 */
class Sensor extends Model
{
    // Таблица БД
    protected $table = "sensors";

    // Изменяемые поля таблицы
    protected $fillable = [
        'id', 'name', 'parameter_type', 'updated_at'
    ];

    // Скрытые поля
    protected $hidden = [
        'created_at'
    ];


    // Получение последней записи датчика
    public function getLastSensorData()
    {
        return $this->sensorData()->latest()->first();
    }

    // Получение всех записей датчика
    public function getSensorDataHistory($startDate = null, $endDate = null)
    {
        // Если даты не указаны, возвращаем все записи
        if (is_null($startDate) && is_null($endDate)) {
            return $this->sensorData()
                ->get();
        }
        // В противном случае, возвращаем записи в указанном диапазоне
        return $this->sensorData()
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->get();
    }


    // Связь с таблицей sensors_data "один ко многим"
    public function sensorData()
    {
        return $this->hasMany(Sensor_data::class, 'sensor_id', 'id');
    }
}
