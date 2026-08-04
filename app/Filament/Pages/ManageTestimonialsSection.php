<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ManageTestimonialsSection extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;
    protected static ?string $navigationLabel = 'Testimonials Section';
    protected static ?string $title = 'Testimonials Section (CRM)';
    protected static ?int $navigationSort = 5;
    protected string $view = 'filament.pages.manage-testimonials-section';

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
                TextInput::make('testi_eyebrow_en')->label('Eyebrow Text (English)')->placeholder('e.g. Testimonials'),
                TextInput::make('testi_eyebrow_bn')->label('Eyebrow Text (বাংলা)')->placeholder('যেমন: শিক্ষার্থীদের মতামত'),
                TextInput::make('testi_title_en')->label('Section Title (English)')->placeholder('e.g. What Our Students Say'),
                TextInput::make('testi_title_bn')->label('Section Title (বাংলা)')->placeholder('যেমন: আমাদের শিক্ষার্থীরা কী বলেন'),
            ])
            ->columns(2)
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();
        SiteSetting::getSettings()->update($state);
        Notification::make()->title('Testimonials Section সফলভাবে আপডেট হয়েছে!')->success()->send();
    }
}
