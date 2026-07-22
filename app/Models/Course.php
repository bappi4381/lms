<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'sub_description',
        'description',
        'thumbnail',
        'price',
        'is_published',
        'instructor_id',
        'category_id',
        'batch_number',
        'seats_total',
        'seats_available',
        'starts_at',
        'live_class_schedule',
        'support_class_schedule',
        'discount_price',
        'video_url',
        'key_features',
        'course_includes',
        'projects',
        'tools',
        'career_opportunities',
        'faqs',
    ];

    protected $casts = [
        'is_published'         => 'boolean',
        'price'                => 'decimal:2',
        'discount_price'       => 'decimal:2',
        'starts_at'            => 'datetime',
        'key_features'         => 'array',
        'course_includes'      => 'array',
        'projects'             => 'array',
        'tools'                => 'array',
        'career_opportunities' => 'array',
        'faqs'                 => 'array',
    ];

    public function modules()
    {
        return $this->hasMany(Module::class);
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function averageRating(): float
    {
        return round((float) $this->reviews()->avg('rating'), 1);
    }

    public function reviewsCount(): int
    {
        return $this->reviews()->count();
    }

    /**
     * Calculate remaining seats dynamically from actual enrollment count.
     * This is always accurate — no stale data from seats_available column.
     */
    public function seatsRemaining(): int
    {
        $enrolled = $this->enrollments()
            ->whereIn('payment_status', ['paid', 'pending'])
            ->count();

        return max(0, ($this->seats_total ?? 0) - $enrolled);
    }

    /**
     * Check if seats are available for new enrollment.
     */
    public function hasSeatAvailable(): bool
    {
        if (! $this->seats_total) {
            return true; // unlimited seats
        }
        return $this->seatsRemaining() > 0;
    }
}
