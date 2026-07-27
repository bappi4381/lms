@php
    $featuredPost = [
        'category' => 'পরীক্ষা টিপস',
        'category_id' => 'exam-tips',
        'date' => '১২ জুলাই, ২০২৪',
        'title' => 'SSC ২০২৪ প্রস্তুতি গাইড: কিভাবে শুরু করবেন',
        'excerpt' => 'SSC পরীক্ষার জন্য সঠিক প্ল্যান, রুটিন ও রিসোর্স — শুরু থেকে ফাইনাল রিভিশন পর্যন্ত সম্পূর্ণ গাইড।',
        'author' => 'SecondShiftBD Editorial',
    ];

    $allPosts = [
        [
            'category' => 'সাফল্যের গল্প',
            'category_id' => 'success',
            'date' => '১০ জুলাই, ২০২৪',
            'title' => 'কিভাবে BCS ক্র্যাক করবেন: একজন ক্যাডারের অভিজ্ঞতা',
            'author' => 'Karim Hassan',
        ],
        [
            'category' => 'স্কিলস',
            'category_id' => 'skills',
            'date' => '৮ জুলাই, ২০২৪',
            'title' => 'পাইথন দিয়ে ডেটা সায়েন্স শেখা: বাংলায় সম্পূর্ণ রোডম্যাপ',
            'author' => 'Nusrat Jahan',
        ],
        [
            'category' => 'পরীক্ষা টিপস',
            'category_id' => 'exam-tips',
            'date' => '৫ জুলাই, ২০২৪',
            'title' => 'IELTS-এ ব্যান্ড স্কোর বাড়ানোর ৭টি কৌশল',
            'author' => 'Rahim Ahmed',
        ],
        [
            'category' => 'স্টাডি টিপস',
            'category_id' => 'study-tips',
            'date' => '২০ জুন, ২০২৪',
            'title' => 'প্রতিদিন ২ ঘণ্টায় বেশি পড়াশোনা করার ৫টি সহজ উপায়',
            'author' => 'Fatima Khan',
        ],
        [
            'category' => 'আপডেট',
            'category_id' => 'update',
            'date' => '১৫ জুন, ২০২৪',
            'title' => 'SecondShiftBD-তে নতুন HSC ২০২৫ ব্যাচ চালু',
            'author' => 'SecondShiftBD Team',
        ],
        [
            'category' => 'স্টাডি টিপস',
            'category_id' => 'study-tips',
            'date' => '১০ জুন, ২০২৪',
            'title' => 'অনলাইন ক্লাসে মনোযোগ ধরে রাখার কার্যকর পদ্ধতি',
            'author' => 'Sadia Islam',
        ],
    ];

    $categories = [
        ['id' => 'all', 'label' => 'সকল'],
        ['id' => 'exam-tips', 'label' => 'পরীক্ষা টিপস'],
        ['id' => 'success', 'label' => 'সাফল্যের গল্প'],
        ['id' => 'skills', 'label' => 'স্কিলস'],
        ['id' => 'study-tips', 'label' => 'স্টাডি টিপস'],
        ['id' => 'update', 'label' => 'আপডেট'],
    ];

    $initialCategory = collect($categories)->pluck('id')->contains(request()->query('category'))
        ? request()->query('category')
        : 'all';
@endphp

<div
    x-data="{
        category: '{{ $initialCategory }}',
        posts: @js($allPosts),
        featured: @js($featuredPost),
        subscribed: false,
        subscribe(email) {
            if (!email || !email.includes('@')) return;
            this.subscribed = true;
            setTimeout(() => this.subscribed = false, 3000);
        },
        get filtered() {
            if (this.category === 'all') return this.posts;
            return this.posts.filter(p => p.category_id === this.category);
        },
        showFeatured() {
            return this.category === 'all' || this.featured.category_id === this.category;
        }
    }"
    class="min-h-screen"
>
    {{-- Page title --}}
    <section class="glass-section">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="glass-page-header">
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-brand-navy tracking-tight">ব্লগ</h1>
                <p class="mt-2 text-sm text-gray-500">শিক্ষা, ক্যারিয়ার ও পরীক্ষার টিপস — সব এক জায়গায়</p>
            </div>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8 sm:pb-10">
        <div class="glass-content-area space-y-10 sm:space-y-12">
        {{-- Featured post --}}
        <article
            x-show="showFeatured()"
            class="group grid grid-cols-1 lg:grid-cols-2 gap-0 glass-card-hover overflow-hidden"
        >
            <div class="relative aspect-video lg:aspect-auto lg:min-h-[280px] glass-thumb overflow-hidden">
                <div class="course-thumb-pattern absolute inset-0"></div>
            </div>
            <div class="p-6 sm:p-8 lg:p-10 flex flex-col justify-center">
                <div class="flex flex-wrap items-center gap-2 text-xs sm:text-sm mb-4">
                    <span class="font-bold text-brand-blue" x-text="featured.category"></span>
                    <span class="text-gray-300">•</span>
                    <time class="text-gray-400" x-text="featured.date"></time>
                </div>
                <h2 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-brand-navy leading-tight mb-4 group-hover:text-brand-blue transition-colors">
                    <a href="#" class="focus-visible:underline" x-text="featured.title"></a>
                </h2>
                <p class="text-sm sm:text-base text-gray-500 leading-relaxed line-clamp-3 mb-4" x-text="featured.excerpt"></p>
                <p class="text-xs text-gray-400 mt-auto" x-text="featured.author"></p>
            </div>
        </article>

        {{-- Category filters --}}
        <div class="flex flex-wrap gap-2.5">
            @foreach($categories as $cat)
                <button
                    type="button"
                    @click="category = '{{ $cat['id'] }}'"
                    :class="category === '{{ $cat['id'] }}'
                        ? 'glass-pill-active'
                        : 'glass-pill'"
                    class="md-ripple inline-flex items-center min-h-[40px] px-4 sm:px-5 py-2 rounded-full text-sm font-bold border transition-colors duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-blue focus-visible:ring-offset-2"
                >
                    {{ $cat['label'] }}
                </button>
            @endforeach
        </div>

        {{-- Post grid --}}
        <section>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
                <template x-for="(post, index) in filtered" :key="category + '-' + index">
                    <article class="group flex flex-col glass-card-hover overflow-hidden h-full">
                        <div class="relative aspect-video glass-thumb overflow-hidden">
                            <div class="course-thumb-pattern absolute inset-0"></div>
                        </div>
                        <div class="p-5 flex flex-col flex-1">
                            <div class="flex flex-wrap items-center gap-1.5 text-xs text-gray-400 mb-3">
                                <span class="text-brand-blue font-semibold" x-text="post.category"></span>
                                <span>•</span>
                                <time x-text="post.date"></time>
                            </div>
                            <h3 class="text-base font-bold text-brand-navy leading-snug line-clamp-3 mb-4 flex-1 group-hover:text-brand-blue transition-colors">
                                <a href="#" class="focus-visible:underline" x-text="post.title"></a>
                            </h3>
                            <p class="text-xs text-gray-400 mt-auto" x-text="post.author"></p>
                        </div>
                    </article>
                </template>
            </div>

            <div x-show="filtered.length === 0" class="flex flex-col items-center justify-center py-20 glass-empty mt-6" style="display: none;">
                <svg class="w-14 h-14 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                <p class="text-lg font-bold text-brand-navy">এই ক্যাটাগরিতে কোনো পোস্ট নেই</p>
            </div>
        </section>

        {{-- Newsletter --}}
        <section class="glass-newsletter px-6 sm:px-10 py-10 sm:py-12">
            <div class="max-w-2xl mx-auto text-center">
                <h2 class="text-xl sm:text-2xl font-extrabold text-brand-navy mb-2">নিউজলেটার সাবস্ক্রাইব করুন</h2>
                <p class="text-sm sm:text-base text-gray-500 mb-6">নতুন কোর্স ও শিক্ষামূলক টিপস সরাসরি আপনার ইমেইলে</p>

                <form
                    @submit.prevent="subscribe($refs.email.value); $refs.email.value = ''"
                    class="flex flex-col sm:flex-row gap-3 max-w-lg mx-auto"
                >
                    <label for="newsletter-email" class="sr-only">আপনার ইমেইল</label>
                    <input
                        x-ref="email"
                        id="newsletter-email"
                        type="email"
                        required
                        placeholder="আপনার ইমেইল"
                        class="flex-1 min-h-[48px] glass-input text-sm px-4"
                    >
                    <button
                        type="submit"
                        class="md-ripple shrink-0 min-h-[48px] px-8 py-3 rounded-xl glass-btn text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-blue focus-visible:ring-offset-2"
                    >
                        সাবস্ক্রাইব
                    </button>
                </form>

                <p
                    x-show="subscribed"
                    x-transition
                    class="mt-4 text-sm font-semibold text-brand-navy"
                    style="display: none;"
                    role="status"
                >
                    সাবস্ক্রিপশন সফল! ধন্যবাদ।
                </p>
            </div>
        </section>
        </div>
    </div>
</div>
