<x-filament-panels::page>
    <form wire:submit="save" class="space-y-6">
        {{ $this->form }}
        <div class="flex items-center gap-4 pt-4">
            <x-filament::button type="submit" size="lg" icon="heroicon-m-check">
                Testimonials Section সেভ করুন / Save Testimonials Section
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
