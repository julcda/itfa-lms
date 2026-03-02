<x-guest-layout>
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">

        {{-- Header --}}
        <div class="mb-7">
            <h2 class="text-2xl font-bold text-gray-900">{{ __('messages.create_account') }}</h2>
            <p class="text-gray-400 text-sm mt-1">{{ __('messages.register_subtitle') }}</p>
        </div>

        @if($errors->any())
        <div class="mb-5 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
            <ul class="list-disc ms-4 space-y-0.5">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            {{-- Name --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.name') }}</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                       class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition {{ $errors->has('name') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-gray-50 focus:bg-white' }}">
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.email') }}</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                       class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-gray-50 focus:bg-white' }}">
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.password') }}</label>
                <input type="password" id="password" name="password" required autocomplete="new-password"
                       class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-gray-50 focus:bg-white' }}">
            </div>

            {{-- Confirm Password --}}
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.confirm_password') }}</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                       class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition {{ $errors->has('password_confirmation') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-gray-50 focus:bg-white' }}">
            </div>

            {{-- Submit --}}
            <button type="submit"
                    class="w-full py-2.5 px-4 rounded-xl text-sm font-semibold text-white transition shadow-sm"
                    style="background:linear-gradient(135deg,#10b981,#059669);box-shadow:0 4px 14px rgba(16,185,129,0.35)">
                {{ __('messages.register') }}
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-6">
            {{ __('messages.already_have_account') }}
            <a href="{{ route('login') }}" class="text-emerald-600 hover:text-emerald-800 font-semibold">{{ __('messages.login') }}</a>
        </p>
    </div>
</x-guest-layout>
