<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Progress Dashboard</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto mt-6">
        <form id="exercise-form" class="mb-4">
            <label for="exercise" class="block mb-2">Select Exercise</label>
            <select id="exercise" name="exercise" class="w-full p-2 border rounded">
                @foreach ($topExercises as $exercise)
                    <option value="{{ $exercise }}">{{ $exercise }}</option>
                @endforeach
            </select>
        </form>

        <canvas id="progressChart" width="600" height="400"></canvas>
        <div class="flex gap-4 mt-4">
            <button id="show-volume" class="px-3 py-1 rounded bg-blue-600 text-white">Total Volume</button>
            <button id="show-e1rm" class="px-3 py-1 rounded bg-gray-300 text-gray-800">Estimated 1RM</button>
            <button id="show-both" class="px-3 py-1 rounded bg-gray-300 text-gray-800">Both</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let chart;
        let chartData = {};

        async function fetchChartData(exerciseName) {
            const res = await fetch(`/api/progress-data?exercise=${encodeURIComponent(exerciseName)}`);
            return await res.json();
        }

        function renderChart(labels, volumes, e1rms, mode = 'volume') {
            if (chart) chart.destroy();
            const ctx = document.getElementById('progressChart').getContext('2d');
            let datasets = [];
            let yDisplay = false;
            let y1Display = false;
            let yTitle = '';
            let y1Title = '';

            if (mode === 'volume') {
                datasets.push({
                    label: 'Total Volume',
                    data: volumes,
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75,192,192,0.1)',
                    tension: 0.3,
                    fill: false,
                    yAxisID: 'y',
                });
                yDisplay = true;
                yTitle = 'Total Volume';
            }
            if (mode === 'e1rm') {
                datasets.push({
                    label: 'Estimated 1RM',
                    data: e1rms,
                    borderColor: 'rgb(255, 99, 132)',
                    backgroundColor: 'rgba(255,99,132,0.1)',
                    tension: 0.3,
                    fill: false,
                    yAxisID: 'y',
                });
                yDisplay = true;
                yTitle = 'Estimated 1RM';
            }
            if (mode === 'both') {
                datasets.push({
                    label: 'Total Volume',
                    data: volumes,
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75,192,192,0.1)',
                    tension: 0.3,
                    fill: false,
                    yAxisID: 'y',
                });
                datasets.push({
                    label: 'Estimated 1RM',
                    data: e1rms,
                    borderColor: 'rgb(255, 99, 132)',
                    backgroundColor: 'rgba(255,99,132,0.1)',
                    tension: 0.3,
                    fill: false,
                    yAxisID: 'y1', // 1RM on right
                });
                yDisplay = true;
                y1Display = true;
                yTitle = 'Total Volume';
                y1Title = 'Estimated 1RM';
            }

            chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    interaction: { mode: 'index', intersect: false },
                    stacked: false,
                    scales: {
                        y: {
                            type: 'linear',
                            display: yDisplay,
                            position: 'left',
                            title: { display: !!yTitle, text: yTitle }
                        },
                        y1: {
                            type: 'linear',
                            display: y1Display,
                            position: 'right',
                            grid: { drawOnChartArea: false },
                            title: { display: !!y1Title, text: y1Title }
                        }
                    }
                }
            });
        }

        async function updateChart(mode = 'volume') {
            const selectedExercise = document.getElementById('exercise').value;
            chartData = await fetchChartData(selectedExercise);
            renderChart(chartData.dates, chartData.volumes, chartData.e1rms, mode);
        }

        document.getElementById('exercise').addEventListener('change', () => updateChart(currentMode));
        let currentMode = 'volume';
        document.getElementById('show-volume').addEventListener('click', function() {
            currentMode = 'volume';
            updateChart('volume');
            this.classList.add('bg-blue-600', 'text-white');
            document.getElementById('show-e1rm').classList.remove('bg-blue-600', 'text-white');
            document.getElementById('show-e1rm').classList.add('bg-gray-300', 'text-gray-800');
            document.getElementById('show-both').classList.remove('bg-blue-600', 'text-white');
            document.getElementById('show-both').classList.add('bg-gray-300', 'text-gray-800');
        });
        document.getElementById('show-e1rm').addEventListener('click', function() {
            currentMode = 'e1rm';
            updateChart('e1rm');
            this.classList.add('bg-blue-600', 'text-white');
            document.getElementById('show-volume').classList.remove('bg-blue-600', 'text-white');
            document.getElementById('show-volume').classList.add('bg-gray-300', 'text-gray-800');
            document.getElementById('show-both').classList.remove('bg-blue-600', 'text-white');
            document.getElementById('show-both').classList.add('bg-gray-300', 'text-gray-800');
        });
        document.getElementById('show-both').addEventListener('click', function() {
            currentMode = 'both';
            updateChart('both');
            this.classList.add('bg-blue-600', 'text-white');
            document.getElementById('show-volume').classList.remove('bg-blue-600', 'text-white');
            document.getElementById('show-volume').classList.add('bg-gray-300', 'text-gray-800');
            document.getElementById('show-e1rm').classList.remove('bg-blue-600', 'text-white');
            document.getElementById('show-e1rm').classList.add('bg-gray-300', 'text-gray-800');
        });

        window.addEventListener('DOMContentLoaded', () => updateChart(currentMode));
    </script>
</x-app-layout>
