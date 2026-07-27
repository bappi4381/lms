<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-neu-heading leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-neu-base">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Quick Links -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <a href="{{ route('dashboard') }}" class="neu-card p-4 text-center hover:shadow-neu-raised transition-shadow duration-200">
                    <div class="text-2xl mb-1">🎓</div>
                    <div class="text-sm font-bold text-neu-heading">আমার কোর্স</div>
                </a>
                <a href="{{ route('profile.certificates') }}" class="neu-card p-4 text-center hover:shadow-neu-raised transition-shadow duration-200">
                    <div class="text-2xl mb-1">🏆</div>
                    <div class="text-sm font-bold text-neu-heading">সার্টিফিকেট</div>
                </a>
                <a href="{{ route('profile.payment-history') }}" class="neu-card p-4 text-center hover:shadow-neu-raised transition-shadow duration-200">
                    <div class="text-2xl mb-1">💳</div>
                    <div class="text-sm font-bold text-neu-heading">পেমেন্ট হিস্টোরি</div>
                </a>
                <a href="{{ route('devices.index') }}" class="neu-card p-4 text-center hover:shadow-neu-raised transition-shadow duration-200">
                    <div class="text-2xl mb-1">📱</div>
                    <div class="text-sm font-bold text-neu-heading">আমার ডিভাইস</div>
                </a>
            </div>

            <div class="neu-panel">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="neu-panel">
                <div class="max-w-xl">
                    @include('profile.partials.phone-verification-form')
                </div>
            </div>

            <div class="neu-panel">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="neu-panel">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
