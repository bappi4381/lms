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
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ManageTestimonialsSection extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;
    protected static ?string $navigationLabel = 'Testimonials Section';
    protected static ?string $title = 'Testimonials Section Management (CRM)';
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
                Section::make('Testimonials Section Headings')
                    ->description('Set eyebrow label and title for Testimonials section.')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('testi_eyebrow_bn')
                                ->label('Eyebrow Text (বাংলা)')
                                ->placeholder('যেমন: শিক্ষার্থীদের মতামত')
                                ->required(),

                            TextInput::make('testi_eyebrow_en')
                                ->label('Eyebrow Text (English)')
                                ->placeholder('e.g. Testimonials')
                                ->required(),

                            TextInput::make('testi_title_bn')
                                ->label('Section Title (বাংলা)')
                                ->placeholder('যেমন: আমাদের শিক্ষার্থীরা কী বলেন')
                                ->required(),

                            TextInput::make('testi_title_en')
                                ->label('Section Title (English)')
                                ->placeholder('e.g. What Our Students Say')
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
            ->title('Testimonials Section সফলভাবে আপডেট হয়েছে!')
            ->success()
            ->send();
    }
}
