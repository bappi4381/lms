<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'order',
        'live_class_provider',
        'live_class_link',
        'live_class_at',
    ];

    protected $casts = [
        'live_class_at' => 'datetime',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    public function hasLiveClass(): bool
    {
        return ! empty($this->live_class_link);
    }
}
