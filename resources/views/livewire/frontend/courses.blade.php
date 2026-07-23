<?php

use App\Models\Category;
use App\Models\Course;
use Livewire\Volt\Component;

new class extends Component
{
    public $categories = [];
    public $courses = [];
    public $selectedCategory = null;
    public $search = '';

    public function mount(): void
    {
        $this->categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        $this->selectedCategory = request()->integer('category') ?: null;
        $this->search = trim((string) request()->query('q', ''));

        $this->loadCourses();
    }

    public function filterByCategory(?int $categoryId): void
    {
        $this->selectedCategory = $categoryId;
        $this->loadCourses();
    }

    public function updatedSearch(): void
    {
        $this->loadCourses();
    }

    private function loadCourses(): void
    {
        $query = Course::where('is_published', true);

        if ($this->selectedCategory) {
            $category = Category::with('children')->find($this->selectedCategory);
            if ($category) {
                $categoryIds = array_merge([$category->id], $category->children->pluck('id')->toArray());
                $query->whereIn('category_id', $categoryIds);
            }
        }

        if ($this->search !== '') {
            $query->where('title', 'like', '%'.$this->search.'%');
        }

        $this->courses = $query->with('category')->latest()->get();
    }
}
?>



<div>
    <!-- Page Header -->
    <section class="w-full bg-white pt-10 pb-6 md:pt-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-ostad-yellow rounded-full flex items-center justify-center shadow-elevation-1">
                    <svg class="w-6 h-6 text-ostad-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                </div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-ostad-black tracking-tight">সকল কোর্স</h1>
            </div>
            <p class="mt-3 text-gray-500 max-w-2xl">পছন্দের কোর্স খুঁজে নিন এবং সেরা ইন্সট্রাক্টরদের সাথে স্কিল ডেভেলপ করুন।</p>
        </div>
    </section>

    <!-- Categories / Courses -->
    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Search -->
            <div class="mb-6 bg-white rounded-full border border-gray-200 shadow-elevation-1 flex items-center gap-2 p-1.5 max-w-xl">
                <svg class="w-5 h-5 text-brand-blue ml-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"></path></svg>
                <input type="text" wire:model.live.debounce.400ms="search" placeholder="কোর্স খুঁজুন..." class="flex-1 border-0 focus:ring-0 text-sm text-ostad-black placeholder-gray-400 bg-transparent min-w-0">
            </div>

            <!-- Dynamic Category Filter Chips (Material Design 3) -->
            <div class="flex overflow-x-auto pb-6 mb-4 gap-2.5 hide-scrollbar">
                <!-- "All" chip -->
                <button
                    wire:click="filterByCategory(null)"
                    class="md-ripple px-4 h-9 text-sm font-semibold rounded-md-full whitespace-nowrap border transition-colors duration-150 ease-md-standard inline-flex items-center gap-1.5
                        {{ $selectedCategory === null
                            ? 'bg-ostad-yellow/20 text-ostad-black border-ostad-yellow-active'
                            : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' }}">
                    @if($selectedCategory === null)
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    @endif
                    সবগুলো
                </button>

                @foreach($categories as $category)
                    <button
                        wire:click="filterByCategory({{ $category->id }})"
                        class="md-ripple px-4 h-9 text-sm font-semibold rounded-md-full whitespace-nowrap border transition-colors duration-150 ease-md-standard inline-flex items-center gap-1.5
                            {{ $selectedCategory === $category->id
                                ? 'bg-ostad-yellow/20 text-ostad-black border-ostad-yellow-active'
                                : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' }}">
                        @if($selectedCategory === $category->id)
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        @elseif($category->icon)
                            @if(str_contains($category->icon, '<'))
                                <span class="inline-flex items-center shrink-0">{!! $category->icon !!}</span>
                            @elseif(str_contains($category->icon, 'fa-') || str_starts_with($category->icon, 'fa'))
                                <i class="{{ (str_contains($category->icon, 'fa-') && !str_contains($category->icon, 'fa-solid') && !str_contains($category->icon, 'fa-regular') && !str_contains($category->icon, 'fas ') && !str_contains($category->icon, 'far ') && !str_contains($category->icon, 'fab ')) ? 'fa-solid '.$category->icon : $category->icon }} text-xs shrink-0"></i>
                            @else
                                <span class="shrink-0">{{ $category->icon }}</span>
                            @endif
                        @endif
                        <span>{{ $category->name }}</span>
                    </button>
                @endforeach

                @if($categories->isEmpty())
                    <span class="px-5 py-2 text-sm text-gray-400 italic">কোনো ক্যাটাগরি নেই</span>
                @endif
            </div>

            <!-- Courses Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($courses as $course)
                    <a href="{{ route('courses.show', $course->slug) }}" class="md-ripple group flex flex-col bg-white rounded-md-lg shadow-elevation-1 hover:shadow-elevation-3 transition-shadow duration-200 ease-md-standard overflow-hidden">
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

                            <!-- Action Button (M3 Tonal Button) -->
                            <div class="mt-4 w-full bg-gray-100 group-hover:bg-ostad-yellow/25 text-ostad-black text-center font-bold h-10 rounded-md-full transition-colors duration-200 ease-md-standard flex justify-center items-center gap-2 text-sm">
                                বিস্তারিত দেখি
                                <svg class="w-4 h-4 transition-transform duration-200 ease-md-standard group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full flex flex-col items-center justify-center py-24 bg-white rounded-md-lg border-2 border-dashed border-gray-300">
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
