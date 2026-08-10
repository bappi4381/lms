{{-- ─────────────────────────────────────────────────────────────────── --}}
{{-- Shared form partial for both Create and Edit course pages.        --}}
{{-- Variables: $course (null for create, Course model for edit)       --}}
{{--            $categories (key=>label map)                           --}}
{{--            $instructors (key=>name map)                           --}}
{{-- ─────────────────────────────────────────────────────────────────── --}}

{{-- ── Tab Navigation ─────────────────────────────────────────────── --}}
<div x-data="{ activeTab: 'basic' }" class="space-y-6">

    <!-- Tab Pills -->
    <div class="flex flex-wrap gap-2 bg-slate-100 p-1.5 rounded-2xl">
        @foreach([
            ['id' => 'basic',    'label' => '① Basic Info',     'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
            ['id' => 'pricing',  'label' => '② Pricing & Schedule', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['id' => 'features', 'label' => '③ Features & Tools', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
            ['id' => 'faqs',     'label' => '④ Projects & FAQs',  'icon' => 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        ] as $tab)
            <button type="button"
                    @click="activeTab = '{{ $tab['id'] }}'"
                    :class="activeTab === '{{ $tab['id'] }}' ? 'bg-white shadow-sm text-sky-700 font-bold' : 'text-slate-600 hover:text-slate-900'"
                    class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm transition-all flex-1 justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $tab['icon'] }}"/>
                </svg>
                {{ $tab['label'] }}
            </button>
        @endforeach
    </div>

    {{-- ══════════════════════════════════════════════════════ --}}
    {{-- TAB 1: Basic Information                              --}}
    {{-- ══════════════════════════════════════════════════════ --}}
    <div x-show="activeTab === 'basic'" class="space-y-5">
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 space-y-6">
            <h3 class="font-bold text-slate-800 text-base border-b border-slate-100 pb-3">Basic Information</h3>

            <!-- Language Tabs -->
            <div x-data="{ lang: 'en' }">
                <div class="flex gap-2 mb-4">
                    <button type="button" @click="lang = 'en'"
                            :class="lang === 'en' ? 'bg-sky-600 text-white' : 'bg-slate-100 text-slate-600'"
                            class="px-4 py-1.5 rounded-lg text-sm font-semibold transition-all">
                        🇬🇧 English
                    </button>
                    <button type="button" @click="lang = 'bn'"
                            :class="lang === 'bn' ? 'bg-sky-600 text-white' : 'bg-slate-100 text-slate-600'"
                            class="px-4 py-1.5 rounded-lg text-sm font-semibold transition-all">
                        🇧🇩 বাংলা
                    </button>
                </div>

                <!-- English Fields -->
                <div x-show="lang === 'en'" class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Course Title (English) <span class="text-rose-500">*</span></label>
                        <input type="text" name="title_en" id="title_en"
                               value="{{ old('title_en', $course?->title_en) }}"
                               placeholder="e.g. AI & ML Engineering Bootcamp"
                               class="w-full px-4 py-2.5 rounded-xl border @error('title_en') border-rose-400 bg-rose-50 @else border-slate-300 @enderror text-sm focus:outline-none focus:ring-2 focus:ring-sky-500"
                               oninput="autoSlug(this.value)">
                        @error('title_en')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Sub Description (English)</label>
                        <textarea name="sub_description_en" rows="3"
                                  placeholder="Short summary or tagline in English..."
                                  class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 resize-none">{{ old('sub_description_en', $course?->sub_description_en) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Full Description (English)</label>
                        <textarea name="description_en" rows="6"
                                  placeholder="Full course description in English..."
                                  class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 resize-y">{{ old('description_en', $course?->description_en) }}</textarea>
                    </div>
                </div>

                <!-- Bengali Fields -->
                <div x-show="lang === 'bn'" class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Course Title (বাংলা) <span class="text-rose-500">*</span></label>
                        <input type="text" name="title_bn" id="title_bn"
                               value="{{ old('title_bn', $course?->title_bn) }}"
                               placeholder="যেমন: এআই এবং মেশিন লার্নিং বুটক্যাম্প"
                               class="w-full px-4 py-2.5 rounded-xl border @error('title_bn') border-rose-400 bg-rose-50 @else border-slate-300 @enderror text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                        @error('title_bn')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Sub Description (বাংলা)</label>
                        <textarea name="sub_description_bn" rows="3"
                                  placeholder="কোর্সের ছোট সারসংক্ষেপ বা সাব-টাইটেল বাংলায়..."
                                  class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 resize-none">{{ old('sub_description_bn', $course?->sub_description_bn) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Full Description (বাংলা)</label>
                        <textarea name="description_bn" rows="6"
                                  placeholder="কোর্সের পূর্ণ বিবরণ বাংলায়..."
                                  class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 resize-y">{{ old('description_bn', $course?->description_bn) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Slug -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Slug (URL) <span class="text-rose-500">*</span></label>
                <div class="flex items-center">
                    <span class="px-3 py-2.5 bg-slate-100 border border-r-0 border-slate-300 rounded-l-xl text-xs text-slate-500">/courses/</span>
                    <input type="text" name="slug" id="slug"
                           value="{{ old('slug', $course?->slug) }}"
                           placeholder="course-slug-here"
                           class="flex-1 px-4 py-2.5 rounded-r-xl border @error('slug') border-rose-400 bg-rose-50 @else border-slate-300 @enderror text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 font-mono">
                </div>
                @error('slug')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Category & Instructor -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Category</label>
                    <x-searchable-select name="category_id"
                                         :options="$categories"
                                         :value="old('category_id', $course?->category_id)"
                                         placeholder="— Select Category —"
                                         searchPlaceholder="Search category..." />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Instructor</label>
                    <x-searchable-select name="instructor_id"
                                         :options="$instructors"
                                         :value="old('instructor_id', $course?->instructor_id)"
                                         placeholder="— No Instructor —"
                                         searchPlaceholder="Search instructor..." />
                </div>
            </div>

            <!-- Thumbnail Upload -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Thumbnail Image</label>
                @if($course?->thumbnail)
                    <div class="mb-3 flex items-center gap-4">
                        <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="Current thumbnail"
                             class="w-32 h-20 object-cover rounded-xl border border-slate-200">
                        <div class="text-xs text-slate-500">Current thumbnail. Upload a new file to replace it.</div>
                    </div>
                @endif
                <input type="file" name="thumbnail_upload" accept="image/*"
                       onchange="handleThumbnailUpload(this)"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-sky-50 file:text-sky-700 file:font-semibold">
                {{-- Keep existing thumbnail path for update --}}
                <input type="hidden" name="thumbnail" id="thumbnail_path" value="{{ old('thumbnail', $course?->thumbnail) }}">
                <p class="text-xs text-slate-400 mt-1">Recommended: 1280×720px (16:9), JPG or PNG, max 2MB</p>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════ --}}
    {{-- TAB 2: Pricing & Schedule                             --}}
    {{-- ══════════════════════════════════════════════════════ --}}
    <div x-show="activeTab === 'pricing'" class="space-y-5">
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 space-y-5">
            <h3 class="font-bold text-slate-800 text-base border-b border-slate-100 pb-3">Pricing & Schedule</h3>

            <!-- Price Fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Course Price (BDT) <span class="text-rose-500">*</span></label>
                    <div class="flex items-center">
                        <span class="px-3 py-2.5 bg-slate-100 border border-r-0 border-slate-300 rounded-l-xl text-sm font-bold text-slate-600">৳</span>
                        <input type="number" name="price" step="0.01" min="0"
                               value="{{ old('price', $course?->price ?? 0) }}"
                               class="flex-1 px-4 py-2.5 rounded-r-xl border @error('price') border-rose-400 @else border-slate-300 @enderror text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                    </div>
                    @error('price')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Discount Price (BDT)</label>
                    <div class="flex items-center">
                        <span class="px-3 py-2.5 bg-slate-100 border border-r-0 border-slate-300 rounded-l-xl text-sm font-bold text-slate-600">৳</span>
                        <input type="number" name="discount_price" step="0.01" min="0"
                               value="{{ old('discount_price', $course?->discount_price) }}"
                               placeholder="Leave empty if no discount"
                               class="flex-1 px-4 py-2.5 rounded-r-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                    </div>
                </div>
            </div>

            <!-- Batch & Seats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Batch Number</label>
                    <input type="number" name="batch_number" min="1"
                           value="{{ old('batch_number', $course?->batch_number ?? 1) }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Total Seats</label>
                    <input type="number" name="seats_total" min="0"
                           value="{{ old('seats_total', $course?->seats_total ?? 0) }}"
                           placeholder="0 = unlimited"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Available Seats (manual)</label>
                    <input type="number" name="seats_available" min="0"
                           value="{{ old('seats_available', $course?->seats_available ?? 0) }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
            </div>

            <!-- Dates & Schedules -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Course Starts At</label>
                    <input type="datetime-local" name="starts_at"
                           value="{{ old('starts_at', $course?->starts_at?->format('Y-m-d\TH:i')) }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Promo Video URL</label>
                    <input type="url" name="video_url"
                           value="{{ old('video_url', $course?->video_url) }}"
                           placeholder="https://www.youtube.com/watch?v=..."
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Live Class Schedule</label>
                    <input type="text" name="live_class_schedule"
                           value="{{ old('live_class_schedule', $course?->live_class_schedule) }}"
                           placeholder="যেমন: রাত ৮:০০-১০:৩০ (সোম, বুধ)"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Support Class Schedule</label>
                    <input type="text" name="support_class_schedule"
                           value="{{ old('support_class_schedule', $course?->support_class_schedule) }}"
                           placeholder="যেমন: রাত ৮:০০-১০:০০ (রবি, মঙ্গল)"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
            </div>

            <!-- Published Toggle -->
            <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl border border-slate-200">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="is_published" value="0">
                    <input type="checkbox" name="is_published" value="1" id="is_published"
                           {{ old('is_published', $course?->is_published ?? true) ? 'checked' : '' }}
                           class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-300 peer-focus:ring-2 peer-focus:ring-sky-300 rounded-full peer peer-checked:bg-sky-600 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                </label>
                <div>
                    <label for="is_published" class="text-sm font-bold text-slate-700 cursor-pointer">Published</label>
                    <p class="text-xs text-slate-500">When enabled, the course will be visible on the website.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════ --}}
    {{-- TAB 3: Features & Tools (repeaters)                   --}}
    {{-- ══════════════════════════════════════════════════════ --}}
    <div x-show="activeTab === 'features'" class="space-y-5">
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 space-y-6">
            <h3 class="font-bold text-slate-800 text-base border-b border-slate-100 pb-3">Features, Tools & Course Includes</h3>

            <div x-data="{ lang: 'en' }">
                <div class="flex gap-2 mb-5">
                    <button type="button" @click="lang = 'en'"
                            :class="lang === 'en' ? 'bg-sky-600 text-white' : 'bg-slate-100 text-slate-600'"
                            class="px-4 py-1.5 rounded-lg text-sm font-semibold transition-all">🇬🇧 English</button>
                    <button type="button" @click="lang = 'bn'"
                            :class="lang === 'bn' ? 'bg-sky-600 text-white' : 'bg-slate-100 text-slate-600'"
                            class="px-4 py-1.5 rounded-lg text-sm font-semibold transition-all">🇧🇩 বাংলা</button>
                </div>

                <div x-show="lang === 'en'" class="space-y-5">
                    @include('admin.courses._repeater', [
                        'fieldId' => 'key_features_en',
                        'label' => 'Key Features (English)',
                        'itemKey' => 'feature',
                        'placeholder' => 'e.g. Live mentorship sessions',
                        'addLabel' => '+ Add English Feature',
                    ])
                    @include('admin.courses._repeater', [
                        'fieldId' => 'tools_en',
                        'label' => 'Tools & Technologies (English)',
                        'itemKey' => 'tool',
                        'placeholder' => 'e.g. Python, TensorFlow',
                        'addLabel' => '+ Add English Tool',
                    ])
                </div>

                <div x-show="lang === 'bn'" class="space-y-5">
                    @include('admin.courses._repeater', [
                        'fieldId' => 'key_features_bn',
                        'label' => 'Key Features (বাংলা)',
                        'itemKey' => 'feature',
                        'placeholder' => 'যেমন: লাইভ মেন্টরশিপ সেশন',
                        'addLabel' => '+ বাংলা ফিচার যোগ করুন',
                    ])
                    @include('admin.courses._repeater', [
                        'fieldId' => 'tools_bn',
                        'label' => 'Tools & Technologies (বাংলা)',
                        'itemKey' => 'tool',
                        'placeholder' => 'যেমন: পাইথন, টেনসরফ্লো',
                        'addLabel' => '+ বাংলা টুল যোগ করুন',
                    ])
                </div>
            </div>

            @include('admin.courses._repeater', [
                'fieldId' => 'course_includes',
                'label' => 'এই কোর্সে আপনি পাচ্ছেন (Course Includes)',
                'itemKey' => 'item',
                'placeholder' => 'যেমন: ৪০+ লাইভ ক্লাস',
                'addLabel' => '+ আরো সুবিধা যোগ করুন',
            ])
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════ --}}
    {{-- TAB 4: Projects & FAQs                               --}}
    {{-- ══════════════════════════════════════════════════════ --}}
    <div x-show="activeTab === 'faqs'" class="space-y-5">
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 space-y-6">
            <h3 class="font-bold text-slate-800 text-base border-b border-slate-100 pb-3">Projects & FAQs</h3>

            <div x-data="{ lang: 'en' }">
                <div class="flex gap-2 mb-5">
                    <button type="button" @click="lang = 'en'"
                            :class="lang === 'en' ? 'bg-sky-600 text-white' : 'bg-slate-100 text-slate-600'"
                            class="px-4 py-1.5 rounded-lg text-sm font-semibold transition-all">🇬🇧 English</button>
                    <button type="button" @click="lang = 'bn'"
                            :class="lang === 'bn' ? 'bg-sky-600 text-white' : 'bg-slate-100 text-slate-600'"
                            class="px-4 py-1.5 rounded-lg text-sm font-semibold transition-all">🇧🇩 বাংলা</button>
                </div>

                <!-- English Projects & FAQs -->
                <div x-show="lang === 'en'" class="space-y-5">
                    @include('admin.courses._faq-repeater', [
                        'fieldId' => 'faqs_en',
                        'label' => 'FAQs (English)',
                        'qPlaceholder' => 'Question in English',
                        'aPlaceholder' => 'Answer in English',
                        'addLabel' => '+ Add English FAQ',
                    ])
                    @include('admin.courses._project-repeater', [
                        'fieldId' => 'projects_en',
                        'label' => 'Projects (English)',
                        'placeholder' => 'Project Title (EN)',
                        'addLabel' => '+ Add English Project',
                    ])
                </div>

                <!-- Bengali Projects & FAQs -->
                <div x-show="lang === 'bn'" class="space-y-5">
                    @include('admin.courses._faq-repeater', [
                        'fieldId' => 'faqs_bn',
                        'label' => 'FAQs (বাংলা)',
                        'qPlaceholder' => 'বাংলায় প্রশ্ন লিখুন',
                        'aPlaceholder' => 'বাংলায় উত্তর লিখুন',
                        'addLabel' => '+ বাংলা FAQ যোগ করুন',
                    ])
                    @include('admin.courses._project-repeater', [
                        'fieldId' => 'projects_bn',
                        'label' => 'Projects (বাংলা)',
                        'placeholder' => 'Project Title (BN)',
                        'addLabel' => '+ বাংলা প্রজেক্ট যোগ করুন',
                    ])
                </div>
            </div>
        </div>
    </div>

</div>
