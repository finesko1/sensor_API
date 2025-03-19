<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SensorController;

Route::get('/', [SensorController::class, 'getSensorData'])->name('sensor.get'); // Получение последнего значения датчика
Route::post('/', [SensorController::class, 'sendSensorData'])->name('sensor.send');; // Отправление значения датчиком

Route::get('/history', [SensorController::class, 'getSensorDataHistory'])->name('sensor.history');; // Получение истории значений датчика
