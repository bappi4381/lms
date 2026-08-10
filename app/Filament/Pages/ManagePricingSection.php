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

class ManagePricingSection extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyDollar;
    protected static ?string $navigationLabel = 'Pricing Section';
    protected static ?string $title = 'Pricing Section Management (CRM)';
    protected static ?int $navigationSort = 4;
    protected string $view = 'filament.pages.manage-pricing-section';

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
                Section::make('Pricing Section Headings')
                    ->description('Set eyebrow label and title for Pricing Section on the homepage.')
                    ->icon('heroicon-o-currency-dollar')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('pricing_eyebrow_bn')
                                ->label('Eyebrow Text (বাংলা)')
                                ->placeholder('যেমন: মূল্য পরিকল্পনা')
                                ->required(),

                            TextInput::make('pricing_eyebrow_en')
                                ->label('Eyebrow Text (English)')
                                ->placeholder('e.g. Pricing')
                                ->required(),

                            TextInput::make('pricing_title_bn')
                                ->label('Section Title (বাংলা)')
                                ->placeholder('যেমন: প্রাইসিং প্ল্যান')
                                ->required(),

                            TextInput::make('pricing_title_en')
                                ->label('Section Title (English)')
                                ->placeholder('e.g. Pricing Plan')
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
            ->title('Pricing Section সফলভাবে আপডেট হয়েছে!')
            ->success()
            ->send();
    }
}
