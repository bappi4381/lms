<?php

use App\Models\Category;
use App\Models\Course;
use Livewire\Volt\Component;

new class extends Component
{
    public $categories = [];
    public $courses = [];
    public $selectedCategory = null;

    public function mount(): void
    {
        $this->categories = Category::where('is_active', true)
            ->orderBy('order')
            ->get();

        $this->loadCourses();
    }

    public function filterByCategory(?int $categoryId): void
    {
        $this->selectedCategory = $categoryId;
        $this->loadCourses();
    }

    private function loadCourses(): void
    {
        $query = Course::where('is_published', true);

        if ($this->selectedCategory) {
            $query->where('category_id', $this->selectedCategory);
        }

        $this->courses = $query->with('category')->latest()->get();
    }
}
?>



<div>
    <!-- Hero Section -->
    <section class="w-full relative bg-ostad-black pt-16 pb-20 md:pt-24 md:pb-28 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div class="flex flex-col gap-6 text-center md:text-left z-20">
                    <div class="text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight">
                        <span class="text-white block mb-2">বাংলাদেশ শিখবে</span>
                        <span class="text-ostad-yellow block">লাইভে</span>
                    </div>
                    <p class="text-lg md:text-xl text-gray-300 max-w-lg mx-auto md:mx-0">
                        স্কিল শেখার মাধ্যমে বদলে ফেলুন নিজের ভবিষ্যৎ। সেরা ইন্সট্রাক্টরদের সাথে লাইভ ইন্টারঅ্যাক্টিভ ক্লাসে অংশ নিন।
                    </p>
                    <div class="mt-4 flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                        <a href="#courses" class="inline-flex justify-center items-center px-8 py-4 bg-ostad-yellow hover:bg-ostad-yellow-hover text-ostad-black text-lg font-bold rounded-lg transition-all duration-200 transform hover:-translate-y-1 shadow-lg active:scale-95">
                            শেখা শুরু করুন
                            <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                </div>
                <div class="relative hidden md:block z-20">
                    <!-- Hero Image -->
                    <div class="w-full h-[350px] bg-gradient-to-tr from-gray-800 to-gray-700 rounded-2xl shadow-2xl overflow-hidden border border-gray-600 relative">
                        <div class="absolute inset-0 bg-ostad-yellow opacity-10 mix-blend-overlay"></div>
                        <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Students learning" class="w-full h-full object-cover mix-blend-overlay opacity-60">
                    </div>
                    <!-- Decorative Elements -->
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-ostad-yellow rounded-full mix-blend-multiply filter blur-3xl opacity-30"></div>
                    <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>
                </div>
            </div>
        </div>
        <!-- Background Blobs -->
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-ostad-yellow/10 rounded-full filter blur-3xl mix-blend-screen opacity-50 pointer-events-none"></div>
    </section>

    <!-- Categories / Upcoming Live Courses -->
    <div id="courses" class="py-16 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex items-center gap-3 mb-10">
                <div class="w-10 h-10 bg-ostad-yellow rounded-full flex items-center justify-center shadow-sm">
                    <svg class="w-6 h-6 text-ostad-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                </div>
                <h2 class="text-2xl md:text-3xl font-extrabold text-ostad-black tracking-tight">আপকামিং লাইভ কোর্স</h2>
            </div>

            <!-- Dynamic Category Pills -->
            <div class="flex overflow-x-auto pb-6 mb-4 gap-3 hide-scrollbar">
                <!-- "All" pill -->
                <button
                    wire:click="filterByCategory(null)"
                    class="px-5 py-2 text-sm font-bold rounded-full whitespace-nowrap shadow-sm border transition
                        {{ $selectedCategory === null
                            ? 'bg-ostad-black text-white border-transparent'
                            : 'bg-white text-ostad-black border-gray-200 hover:border-ostad-yellow' }}">
                    সবগুলো
                </button>

                @foreach($categories as $category)
                    <button
                        wire:click="filterByCategory({{ $category->id }})"
                        class="px-5 py-2 text-sm font-bold rounded-full whitespace-nowrap shadow-sm border transition
                            {{ $selectedCategory === $category->id
                                ? 'bg-ostad-black text-white border-transparent'
                                : 'bg-white text-ostad-black border-gray-200 hover:border-ostad-yellow' }}">
                        @if($category->icon)
                            <span class="mr-1">{{ $category->icon }}</span>
                        @endif
                        {{ $category->name }}
                    </button>
                @endforeach

                @if($categories->isEmpty())
                    <span class="px-5 py-2 text-sm text-gray-400 italic">কোনো ক্যাটাগরি নেই</span>
                @endif
            </div>

            <!-- Courses Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($courses as $course)
                    <a href="{{ route('courses.show', $course->slug) }}" class="group flex flex-col bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-xl hover:border-gray-300 transition-all duration-300 overflow-hidden transform hover:-translate-y-1">
                        <!-- Thumbnail -->
                        <div class="relative w-full aspect-video overflow-hidden bg-gray-100">
                            <img src="{{ $course->thumbnail ? asset('storage/'.$course->thumbnail) : 'https://ui-avatars.com/api/?name='.urlencode($course->title).'&background=random&size=600' }}" alt="{{ $course->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            <div class="absolute top-2 left-2 bg-ostad-black/80 backdrop-blur-sm text-white text-xs font-bold px-2 py-1 rounded flex items-center gap-1 border border-white/20">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                                লাইভ
                            </div>
                            @if($course->category)
                                <div class="absolute top-2 right-2 bg-ostad-yellow/90 text-ostad-black text-xs font-bold px-2 py-1 rounded">
                                    {{ $course->category->name }}
                                </div>
                            @endif
                        </div>

                        <!-- Batch Info Banner -->
                        <div class="flex items-center justify-between px-3 py-2 bg-[#f8f9fa] border-b border-gray-100 text-[11px] font-semibold text-gray-600">
                            <div class="bg-gray-200 px-2 py-0.5 rounded text-gray-800">ব্যাচ {{ $course->batch_number }}</div>
                            <div class="flex items-center gap-1"><svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg> {{ $course->seatsRemaining() }} সিট বাকি</div>
                            @if($course->starts_at)
                                <div class="flex items-center gap-1"><svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> {{ \Carbon\Carbon::parse($course->starts_at)->diffForHumans() }}</div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="p-4 flex flex-col flex-grow">
                            <h3 class="text-[17px] font-bold text-ostad-black mb-3 line-clamp-2 leading-snug group-hover:text-blue-600 transition-colors">{{ $course->title }}</h3>

                            <div class="mt-auto pt-4 flex items-center justify-between">
                                <div class="text-lg font-extrabold text-ostad-black">৳{{ number_format($course->price, 0) }}</div>
                            </div>

                            <!-- Action Button -->
                            <div class="mt-4 w-full bg-gray-100 group-hover:bg-ostad-yellow/20 text-ostad-black text-center font-bold py-2.5 rounded transition-colors flex justify-center items-center gap-2 text-sm border border-transparent group-hover:border-ostad-yellow/50">
                                বিস্তারিত দেখি
                                <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full flex flex-col items-center justify-center py-24 bg-white rounded-xl border border-dashed border-gray-300">
                        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        <p class="text-xl text-ostad-black font-bold">বর্তমানে কোনো কোর্স পাওয়া যায়নি।</p>
                        <p class="text-gray-500 mt-1">শীঘ্রই নতুন কোর্স যুক্ত করা হবে।</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</div>