<?php

namespace App\Filament\Resources\Courses\Schemas;

use App\Models\Category;
use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CourseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([

                    // ── Step 1: Basic Details ──
                    Step::make('মৌলিক তথ্য')
                        ->description('কোর্সের নাম, ক্যাটাগরি, শিক্ষক ও বিবরণ')
                        ->icon('heroicon-o-document-text')
                        ->schema([
                            TextInput::make('title')
                                ->label('Course Title (কোর্সের নাম)')
                                ->placeholder('যেমন: AI & ML Engineering Bootcamp')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn ($state, callable $set) =>
                                    $set('slug', Str::slug($state))
                                ),

                            TextInput::make('slug')
                                ->label('Slug (ইউআরএল)')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255),

                            Textarea::make('sub_description')
                                ->label('Sub Description (কোর্সের ছোট সারসংক্ষেপ / সাব-টাইটেল)')
                                ->placeholder('যেমন: আপনি যদি একদম বিগিনার হয়ে থাকেন, তাহলে এই কোর্সটি আপনার জন্য...')
                                ->rows(3)
                                ->columnSpanFull(),

                            Select::make('category_id')
                                ->label('Category (ক্যাটাগরি)')
                                ->options(Category::where('is_active', true)->orderBy('order')->pluck('name', 'id'))
                                ->searchable()
                                ->preload()
                                ->placeholder('ক্যাটাগরি নির্বাচন করুন'),

                            Select::make('instructor_id')
                                ->label('Instructor (শিক্ষক)')
                                ->options(User::role('instructor')->pluck('name', 'id'))
                                ->searchable()
                                ->preload()
                                ->nullable()
                                ->placeholder('ইন্সট্রাক্টর নির্বাচন করুন'),

                            RichEditor::make('description')
                                ->label('Description (কোর্সের বিস্তারিত বিবরণ)')
                                ->columnSpanFull(),

                            FileUpload::make('thumbnail')
                                ->label('Thumbnail Image (কভার ছবি)')
                                ->image()
                                ->disk('public')
                                ->directory('course-thumbnails')
                                ->columnSpanFull(),
                        ])->columns(2),

                    // ── Step 2: Pricing & Schedule ──
                    Step::make('ফি ও শিডিউল')
                        ->description('মূল্য, ডিসকাউন্ট, ব্যাচ ও আসন সংখ্যা')
                        ->icon('heroicon-o-banknotes')
                        ->schema([
                            TextInput::make('price')
                                ->label('Price (কোর্স ফি BDT)')
                                ->required()
                                ->numeric()
                                ->default(0)
                                ->prefix('৳'),

                            TextInput::make('discount_price')
                                ->label('Discount Price (ছাড় পরবর্তী মূল্য BDT)')
                                ->numeric()
                                ->prefix('৳'),

                            TextInput::make('batch_number')
                                ->label('Batch Number (ব্যাচ নম্বর)')
                                ->numeric()
                                ->default(1),

                            TextInput::make('seats_total')
                                ->label('Total Seats (সর্বমোট সিট সংখ্যা)')
                                ->numeric()
                                ->default(0),

                            TextInput::make('seats_available')
                                ->label('Available Seats (বাকি সিট - ম্যানুয়াল)')
                                ->numeric()
                                ->default(0),

                            DateTimePicker::make('starts_at')
                                ->label('Starts At (কোর্স শুরুর তারিখ ও সময়)'),

                            TextInput::make('live_class_schedule')
                                ->label('Live Class Schedule (লাইভ ক্লাসের সময়)')
                                ->placeholder('যেমন: রাত ৮:০০-১০:৩০ (সোম, বুধ)'),

                            TextInput::make('support_class_schedule')
                                ->label('Support Class Schedule (সাপোর্ট ক্লাসের সময়)')
                                ->placeholder('যেমন: রাত ৮:০০-১০:০০ (রবি, মঙ্গল)'),

                            Toggle::make('is_published')
                                ->label('Published (ওয়েবসাইটে প্রকাশ করুন)')
                                ->default(true)
                                ->required(),
                        ])->columns(2),

                    // ── Step 3: Landing Page & Features ──
                    Step::make('ল্যান্ডিং পেজ ও সুবিধা')
                        ->description('প্রোমো ভিডিও, কোর্সের সুবিধা ও টুলস')
                        ->icon('heroicon-o-sparkles')
                        ->schema([
                            TextInput::make('video_url')
                                ->label('Promo Video URL (ইউটিউব ডেমো ভিডিও লিংক)')
                                ->placeholder('https://www.youtube.com/watch?v=...')
                                ->url()
                                ->columnSpanFull(),

                            Repeater::make('course_includes')
                                ->label('এই কোর্সে আপনি পাচ্ছেন (সুবিধাসমূহ)')
                                ->helperText('যেমন: "৪০+ লাইভ ক্লাস", "রেকর্ডেড ভিডিও", "সার্টিফিকেট"')
                                ->simple(
                                    TextInput::make('item')
                                        ->placeholder('যেমন: ৪০+ লাইভ ক্লাস')
                                        ->required()
                                )
                                ->defaultItems(0)
                                ->addActionLabel('+ আরো সুবিধা যোগ করুন')
                                ->columnSpanFull(),

                            Repeater::make('key_features')
                                ->label('Key Features (মূল আকর্ষণ)')
                                ->simple(
                                    TextInput::make('feature')->required()
                                )
                                ->defaultItems(0)
                                ->addActionLabel('+ ফিচার যোগ করুন')
                                ->columnSpanFull(),

                            Repeater::make('tools')
                                ->label('Tools & Technologies (যেসব টুলস শিখবেন)')
                                ->simple(
                                    TextInput::make('tool')->required()
                                )
                                ->defaultItems(0)
                                ->addActionLabel('+ টুল যোগ করুন')
                                ->columnSpanFull(),

                            Repeater::make('career_opportunities')
                                ->label('Career Opportunities (ক্যারিয়ার সুযোগ)')
                                ->simple(
                                    TextInput::make('job_role')->required()
                                )
                                ->defaultItems(0)
                                ->addActionLabel('+ জব রোল যোগ করুন')
                                ->columnSpanFull(),
                        ]),

                    // ── Step 4: Projects & FAQs ──
                    Step::make('প্রজেক্ট ও সাধারণ জিজ্ঞাসা')
                        ->description('প্র্যাকটিক্যাল প্রজেক্ট ও FAQ')
                        ->icon('heroicon-o-academic-cap')
                        ->schema([
                            Repeater::make('projects')
                                ->label('Projects (যেসব প্রজেক্ট করবেন)')
                                ->schema([
                                    TextInput::make('title')
                                        ->label('Project Title')
                                        ->required(),
                                    FileUpload::make('image')
                                        ->label('Project Preview Image')
                                        ->image()
                                        ->disk('public')
                                        ->directory('course-projects')
                                        ->required(),
                                ])
                                ->columns(2)
                                ->columnSpanFull(),

                            Repeater::make('faqs')
                                ->label('FAQs (সচরাচর জিজ্ঞাসা ও উত্তর)')
                                ->schema([
                                    TextInput::make('question')
                                        ->label('Question (প্রশ্ন)')
                                        ->required()
                                        ->columnSpanFull(),
                                    Textarea::make('answer')
                                        ->label('Answer (উত্তর)')
                                        ->required()
                                        ->columnSpanFull(),
                                ])
                                ->columnSpanFull(),
                        ]),

                ])->columnSpanFull(),
            ]);
    }
}
