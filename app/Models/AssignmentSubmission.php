<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignmentSubmission extends Model
{
    protected $fillable = [
        'assignment_id',
        'user_id',
        'file_path',
        'submission_text',
        'submitted_at',
        'grade',
        'feedback',
        'graded_by',
        'graded_at',
        'status',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'graded_at' => 'datetime',
        'grade' => 'decimal:2',
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function grader()
    {
        return $this->belongsTo(User::class, 'graded_by');
    }
}
