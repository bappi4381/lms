<?php

namespace App\Filament\Resources\SupportTickets;

use App\Filament\Resources\SupportTickets\Pages\EditSupportTicket;
use App\Filament\Resources\SupportTickets\Pages\ListSupportTickets;
use App\Filament\Resources\SupportTickets\RelationManagers\RepliesRelationManager;
use App\Models\SupportTicket;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SupportTicketResource extends Resource
{
    protected static ?string $model = SupportTicket::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLifebuoy;

    protected static ?string $navigationLabel = 'Support Tickets';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'subject';

    public static function getNavigationGroup(): ?string
    {
        return 'Support & Engagement';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('status')
                    ->options([
                        'open' => 'Open',
                        'pending' => 'Pending',
                        'resolved' => 'Resolved',
                        'closed' => 'Closed',
                    ])
                    ->required(),

                Select::make('priority')
                    ->options([
                        'low' => 'Low',
                        'medium' => 'Medium',
                        'high' => 'High',
                    ])
                    ->required(),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('subject')
                    ->searchable()
                    ->weight('bold')
                    ->limit(40),
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable(),
                TextColumn::make('category')
                    ->badge(),
                TextColumn::make('priority')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'high' => 'danger',
                        'medium' => 'warning',
                        'low' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'open' => 'danger',
                        'pending' => 'warning',
                        'resolved' => 'success',
                        'closed' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'open' => 'Open',
                        'pending' => 'Pending',
                        'resolved' => 'Resolved',
                        'closed' => 'Closed',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RepliesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSupportTickets::route('/'),
            'edit' => EditSupportTicket::route('/{record}/edit'),
        ];
    }
}
