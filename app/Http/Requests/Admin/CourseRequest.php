<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $courseId = $this->route('course')?->id;

        return [
            'title_en'               => 'required|string|max:255',
            'title_bn'               => 'required|string|max:255',
            'slug'                   => "required|string|max:255|unique:courses,slug,{$courseId}",
            'sub_description_en'     => 'nullable|string|max:1000',
            'sub_description_bn'     => 'nullable|string|max:1000',
            'description_en'         => 'nullable|string',
            'description_bn'         => 'nullable|string',
            'thumbnail'              => 'nullable|string|max:500',
            'thumbnail_upload'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'category_id'            => 'nullable|exists:categories,id',
            'instructor_id'          => 'nullable|exists:users,id',
            'price'                  => 'required|numeric|min:0',
            'discount_price'         => 'nullable|numeric|min:0',
            'batch_number'           => 'nullable|integer|min:1',
            'seats_total'            => 'nullable|integer|min:0',
            'seats_available'        => 'nullable|integer|min:0',
            'starts_at'              => 'nullable|date',
            'live_class_schedule'    => 'nullable|string|max:255',
            'support_class_schedule' => 'nullable|string|max:255',
            'video_url'              => 'nullable|url|max:500',
            'is_published'           => 'boolean',
            // JSON-encoded array fields (processed in controller)
            'key_features_en'        => 'nullable|string',
            'key_features_bn'        => 'nullable|string',
            'tools_en'               => 'nullable|string',
            'tools_bn'               => 'nullable|string',
            'course_includes'        => 'nullable|string',
            'projects_en'            => 'nullable|string',
            'projects_bn'            => 'nullable|string',
            'faqs_en'                => 'nullable|string',
            'faqs_bn'                => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'title_en.required'  => 'English course title is required.',
            'title_bn.required'  => 'Bengali course title is required.',
            'slug.required'      => 'Slug (URL) is required.',
            'slug.unique'        => 'This slug is already in use. Please choose another.',
            'price.required'     => 'Course price is required.',
            'price.numeric'      => 'Price must be a valid number.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_published' => $this->boolean('is_published'),
        ]);
    }
}
