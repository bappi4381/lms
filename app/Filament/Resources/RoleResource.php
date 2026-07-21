<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';


    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Role';

    protected static ?string $pluralModelLabel = 'Roles';

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
            Forms\Components\Section::make('Role Details')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Role Name')
                        ->required()
                        ->maxLength(125)
                        ->unique(ignoreRecord: true)
                        ->placeholder('e.g. editor, moderator'),

                    Forms\Components\TextInput::make('guard_name')
                        ->label('Guard')
                        ->default('web')
                        ->required()
                        ->maxLength(125),
                ])->columns(2),

            Forms\Components\Section::make('Permissions')
                ->description('এই role কে কোন কোন permission দেওয়া হবে select করুন।')
                ->schema([
                    Forms\Components\CheckboxList::make('permissions')
                        ->label('')
                        ->relationship('permissions', 'name')
                        ->options(
                            Permission::orderBy('name')->pluck('name', 'id')->toArray()
                        )
                        ->columns(3)
                        ->searchable()
                        ->bulkToggleable(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Role Name')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin'      => 'danger',
                        'instructor' => 'warning',
                        'student'    => 'success',
                        default      => 'gray',
                    }),

                Tables\Columns\TextColumn::make('permissions_count')
                    ->label('Permissions')
                    ->counts('permissions')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('users_count')
                    ->label('Users')
                    ->counts('users')
                    ->badge()
                    ->color('gray'),

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
            ->filters([])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make()
                    ->before(function (Role $record) {
                        // core role গুলো delete হতে দেওয়া হবে না
                        if (in_array($record->name, ['admin', 'instructor', 'student'])) {
                            \Filament\Notifications\Notification::make()
                                ->title('Cannot delete core role')
                                ->body("The \"{$record->name}\" role is a core role and cannot be deleted.")
                                ->danger()
                                ->send();

                            $this->halt();
                        }
                    }),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit'   => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
