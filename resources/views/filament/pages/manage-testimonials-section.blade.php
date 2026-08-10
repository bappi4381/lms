<x-filament-panels::page>
    <form wire:submit="save" class="space-y-6">
        {{ $this->form }}

        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-200 dark:border-gray-800">
            <x-filament::button 
                type="submit" 
                size="lg" 
                icon="heroicon-m-check"
                wire:loading.attr="disabled">
                <span wire:loading.remove>
                    Testimonials Section সেভ করুন / Save Testimonials Section
                </span>
                <span wire:loading>
                    সেভ করা হচ্ছে... / Saving...
                </span>
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
