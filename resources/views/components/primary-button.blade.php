<button {{ $attributes->merge(['type' => 'submit', 'class' => 'glass-btn px-4 py-2 rounded-xl text-xs uppercase tracking-widest']) }}>
    {{ $slot }}
</button>
