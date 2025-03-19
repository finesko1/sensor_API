<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Выполняет миграцию.
     *
     * Создание таблицы `датчики` с полями:
     *
     * - `id`: уникальный номер датчика
     * - `name`: название датчика
     * - `parameter_type`: тип измеряемого значения
     * - `created_at`: создание записи
     * - `updated_at`: обновление записи
     */
    public function up(): void
    {
        Schema::create('sensors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('parameter_type', ['T', 'P', 'v']);
            $table->timestamps();
        });
    }

    /**
     * Отменяет миграцию.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensors');
    }
};
