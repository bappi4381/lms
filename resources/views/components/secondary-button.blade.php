<button {{ $attributes->merge(['type' => 'button', 'class' => 'glass-btn-ghost px-4 py-2 rounded-xl text-xs uppercase tracking-widest disabled:opacity-25']) }}>
    {{ $slot }}
</button>
