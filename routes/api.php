use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/progress-data', function (\Illuminate\Http\Request $request) {
    $exerciseName = $request->query('exercise');
    $userId = auth()->id();

    $data = DB::table('exercises')
        ->join('workouts', 'exercises.workout_id', '=', 'workouts.id')
        ->where('workouts.user_id', $userId)
        ->where('exercises.name', $exerciseName)
        ->orderBy('workouts.workout_date')
        ->select('workouts.workout_date', 'exercises.weight')
        ->get();

    $dates = $data->pluck('workout_date')->map(fn($d) => \Carbon\Carbon::parse($d)->toDateString());
    $weights = $data->pluck('weight');

    return response()->json([
        'dates' => $dates,
        'weights' => $weights,
    ]);
})->middleware('auth:sanctum');
