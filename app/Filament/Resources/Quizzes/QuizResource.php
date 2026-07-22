<?php

namespace App\Filament\Resources\Quizzes;

use App\Filament\Resources\Quizzes\Pages\CreateQuiz;
use App\Filament\Resources\Quizzes\Pages\EditQuiz;
use App\Filament\Resources\Quizzes\Pages\ListQuizzes;
use App\Models\Lesson;
use App\Models\Quiz;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class QuizResource extends Resource
{
    protected static ?string $model = Quiz::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Quizzes';

    protected static ?int $navigationSort = 4;

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
                    ->label('Lesson (type = quiz)')
                    ->options(fn ($record) => Lesson::query()
                        ->where('type', 'quiz')
                        ->when($record, fn ($q) => $q->orWhere('id', $record->lesson_id))
                        ->with('module.course')
                        ->get()
                        ->mapWithKeys(fn ($lesson) => [
                            $lesson->id => ($lesson->module?->course?->title ? $lesson->module->course->title.' — ' : '').($lesson->module?->title ? $lesson->module->title.' — ' : '').$lesson->title,
                        ]))
                    ->searchable()
                    ->preload()
                    ->required()
                    ->helperText('প্রথমে Course এর ভেতরে Module → Lesson (type=quiz) তৈরি করুন, তারপর এখানে সেটি সিলেক্ট করুন।'),

                TextInput::make('title')
                    ->required()
                    ->maxLength(255),

                Textarea::make('instructions')
                    ->rows(2)
                    ->columnSpanFull(),

                TextInput::make('pass_percentage')
                    ->numeric()
                    ->default(60)
                    ->suffix('%')
                    ->required(),

                TextInput::make('time_limit_minutes')
                    ->numeric()
                    ->label('Time Limit (minutes, blank = unlimited)'),

                Repeater::make('questions')
                    ->relationship()
                    ->schema([
                        Textarea::make('question')
                            ->required()
                            ->rows(2)
                            ->columnSpanFull(),

                        Select::make('type')
                            ->options([
                                'single' => 'Single Choice',
                                'multiple' => 'Multiple Choice',
                                'true_false' => 'True / False',
                            ])
                            ->default('single')
                            ->required(),

                        TextInput::make('points')
                            ->numeric()
                            ->default(1)
                            ->required(),

                        TextInput::make('order')
                            ->numeric()
                            ->default(0),

                        Repeater::make('choices')
                            ->relationship()
                            ->schema([
                                TextInput::make('choice_text')
                                    ->label('Choice')
                                    ->required(),
                                Toggle::make('is_correct')
                                    ->label('Correct?'),
                                TextInput::make('order')
                                    ->numeric()
                                    ->default(0),
                            ])
                            ->columns(3)
                            ->defaultItems(2)
                            ->addActionLabel('+ Add Choice')
                            ->columnSpanFull(),
                    ])
                    ->columns(3)
                    ->addActionLabel('+ Add Question')
                    ->collapsible()
                    ->columnSpanFull(),
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
                    ->label('Lesson')
                    ->limit(30),
                TextColumn::make('lesson.module.course.title')
                    ->label('Course')
                    ->limit(25),
                TextColumn::make('questions_count')
                    ->counts('questions')
                    ->label('Questions')
                    ->badge(),
                TextColumn::make('pass_percentage')
                    ->suffix('%')
                    ->label('Pass %'),
                TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListQuizzes::route('/'),
            'create' => CreateQuiz::route('/create'),
            'edit' => EditQuiz::route('/{record}/edit'),
        ];
    }
}
