<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Logo -->
        <div class="flex justify-center mb-2">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-16 w-auto">
        </div>

        <!-- Nama Sistem -->
        <div class="text-center mb-1">
            <h1 class="text-xl font-bold text-gray-800">IT Support Ticketing System</h1>
        </div>

        <!-- Pesan Welcome -->
        <div class="text-center mb-6">
            <p class="text-sm text-gray-500">Silakan login untuk melaporkan kendala IT Anda</p>
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        <!-- Nama Perusahaan -->
        <div class="text-center mt-6 pt-4 border-t border-gray-200">
            <p class="text-xs text-gray-400">© {{ date('Y') }} <span class="font-semibold text-gray-500">Century</span>. All rights reserved.</p>
        </div>

    </form>
</x-guest-layout>