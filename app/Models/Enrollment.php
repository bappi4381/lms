<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'payment_status',
        'source',
        'amount_paid',
        'transaction_id',
        'enrolled_at',
        'expires_at',
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
        'expires_at' => 'datetime',
        'amount_paid' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function isActive(): bool
    {
        return $this->payment_status === 'paid'
            && (! $this->expires_at || $this->expires_at->isFuture());
    }
}
