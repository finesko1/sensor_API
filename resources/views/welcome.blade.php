<!DOCTYPE html>
<html lang="eu">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        @vite('resources/css/app.css')
        <title>Sensors</title>
    </head>
    <body>
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-lg font-bold mb-4">Датчик id 1 (T)</h2>
            <button data-id="1" class="bg-blue-500 text-white px-4 py-2 rounded getDataButton hover:scale-95 duration-300 transition-transform">Получить данные</button>
            <div class="mt-4">
                <h2 class="text-lg font-bold">Полученные данные:</h2>
                <p class="result border rounded-2xl p-4 mt-2 text-center" data-id="1"></p>
            </div>
            <button data-id="1" class="bg-blue-500 text-white px-4 py-2 rounded createGraphButton hover:scale-95 duration-300 transition-transform mt-4">Построить график</button>
            <canvas id="myChart_1" width="400" height="200"></canvas>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-lg font-bold mb-4">Датчик id 2 (P)</h2>
            <button data-id="2" class="bg-blue-500 text-white px-4 py-2 rounded getDataButton hover:scale-95 duration-300 transition-transform">Получить данные</button>
            <div class="mt-4">
                <h2 class="text-lg font-bold">Полученные данные:</h2>
                <p class="result border rounded-2xl p-4 mt-2 text-center" data-id="2"></p>
            </div>
            <button data-id="2" class="bg-blue-500 text-white px-4 py-2 rounded createGraphButton hover:scale-95 duration-300 transition-transform mt-4">Построить график</button>
            <canvas id="myChart_2" width="400" height="200"></canvas>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-lg font-bold mb-4">Датчик id 3 (v)</h2>
            <button data-id="3" class="bg-blue-500 text-white px-4 py-2 rounded getDataButton hover:scale-95 duration-300 transition-transform">Получить данные</button>
            <div class="mt-4">
                <h2 class="text-lg font-bold">Полученные данные:</h2>
                <p class="result border rounded-2xl p-4 mt-2 text-center" data-id="3"></p>
            </div>
            <button data-id="3" class="bg-blue-500 text-white px-4 py-2 rounded createGraphButton hover:scale-95 duration-300 transition-transform mt-4">Построить график</button>
            <canvas id="myChart_3" width="400" height="200"></canvas>
        </div>
    </div>
    <div class="flex items-center justify-center bg-gray-100">
        <div class="bg-white p-6 rounded-lg shadow-md w-96">
            <div class="mb-4 text-center text-xl font-bold">Отправление данных</div>
            <form id="dataForm">
                <div class="mb-4">
                    <label for="sensor" class="block text-sm font-medium">ID:</label>
                    <input type="number" id="sensor" name="sensor" required class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring focus:ring-blue-300" />
                </div>
                <div class="mb-4">
                    <label for="parameter_type" class="block text-sm font-medium">Параметр:</label>
                    <input type="text" id="parameter_type" name="parameter_type" required class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring focus:ring-blue-300" />
                </div>
                <div class="mb-4">
                    <label for="value" class="block text-sm font-medium">Значение:</label>
                    <input type="text" id="value" name="value" required class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring focus:ring-blue-300" />
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-200">Отправить</button>
            </form>
        </div>
    </div>
    </body>

    <script>
        document.querySelectorAll('.getDataButton').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const resultElement = document.querySelector(`.result[data-id="${id}"]`);

                fetch(`{{ route('sensor.get') }}?sensor=${id}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Ошибка сервера');
                        }
                        return response.json();
                    })
                    .then(data => {
                        resultElement.textContent = data.data;
                    })
                    .catch(error => {
                        console.error('Ошибка:', error);
                    });
            });
        });

        document.getElementById('dataForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Предотвращаем стандартное поведение формы

            const formData = new FormData(this);

            fetch('/api', {
                method: 'POST',
                body: formData,
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Сеть не в порядке.');
                    }
                    return response.json();
                })
                .catch(error => {
                    console.error('Ошибка:', error);
                });
        });

        document.querySelectorAll('.createGraphButton').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                fetchDataAndRenderChart(id);
            });
        });

        function fetchDataAndRenderChart(id) {
            fetch(`/api/history?sensor=${id}`) // Замените на ваш URL
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Сеть не в порядке.');
                    }
                    return response.json(); // Предполагаем, что ответ в формате JSON
                })
                .then(data => {
                    const values = data.values; // Предполагаем, что массив значений находится в поле 'values'

                    // Создаем массив для оси X
                    const labels = values.map((_, index) => index + 1); // Равномерные значения по оси X

                    const ctx = document.getElementById(`myChart_${id}`).getContext('2d');
                    const myChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: `Значения датчика ${id}`,
                                data: values,
                                borderColor: 'rgba(75, 192, 192, 1)',
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderWidth: 1,
                                fill: true
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                })
                .catch(error => {
                    console.error('Ошибка:', error);
                });
        }
    </script>
</html>
