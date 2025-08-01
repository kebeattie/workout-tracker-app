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
        ->select('workouts.workout_date as date', 'exercises.sets', 'exercises.reps', 'exercises.weight')
        ->get();

    // Group by workout date and sum volume, calculate max e1RM per workout
    $byDate = [];
    foreach ($data as $row) {
        $date = \Carbon\Carbon::parse($row->date)->toFormattedDateString();
        $volume = $row->sets * $row->reps * $row->weight;
        $e1rm = $row->weight * (1 + $row->reps / 30);

        if (!isset($byDate[$date])) {
            $byDate[$date] = ['volume' => 0, 'e1rm' => 0];
        }
        $byDate[$date]['volume'] += $volume;
        // Keep the max e1RM for the workout
        if ($e1rm > $byDate[$date]['e1rm']) {
            $byDate[$date]['e1rm'] = round($e1rm, 2);
        }
    }

    $dates = array_keys($byDate);
    $volumes = array_column($byDate, 'volume');
    $e1rms = array_column($byDate, 'e1rm');

    return response()->json([
        'dates' => $dates,
        'volumes' => $volumes,
        'e1rms' => $e1rms,
    ]);
}

}
