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
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
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

                    // ── Step 1: Basic Details (Multilingual) ──
                    Step::make('মৌলিক তথ্য')
                        ->description('কোর্সের নাম, ক্যাটাগরি, শিক্ষক ও বিবরণ (EN / BN)')
                        ->icon('heroicon-o-document-text')
                        ->schema([
                            Tabs::make('Course Titles & Details')
                                ->tabs([
                                    Tab::make('English Content')
                                        ->icon('heroicon-o-language')
                                        ->schema([
                                            TextInput::make('title_en')
                                                ->label('Course Title (English)')
                                                ->placeholder('e.g. AI & ML Engineering Bootcamp')
                                                ->required()
                                                ->maxLength(255)
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                                    if (! $get('slug')) {
                                                        $set('slug', Str::slug((string) $state));
                                                    }
                                                    if (! $get('title')) {
                                                        $set('title', $state);
                                                    }
                                                }),

                                            Textarea::make('sub_description_en')
                                                ->label('Sub Description (English)')
                                                ->placeholder('Short summary or tagline in English...')
                                                ->rows(3),

                                            RichEditor::make('description_en')
                                                ->label('Full Description (English)'),
                                        ]),

                                    Tab::make('বাংলা কন্টেন্ট')
                                        ->icon('heroicon-o-language')
                                        ->schema([
                                            TextInput::make('title_bn')
                                                ->label('Course Title (বাংলা)')
                                                ->placeholder('যেমন: এআই এবং মেশিন লার্নিং বুটক্যাম্প')
                                                ->required()
                                                ->maxLength(255)
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                                    if (! $get('title')) {
                                                        $set('title', $state);
                                                    }
                                                }),

                                            Textarea::make('sub_description_bn')
                                                ->label('Sub Description (বাংলা)')
                                                ->placeholder('কোর্সের ছোট সারসংক্ষেপ বা সাব-টাইটেল বাংলায়...')
                                                ->rows(3),

                                            RichEditor::make('description_bn')
                                                ->label('Full Description (বাংলা)'),
                                        ]),
                                ])->columnSpanFull(),

                            TextInput::make('title')
                                ->label('Legacy Title (auto-synced)')
                                ->hidden(),

                            TextInput::make('slug')
                                ->label('Slug (ইউআরএল)')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255),

                            Select::make('category_id')
                                ->label('Category (ক্যাটাগরি)')
                                ->options(fn () => Category::where('is_active', true)->orderBy('order')->get()->mapWithKeys(fn ($cat) => [$cat->id => "{$cat->name_en} / {$cat->name_bn}"]))
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

                    // ── Step 3: Landing Page & Features (EN & BN) ──
                    Step::make('ল্যান্ডিং পেজ ও সুবিধা')
                        ->description('প্রোমো ভিডিও, কোর্সের সুবিধা ও টুলস (EN / BN)')
                        ->icon('heroicon-o-sparkles')
                        ->schema([
                            TextInput::make('video_url')
                                ->label('Promo Video URL (ইউটিউব ডেমো ভিডিও লিংক)')
                                ->placeholder('https://www.youtube.com/watch?v=...')
                                ->url()
                                ->columnSpanFull(),

                            Tabs::make('Multilingual Features & Tools')
                                ->tabs([
                                    Tab::make('English Features')
                                        ->schema([
                                            Repeater::make('key_features_en')
                                                ->label('Key Features (English)')
                                                ->simple(
                                                    TextInput::make('feature')->required()
                                                )
                                                ->defaultItems(0)
                                                ->addActionLabel('+ Add English Feature'),

                                            Repeater::make('tools_en')
                                                ->label('Tools & Technologies (English)')
                                                ->simple(
                                                    TextInput::make('tool')->required()
                                                )
                                                ->defaultItems(0)
                                                ->addActionLabel('+ Add English Tool'),
                                        ]),

                                    Tab::make('বাংলা সুবিধা ও টুলস')
                                        ->schema([
                                            Repeater::make('key_features_bn')
                                                ->label('Key Features (বাংলা)')
                                                ->simple(
                                                    TextInput::make('feature')->required()
                                                )
                                                ->defaultItems(0)
                                                ->addActionLabel('+ বাংলা ফিচার যোগ করুন'),

                                            Repeater::make('tools_bn')
                                                ->label('Tools & Technologies (বাংলা)')
                                                ->simple(
                                                    TextInput::make('tool')->required()
                                                )
                                                ->defaultItems(0)
                                                ->addActionLabel('+ বাংলা টুল যোগ করুন'),
                                        ]),
                                ])->columnSpanFull(),

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
                        ]),

                    // ── Step 4: Projects & FAQs (EN & BN) ──
                    Step::make('প্রজেক্ট ও সাধারণ জিজ্ঞাসা')
                        ->description('প্র্যাকটিক্যাল প্রজেক্ট ও FAQ (EN / BN)')
                        ->icon('heroicon-o-academic-cap')
                        ->schema([
                            Tabs::make('Multilingual Projects & FAQs')
                                ->tabs([
                                    Tab::make('English Projects & FAQs')
                                        ->schema([
                                            Repeater::make('projects_en')
                                                ->label('Projects (English)')
                                                ->schema([
                                                    TextInput::make('title')
                                                        ->label('Project Title (EN)')
                                                        ->required(),
                                                    FileUpload::make('image')
                                                        ->label('Project Preview Image')
                                                        ->image()
                                                        ->disk('public')
                                                        ->directory('course-projects')
                                                        ->required(),
                                                ])
                                                ->columns(2),

                                            Repeater::make('faqs_en')
                                                ->label('FAQs (English)')
                                                ->schema([
                                                    TextInput::make('question')
                                                        ->label('Question (EN)')
                                                        ->required()
                                                        ->columnSpanFull(),
                                                    Textarea::make('answer')
                                                        ->label('Answer (EN)')
                                                        ->required()
                                                        ->columnSpanFull(),
                                                ]),
                                        ]),

                                    Tab::make('বাংলা প্রজেক্ট ও FAQ')
                                        ->schema([
                                            Repeater::make('projects_bn')
                                                ->label('Projects (বাংলা)')
                                                ->schema([
                                                    TextInput::make('title')
                                                        ->label('Project Title (BN)')
                                                        ->required(),
                                                    FileUpload::make('image')
                                                        ->label('Project Preview Image')
                                                        ->image()
                                                        ->disk('public')
                                                        ->directory('course-projects')
                                                        ->required(),
                                                ])
                                                ->columns(2),

                                            Repeater::make('faqs_bn')
                                                ->label('FAQs (বাংলা)')
                                                ->schema([
                                                    TextInput::make('question')
                                                        ->label('Question (BN)')
                                                        ->required()
                                                        ->columnSpanFull(),
                                                    Textarea::make('answer')
                                                        ->label('Answer (BN)')
                                                        ->required()
                                                        ->columnSpanFull(),
                                                ]),
                                        ]),
                                ])->columnSpanFull(),
                        ]),

                ])->columnSpanFull(),
            ]);
    }
}
