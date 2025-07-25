<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    protected $fillable = [
        'name',
        'sets',
        'reps',
        'weight',
        'workout_id',
    ];
    
    public function workout() {
        return $this->belongsTo(Workout::class);
    }
}
