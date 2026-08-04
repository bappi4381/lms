<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use BackedEnum;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ManageWhyUsSection extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;
    protected static ?string $navigationLabel = 'Why Choose Us';
    protected static ?string $title = 'Why Choose Us Section (CRM)';
    protected static ?int $navigationSort = 3;
    protected string $view = 'filament.pages.manage-whyus-section';

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
                TextInput::make('whyus_eyebrow_en')->label('Eyebrow Text (English)')->placeholder('e.g. Why choose us'),
                TextInput::make('whyus_eyebrow_bn')->label('Eyebrow Text (বাংলা)')->placeholder('যেমন: আমাদের বেছে নেওয়ার কারণ'),
                TextInput::make('whyus_title_en')->label('Title (English)')->placeholder('e.g. Our courses are designed to be immersive!'),
                TextInput::make('whyus_title_bn')->label('Title (বাংলা)')->placeholder('যেমন: আমাদের কোর্সগুলো ইমার্সিভভাবে তৈরি!'),

                Repeater::make('whyus_cards')
                    ->label('Feature Cards (ফিচার কার্ডসমূহ)')
                    ->schema([
                        TextInput::make('title_en')->label('Card Title (English)')->required(),
                        TextInput::make('title_bn')->label('Card Title (বাংলা)')->required(),
                        Textarea::make('desc_en')->label('Description (English)')->rows(2)->required(),
                        Textarea::make('desc_bn')->label('Description (বাংলা)')->rows(2)->required(),
                    ])
                    ->columns(2)
                    ->addActionLabel('+ Add Feature Card')
                    ->defaultItems(0)
                    ->columnSpanFull(),
            ])
            ->columns(2)
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();
        SiteSetting::getSettings()->update($state);
        Notification::make()->title('Why Choose Us Section সফলভাবে আপডেট হয়েছে!')->success()->send();
    }
}
