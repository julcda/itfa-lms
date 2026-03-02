<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-5 py-2.5 rounded-xl font-semibold text-sm text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition']) }} style="background:linear-gradient(135deg,#10b981,#059669)">
    {{ $slot }}
</button>
