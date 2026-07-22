<div {{ $attributes->merge(['class' => 'flex items-center gap-2.5 font-extrabold tracking-tight select-none']) }}>
    <!-- Logo Icon -->
    <div class="relative flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-tr from-amber-500 via-yellow-400 to-amber-300 text-slate-950 shadow-md shadow-amber-400/20 ring-1 ring-black/5">
        <svg class="w-6 h-6 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
        </svg>
    </div>
    <!-- Logo Text -->
    <div class="flex flex-col leading-none">
        <span class="text-xl font-black text-slate-900 tracking-wider">LMS<span class="text-amber-500">.</span></span>
        <span class="text-[9px] font-bold tracking-widest text-slate-400 uppercase">Learning Platform</span>
    </div>
</div>
