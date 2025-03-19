<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * `Модель` для работы с таблицей `sensors_data`
 */
class Sensor_data extends Model
{
    // Таблица БД
    protected $table = 'sensors_data';

    // Изменяемые поля
    protected $fillable = [
      'id', 'sensor_id', 'value', 'updated_at'
    ];

    // Скрытые поля
    protected $hidden = [
      'created_at'
    ];

    // Связь с таблицей sensors "один ко многим"
    public function sensor()
    {
        return $this->belongsTo(Sensor::class, 'sensor_id', 'id');
    }
}
