<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExerciseTemplate;

class ExerciseTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $exercises = [
            'Bench Press',
            'Squat',
            'Deadlift',
            'Overhead Press',
            'Barbell Row',
            'Pull-Up',
            'Chin-Up',
            'Dumbbell Press',
            'Lateral Raise',
            'Bicep Curl',
            'Tricep Pushdown',
            'Leg Press',
            'Leg Curl',
            'Calf Raise',
            'Plank',
            'Russian Twist',
            'Hanging Leg Raise',
            'Burpee',
            'Jump Squat',
            'Mountain Climbers',
        ];

        foreach ($exercises as $name) {
            ExerciseTemplate::firstOrCreate(['name' => $name, 'user_id' => null]);
        }
    }
}

