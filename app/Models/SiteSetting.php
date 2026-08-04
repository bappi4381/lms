<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = [
        // Footer
        'header_links',
        'brand_description_en', 'brand_description_bn',
        'footer_columns',
        'contact_email',
        'contact_phone_en', 'contact_phone_bn',
        'contact_address_en', 'contact_address_bn',
        'social_links',
        'copyright_text_en', 'copyright_text_bn',

        // Hero Section
        'hero_eyebrow_en', 'hero_eyebrow_bn',
        'hero_title_en', 'hero_title_bn',
        'hero_highlight_en', 'hero_highlight_bn',
        'hero_description_en', 'hero_description_bn',
        'hero_btn_primary_en', 'hero_btn_primary_bn',
        'hero_btn_secondary_en', 'hero_btn_secondary_bn',

        // About Section
        'about_eyebrow_en', 'about_eyebrow_bn',
        'about_title_en', 'about_title_bn',
        'about_description_en', 'about_description_bn',
        'about_btn_en', 'about_btn_bn',

        // Why Choose Us Section
        'whyus_eyebrow_en', 'whyus_eyebrow_bn',
        'whyus_title_en', 'whyus_title_bn',
        'whyus_cards',

        // Pricing Section
        'pricing_eyebrow_en', 'pricing_eyebrow_bn',
        'pricing_title_en', 'pricing_title_bn',

        // Testimonials Section
        'testi_eyebrow_en', 'testi_eyebrow_bn',
        'testi_title_en', 'testi_title_bn',
    ];

    protected $casts = [
        'header_links'  => 'array',
        'footer_columns' => 'array',
        'social_links'  => 'array',
        'whyus_cards'   => 'array',
    ];

    /**
     * Get single site setting record (singleton pattern with defaults)
     */
    public static function getSettings(): static
    {
        return static::firstOrCreate([], [
            // Header
            'header_links' => [
                ['label_en' => 'Home',        'label_bn' => 'হোম',                'url' => '/',             'is_active' => true],
                ['label_en' => 'About',       'label_bn' => 'আমাদের সম্পর্কে',   'url' => '#about',        'is_active' => true],
                ['label_en' => 'Courses',     'label_bn' => 'কোর্সসমূহ',         'url' => '#courses',      'is_active' => true],
                ['label_en' => 'Pricing',     'label_bn' => 'প্রাইসিং',           'url' => '#pricing',      'is_active' => true],
                ['label_en' => 'Instructors', 'label_bn' => 'ইনস্ট্রাক্টর',      'url' => '#testimonials', 'is_active' => true],
                ['label_en' => 'Contact',     'label_bn' => 'যোগাযোগ',            'url' => '#contact',      'is_active' => true],
            ],

            // Footer Brand
            'brand_description_bn' => 'বাংলাদেশের শিক্ষার্থীদের জন্য মানসম্মত অনলাইন শিক্ষা — একাডেমিক থেকে প্রফেশনাল, সব এক প্ল্যাটফর্মে।',
            'brand_description_en' => 'Quality online education for Bangladeshi learners — academic to professional, all on one platform.',

            // Footer Columns
            'footer_columns' => [
                [
                    'column_title_bn' => 'শেখা', 'column_title_en' => 'Learn',
                    'links' => [
                        ['label_bn' => 'সকল কোর্স',   'label_en' => 'All Courses',    'url' => '/courses/search',           'is_active' => true],
                        ['label_bn' => 'ফ্রি রিসোর্স', 'label_en' => 'Free Resources', 'url' => '/courses/search?type=free', 'is_active' => true],
                        ['label_bn' => 'ব্লগ',         'label_en' => 'Blog',           'url' => '/courses/search?type=blog', 'is_active' => true],
                    ],
                ],
                [
                    'column_title_bn' => 'প্ল্যাটফর্ম', 'column_title_en' => 'Platform',
                    'links' => [
                        ['label_bn' => 'কমিউনিটি ফোরাম', 'label_en' => 'Community Forum', 'url' => '#', 'is_active' => true],
                        ['label_bn' => 'লাইভ ক্লাস',      'label_en' => 'Live Classes',    'url' => '#', 'is_active' => true],
                        ['label_bn' => 'মেন্টরশিপ',        'label_en' => 'Mentorship',      'url' => '#', 'is_active' => true],
                        ['label_bn' => 'জব বোর্ড',         'label_en' => 'Job Board',       'url' => '#', 'is_active' => true],
                    ],
                ],
            ],

            // Contact
            'contact_email'      => 'support@secondshiftbd.com',
            'contact_phone_bn'   => '+(৮৮০) ১২৩৪ ৫৬৭৮৯০',
            'contact_phone_en'   => '+(880) 1234 567890',
            'contact_address_bn' => 'ঢাকা, বাংলাদেশ',
            'contact_address_en' => 'Dhaka, Bangladesh',

            // Social
            'social_links' => [
                ['platform' => 'facebook',  'url' => 'https://facebook.com',  'is_active' => true],
                ['platform' => 'youtube',   'url' => 'https://youtube.com',   'is_active' => true],
                ['platform' => 'instagram', 'url' => 'https://instagram.com', 'is_active' => true],
                ['platform' => 'linkedin',  'url' => 'https://linkedin.com',  'is_active' => true],
            ],

            // Copyright
            'copyright_text_bn' => '© ' . date('Y') . ' SecondShiftBD. সর্বস্বত্ব সংরক্ষিত।',
            'copyright_text_en' => '© ' . date('Y') . ' SecondShiftBD. All rights reserved.',

            // Hero
            'hero_eyebrow_en'      => 'See how our teachers learn',
            'hero_eyebrow_bn'      => 'আমাদের শিক্ষকরা কীভাবে শেখান দেখুন',
            'hero_title_en'        => 'We provide',
            'hero_title_bn'        => 'আমরা দিচ্ছি',
            'hero_highlight_en'    => 'fun e-course',
            'hero_highlight_bn'    => 'মজার ই-কোর্স',
            'hero_description_en'  => 'Learn new skills the way that actually sticks — bite-sized lessons, live mentors, and a community that keeps you moving forward.',
            'hero_description_bn'  => 'নতুন দক্ষতা শিখুন সহজে — ছোট ছোট পাঠ, লাইভ মেন্টর এবং একটি কমিউনিটি যা আপনাকে এগিয়ে রাখবে।',
            'hero_btn_primary_en'  => 'View Courses',
            'hero_btn_primary_bn'  => 'কোর্স দেখুন',
            'hero_btn_secondary_en' => 'Watch intro',
            'hero_btn_secondary_bn' => 'ইন্ট্রো দেখুন',

            // About
            'about_eyebrow_en'    => 'About us',
            'about_eyebrow_bn'    => 'আমাদের সম্পর্কে',
            'about_title_en'      => 'Founded in 2015',
            'about_title_bn'      => '২০১৫ সালে প্রতিষ্ঠিত',
            'about_description_en' => 'E-Learning Adventures is committed to transforming the traditional learning landscape. With a blend of engaging content, interactive exercises, and cutting-edge technology, we ensure every learner finds their path to success.',
            'about_description_bn' => 'আমরা ঐতিহ্যবাহী শিক্ষার চেহারা বদলে দিতে প্রতিশ্রুতিবদ্ধ। আকর্ষণীয় কন্টেন্ট, ইন্টারেক্টিভ অনুশীলন এবং অত্যাধুনিক প্রযুক্তির মিশেলে আমরা প্রতিটি শিক্ষার্থীর সাফল্যের পথ নিশ্চিত করি।',
            'about_btn_en'        => 'Learn more',
            'about_btn_bn'        => 'আরও জানুন',

            // Why Choose Us
            'whyus_eyebrow_en' => 'Why choose us',
            'whyus_eyebrow_bn' => 'আমাদের বেছে নেওয়ার কারণ',
            'whyus_title_en'   => 'Our courses are designed to be immersive and interactive!',
            'whyus_title_bn'   => 'আমাদের কোর্সগুলো ইমার্সিভ ও ইন্টারেক্টিভভাবে তৈরি!',
            'whyus_cards'      => [
                ['title_en' => 'Interactive Learning', 'title_bn' => 'ইন্টারেক্টিভ শিক্ষা', 'desc_en' => 'Video, quizzes, and assignments with hands-on practice.', 'desc_bn' => 'ভিডিও, কুইজ ও অ্যাসাইনমেন্টে হাতে-কলমে অনুশীলন।'],
                ['title_en' => 'Expert Instructors',   'title_bn' => 'বিশেষজ্ঞ শিক্ষক',       'desc_en' => 'Industry-experienced mentors guide you every step.', 'desc_bn' => 'শিল্পে অভিজ্ঞ মেন্টররা প্রতিটি ধাপে গাইড করেন।'],
                ['title_en' => 'Flexible Schedules',   'title_bn' => 'নমনীয় সময়সূচি',        'desc_en' => 'Learn at your own pace — anytime, on any device.', 'desc_bn' => 'নিজের গতিতে শিখুন — যেকোনো সময়, যেকোনো ডিভাইসে।'],
                ['title_en' => 'Affordable Pricing',   'title_bn' => 'সাশ্রয়ী মূল্য',          'desc_en' => 'Flexible pricing and subscription options for everyone.', 'desc_bn' => 'সকলের জন্য নমনীয় মূল্য ও সাবস্ক্রিপশন অপশন।'],
            ],

            // Pricing
            'pricing_eyebrow_en' => 'Pricing',
            'pricing_eyebrow_bn' => 'মূল্য পরিকল্পনা',
            'pricing_title_en'   => 'Pricing Plan',
            'pricing_title_bn'   => 'প্রাইসিং প্ল্যান',

            // Testimonials
            'testi_eyebrow_en' => 'Testimonials',
            'testi_eyebrow_bn' => 'শিক্ষার্থীদের মতামত',
            'testi_title_en'   => 'What Our Students Say',
            'testi_title_bn'   => 'আমাদের শিক্ষার্থীরা কী বলেন',
        ]);
    }

    /** Locale-aware helper */
    private function locale(string $en, string $bn): string
    {
        return app()->getLocale() === 'bn' ? ($bn ?: $en) : ($en ?: $bn);
    }

    public function getBrandDescriptionAttribute(): string  { return $this->locale($this->brand_description_en ?? '', $this->brand_description_bn ?? ''); }
    public function getCopyrightTextAttribute(): string     { return $this->locale($this->copyright_text_en ?? '', $this->copyright_text_bn ?? ''); }
    public function getPhoneAttribute(): string             { return $this->locale($this->contact_phone_en ?? '', $this->contact_phone_bn ?? ''); }
    public function getAddressAttribute(): string           { return $this->locale($this->contact_address_en ?? '', $this->contact_address_bn ?? ''); }

    // Hero
    public function getHeroEyebrowAttribute(): string       { return $this->locale($this->hero_eyebrow_en ?? 'See how our teachers learn', $this->hero_eyebrow_bn ?? 'আমাদের শিক্ষকরা কীভাবে শেখান দেখুন'); }
    public function getHeroTitleAttribute(): string         { return $this->locale($this->hero_title_en ?? 'We provide', $this->hero_title_bn ?? 'আমরা দিচ্ছি'); }
    public function getHeroHighlightAttribute(): string     { return $this->locale($this->hero_highlight_en ?? 'fun e-course', $this->hero_highlight_bn ?? 'মজার ই-কোর্স'); }
    public function getHeroDescriptionAttribute(): string   { return $this->locale($this->hero_description_en ?? '', $this->hero_description_bn ?? ''); }
    public function getHeroBtnPrimaryAttribute(): string    { return $this->locale($this->hero_btn_primary_en ?? 'View Courses', $this->hero_btn_primary_bn ?? 'কোর্স দেখুন'); }
    public function getHeroBtnSecondaryAttribute(): string  { return $this->locale($this->hero_btn_secondary_en ?? 'Watch intro', $this->hero_btn_secondary_bn ?? 'ইন্ট্রো দেখুন'); }

    // About
    public function getAboutEyebrowAttribute(): string      { return $this->locale($this->about_eyebrow_en ?? 'About us', $this->about_eyebrow_bn ?? 'আমাদের সম্পর্কে'); }
    public function getAboutTitleAttribute(): string        { return $this->locale($this->about_title_en ?? 'Founded in 2015', $this->about_title_bn ?? '২০১৫ সালে প্রতিষ্ঠিত'); }
    public function getAboutDescriptionAttribute(): string  { return $this->locale($this->about_description_en ?? '', $this->about_description_bn ?? ''); }
    public function getAboutBtnAttribute(): string          { return $this->locale($this->about_btn_en ?? 'Learn more', $this->about_btn_bn ?? 'আরও জানুন'); }

    // Why Choose Us
    public function getWhyusEyebrowAttribute(): string      { return $this->locale($this->whyus_eyebrow_en ?? 'Why choose us', $this->whyus_eyebrow_bn ?? 'আমাদের বেছে নেওয়ার কারণ'); }
    public function getWhyusTitleAttribute(): string        { return $this->locale($this->whyus_title_en ?? 'Our courses are designed to be immersive and interactive!', $this->whyus_title_bn ?? 'আমাদের কোর্সগুলো ইমার্সিভ ও ইন্টারেক্টিভভাবে তৈরি!'); }

    // Pricing
    public function getPricingEyebrowAttribute(): string    { return $this->locale($this->pricing_eyebrow_en ?? 'Pricing', $this->pricing_eyebrow_bn ?? 'মূল্য পরিকল্পনা'); }
    public function getPricingTitleAttribute(): string      { return $this->locale($this->pricing_title_en ?? 'Pricing Plan', $this->pricing_title_bn ?? 'প্রাইসিং প্ল্যান'); }

    // Testimonials
    public function getTestiEyebrowAttribute(): string      { return $this->locale($this->testi_eyebrow_en ?? 'Testimonials', $this->testi_eyebrow_bn ?? 'শিক্ষার্থীদের মতামত'); }
    public function getTestiTitleAttribute(): string        { return $this->locale($this->testi_title_en ?? 'What Our Students Say', $this->testi_title_bn ?? 'আমাদের শিক্ষার্থীরা কী বলেন'); }

    public static function clearCache(): void
    {
        Cache::forget('site_settings_record');
    }
}
