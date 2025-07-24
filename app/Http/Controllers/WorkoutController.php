<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WorkoutController extends Controller
{
    public function create()
    {
        return view('workouts.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'workout_date' => 'required|date',
            'type' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        auth()->user()->workouts()->create($validatedData);

        return redirect()->route('workouts.create')->with('success', 'Workout created successfully!');
    }
}
