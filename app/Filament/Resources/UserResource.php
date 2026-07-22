<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';


    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'User';

    protected static ?string $pluralModelLabel = 'Users';

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
            Forms\Components\Section::make('User Information')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Full Name')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('email')
                        ->label('Email Address')
                        ->email()
                        ->required()
                        ->maxLength(255)
                        ->unique(User::class, 'email', ignoreRecord: true),
                ])->columns(2),

            Forms\Components\Section::make('Password')
                ->schema([
                    Forms\Components\TextInput::make('password')
                        ->label('Password')
                        ->password()
                        ->revealable()
                        ->dehydrateStateUsing(fn($state) => Hash::make($state))
                        ->dehydrated(fn($state) => filled($state))
                        ->required(fn(string $operation): bool => $operation === 'create')
                        ->minLength(8)
                        ->helperText('Edit করার সময় blank রাখলে password পরিবর্তন হবে না।'),
                ]),

            Forms\Components\Section::make('Roles')
                ->description('এই user কে কোন কোন role দেওয়া হবে select করুন।')
                ->schema([
                    Forms\Components\CheckboxList::make('roles')
                        ->label('')
                        ->relationship('roles', 'name')
                        ->options(
                            Role::orderBy('name')->pluck('name', 'id')->toArray()
                        )
                        ->columns(3)
                        ->bulkToggleable(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Email copied!'),

                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Roles')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'admin' => 'danger',
                        'instructor' => 'warning',
                        'student' => 'success',
                        default => 'gray',
                    })
                    ->separator(','),

                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('Verified')
                    ->boolean()
                    ->getStateUsing(fn(User $record): bool => $record->email_verified_at !== null)
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-circle'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Joined')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('roles')
                    ->label('Role')
                    ->relationship('roles', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make()
                    ->before(function (User $record) {
                        // নিজেকে delete করতে পারবে না
                        if ($record->id === auth()->id()) {
                            \Filament\Notifications\Notification::make()
                                ->title('Cannot delete yourself')
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
