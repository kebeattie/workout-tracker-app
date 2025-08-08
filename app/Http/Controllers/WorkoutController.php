<?php

namespace App\Http\Controllers;
use App\Models\ExerciseTemplate;
use Illuminate\Http\Request;

class WorkoutController extends Controller
{
    public function create()
    {
        $exerciseTemplates = ExerciseTemplate::whereNull('user_id')
            ->orWhere('user_id', auth()->id())
            ->orderBy('name')
            ->get();

        $templateNames = $exerciseTemplates->pluck('name')->toArray();

        return view('workouts.create', compact('templateNames', 'exerciseTemplates'));
    }

   public function store(Request $request)
    {
        // Common validation rules
        $rules = [
            'workout_date' => 'required|date',
            'type' => 'required|string',
            'notes' => 'nullable|string',
        ];

        // Add rules based on workout type
        if ($request->type === 'Running') {
            $rules['distance'] = 'required|numeric|min:0.01';
            $rules['duration'] = 'required|integer|min:1';
            $rules['pace'] = 'nullable|string';
        } else {
            $rules['exercises'] = 'required|array';
            $rules['exercises.*.name'] = 'required|string';
            $rules['exercises.*.sets'] = 'required|integer|min:1';
            $rules['exercises.*.reps'] = 'required|integer|min:1';
            $rules['exercises.*.weight'] = 'required|numeric|min:0';
        }

        $validatedData = $request->validate($rules);

        $templateNames = ExerciseTemplate::whereNull('user_id')
            ->orWhere('user_id', auth()->id())
            ->pluck('name')
            ->toArray();

        if ($request->type === 'Running') {
            $distance = $validatedData['distance'];
            $duration = $validatedData['duration'];
            // Avoid division by zero
            $pace = ($distance > 0) ? round($duration / $distance, 2) : null;
        } else {
            $pace = null;
}

        // Create workout with running fields if present
        $workout = auth()->user()->workouts()->create([
            'workout_date' => $validatedData['workout_date'],
            'type' => $validatedData['type'],
            'notes' => $validatedData['notes'] ?? null,
            'distance' => $validatedData['distance'] ?? null,
            'duration' => $validatedData['duration'] ?? null,
            'pace' => $pace ?? null,
        ]);

        // Only add exercises if not running
        if ($request->type !== 'Running' && isset($validatedData['exercises'])) {
            foreach ($validatedData['exercises'] as $exerciseData) {
                $workout->exercises()->create($exerciseData);

                if (!in_array($exerciseData['name'], $templateNames)) {
                    ExerciseTemplate::create([
                        'name' => $exerciseData['name'],
                        'user_id' => auth()->id(),
                    ]);
                }
            }
        }

        return redirect()->route('workouts.create')->with('success', 'Workout created successfully!');
    }

    public function index()
    {
        $workouts = auth()->user()->workouts()->with('exercises')->orderBy('workout_date', 'desc')->get();
        return view('workouts.index', compact('workouts'));

    }

    public function destroy($id)
    {
        $workout = auth()->user()->workouts()->findOrFail($id);
        $workout->delete();

        return redirect()->route('workouts.index')->with('success', 'Workout deleted successfully!');
    }
}
