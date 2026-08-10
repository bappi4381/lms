<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use BackedEnum;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ManageHeroSection extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;
    protected static ?string $navigationLabel = 'Hero Section';
    protected static ?string $title = 'Hero Section Management (CRM)';
    protected static ?int $navigationSort = 1;
    protected string $view = 'filament.pages.manage-hero-section';

    public ?array $data = [];

    public static function getNavigationGroup(): ?string
    {
        return 'CRM';
    }

    public function mount(): void
    {
        $settings = SiteSetting::getSettings();
        $this->form->fill($settings->toArray());
    }

    public function form(Schema|Form $form): Schema|Form
    {
        return $form
            ->schema([
                Section::make('Hero Headings & Eyebrow')
                    ->description('Configure the primary headline and eyebrow tags shown at the top of the homepage.')
                    ->icon('heroicon-o-sparkles')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('hero_eyebrow_bn')
                                ->label('Eyebrow Text (বাংলা)')
                                ->placeholder('যেমন: আমাদের শিক্ষকরা কীভাবে শেখান দেখুন')
                                ->required(),

                            TextInput::make('hero_eyebrow_en')
                                ->label('Eyebrow Text (English)')
                                ->placeholder('e.g. See how our teachers learn')
                                ->required(),

                            TextInput::make('hero_title_bn')
                                ->label('Main Title (বাংলা)')
                                ->placeholder('যেমন: আমরা দিচ্ছি')
                                ->required(),

                            TextInput::make('hero_title_en')
                                ->label('Main Title (English)')
                                ->placeholder('e.g. We provide')
                                ->required(),

                            TextInput::make('hero_highlight_bn')
                                ->label('Highlighted Word (বাংলা)')
                                ->placeholder('যেমন: মজার ই-কোর্স')
                                ->required(),

                            TextInput::make('hero_highlight_en')
                                ->label('Highlighted Word (English)')
                                ->placeholder('e.g. fun e-course')
                                ->required(),
                        ]),
                    ]),

                Section::make('Hero Description')
                    ->description('Subtitle and introduction paragraph.')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Grid::make(2)->schema([
                            Textarea::make('hero_description_bn')
                                ->label('Description (বাংলা)')
                                ->placeholder('যেমন: নতুন দক্ষতা শিখুন সহজে — ছোট ছোট পাঠ...')
                                ->rows(3)
                                ->required(),

                            Textarea::make('hero_description_en')
                                ->label('Description (English)')
                                ->placeholder('e.g. Learn new skills the way that actually sticks...')
                                ->rows(3)
                                ->required(),
                        ]),
                    ]),

                Section::make('Call to Action Buttons')
                    ->description('Button labels displayed on the Hero CTA area.')
                    ->icon('heroicon-o-cursor-arrow-rays')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('hero_btn_primary_bn')
                                ->label('Primary Button Text (বাংলা)')
                                ->placeholder('যেমন: কোর্স দেখুন')
                                ->required(),

                            TextInput::make('hero_btn_primary_en')
                                ->label('Primary Button Text (English)')
                                ->placeholder('e.g. View Courses')
                                ->required(),

                            TextInput::make('hero_btn_secondary_bn')
                                ->label('Secondary Button Text (বাংলা)')
                                ->placeholder('যেমন: ইন্ট্রো দেখুন')
                                ->required(),

                            TextInput::make('hero_btn_secondary_en')
                                ->label('Secondary Button Text (English)')
                                ->placeholder('e.g. Watch intro')
                                ->required(),
                        ]),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();
        SiteSetting::getSettings()->update($state);
        SiteSetting::clearCache();

        Notification::make()
            ->title('Hero Section সফলভাবে আপডেট হয়েছে!')
            ->success()
            ->send();
    }
}
