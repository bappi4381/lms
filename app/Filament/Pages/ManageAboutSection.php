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
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ManageAboutSection extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInformationCircle;
    protected static ?string $navigationLabel = 'About Section';
    protected static ?string $title = 'About Section (CRM)';
    protected static ?int $navigationSort = 2;
    protected string $view = 'filament.pages.manage-about-section';

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
                TextInput::make('about_eyebrow_en')->label('Eyebrow Text (English)')->placeholder('e.g. About us'),
                TextInput::make('about_eyebrow_bn')->label('Eyebrow Text (বাংলা)')->placeholder('যেমন: আমাদের সম্পর্কে'),
                TextInput::make('about_title_en')->label('Title (English)')->placeholder('e.g. Founded in 2015'),
                TextInput::make('about_title_bn')->label('Title (বাংলা)')->placeholder('যেমন: ২০১৫ সালে প্রতিষ্ঠিত'),
                Textarea::make('about_description_en')->label('Description (English)')->rows(4),
                Textarea::make('about_description_bn')->label('Description (বাংলা)')->rows(4),
                TextInput::make('about_btn_en')->label('Button Text (English)')->placeholder('e.g. Learn more'),
                TextInput::make('about_btn_bn')->label('Button Text (বাংলা)')->placeholder('যেমন: আরও জানুন'),
            ])
            ->columns(2)
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();
        SiteSetting::getSettings()->update($state);
        Notification::make()->title('About Section সফলভাবে আপডেট হয়েছে!')->success()->send();
    }
}
