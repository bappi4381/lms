<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use BackedEnum;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ManageHeaderFooter extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAlt;
    protected static ?string $navigationLabel = 'Footer Settings';
    protected static ?string $title = 'Footer Management (CRM)';
    protected static ?int $navigationSort = 6;
    protected string $view = 'filament.pages.manage-header-footer';

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
                Tabs::make('Footer Settings')
                    ->tabs([

                        // ── Tab 1: Footer Brand & About ──
                        Tab::make('Footer Brand (ব্র্যান্ড ও পরিচিতি)')
                            ->icon('heroicon-o-building-office-2')
                            ->schema([
                                Textarea::make('brand_description_bn')
                                    ->label('Footer Description (বাংলা)')
                                    ->placeholder('যেমন: বাংলাদেশের শিক্ষার্থীদের জন্য মানসম্মত অনলাইন শিক্ষা...')
                                    ->rows(3),

                                Textarea::make('brand_description_en')
                                    ->label('Footer Description (English)')
                                    ->placeholder('e.g. Quality online education for Bangladeshi learners...')
                                    ->rows(3),
                            ]),

                        // ── Tab 2: Footer Navigation Columns ──
                        Tab::make('Footer Navigation (ফুটার কলামসমূহ)')
                            ->icon('heroicon-o-rectangle-stack')
                            ->schema([
                                Repeater::make('footer_columns')
                                    ->label('Footer Link Columns (ফুটার লিংক কলাম)')
                                    ->schema([
                                        TextInput::make('column_title_bn')
                                            ->label('Column Header (বাংলা)')
                                            ->placeholder('যেমন: শেখা / প্ল্যাটফর্ম')
                                            ->required(),

                                        TextInput::make('column_title_en')
                                            ->label('Column Header (English)')
                                            ->placeholder('e.g. Learn / Platform')
                                            ->required(),

                                        Repeater::make('links')
                                            ->label('Column Items (কলামের লিংকসমূহ)')
                                            ->schema([
                                                TextInput::make('label_bn')->label('Link Label (বাংলা)')->required(),
                                                TextInput::make('label_en')->label('Link Label (English)')->required(),
                                                TextInput::make('url')->label('Target URL')->required(),
                                                Toggle::make('is_active')->label('Active')->default(true),
                                            ])
                                            ->columns(2)
                                            ->defaultItems(0)
                                            ->addActionLabel('+ Add Column Link')
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2)
                                    ->defaultItems(0)
                                    ->addActionLabel('+ Add Footer Column'),
                            ]),

                        // ── Tab 3: Contact & Social ──
                        Tab::make('Contact & Social (যোগাযোগ ও সোশাল)')
                            ->icon('heroicon-o-phone')
                            ->schema([
                                TextInput::make('contact_email')
                                    ->label('Support Email')
                                    ->email()
                                    ->placeholder('support@secondshiftbd.com'),

                                TextInput::make('contact_phone_bn')
                                    ->label('Phone Number (বাংলা)')
                                    ->placeholder('+(৮৮০) ১২৩৪ ৫৬৭৮৯০'),

                                TextInput::make('contact_phone_en')
                                    ->label('Phone Number (English)')
                                    ->placeholder('+(880) 1234 567890'),

                                TextInput::make('contact_address_bn')
                                    ->label('Address (বাংলা)')
                                    ->placeholder('ঢাকা, বাংলাদেশ'),

                                TextInput::make('contact_address_en')
                                    ->label('Address (English)')
                                    ->placeholder('Dhaka, Bangladesh'),

                                Repeater::make('social_links')
                                    ->label('Social Media Links (সোশ্যাল মিডিয়া লিংক)')
                                    ->schema([
                                        Select::make('platform')
                                            ->label('Social Platform')
                                            ->options([
                                                'facebook'  => 'Facebook',
                                                'youtube'   => 'YouTube',
                                                'instagram' => 'Instagram',
                                                'linkedin'  => 'LinkedIn',
                                                'twitter'   => 'Twitter / X',
                                                'tiktok'    => 'TikTok',
                                            ])
                                            ->required(),

                                        TextInput::make('url')
                                            ->label('Profile / Page URL')
                                            ->url()
                                            ->required(),

                                        Toggle::make('is_active')
                                            ->label('Active')
                                            ->default(true),
                                    ])
                                    ->columns(3)
                                    ->defaultItems(0)
                                    ->addActionLabel('+ Add Social Link')
                                    ->columnSpanFull(),
                            ]),

                        // ── Tab 4: Copyright Bar ──
                        Tab::make('Copyright (কপিরাইট নোটিশ)')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                TextInput::make('copyright_text_bn')
                                    ->label('Copyright Notice (বাংলা)')
                                    ->placeholder('© ২০২৬ SecondShiftBD. সর্বস্বত্ব সংরক্ষিত।'),

                                TextInput::make('copyright_text_en')
                                    ->label('Copyright Notice (English)')
                                    ->placeholder('© 2026 SecondShiftBD. All rights reserved.'),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();
        SiteSetting::getSettings()->update($state);
        Notification::make()
            ->title('Footer Settings সফলভাবে আপডেট করা হয়েছে!')
            ->success()
            ->send();
    }
}
