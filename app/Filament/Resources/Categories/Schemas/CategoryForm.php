<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Category;
use Closure;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    /**
     * Max nesting depth (top-level category + sub-category + sub-sub-category).
     */
    private const MAX_DEPTH = 3;

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('parent_id')
                    ->label('Parent Category (রাখলে এটি সাব/সাব-সাব-ক্যাটাগরি হবে)')
                    ->options(fn ($record) => self::parentOptions($record))
                    ->searchable()
                    ->preload()
                    ->live()
                    ->placeholder('— কোনো Parent নেই (Top-level Category) —')
                    ->rule(fn ($record) => self::validParentRule($record))
                    ->helperText('সর্বোচ্চ ৩ লেভেল পর্যন্ত নেস্টিং সম্ভব: ক্যাটাগরি → সাব-ক্যাটাগরি → সাব-সাব-ক্যাটাগরি।'),

                Select::make('main_type')
                    ->label('Main Navbar Section')
                    ->options([
                        'academic' => 'Academic (একাডেমিক)',
                        'skills' => 'Skills (স্কিলস)',
                        'test_prep' => 'Test Preparation (টেস্ট প্রস্তুতি)',
                        'professional' => 'Professional (প্রফেশনাল)',
                    ])
                    ->native(false)
                    ->visible(fn ($get) => blank($get('parent_id')))
                    ->required(fn ($get) => blank($get('parent_id')))
                    ->dehydrateStateUsing(fn ($state, $get) => blank($get('parent_id')) ? $state : null)
                    ->helperText('শুধু Top-level Category-এর জন্য — সাব-ক্যাটাগরি এটি তার সবচেয়ে উপরের Parent থেকে ইনহেরিট করে।'),

                TextInput::make('name_en')
                    ->label('Category Name (English)')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug_en', Str::slug((string) $state))),

                TextInput::make('slug_en')
                    ->label('Slug (English)')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                TextInput::make('name_bn')
                    ->label('Category Name (বাংলা)')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug_bn', Str::slug((string) $state))),

                TextInput::make('slug_bn')
                    ->label('Slug (বাংলা)')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                TextInput::make('icon')
                    ->label('Icon (emoji or CSS class)')
                    ->placeholder('e.g. 💻 or fa-code')
                    ->maxLength(100),

                TextInput::make('order')
                    ->label('Display Order')
                    ->numeric()
                    ->default(0),

                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ]);
    }

    /**
     * Valid parent choices: any top-level or 2nd-level category (depth 1 or
     * 2) can be a parent — their children would land at depth 2 or 3,
     * respecting the 3-level cap. Depth-3 categories are never valid
     * parents. The record itself and any of its own descendants are
     * excluded to prevent cycles.
     */
    private static function parentOptions(?Category $record): array
    {
        return Category::query()
            ->with('parent')
            ->orderBy('order')
            ->get()
            ->filter(fn (Category $category) => $category->depth() < self::MAX_DEPTH)
            ->when($record, fn ($categories) => $categories->reject(
                fn (Category $category) => $category->id === $record->id || $category->isDescendantOf($record)
            ))
            ->sortBy([['parent_id', 'asc'], ['order', 'asc']])
            ->mapWithKeys(fn (Category $category) => [
                $category->id => $category->parent
                    ? "{$category->parent->name_en} → {$category->name_en} / {$category->parent->name_bn} → {$category->name_bn}"
                    : "{$category->name_en} / {$category->name_bn}",
            ])
            ->all();
    }

    private static function validParentRule(?Category $record): Closure
    {
        return function (string $attribute, $value, Closure $fail) use ($record) {
            if (! $value) {
                return;
            }

            $parent = Category::find($value);

            if (! $parent) {
                return;
            }

            if ($record && ($parent->id === $record->id || $parent->isDescendantOf($record))) {
                $fail('একটি ক্যাটাগরিকে নিজের অধীনে বা নিজের সাব-ক্যাটাগরির অধীনে রাখা যাবে না।');

                return;
            }

            $newDepth = $parent->depth() + 1;
            $subtreeHeight = $record ? $record->subtreeHeight() : 0;

            if ($newDepth + $subtreeHeight > self::MAX_DEPTH) {
                $fail('সর্বোচ্চ ৩ লেভেল পর্যন্ত ক্যাটাগরি নেস্টিং সম্ভব — এই Parent নির্বাচন করলে সীমা অতিক্রম করবে।');
            }
        };
    }
}
