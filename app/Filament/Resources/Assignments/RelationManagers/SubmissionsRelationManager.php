<?php

namespace App\Filament\Resources\Assignments\RelationManagers;

use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SubmissionsRelationManager extends RelationManager
{
    protected static string $relationship = 'submissions';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('submission_text')
                    ->label('Submission Text')
                    ->disabled()
                    ->rows(3)
                    ->columnSpanFull(),

                TextInput::make('file_path')
                    ->label('Uploaded File')
                    ->disabled()
                    ->columnSpanFull(),

                TextInput::make('grade')
                    ->numeric()
                    ->label('Grade'),

                Textarea::make('feedback')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('user.name')
                    ->label('Student')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'submitted' => 'warning',
                        'graded' => 'success',
                        'resubmit' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('grade')
                    ->placeholder('—'),
                TextColumn::make('submitted_at')
                    ->dateTime('d M Y, h:i A'),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Grade')
                    ->mutateFormDataUsing(function (array $data) {
                        if (! empty($data['grade']) && $data['grade'] !== '') {
                            $data['status'] = 'graded';
                            $data['graded_at'] = now();
                            $data['graded_by'] = auth()->id();
                        }

                        return $data;
                    }),
            ])
            ->toolbarActions([]);
    }
}
