<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Dashboard</h2>
    </x-slot>

    <div class="max-w-4xl mx-auto mt-6 space-y-6">
        {{-- Total Workouts --}}
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-lg font-semibold">Total Workouts</h3>
            <p class="text-2xl mt-2">{{ $totalWorkouts }}</p>
        </div>

        {{-- Most Recent Workout --}}
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-lg font-semibold">Latest Workout</h3>
            @if ($latestWorkout)
                <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($latestWorkout->workout_date)->toFormattedDateString() }} — {{ $latestWorkout->type }}</p>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($latestWorkout->exercises as $ex)
                        <li>{{ $ex->name }}: {{ $ex->sets }} × {{ $ex->reps }} @ {{ $ex->weight }}kg</li>
                    @endforeach
                </ul>
                @if ($latestWorkout->notes)
                    <p class="mt-2 text-sm text-gray-700"><strong>Notes:</strong> {{ $latestWorkout->notes }}</p>
                @endif
            @else
                <p class="text-gray-500">No workouts yet.</p>
            @endif
        </div>

        {{-- Top Exercises --}}
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-lg font-semibold">Top 3 Exercises</h3>
            @if ($topExercises->count())
                <ul class="mt-2 list-decimal list-inside">
                    @foreach ($topExercises as $exercise)
                        <li>{{ $exercise->name }} ({{ $exercise->count }} times)</li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500">No data yet.</p>
            @endif
        </div>
    </div>
</x-app-layout>
