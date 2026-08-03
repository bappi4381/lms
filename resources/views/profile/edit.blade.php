<x-app-layout>
    <div class="min-h-screen bg-[var(--surface-canvas)] py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-6 items-start">

                {{-- LEFT SIDEBAR --}}
                <x-user-sidebar />

                {{-- MAIN CONTENT --}}
                <div class="flex-1 w-full space-y-5">
                    <div class="bg-[var(--surface-default)] border border-[var(--outline)] rounded-2xl p-6 shadow-elevation-1">
                        <div class="max-w-xl">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    <div class="bg-[var(--surface-default)] border border-[var(--outline)] rounded-2xl p-6 shadow-elevation-1">
                        <div class="max-w-xl">
                            @include('profile.partials.phone-verification-form')
                        </div>
                    </div>

                    <div class="bg-[var(--surface-default)] border border-[var(--outline)] rounded-2xl p-6 shadow-elevation-1">
                        <div class="max-w-xl">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                    <div class="bg-[var(--surface-default)] border border-[var(--outline)] rounded-2xl p-6 shadow-elevation-1">
                        <div class="max-w-xl">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
