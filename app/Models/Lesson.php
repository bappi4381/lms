<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = [
        'module_id',
        'title',
        'type',
        'video_id',
        'pdf_url',
        'content',
        'duration_seconds',
        'is_preview',
        'order',
    ];

    protected $casts = [
        'is_preview' => 'boolean',
    ];

    public const TYPE_VIDEO = 'video';

    public const TYPE_PDF = 'pdf';

    public const TYPE_QUIZ = 'quiz';

    public const TYPE_ASSIGNMENT = 'assignment';

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function quiz()
    {
        return $this->hasOne(Quiz::class);
    }

    public function assignment()
    {
        return $this->hasOne(Assignment::class);
    }

    public function progress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function progressFor(?int $userId): ?LessonProgress
    {
        if (! $userId) {
            return null;
        }

        return $this->progress()->where('user_id', $userId)->first();
    }
}
