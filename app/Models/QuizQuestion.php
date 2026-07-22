<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    protected $fillable = [
        'quiz_id',
        'question',
        'type',
        'points',
        'order',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function choices()
    {
        return $this->hasMany(QuizChoice::class)->orderBy('order');
    }

    public function correctChoiceIds(): array
    {
        return $this->choices()->where('is_correct', true)->pluck('id')->toArray();
    }
}
