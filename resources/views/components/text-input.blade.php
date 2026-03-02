@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full border border-gray-200 bg-gray-50 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent focus:bg-white transition']) }}>
