<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Workout;
use Illuminate\Support\Facades\DB;
class DashboardController extends Controller
{
    public function index()
{
    $user = auth()->user();

    $totalWorkouts = $user->workouts()->count();

    $latestWorkout = $user->workouts()
        ->with('exercises')
        ->latest('workout_date')
        ->first();

    $topExercises = DB::table('exercises')
        ->select('name', DB::raw('count(*) as count'))
        ->whereIn('workout_id', function ($query) use ($user) {
            $query->select('id')->from('workouts')->where('user_id', $user->id);
        })
        ->groupBy('name')
        ->orderByDesc('count')
        ->limit(3)
        ->get();

    return view('dashboard.index', compact('totalWorkouts', 'latestWorkout', 'topExercises'));
}
}
