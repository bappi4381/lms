<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = [
        'lesson_id',
        'title',
        'instructions',
        'pass_percentage',
        'time_limit_minutes',
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function questions()
    {
        return $this->hasMany(QuizQuestion::class)->orderBy('order');
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function totalPoints(): int
    {
        return $this->questions->sum('points');
    }
}
