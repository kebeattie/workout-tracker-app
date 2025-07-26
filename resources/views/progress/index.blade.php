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
    </div>

    <!-- Add Chart.js import here -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let chart;

        async function fetchChartData(exerciseName) {
            const res = await fetch(`/api/progress-data?exercise=${encodeURIComponent(exerciseName)}`);
            return await res.json();
        }

        function renderChart(labels, weights) {
            if (chart) chart.destroy();

            const ctx = document.getElementById('progressChart').getContext('2d');
            chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Weight (kg)',
                        data: weights,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.3,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true },
                    },
                }
            });
        }

        async function updateChart() {
            const selectedExercise = document.getElementById('exercise').value;
            const { dates, weights } = await fetchChartData(selectedExercise);
            renderChart(dates, weights);
        }

        document.getElementById('exercise').addEventListener('change', updateChart);
        window.addEventListener('DOMContentLoaded', updateChart);
    </script>
</x-app-layout>
