<?php

namespace App\Filament\Resources\Assignments;

use App\Filament\Resources\Assignments\Pages\CreateAssignment;
use App\Filament\Resources\Assignments\Pages\EditAssignment;
use App\Filament\Resources\Assignments\Pages\ListAssignments;
use App\Filament\Resources\Assignments\RelationManagers\SubmissionsRelationManager;
use App\Models\Assignment;
use App\Models\Lesson;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AssignmentResource extends Resource
{
    protected static ?string $model = Assignment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static ?string $navigationLabel = 'Assignments';

    protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getNavigationGroup(): ?string
    {
        return 'Course Management';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('lesson_id')
                    ->label('Lesson (type = assignment)')
                    ->options(fn ($record) => Lesson::query()
                        ->where('type', 'assignment')
                        ->when($record, fn ($q) => $q->orWhere('id', $record->lesson_id))
                        ->with('module.course')
                        ->get()
                        ->mapWithKeys(fn ($lesson) => [
                            $lesson->id => ($lesson->module?->course?->title ? $lesson->module->course->title.' — ' : '').($lesson->module?->title ? $lesson->module->title.' — ' : '').$lesson->title,
                        ]))
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('title')
                    ->required()
                    ->maxLength(255),

                Textarea::make('instructions')
                    ->rows(4)
                    ->columnSpanFull(),

                TextInput::make('max_points')
                    ->numeric()
                    ->default(100)
                    ->required(),

                DateTimePicker::make('due_at')
                    ->label('Due Date'),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('lesson.title')
                    ->label('Lesson'),
                TextColumn::make('lesson.module.course.title')
                    ->label('Course')
                    ->limit(25),
                TextColumn::make('submissions_count')
                    ->counts('submissions')
                    ->label('Submissions')
                    ->badge(),
                TextColumn::make('max_points')
                    ->label('Max Points'),
                TextColumn::make('due_at')
                    ->dateTime('d M Y')
                    ->placeholder('—'),
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
            ]);
    }

    public static function getRelations(): array
    {
        return [
            SubmissionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAssignments::route('/'),
            'create' => CreateAssignment::route('/create'),
            'edit' => EditAssignment::route('/{record}/edit'),
        ];
    }
}
