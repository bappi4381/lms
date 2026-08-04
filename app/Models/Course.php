<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    /**
     * Locales supported for course content.
     */
    public const LOCALES = ['en', 'bn'];

    protected $fillable = [
        'title',
        'title_en',
        'title_bn',
        'slug',
        'sub_description',
        'sub_description_en',
        'sub_description_bn',
        'description',
        'description_en',
        'description_bn',
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
        'key_features_en',
        'key_features_bn',
        'course_includes',
        'projects',
        'projects_en',
        'projects_bn',
        'tools',
        'tools_en',
        'tools_bn',
        'career_opportunities',
        'faqs',
        'faqs_en',
        'faqs_bn',
    ];

    protected $casts = [
        'is_published'         => 'boolean',
        'price'                => 'decimal:2',
        'discount_price'       => 'decimal:2',
        'starts_at'            => 'datetime',
        'key_features'         => 'array',
        'key_features_en'      => 'array',
        'key_features_bn'      => 'array',
        'course_includes'      => 'array',
        'projects'             => 'array',
        'projects_en'          => 'array',
        'projects_bn'          => 'array',
        'tools'                => 'array',
        'tools_en'             => 'array',
        'tools_bn'             => 'array',
        'career_opportunities' => 'array',
        'faqs'                 => 'array',
        'faqs_en'              => 'array',
        'faqs_bn'              => 'array',
    ];

    // ──────────────────────────────────────────────────────────────
    //  Locale-aware helpers  (same approach as Category::nameFor)
    // ──────────────────────────────────────────────────────────────

    /**
     * Return the requested locale's value for any localised column,
     * falling back to English then Bengali if not set, and finally raw original attribute.
     */
    public function localised(string $field, ?string $locale = null): mixed
    {
        $locale = in_array($locale ?? app()->getLocale(), self::LOCALES, true)
            ? ($locale ?? app()->getLocale())
            : 'en';

        $localisedField = "{$field}_{$locale}";
        $fallbackEn     = "{$field}_en";
        $fallbackBn     = "{$field}_bn";

        // 1. Try requested locale attribute (e.g. key_features_en)
        $val = $this->getAttributeValue($localisedField);
        if (! empty($val)) {
            return $val;
        }

        // 2. Fallback to English
        $valEn = $this->getAttributeValue($fallbackEn);
        if (! empty($valEn)) {
            return $valEn;
        }

        // 3. Fallback to Bangla
        $valBn = $this->getAttributeValue($fallbackBn);
        if (! empty($valBn)) {
            return $valBn;
        }

        // 4. Fallback to raw database column (bypassing accessor recursion)
        $raw = $this->attributes[$field] ?? null;
        if ($raw !== null && is_string($raw) && (str_starts_with($raw, '[') || str_starts_with($raw, '{'))) {
            $decoded = json_decode($raw, true);
            return is_array($decoded) ? $decoded : $raw;
        }

        return $raw;
    }

    // ──────────────────────────────────────────────────────────────
    //  Virtual attributes — existing code keeps working unchanged
    // ──────────────────────────────────────────────────────────────

    protected function title(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->localised('title'),
        );
    }

    protected function subDescription(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->localised('sub_description'),
        );
    }

    protected function description(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->localised('description'),
        );
    }

    protected function keyFeatures(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->localised('key_features'),
        );
    }

    protected function faqs(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->localised('faqs'),
        );
    }

    protected function projects(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->localised('projects'),
        );
    }

    protected function tools(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->localised('tools'),
        );
    }

    // ──────────────────────────────────────────────────────────────
    //  Relationships
    // ──────────────────────────────────────────────────────────────

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
            return true;
        }
        return $this->seatsRemaining() > 0;
    }
}

