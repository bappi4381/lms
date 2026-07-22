<?php

namespace App\Filament\Resources\Devices;

use App\Filament\Resources\Devices\Pages\EditDevice;
use App\Filament\Resources\Devices\Pages\ListDevices;
use App\Models\Device;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DeviceResource extends Resource
{
    protected static ?string $model = Device::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDevicePhoneMobile;

    protected static ?string $navigationLabel = 'Devices';

    protected static ?int $navigationSort = 4;

    public static function getNavigationGroup(): ?string
    {
        return 'Access Control';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->disabled(),

                TextInput::make('device_type')
                    ->disabled(),

                TextInput::make('device_name')
                    ->disabled()
                    ->columnSpanFull(),

                Toggle::make('is_active')
                    ->label('Active (turn off to free up device slot)'),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('device_type')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'mobile' ? 'info' : 'gray'),
                TextColumn::make('device_name')
                    ->label('Device')
                    ->limit(40),
                TextColumn::make('ip_address')
                    ->label('IP'),
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('last_active_at')
                    ->label('Last Active')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('device_type')
                    ->options(['mobile' => 'Mobile', 'desktop' => 'Desktop']),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->label('Remove Device'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('last_active_at', 'desc');
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
            'index' => ListDevices::route('/'),
            'edit' => EditDevice::route('/{record}/edit'),
        ];
    }
}
