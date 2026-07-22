<?php

namespace App\Filament\Resources\Modules\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LessonsRelationManager extends RelationManager
{
    protected static string $relationship = 'lessons';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->columnSpanFull(),

                Select::make('type')
                    ->label('Lesson Type')
                    ->options([
                        'video' => 'Video',
                        'pdf' => 'PDF',
                        'quiz' => 'Quiz',
                        'assignment' => 'Assignment',
                    ])
                    ->default('video')
                    ->live()
                    ->required(),

                TextInput::make('video_id')
                    ->label('Bunny Video ID')
                    ->visible(fn ($get) => $get('type') === 'video'),

                TextInput::make('pdf_url')
                    ->label('PDF URL')
                    ->visible(fn ($get) => $get('type') === 'pdf'),

                Textarea::make('content')
                    ->label('Notes / Description')
                    ->rows(2)
                    ->columnSpanFull(),

                TextInput::make('duration_seconds')
                    ->label('Duration (seconds)')
                    ->numeric(),

                Toggle::make('is_preview')
                    ->label('Free Preview')
                    ->default(false),

                TextInput::make('order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'video' => 'info',
                        'pdf' => 'gray',
                        'quiz' => 'warning',
                        'assignment' => 'success',
                        default => 'gray',
                    }),
                IconColumn::make('is_preview')
                    ->boolean(),
                TextColumn::make('order')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
                AssociateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DissociateAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
