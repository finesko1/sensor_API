<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\Sensor_data;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SensorController extends Controller
{
    /**
     * Получение последнего значения датчика
     *
     * @param Request $request
     *
     * @return `json` с полями
     *
     * `message` и `data` в случае успешной обработки запроса
     *
     * `message` и `errors` при наличии ошибок
     */
    public function getSensorData(Request $request)
    {
        // Обработка запроса
        try
        {
            // Валидация входных данных
            $validatedData = $request->validate([
                'sensor' => 'required|integer'
            ]);
            $sensorId = $validatedData['sensor'];
            // Поиск записи в таблице БД
            $row = Sensor::findOrFail($sensorId);

            // Получение параметра, измеряемого датчиком
            $sensorParam = $row->parameter_type;
            // Получение последнего значения, измеренного датчиком
            $lastValue = $row->getLastSensorData()->value;

            // Строка с итоговым значением
            $sensorValue = $sensorParam . " = " . $lastValue;
        }
        // Обработка ошибок в процессе выполнения запроса
        catch (ValidationException $e) // Ошибки валидации
        {
            return response()->json([
                'message' => 'Ошибка валидации данных',
                'errors' => $e->getMessage()
            ]);
        }
        catch (ModelNotFoundException $e) // Отсутствие записи в БД
        {
            return response()->json([
                'message' => "Датчика с id=$sensorId не существует",
                'errors' => $e->getMessage()
            ]);
        }
        catch (\Exception $e) // Иные, общие ошибки
        {
            return response()->json([
                'message' => 'Ошибка получения данных с датчика',
                'errors' => $e->getMessage()
            ]);
        }
        return response()->json([
            'message' => "Данные с датчика $sensorId успешно получены ",
            'data' => $sensorValue
        ]);
    }

    /**
     * Сохранение данных с датчика
     *
     * @param Request $request
     *
     * @return `json` с полями
     *
     * `message` в случае успешной обработки запроса
     *
     * `message` и `errors` при наличии ошибок
     */
    public function sendSensorData(Request $request)
    {
        // Обработка запроса
        try
        {
            // Валидация входных данных
            $validatedData = $request->validate([
                'sensor' => 'required|integer',
                'parameter_type' => 'required|in:T,P,v', // ограничение ENUM T, P, v - температура, давление и скорость
                'value' => 'required|numeric'
            ]);
            $sensorId = $validatedData['sensor'];
            $sensorValidatedParam = $validatedData['parameter_type'];
            $sensorValue = $validatedData['value'];
            // Поиск записи в таблице БД
            $row = Sensor::findOrFail($sensorId);

            // Получение параметра, измеряемого датчиком
            $sensorParam = $row->parameter_type;
            // Сравнение передаваемого параметра с ожидаемым
            if ($sensorValidatedParam !== $sensorParam)
            {
                return response()->json([
                    'message' => "Ошибка передаваемого параметра на датчик $sensorId",
                    'errors' => "inputParam=$sensorValidatedParam  !==  tableParam=$sensorParam"
                ]);
            }

            // Создание новой записи в таблице БД
            Sensor_data::create([
                'sensor_id' => $sensorId,
                'value' => $sensorValue,
            ]);
        }
            // Обработка ошибок в процессе выполнения запроса
        catch (ValidationException $e) // Ошибки валидации
        {
            return response()->json([
                'message' => 'Ошибка валидации данных',
                'errors' => $e->getMessage()
            ]);
        }
        catch (ModelNotFoundException $e) // Отсутствие записи в БД
        {
            return response()->json([
                'message' => "Датчика с id=$sensorId не существует",
                'errors' => $e->getMessage()
            ]);
        }
        catch (\Exception $e) // Иные, общие ошибки
        {
            return response()->json([
                'message' => 'Ошибка получения данных с датчика',
                'errors' => $e->getMessage()
            ]);
        }
        return response()->json([
            'message' => "Данные с датчика $sensorId успешно записаны",
        ]);
    }


    /**
     * Получение истории данных с датчика с возможностью выбора даты
     *
     * @param Request $request
     *
     * @return `json` с полями
     *
     * `message`, `parameter_type` и `data` в случае успешной обработки запроса
     *
     * `message` и `errors` при наличии ошибок
     */
    public function getSensorDataHistory(Request $request)
    {
        // Обработка запроса
        try
        {
            // Валидация входных данных
            $validatedData = $request->validate([
                'sensor' => 'required|integer',
                'start' => 'date',
                'end' => 'date',
            ]);
            $sensorId = $validatedData['sensor'];
            // Поиск записи в таблице БД
            $row = Sensor::findOrFail($sensorId);

            // Получение параметра, измеряемого датчиком
            $sensorParam = $row->parameter_type;
            // Получение всех значений датчика за определенный период
            $rowOfvalues = $row->getSensorDataHistory($validatedData['start'] = null, $validatedData['end'] = null);
            foreach ($rowOfvalues as $value)
            {
                $values[] = $value->value;
            }
        }
        // Обработка ошибок в процессе выполнения запроса
        catch (ValidationException $e) // Ошибки валидации
        {
            return response()->json([
                'message' => 'Ошибка валидации данных',
                'errors' => $e->getMessage()
            ]);
        }
        catch (ModelNotFoundException $e) // Отсутствие записи в БД
        {
            return response()->json([
                'message' => "Датчика с id=$sensorId не существует",
                'errors' => $e->getMessage()
            ]);
        }
        catch (\Exception $e) // Иные, общие ошибки
        {
            return response()->json([
                'message' => 'Ошибка получения данных с датчика',
                'errors' => $e->getMessage()
            ]);
        }
        return response()->json([
            'message' => "Данные с датчика $sensorId успешно получены",
            'parameter_type' => $sensorParam,
            'values' => $values
        ]);
    }
}
