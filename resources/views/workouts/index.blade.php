<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Workout History</h2>
    </x-slot>

    <div class="max-w-4xl mx-auto mt-6 space-y-6">
        @forelse ($workouts as $workout)
            <div class="bg-white rounded shadow p-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold">{{ $workout->type }}</h3>
                        <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($workout->workout_date)->toFormattedDateString() }}</p>
                    </div>
                    <div class="flex gap-2 items-center">
                        <button type="button" class="toggle-details text-blue-600 hover:underline text-sm">
                            Show Exercises
                        </button>
                        <form action="{{ route('workouts.destroy', $workout) }}" method="POST" onsubmit="return confirm('Delete this workout?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline text-sm">Delete</button>
                        </form>
                    </div>
                </div>

                <div class="exercise-details mt-4 hidden">
                    @if ($workout->exercises->count())
                        <table class="w-full text-sm text-left mt-2">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="p-2">Exercise</th>
                                    <th class="p-2">Sets</th>
                                    <th class="p-2">Reps</th>
                                    <th class="p-2">Weight (kg)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($workout->exercises as $exercise)
                                    <tr class="border-t">
                                        <td class="p-2">{{ $exercise->name }}</td>
                                        <td class="p-2">{{ $exercise->sets }}</td>
                                        <td class="p-2">{{ $exercise->reps }}</td>
                                        <td class="p-2">{{ $exercise->weight }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-gray-500 mt-2">No exercises recorded.</p>
                    @endif

                    @if ($workout->notes)
                        <div class="mt-4">
                            <strong>Notes:</strong>
                            <p>{{ $workout->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-center text-gray-500">No workouts logged yet.</p>
        @endforelse
    </div>

    <script>
        document.querySelectorAll('.toggle-details').forEach(button => {
            button.addEventListener('click', () => {
                const card = button.closest('.bg-white.rounded.shadow.p-4');
                const details = card.querySelector('.exercise-details');
                details.classList.toggle('hidden');
                button.textContent = details.classList.contains('hidden') ? 'Show Exercises' : 'Hide Exercises';
            });
        });
    </script>
</x-app-layout>
