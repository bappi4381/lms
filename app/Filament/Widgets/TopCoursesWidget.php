<?php

namespace App\Filament\Widgets;

use App\Models\Course;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopCoursesWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('সর্বোচ্চ পারফরমিং কোর্স')
            ->query(
                Course::query()
                    ->withCount(['enrollments' => fn ($q) => $q->where('payment_status', 'paid')])
                    ->orderByDesc('enrollments_count')
            )
            ->columns([
                TextColumn::make('title')
                    ->label('কোর্স')
                    ->limit(40)
                    ->weight('bold'),
                TextColumn::make('enrollments_count')
                    ->label('পেইড এনরোলমেন্ট')
                    ->badge()
                    ->color('success'),
                TextColumn::make('price')
                    ->label('মূল্য')
                    ->formatStateUsing(fn ($state) => '৳'.number_format($state, 0)),
                TextColumn::make('revenue')
                    ->label('আনুমানিক আয়')
                    ->state(fn (Course $record) => '৳'.number_format($record->enrollments_count * (float) ($record->discount_price ?: $record->price), 0)),
            ])
            ->paginated([5, 10, 25]);
    }
}
