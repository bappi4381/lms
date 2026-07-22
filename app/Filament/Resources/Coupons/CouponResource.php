<?php

namespace App\Filament\Resources\Coupons;

use App\Filament\Resources\Coupons\Pages\CreateCoupon;
use App\Filament\Resources\Coupons\Pages\EditCoupon;
use App\Filament\Resources\Coupons\Pages\ListCoupons;
use App\Models\Coupon;
use App\Models\Course;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTicket;

    protected static ?string $navigationLabel = 'Coupons';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'code';

    public static function getNavigationGroup(): ?string
    {
        return 'Payments & Subscriptions';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(50)
                    ->alphaDash()
                    ->afterStateUpdated(fn ($state, callable $set) => $set('code', strtoupper($state)))
                    ->live(onBlur: true),

                Select::make('type')
                    ->options([
                        'percent' => 'Percent (%)',
                        'fixed' => 'Fixed Amount (৳)',
                    ])
                    ->default('percent')
                    ->required(),

                TextInput::make('value')
                    ->numeric()
                    ->required(),

                TextInput::make('max_uses')
                    ->numeric()
                    ->label('Max Uses (blank = unlimited)'),

                TextInput::make('min_order_amount')
                    ->numeric()
                    ->prefix('৳')
                    ->label('Minimum Order Amount'),

                Select::make('applicable_to')
                    ->options([
                        'all' => 'All Courses',
                        'specific_courses' => 'Specific Courses',
                    ])
                    ->default('all')
                    ->live()
                    ->required(),

                Select::make('course_ids')
                    ->label('Applicable Courses')
                    ->multiple()
                    ->options(Course::pluck('title', 'id'))
                    ->searchable()
                    ->preload()
                    ->visible(fn ($get) => $get('applicable_to') === 'specific_courses')
                    ->columnSpanFull(),

                DateTimePicker::make('starts_at'),
                DateTimePicker::make('expires_at'),

                Toggle::make('is_active')
                    ->default(true),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->searchable()
                    ->weight('bold')
                    ->copyable(),
                TextColumn::make('type')
                    ->badge(),
                TextColumn::make('value')
                    ->formatStateUsing(fn ($record) => $record->type === 'percent' ? "{$record->value}%" : '৳'.number_format($record->value, 0)),
                TextColumn::make('used_count')
                    ->label('Used')
                    ->formatStateUsing(fn ($record) => $record->used_count.($record->max_uses ? "/{$record->max_uses}" : '')),
                TextColumn::make('expires_at')
                    ->dateTime('d M Y')
                    ->placeholder('No Expiry'),
                TextColumn::make('is_active')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? 'Active' : 'Inactive')
                    ->color(fn ($state) => $state ? 'success' : 'danger'),
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
            'index' => ListCoupons::route('/'),
            'create' => CreateCoupon::route('/create'),
            'edit' => EditCoupon::route('/{record}/edit'),
        ];
    }
}
