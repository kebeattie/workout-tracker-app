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

    public function store(Request $request,)
    {
        $validatedData = $request->validate([
            'workout_date' => 'required|date',
            'type' => 'required|string',
            'notes' => 'nullable|string',
            'exercises' => 'required|array',
            'exercises.*.name' => 'required|string',
            'exercises.*.sets' => 'required|integer|min:1',
            'exercises.*.reps' => 'required|integer|min:1',
            'exercises.*.weight' => 'required|numeric|min:0',
        ]);

        $templateNames = ExerciseTemplate::whereNull('user_id')
            ->orWhere('user_id', auth()->id())
            ->pluck('name')
            ->toArray();

        $workout = auth()->user()->workouts()->create([
            'workout_date' => $validatedData['workout_date'],
            'type' => $validatedData['type'],
            'notes' => $validatedData['notes'] ?? null,
        ]);

        foreach ($validatedData['exercises'] as $exerciseData) {
            $workout->exercises()->create($exerciseData);

            if(!in_array($exerciseData['name'], $templateNames)) {
                ExerciseTemplate::create([
                    'name' => $exerciseData['name'],
                    'user_id' => auth()->id(),
                ]);
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
