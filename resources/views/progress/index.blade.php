<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Progress Dashboard</h2>
    </x-slot>

    <div class="max-w-4xl mx-auto mt-6">
        <div class="flex gap-4 mb-4">
            <button id="show-exercise-mode" class="bg-blue-600 text-white px-4 py-2 rounded">Exercise Chart</button>
            <button id="show-run-mode" class="bg-gray-300 text-gray-800 px-4 py-2 rounded">Run Chart</button>
        </div>

        <div id="exercise-controls" class="mb-4">
            <label for="exercise-select" class="block mb-2 font-semibold">Select Exercise:</label>
            <select id="exercise-select" class="mb-4 p-2 border rounded" style="min-width: 300px; max-width: 100%;">
                @foreach ($topExercises as $exercise)
                    <option value="{{ $exercise }}">{{ $exercise }}</option>
                @endforeach
            </select>
            <button id="show-volume" class="bg-blue-600 text-white px-3 py-1 rounded">Total Volume</button>
            <button id="show-e1rm" class="bg-gray-300 text-gray-800 px-3 py-1 rounded">Estimated 1RM</button>
            <button id="show-both" class="bg-gray-300 text-gray-800 px-3 py-1 rounded">Both</button>
        </div>
        <div id="run-controls" class="mb-4" style="display:none;">
            <button id="show-distance" class="bg-blue-600 text-white px-3 py-1 rounded">Distance</button>
            <button id="show-duration" class="bg-gray-300 text-gray-800 px-3 py-1 rounded">Duration</button>
            <button id="show-run-both" class="bg-gray-300 text-gray-800 px-3 py-1 rounded">Both</button>
        </div>

        <div id="loading-indicator" class="text-center my-4" style="display:none;">
            <span class="inline-block animate-spin mr-2">&#9696;</span> Loading...
        </div>

        <canvas id="mainChart" width="600" height="400"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    let chart;
    let currentMode = 'exercise'; // or 'run'
    let exerciseChartType = 'volume';
    let runChartType = 'distance';
    let selectedExercise = document.getElementById('exercise-select').value;

    // --- Chart rendering functions ---
    async function fetchExerciseChartData(type = 'volume', exercise = null) {
        const query = exercise ? `&exercise=${encodeURIComponent(exercise)}` : '';
        const res = await fetch(`/api/progress-data?type=${type}${query}`);
        return await res.json();
    }
    async function fetchRunChartData() {
        const res = await fetch('/api/run-progress-data');
        return await res.json();
    }

    function renderExerciseChart(labels, volumes, e1rms, mode = 'volume') {
        if (chart) chart.destroy();
        const ctx = document.getElementById('mainChart').getContext('2d');
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
                yAxisID: 'y1',
            });
            yDisplay = true;
            y1Display = true;
            yTitle = 'Total Volume';
            y1Title = 'Estimated 1RM';
        }
        chart = new Chart(ctx, {
            type: 'line',
            data: { labels, datasets },
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

    function renderRunChart(labels, distances, durations, mode = 'distance') {
        if (chart) chart.destroy();
        const ctx = document.getElementById('mainChart').getContext('2d');
        let datasets = [];
        let yDisplay = false;
        let y1Display = false;
        let yTitle = '';
        let y1Title = '';
        if (mode === 'distance') {
            datasets.push({
                label: 'Distance (km)',
                data: distances,
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75,192,192,0.1)',
                tension: 0.3,
                fill: false,
                yAxisID: 'y',
            });
            yDisplay = true;
            yTitle = 'Distance (km)';
        }
        if (mode === 'duration') {
            datasets.push({
                label: 'Duration (min)',
                data: durations,
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255,99,132,0.1)',
                tension: 0.3,
                fill: false,
                yAxisID: 'y',
            });
            yDisplay = true;
            yTitle = 'Duration (min)';
        }
        if (mode === 'both') {
            datasets.push({
                label: 'Distance (km)',
                data: distances,
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75,192,192,0.1)',
                tension: 0.3,
                fill: false,
                yAxisID: 'y',
            });
            datasets.push({
                label: 'Duration (min)',
                data: durations,
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255,99,132,0.1)',
                tension: 0.3,
                fill: false,
                yAxisID: 'y1',
            });
            yDisplay = true;
            y1Display = true;
            yTitle = 'Distance (km)';
            y1Title = 'Duration (min)';
        }
        chart = new Chart(ctx, {
            type: 'line',
            data: { labels, datasets },
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

    function showLoading() {
        document.getElementById('loading-indicator').style.display = '';
    }
    function hideLoading() {
        document.getElementById('loading-indicator').style.display = 'none';
    }

    // --- Chart mode switching ---
    async function showExerciseChart(mode = 'volume') {
        showLoading();
        document.getElementById('exercise-controls').style.display = '';
        document.getElementById('run-controls').style.display = 'none';
        document.getElementById('show-exercise-mode').classList.add('bg-blue-600', 'text-white');
        document.getElementById('show-run-mode').classList.remove('bg-blue-600', 'text-white');
        document.getElementById('show-run-mode').classList.add('bg-gray-300', 'text-gray-800');
        selectedExercise = document.getElementById('exercise-select').value;
        const data = await fetchExerciseChartData(mode, selectedExercise);
        renderExerciseChart(data.dates, data.volumes, data.e1rms, mode);
        hideLoading();
    }
    async function showRunChart(mode = 'distance') {
        showLoading();
        document.getElementById('exercise-controls').style.display = 'none';
        document.getElementById('run-controls').style.display = '';
        document.getElementById('show-run-mode').classList.add('bg-blue-600', 'text-white');
        document.getElementById('show-exercise-mode').classList.remove('bg-blue-600', 'text-white');
        document.getElementById('show-exercise-mode').classList.add('bg-gray-300', 'text-gray-800');
        const data = await fetchRunChartData();
        renderRunChart(data.dates, data.distances, data.durations, mode);
        hideLoading();
    }

    // --- Button event listeners ---
    document.getElementById('show-exercise-mode').addEventListener('click', () => showExerciseChart(exerciseChartType));
    document.getElementById('show-run-mode').addEventListener('click', () => showRunChart(runChartType));

    document.getElementById('show-volume').addEventListener('click', function() {
        exerciseChartType = 'volume';
        showExerciseChart('volume');
        this.classList.add('bg-blue-600', 'text-white');
        document.getElementById('show-e1rm').classList.remove('bg-blue-600', 'text-white');
        document.getElementById('show-e1rm').classList.add('bg-gray-300', 'text-gray-800');
        document.getElementById('show-both').classList.remove('bg-blue-600', 'text-white');
        document.getElementById('show-both').classList.add('bg-gray-300', 'text-gray-800');
    });
    document.getElementById('show-e1rm').addEventListener('click', function() {
        exerciseChartType = 'e1rm';
        showExerciseChart('e1rm');
        this.classList.add('bg-blue-600', 'text-white');
        document.getElementById('show-volume').classList.remove('bg-blue-600', 'text-white');
        document.getElementById('show-volume').classList.add('bg-gray-300', 'text-gray-800');
        document.getElementById('show-both').classList.remove('bg-blue-600', 'text-white');
        document.getElementById('show-both').classList.add('bg-gray-300', 'text-gray-800');
    });
    document.getElementById('show-both').addEventListener('click', function() {
        exerciseChartType = 'both';
        showExerciseChart('both');
        this.classList.add('bg-blue-600', 'text-white');
        document.getElementById('show-volume').classList.remove('bg-blue-600', 'text-white');
        document.getElementById('show-volume').classList.add('bg-gray-300', 'text-gray-800');
        document.getElementById('show-e1rm').classList.remove('bg-blue-600', 'text-white');
        document.getElementById('show-e1rm').classList.add('bg-gray-300', 'text-gray-800');
    });

    document.getElementById('show-distance').addEventListener('click', function() {
        runChartType = 'distance';
        showRunChart('distance');
        this.classList.add('bg-blue-600', 'text-white');
        document.getElementById('show-duration').classList.remove('bg-blue-600', 'text-white');
        document.getElementById('show-duration').classList.add('bg-gray-300', 'text-gray-800');
        document.getElementById('show-run-both').classList.remove('bg-blue-600', 'text-white');
        document.getElementById('show-run-both').classList.add('bg-gray-300', 'text-gray-800');
    });
    document.getElementById('show-duration').addEventListener('click', function() {
        runChartType = 'duration';
        showRunChart('duration');
        this.classList.add('bg-blue-600', 'text-white');
        document.getElementById('show-distance').classList.remove('bg-blue-600', 'text-white');
        document.getElementById('show-distance').classList.add('bg-gray-300', 'text-gray-800');
        document.getElementById('show-run-both').classList.remove('bg-blue-600', 'text-white');
        document.getElementById('show-run-both').classList.add('bg-gray-300', 'text-gray-800');
    });
    document.getElementById('show-run-both').addEventListener('click', function() {
        runChartType = 'both';
        showRunChart('both');
        this.classList.add('bg-blue-600', 'text-white');
        document.getElementById('show-distance').classList.remove('bg-blue-600', 'text-white');
        document.getElementById('show-distance').classList.add('bg-gray-300', 'text-gray-800');
        document.getElementById('show-duration').classList.remove('bg-blue-600', 'text-white');
        document.getElementById('show-duration').classList.add('bg-gray-300', 'text-gray-800');
    });

    document.getElementById('exercise-select').addEventListener('change', function() {
        selectedExercise = this.value;
        showExerciseChart(exerciseChartType);
    });

    // --- Show exercise chart by default ---
    showExerciseChart('volume');
});
    </script>
</x-app-layout>
