<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">Log Workout</h2>
    </x-slot>

    <div class="max-w-2xl mx-auto mt-6 p-6 bg-white rounded shadow">
        <form action="{{ route('workouts.store')}}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="workout_date" class="block font-medium">Date</label>
                <input type="date" name="workout_date" id="workout_date" class="w-full border p-2 rounded"
                    value="{{ old('workout_date', now()->toDateString()) }}" required>
                @error('workout_date') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="type" class="block font-medium">Workout Type</label>
                <input type="text" name="type" id="type" class="w-full border p-2 rounded"
                    placeholder="Push, Pull, Cardio, etc." value="{{ old('type') }}" required>
                @error('type') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>
            <div id="exercise-section" class="mb-4">
                <h3 class="font-semibold mb-2">Exercises</h3>

                {{-- Select dropdown --}}
                <div class="flex gap-2 items-end">
                    <div class="flex-1">
                        <label class="block font-medium mb-1">Choose Exercise</label>
                        <select id="exercise-picker" class="w-full border p-2 rounded">
                            <option value="">-- Select --</option>
                            @foreach ($exerciseTemplates as $template)
                                <option value="{{ $template->name }}">{{ $template->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="button" id="add-exercise-btn"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        Add Exercise
                    </button>
                </div>

                {{-- Where exercises will be added --}}
                <div id="exercise-list" class="mt-4 space-y-4"></div>
            </div>



            <div class="mb-4">
                <label for="notes" class="block font-medium">Notes</label>
                <textarea name="notes" id="notes" rows="4"
                    class="w-full border p-2 rounded">{{ old('notes') }}</textarea>
                @error('notes') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Save Workout
            </button>
        </form>

    </div>
    <script>
        let exerciseIndex = 0;

        document.getElementById('add-exercise-btn').addEventListener('click', function () {
            const select = document.getElementById('exercise-picker');
            const selectedName = select.value;
            const selectedText = select.options[select.selectedIndex].text;

            if (!selectedName) return;

            // Prevent duplicates
            const existing = Array.from(document.querySelectorAll('input[name^="exercises"]'))
                .some(input => input.value === selectedName);
            if (existing) return alert("You've already added that exercise.");

            const container = document.createElement('div');
            container.classList.add('p-4', 'bg-gray-100', 'rounded', 'exercise-block');

            container.innerHTML = `
        <input type="hidden" name="exercises[${exerciseIndex}][name]" value="${selectedName}">
        <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-2">
            <div class="flex-1">
                <label class="block font-medium">Exercise</label>
                <input type="text" class="w-full border p-2 rounded bg-gray-200" value="${selectedText}" readonly>
            </div>
            <div>
                <label class="block font-medium">Sets</label>
                <input type="number" name="exercises[${exerciseIndex}][sets]" class="w-24 border p-2 rounded" required min="1">
            </div>
            <div>
                <label class="block font-medium">Reps</label>
                <input type="number" name="exercises[${exerciseIndex}][reps]" class="w-24 border p-2 rounded" required min="1">
            </div>
            <div>
                <label class="block font-medium">Weight (kg)</label>
                <input type="number" name="exercises[${exerciseIndex}][weight]" class="w-28 border p-2 rounded" required min="0" step="0.1">
            </div>
            <button type="button" class="remove-exercise text-red-600 text-sm hover:underline mt-6">
                Remove
            </button>
        </div>
    `;

            document.getElementById('exercise-list').appendChild(container);

            // Reset the dropdown
            select.value = '';

            exerciseIndex++;
        });

        // Remove exercise
        document.getElementById('exercise-list').addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-exercise')) {
                e.target.closest('.exercise-block').remove();
            }
        });
    </script>

</x-app-layout>