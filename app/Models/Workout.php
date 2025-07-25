<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workout extends Model
{

    protected $fillable = [
        'workout_date',
        'type',
        'notes',
        'user_id',
    ];
    public function user()  {
        return $this->belongsTo(User::class);
    }

    public function exercises() {
        return $this->hasMany(Exercise::class);
    }
}
