<?php

namespace App\Filament\Resources\Courses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CoursesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Course Title')
                    ->searchable()
                    ->weight('bold')
                    ->limit(40),

                TextColumn::make('category.name')
                    ->label('Category')
                    ->badge()
                    ->color('info')
                    ->placeholder('—'),

                TextColumn::make('price')
                    ->label('Price')
                    ->formatStateUsing(fn ($state) => '৳' . number_format($state, 0))
                    ->sortable(),

                IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean(),

                TextColumn::make('instructor.name')
                    ->label('Instructor')
                    ->placeholder('—')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
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
