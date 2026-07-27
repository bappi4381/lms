<footer class="glass-footer mx-4 sm:mx-6 lg:mx-8 mb-6 rounded-[2rem] pt-14 sm:pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 pb-12 border-b glass-divider">
            {{-- Brand --}}
            <div class="space-y-4">
                <x-application-logo compact wordmark="lg" />
                <p class="text-sm text-neu-muted leading-relaxed max-w-xs">
                    বাংলাদেশের শিক্ষার্থীদের জন্য মানসম্মত অনলাইন শিক্ষা — একাডেমিক থেকে প্রফেশনাল, সব এক প্ল্যাটফর্মে।
                </p>
            </div>

            {{-- Learn --}}
            <div class="space-y-4">
                <h4 class="text-brand-navy text-base font-bold">শেখা</h4>
                <ul class="space-y-2.5 text-sm text-neu-muted">
                    <li><a href="{{ route('courses.list') }}" class="hover:text-brand-blue transition-colors">সকল কোর্স</a></li>
                    <li><a href="{{ route('courses.list', ['resources' => 1]) }}" class="hover:text-brand-blue transition-colors">ফ্রি রিসোর্স</a></li>
                    <li><a href="{{ route('courses.list', ['blog' => 1]) }}" class="hover:text-brand-blue transition-colors">ব্লগ</a></li>
                </ul>
            </div>

            {{-- Platform --}}
            <div class="space-y-4">
                <h4 class="text-brand-navy text-base font-bold">প্ল্যাটফর্ম</h4>
                <ul class="space-y-2.5 text-sm text-neu-muted">
                    <li><a href="{{ route('support.index') }}" class="hover:text-brand-blue transition-colors">কমিউনিটি ফোরাম</a></li>
                    <li><a href="{{ route('courses.list') }}" class="hover:text-brand-blue transition-colors">লাইভ ক্লাস</a></li>
                    <li><a href="{{ route('subscriptions.index') }}" class="hover:text-brand-blue transition-colors">মেন্টরশিপ</a></li>
                    <li><a href="{{ route('courses.list') }}" class="hover:text-brand-blue transition-colors">জব বোর্ড</a></li>
                </ul>
            </div>

            {{-- Contact --}}
            <div class="space-y-4">
                <h4 class="text-brand-navy text-base font-bold">যোগাযোগ</h4>
                <div class="flex items-center gap-3">
                    <a href="#" class="w-10 h-10 rounded-2xl glass-footer-icon" title="Facebook" aria-label="Facebook">
                        <i class="fa-brands fa-facebook-f text-sm"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-2xl glass-footer-icon" title="YouTube" aria-label="YouTube">
                        <i class="fa-brands fa-youtube text-sm"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-2xl glass-footer-icon" title="Instagram" aria-label="Instagram">
                        <i class="fa-brands fa-instagram text-sm"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-2xl glass-footer-icon" title="LinkedIn" aria-label="LinkedIn">
                        <i class="fa-brands fa-linkedin-in text-sm"></i>
                    </a>
                </div>
                <p class="text-xs text-neu-muted">support@secondshiftbd.com</p>
            </div>
        </div>

        <div class="pt-8 text-center text-xs sm:text-sm text-neu-muted">
            © {{ date('Y') }} SecondShiftBD. সর্বস্বত্ব সংরক্ষিত।
        </div>
    </div>
</footer>
