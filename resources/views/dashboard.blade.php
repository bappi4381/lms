<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('আমার লার্নিং ড্যাশবোর্ড') }}
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-50 min-h-[calc(100vh-140px)]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Welcome Banner -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-3xl shadow-xl p-8 text-white flex flex-col md:flex-row justify-between items-center gap-6">
                <div>
                    <h1 class="text-3xl font-extrabold mb-2">স্বাগতম, {{ Auth::user()->name }}! 👋</h1>
                    <p class="text-indigo-100 text-lg">আপনার লার্নিং জার্নিতে স্বাগতম। নিচে আপনার এনরোল করা কোর্সগুলো দেখতে পাবেন।</p>
                </div>
                <a href="{{ route('courses.index') }}" class="px-6 py-3 bg-white text-indigo-700 font-bold rounded-xl shadow hover:bg-indigo-50 transition-colors whitespace-nowrap">
                    নতুন কোর্স খুঁজুন
                </a>
            </div>

            <!-- Enrolled Courses Section -->
            <div>
                <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                    আমার এনরোল করা কোর্সসমূহ
                </h3>

                @if($enrollments->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($enrollments as $enrollment)
                            @php
                                $course = $enrollment->course;
                                $firstLessonId = $course->modules->first()?->lessons->first()?->id ?? 0;
                            @endphp
                            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden flex flex-col hover:shadow-md transition-shadow">
                                <div class="relative h-48 bg-gray-100">
                                    <img src="{{ $course->thumbnail ? asset('storage/'.$course->thumbnail) : 'https://ui-avatars.com/api/?name='.urlencode($course->title).'&background=random&size=600' }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                                    
                                    <div class="absolute top-3 right-3">
                                        @if($enrollment->payment_status === 'paid')
                                            <span class="bg-green-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow">পেইড / অ্যাক্টিভ</span>
                                        @elseif($enrollment->payment_status === 'pending')
                                            <span class="bg-amber-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow">অনুমোদনের অপেক্ষায়</span>
                                        @else
                                            <span class="bg-red-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow">{{ ucfirst($enrollment->payment_status) }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="p-6 flex flex-col flex-grow">
                                    <h4 class="text-xl font-bold text-gray-900 mb-2 line-clamp-2">{{ $course->title }}</h4>
                                    <p class="text-gray-500 text-sm mb-6 line-clamp-2">{{ $course->description }}</p>

                                    <div class="mt-auto pt-4 border-t border-gray-100">
                                        @if($enrollment->payment_status === 'paid')
                                            <a href="{{ route('courses.lesson', ['slug' => $course->slug, 'lesson_id' => $firstLessonId]) }}" class="block w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-center font-bold rounded-xl transition-colors shadow">
                                                শেখা চালিয়ে যান →
                                            </a>
                                        @elseif($enrollment->payment_status === 'pending')
                                            <div class="w-full py-3 px-4 bg-amber-50 text-amber-700 text-center font-semibold text-sm rounded-xl border border-amber-200">
                                                অ্যাডমিন আপনার এনরোলমেন্ট চেক করছেন।
                                            </div>
                                        @else
                                            <a href="{{ route('courses.show', $course->slug) }}" class="block w-full py-3 px-4 bg-gray-100 text-gray-700 text-center font-bold rounded-xl transition-colors">
                                                কোর্স ডিটেইলস দেখুন
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white border border-dashed border-gray-300 rounded-3xl p-12 text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        <h4 class="text-xl font-bold text-gray-800 mb-2">আপনি এখনো কোনো কোর্সে এনরোল করেননি।</h4>
                        <p class="text-gray-500 mb-6">আমাদের জনপ্রিয় কোর্সগুলো থেকে আপনার পছন্দের বিষয় বেছে নিন।</p>
                        <a href="{{ route('courses.index') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition-colors shadow">
                            কোর্সসমূহ ব্রাউজ করুন →
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
