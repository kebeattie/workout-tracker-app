<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exercise;
use Illuminate\Support\Facades\DB;

class ProgressController extends Controller
{


public function index()
{
    $user = auth()->user();

    // Get top 5 most used exercises for dropdown
    $topExercises = DB::table('exercises')
        ->select('name', DB::raw('count(*) as count'))
        ->join('workouts', 'exercises.workout_id', '=', 'workouts.id')
        ->where('workouts.user_id', $user->id)
        ->groupBy('name')
        ->orderByDesc('count')
        ->limit(5)
        ->pluck('name');

    return view('progress.index', compact('topExercises'));
}

public function getProgressData(Request $request)
{
    $user = auth()->user();
    $exerciseName = $request->query('exercise');

    $data = \DB::table('exercises')
        ->join('workouts', 'exercises.workout_id', '=', 'workouts.id')
        ->where('workouts.user_id', $user->id)
        ->where('exercises.name', $exerciseName)
        ->orderBy('workouts.workout_date')
        ->select('workouts.workout_date as date', 'exercises.weight')
        ->get();

    $dates = $data->pluck('date')->map(function($d) {
        return \Carbon\Carbon::parse($d)->toFormattedDateString();
    })->toArray();
    $weights = $data->pluck('weight')->toArray();

    return response()->json([
        'dates' => $dates,
        'weights' => $weights,
    ]);
}

}
