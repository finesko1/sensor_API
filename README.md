# API для работы с датчиками

## Необходимые расширения php.ini: *mysqli, pdo_mysql, mbstring*

# Endpoints
get('/api?sensor=1') - Получение последнего значения датчика.
Формат ответа:
`message` и `data` в случае успешной обработки запроса;
`message` и `errors` при наличии ошибок

post('/api') - Отправление значения датчика. В теле запроса: параметр, значение, id датчика
Формат ответа:
`message` в случае успешной обработки запроса;
`message` и `errors` при наличии ошибок

Route::get('/api/history?sensor=1') - Получение истории значений датчика
Формат ответа:
`message`, `parameter_type` и `data - array()` в случае успешной обработки запроса;
`message` и `errors` при наличии ошибок

# Локальное тестирование
## Создайте окружение .env *основываясь на env.example*
## Установите зависимости 
### npm i && composer i
## Сгенерируйте тестовые записи в таблицах БД
### php artisan db:seed
## Запустите сервер vite и artisan
### npm run dev && php artisan serve
## Протестируйте работу с API на простых формах
### Откройте сайт http://localhost:8000

# Пример отображаемых данных
![Тестовая страница](https://github.com/finesko1/sensor_API/tree/master/public/test.png)
