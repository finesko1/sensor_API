<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Выполняет миграцию.
     *
     * Создание таблицы `показания датчиков` с полями:
     *
     * - `id`: уникальный номер записи
     * - `sensor_id`: уникальный номер датчика
     * - `value`: показатели датчика
     * - `created_at`: создание записи
     * - `updated_at`: обновление записи
     */
    public function up(): void
    {
        Schema::create('sensors_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sensor_id')->constrained('sensors');
            $table->float('value');
            $table->timestamps();
        });
    }

    /**
     * Отменяет миграцию.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensors_data');
    }
};
