<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = [
        'lesson_id',
        'title',
        'instructions',
        'max_points',
        'due_at',
    ];

    protected $casts = [
        'due_at' => 'datetime',
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }
}
