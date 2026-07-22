<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order')
                    ->label('#')
                    ->sortable()
                    ->width(50),

                TextColumn::make('icon')
                    ->label('Icon'),

                TextColumn::make('parent.name')
                    ->label('Parent')
                    ->placeholder('—')
                    ->color('gray')
                    ->toggleable(),

                TextColumn::make('name')
                    ->label('Category Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->parent ? '↳ Sub-category' : null),

                TextColumn::make('slug')
                    ->label('Slug')
                    ->color('gray'),

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
