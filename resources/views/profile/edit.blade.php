<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Quick Links -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <a href="{{ route('dashboard') }}" class="bg-white shadow sm:rounded-lg p-4 text-center hover:bg-gray-50 transition-colors">
                    <div class="text-2xl mb-1">🎓</div>
                    <div class="text-sm font-bold text-gray-800">আমার কোর্স</div>
                </a>
                <a href="{{ route('profile.certificates') }}" class="bg-white shadow sm:rounded-lg p-4 text-center hover:bg-gray-50 transition-colors">
                    <div class="text-2xl mb-1">🏆</div>
                    <div class="text-sm font-bold text-gray-800">সার্টিফিকেট</div>
                </a>
                <a href="{{ route('profile.payment-history') }}" class="bg-white shadow sm:rounded-lg p-4 text-center hover:bg-gray-50 transition-colors">
                    <div class="text-2xl mb-1">💳</div>
                    <div class="text-sm font-bold text-gray-800">পেমেন্ট হিস্টোরি</div>
                </a>
                <a href="{{ route('devices.index') }}" class="bg-white shadow sm:rounded-lg p-4 text-center hover:bg-gray-50 transition-colors">
                    <div class="text-2xl mb-1">📱</div>
                    <div class="text-sm font-bold text-gray-800">আমার ডিভাইস</div>
                </a>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.phone-verification-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
