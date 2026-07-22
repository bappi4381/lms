<?php

namespace App\Filament\Resources\Orders;

use App\Filament\Resources\Orders\Pages\EditOrder;
use App\Filament\Resources\Orders\Pages\ListOrders;
use App\Models\Order;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static ?string $navigationLabel = 'Orders';

    protected static ?int $navigationSort = 0;

    public static function getNavigationGroup(): ?string
    {
        return 'Payments & Subscriptions';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('payment_status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                        'canceled' => 'Cancelled',
                        'refund_requested' => 'Refund Requested',
                        'refunded' => 'Refunded',
                    ])
                    ->required(),

                TextInput::make('transaction_id')
                    ->disabled(),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('transaction_id')
                    ->label('Txn ID')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable(),
                TextColumn::make('type')
                    ->badge(),
                TextColumn::make('course.title')
                    ->label('Item')
                    ->formatStateUsing(fn ($record) => $record->type === 'subscription' ? $record->subscriptionPlan?->name : $record->course?->title)
                    ->limit(30),
                TextColumn::make('amount')
                    ->formatStateUsing(fn ($state) => '৳'.number_format($state, 0)),
                TextColumn::make('discount_amount')
                    ->label('Discount')
                    ->formatStateUsing(fn ($state) => $state > 0 ? '৳'.number_format($state, 0) : '—'),
                TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        'failed', 'canceled' => 'danger',
                        'refund_requested' => 'warning',
                        'refunded' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('payment_method'),
                TextColumn::make('created_at')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('payment_status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                        'canceled' => 'Cancelled',
                        'refund_requested' => 'Refund Requested',
                        'refunded' => 'Refunded',
                    ]),
                SelectFilter::make('type')
                    ->options(['course' => 'Course', 'subscription' => 'Subscription']),
            ])
            ->recordActions([
                Action::make('markRefunded')
                    ->label('Mark Refunded')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('warning')
                    ->visible(fn (Order $record) => $record->payment_status === 'refund_requested')
                    ->requiresConfirmation()
                    ->action(function (Order $record) {
                        $record->update(['payment_status' => 'refunded']);
                        Notification::make()->title('Order marked as refunded')->success()->send();
                    }),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrders::route('/'),
            'edit' => EditOrder::route('/{record}/edit'),
        ];
    }
}
