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

            <div class="mb-4">
                <label for="notes" class="block font-medium">Notes</label>
                <textarea name="notes" id="notes" rows="4" class="w-full border p-2 rounded">{{ old('notes') }}</textarea>
                @error('notes') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Save Workout
            </button>
        </form>
    </div>

</x-app-layout>