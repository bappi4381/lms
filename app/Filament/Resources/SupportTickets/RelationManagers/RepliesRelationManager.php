<?php

namespace App\Filament\Resources\SupportTickets\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RepliesRelationManager extends RelationManager
{
    protected static string $relationship = 'replies';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('message')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('message')
            ->columns([
                TextColumn::make('user.name')
                    ->label('From'),
                IconColumn::make('is_staff_reply')
                    ->label('Staff')
                    ->boolean(),
                TextColumn::make('message')
                    ->limit(60)
                    ->wrap(),
                TextColumn::make('created_at')
                    ->dateTime('d M Y, h:i A'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateFormDataUsing(function (array $data) {
                        $data['user_id'] = auth()->id();
                        $data['is_staff_reply'] = true;

                        return $data;
                    }),
            ])
            ->recordActions([
                DeleteAction::make(),
            ])
            ->defaultSort('created_at');
    }
}
