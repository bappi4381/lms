<?php

namespace App\Filament\Resources\Enrollments\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Schema;

class EnrollmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                
                Select::make('course_id')
                    ->relationship('course', 'title')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('payment_status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                        'refunded' => 'Refunded',
                    ])
                    ->default('pending')
                    ->required(),

                TextInput::make('amount_paid')
                    ->numeric()
                    ->default(0)
                    ->prefix('৳'),

                TextInput::make('transaction_id')
                    ->maxLength(255),

                DateTimePicker::make('enrolled_at')
                    ->default(now()),
            ]);
    }
}
