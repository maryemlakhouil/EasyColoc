<x-guest-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="text-center">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-500 to-cyan-500 bg-clip-text text-transparent mb-2">
                Welcome Back
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Sign in to your EasyColoc account
            </p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email Address')" />
                <x-text-input
                    id="email"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="you@example.com"
                />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center gap-3 py-2">
                <input
                    id="remember_me"
                    type="checkbox"
                    name="remember"
                    class="w-4 h-4 rounded border-2 border-gray-300 dark:border-slate-600 text-blue-500 bg-white dark:bg-slate-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-slate-800 cursor-pointer transition-all"
                />
                <label for="remember_me" class="text-sm text-gray-700 dark:text-gray-300 cursor-pointer font-medium">
                    {{ __('Remember me') }}
                </label>
            </div>

            <!-- Forgot Password & Submit -->
            <div class="flex items-center justify-between pt-2">
                @if (Route::has('password.request'))
                    <a
                        class="text-sm text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-300 font-medium transition-colors"
                        href="{{ route('password.request') }}"
                    >
                        {{ __('Forgot password?') }}
                    </a>
                @endif

                <x-primary-button>
                    {{ __('Sign In') }}
                </x-primary-button>
            </div>

            <!-- Sign Up Link -->
            <div class="text-center pt-4 border-t border-gray-200 dark:border-slate-700">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __("Don't have an account?") }}
                    <a href="{{ route('register') }}" class="text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-300 font-semibold transition-colors">
                        {{ __('Sign up') }}
                    </a>
                </p>
            </div>
        </form>
    </div>
</x-guest-layout>
