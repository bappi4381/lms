<button {{ $attributes->merge(['type' => 'submit', 'class' => 'glass-btn-danger px-4 py-2 rounded-xl text-xs uppercase tracking-widest']) }}>
    {{ $slot }}
</button>
