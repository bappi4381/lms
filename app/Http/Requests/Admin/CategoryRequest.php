<?php

namespace App\Http\Requests\Admin;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    private const MAX_DEPTH = 3;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $categoryId = $this->route('category')?->id ?? $this->route('category');

        return [
            'name_en' => ['required', 'string', 'max:255'],
            'name_bn' => ['required', 'string', 'max:255'],
            'parent_id' => [
                'nullable',
                'exists:categories,id',
                function (string $attribute, $value, \Closure $fail) use ($categoryId) {
                    if (! $value) {
                        return;
                    }

                    if ($categoryId && (int) $value === (int) $categoryId) {
                        $fail('একটি ক্যাটাগরিকে নিজের অধীনে রাখা যাবে না।');
                        return;
                    }

                    $parent = Category::find($value);
                    if (! $parent) {
                        return;
                    }

                    $currentRecord = $categoryId ? Category::find($categoryId) : null;

                    if ($currentRecord && $parent->isDescendantOf($currentRecord)) {
                        $fail('একটি ক্যাটাগরিকে নিজের সাব-ক্যাটাগরির অধীনে রাখা যাবে না।');
                        return;
                    }

                    $newDepth = $parent->depth() + 1;
                    $subtreeHeight = $currentRecord ? $currentRecord->subtreeHeight() : 0;

                    if ($newDepth + $subtreeHeight > self::MAX_DEPTH) {
                        $fail('সর্বোচ্চ ৩ লেভেল পর্যন্ত ক্যাটাগরি নেস্টিং সম্ভব — এই Parent নির্বাচন করলে সীমা অতিক্রম করবে।');
                    }
                },
            ],
            'main_type' => [
                'nullable',
                'required_if:parent_id,null',
                'in:academic,skills,test_prep,professional',
            ],
            'icon' => ['nullable', 'string', 'max:50'],
            'order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'order' => $this->filled('order') ? (int) $this->input('order') : 0,
        ]);
    }
}
