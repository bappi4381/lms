<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    protected $fillable = [
        'quiz_id',
        'user_id',
        'score',
        'total_points',
        'is_passed',
        'started_at',
        'submitted_at',
    ];

    protected $casts = [
        'is_passed' => 'boolean',
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'score' => 'decimal:2',
        'total_points' => 'decimal:2',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(QuizAttemptAnswer::class);
    }

    public function percentage(): float
    {
        if ((float) $this->total_points <= 0) {
            return 0;
        }

        return round(((float) $this->score / (float) $this->total_points) * 100, 2);
    }
}
