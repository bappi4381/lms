<?php

namespace App\Filament\Resources\Categories\Tables;

use App\Models\Category;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;

class CategoriesTable
{
    protected const MAIN_TYPE_LABELS = [
        'academic' => 'Academic',
        'skills' => 'Skills',
        'test_prep' => 'Test Prep',
        'professional' => 'Professional',
    ];

    protected const MAIN_TYPE_COLORS = [
        'academic' => 'info',
        'skills' => 'success',
        'test_prep' => 'warning',
        'professional' => 'gray',
    ];

    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextInputColumn::make('order')
                    ->label('#')
                    ->rules(['integer'])
                    ->sortable()
                    ->width(50),

                TextColumn::make('depth_level')
                    ->label('Depth')
                    ->state(fn (Category $record) => $record->depth())
                    ->width(50),

                TextColumn::make('icon')
                    ->label('Icon'),

                TextColumn::make('parent.name_en')
                    ->label('Parent')
                    ->placeholder('—')
                    ->color('gray')
                    ->toggleable(),

                TextColumn::make('name_en')
                    ->label('Name (EN)')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Category $record) => $record->parent ? '↳ Sub-category' : null),

                TextColumn::make('name_bn')
                    ->label('Name (BN)')
                    ->searchable(),

                TextColumn::make('main_type')
                    ->label('Navbar Section')
                    ->badge()
                    ->formatStateUsing(fn (Category $record) => self::MAIN_TYPE_LABELS[$record->resolvedMainType()] ?? '—')
                    ->color(fn (Category $record) => self::MAIN_TYPE_COLORS[$record->resolvedMainType()] ?? 'gray'),

                TextColumn::make('slug_en')
                    ->label('Slug (EN)')
                    ->color('gray')
                    ->toggleable(),

                TextColumn::make('courses_count')
                    ->label('Courses')
                    ->counts('courses')
                    ->badge()
                    ->color('info'),

                ToggleColumn::make('is_active')
                    ->label('Active'),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->defaultSort('order')
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
