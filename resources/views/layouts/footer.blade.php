<footer class="bg-brand-navy text-blue-100 pt-16 pb-8 border-t border-white/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Top Section: Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 pb-12 border-b border-white/10">
            
            {{-- Column 1: Brand Info & Contact (2 cols on lg) --}}
            <div class="lg:col-span-2 space-y-4">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-navy-light to-brand-blue text-white flex items-center justify-center font-black text-lg shadow-md">
                        S
                    </div>
                    <span class="text-2xl font-black text-white tracking-tight">SecondShift<span class="text-brand-blue">BD</span></span>
                </div>
                
                <p class="text-sm text-blue-100/70 leading-relaxed max-w-sm">
                    স্কিল শেখার মাধ্যমে বদলে ফেলুন নিজের ভবিষ্যৎ। দেশে সেরা ইন্সট্রাক্টরদের সাথে লাইভ ইন্টারঅ্যাক্টিভ ক্লাসে অংশ নিন এবং নিজের ক্যারিয়ার গড়ে তুলুন।
                </p>

                <div class="pt-2 space-y-2 text-xs sm:text-sm text-blue-100/80">
                    <div class="flex items-center gap-2.5 hover:text-brand-blue transition-colors">
                        <i class="fa-solid fa-phone text-brand-blue"></i>
                        <span>+880 1700-000000 (সকাল ১০টা - রাত ১০টা)</span>
                    </div>
                    <div class="flex items-center gap-2.5 hover:text-brand-blue transition-colors">
                        <i class="fa-solid fa-envelope text-brand-blue"></i>
                        <span>support@secondshiftbd.com</span>
                    </div>
                    <div class="flex items-center gap-2.5 text-blue-100/70">
                        <i class="fa-solid fa-location-dot text-brand-blue"></i>
                        <span>ঢাকা, বাংলাদেশ</span>
                    </div>
                </div>
            </div>

            {{-- Column 2: Quick Links --}}
            <div class="space-y-4">
                <h4 class="text-white text-base font-bold tracking-wide border-l-4 border-brand-blue pl-3">কুইক লিঙ্কস</h4>
                <ul class="space-y-2.5 text-sm text-blue-100/70">
                    <li>
                        <a href="{{ route('courses.index') }}" class="hover:text-brand-blue transition-colors flex items-center gap-1.5">
                            <i class="fa-solid fa-angle-right text-xs text-brand-blue"></i>
                            সকল কোর্সসমূহ
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('dashboard') }}" class="hover:text-brand-blue transition-colors flex items-center gap-1.5">
                            <i class="fa-solid fa-angle-right text-xs text-brand-blue"></i>
                            আমার ড্যাশবোর্ড
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('subscriptions.index') }}" class="hover:text-brand-blue transition-colors flex items-center gap-1.5">
                            <i class="fa-solid fa-angle-right text-xs text-brand-blue"></i>
                            সাবস্ক্রিপশন প্ল্যান
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('profile.certificates') }}" class="hover:text-brand-blue transition-colors flex items-center gap-1.5">
                            <i class="fa-solid fa-angle-right text-xs text-brand-blue"></i>
                            সার্টিফিকেট ডাউনলোড
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('support.index') }}" class="hover:text-brand-blue transition-colors flex items-center gap-1.5">
                            <i class="fa-solid fa-angle-right text-xs text-brand-blue"></i>
                            হেল্প ও সাপোর্ট
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Column 3: Top Categories --}}
            <div class="space-y-4">
                <h4 class="text-white text-base font-bold tracking-wide border-l-4 border-brand-blue pl-3">জনপ্রিয় বিষয়</h4>
                <ul class="space-y-2.5 text-sm text-blue-100/70">
                    <li class="hover:text-brand-blue transition-colors flex items-center gap-1.5 cursor-pointer">
                        <i class="fa-solid fa-laptop-code text-xs text-brand-blue"></i>
                        ICT & Computer Basics
                    </li>
                    <li class="hover:text-brand-blue transition-colors flex items-center gap-1.5 cursor-pointer">
                        <i class="fa-solid fa-graduation-cap text-xs text-brand-blue"></i>
                        HSC Academic Course
                    </li>
                    <li class="hover:text-brand-blue transition-colors flex items-center gap-1.5 cursor-pointer">
                        <i class="fa-solid fa-code text-xs text-brand-blue"></i>
                        Web Development
                    </li>
                    <li class="hover:text-brand-blue transition-colors flex items-center gap-1.5 cursor-pointer">
                        <i class="fa-solid fa-pen-nib text-xs text-brand-blue"></i>
                        Graphic Design
                    </li>
                    <li class="hover:text-brand-blue transition-colors flex items-center gap-1.5 cursor-pointer">
                        <i class="fa-solid fa-chart-line text-xs text-brand-blue"></i>
                        Digital Marketing
                    </li>
                </ul>
            </div>

            {{-- Column 4: Social & Support Badge --}}
            <div class="space-y-4">
                <h4 class="text-white text-base font-bold tracking-wide border-l-4 border-brand-blue pl-3">আমাদের সোশ্যাল মিডিয়া</h4>
                <p class="text-xs text-blue-100/70">
                    আমাদের সাথে সোশ্যাল মিডিয়ায় যুক্ত হয়ে আপডেট থাকুন।
                </p>

                {{-- Social Icons --}}
                <div class="flex items-center gap-3 pt-1">
                    <a href="#" class="w-9 h-9 rounded-lg bg-white/10 hover:bg-brand-blue hover:text-white text-blue-100 flex items-center justify-center transition-all duration-200 shadow-sm" title="Facebook">
                        <i class="fa-brands fa-facebook-f text-sm"></i>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-lg bg-white/10 hover:bg-brand-blue hover:text-white text-blue-100 flex items-center justify-center transition-all duration-200 shadow-sm" title="YouTube">
                        <i class="fa-brands fa-youtube text-sm"></i>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-lg bg-white/10 hover:bg-brand-blue hover:text-white text-blue-100 flex items-center justify-center transition-all duration-200 shadow-sm" title="LinkedIn">
                        <i class="fa-brands fa-linkedin-in text-sm"></i>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-lg bg-white/10 hover:bg-brand-blue hover:text-white text-blue-100 flex items-center justify-center transition-all duration-200 shadow-sm" title="Instagram">
                        <i class="fa-brands fa-instagram text-sm"></i>
                    </a>
                </div>

                {{-- Live Support Badge --}}
                <div class="p-3 bg-white/5 border border-white/10 rounded-xl flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-headset text-sm"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-white">২৪/৭ কাস্টমার সাপোর্ট</p>
                        <p class="text-[11px] text-blue-100/70">যে কোনো প্রয়োজনে যোগাযোগ করুন</p>
                    </div>
                </div>
            </div>

        </div>

        {{-- Bottom Section: Copyright & Payment Badges --}}
        <div class="pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-blue-100/70">
            <div>
                © {{ date('Y') }} <span class="text-white font-bold">SecondShiftBD</span>. সর্বস্বত্ব সংরক্ষিত।
            </div>

            <div class="flex items-center gap-6">
                <a href="#" class="hover:text-brand-blue transition-colors">প্রাইভেসি পলিসি</a>
                <span>•</span>
                <a href="#" class="hover:text-brand-blue transition-colors">টার্মস ও কন্ডিশনস</a>
                <span>•</span>
                <a href="#" class="hover:text-brand-blue transition-colors">রিফান্ড পলিসি</a>
            </div>
        </div>

    </div>
</footer>
