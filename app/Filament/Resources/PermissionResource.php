<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermissionResource\Pages;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Spatie\Permission\Models\Permission;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-key';


    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Permission';

    protected static ?string $pluralModelLabel = 'Permissions';

    /**
     * শুধু admin role-এ থাকা user দেখতে পাবে।
     */
    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }

    public static function getNavigationGroup(): string
    {
        return 'Access Control';
    }

    public static function form(Schema $form): Schema
    {
        return $form->schema([
            Forms\Components\Section::make('Permission Details')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Permission Name')
                        ->required()
                        ->maxLength(125)
                        ->unique(ignoreRecord: true)
                        ->placeholder('e.g. course.create, user.delete')
                        ->helperText('Convention: resource.action (e.g. course.viewAny, course.create)'),

                    Forms\Components\TextInput::make('guard_name')
                        ->label('Guard')
                        ->default('web')
                        ->required()
                        ->maxLength(125),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Permission')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Copied!')
                    ->fontFamily('mono'),

                Tables\Columns\TextColumn::make('roles_count')
                    ->label('Roles')
                    ->counts('roles')
                    ->badge()
                    ->color('warning'),

                Tables\Columns\TextColumn::make('guard_name')
                    ->label('Guard')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('guard_name')
                    ->label('Guard')
                    ->options([
                        'web' => 'Web',
                        'api' => 'API',
                    ]),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name')
            ->groups([
                Tables\Grouping\Group::make('name')
                    ->label('Resource')
                    ->getTitleFromRecordUsing(
                        fn (Permission $record): string => explode('.', $record->name)[0] ?? $record->name
                    ),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPermissions::route('/'),
            'create' => Pages\CreatePermission::route('/create'),
            'edit'   => Pages\EditPermission::route('/{record}/edit'),
        ];
    }
}
