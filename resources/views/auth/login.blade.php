<x-guest-layout>
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">

        {{-- Header --}}
        <div class="mb-7">
            <h2 class="text-2xl font-bold text-gray-900">{{ __('messages.welcome_back') }}</h2>
            <p class="text-gray-400 text-sm mt-1">{{ __('messages.login_subtitle') }}</p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        @if($errors->any())
        <div class="mb-5 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
            <ul class="list-disc ms-4 space-y-0.5">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.email') }}</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                       class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-gray-50 focus:bg-white' }}">
            </div>

            {{-- Password --}}
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label for="password" class="block text-sm font-medium text-gray-700">{{ __('messages.password') }}</label>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs text-emerald-600 hover:text-emerald-800 font-medium">
                            {{ __('messages.forgot_password') }}
                        </a>
                    @endif
                </div>
                <input type="password" id="password" name="password" required autocomplete="current-password"
                       class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-gray-50 focus:bg-white' }}">
            </div>

            {{-- Remember me --}}
            <div class="flex items-center gap-2">
                <input id="remember_me" type="checkbox" name="remember"
                       class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                <label for="remember_me" class="text-sm text-gray-600 cursor-pointer select-none">{{ __('messages.remember_me') }}</label>
            </div>

            {{-- Submit --}}
            <button type="submit"
                    class="w-full py-2.5 px-4 rounded-xl text-sm font-semibold text-white transition shadow-sm"
                    style="background:linear-gradient(135deg,#10b981,#059669);box-shadow:0 4px 14px rgba(16,185,129,0.35)">
                {{ __('messages.login') }}
            </button>
        </form>

        @if(Route::has('register'))
        <p class="text-center text-sm text-gray-500 mt-6">
            {{ __('messages.no_account') }}
            <a href="{{ route('register') }}" class="text-emerald-600 hover:text-emerald-800 font-semibold">{{ __('messages.register') }}</a>
        </p>
        @endif
    </div>
</x-guest-layout>
