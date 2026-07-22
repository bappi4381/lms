<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Category;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput as SlugInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('parent_id')
                    ->label('Parent Category (রাখলে এটি সাব-ক্যাটাগরি হবে)')
                    ->options(fn ($record) => Category::whereNull('parent_id')
                        ->when($record, fn ($q) => $q->whereKeyNot($record->id))
                        ->orderBy('order')
                        ->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->placeholder('— কোনো Parent নেই (Top-level Category) —'),

                TextInput::make('name')
                    ->label('Category Name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) =>
                        $set('slug', Str::slug($state))
                    ),

                TextInput::make('slug')
                    ->label('Slug')
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
}
