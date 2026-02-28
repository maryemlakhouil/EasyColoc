<x-guest-layout>
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 via-cyan-500 to-green-500 bg-clip-text text-transparent mb-2">
            {{ __('Join EasyColoc') }}
        </h1>
        <p class="text-gray-600 dark:text-gray-400 text-base">
            {{ __('Create your account and start managing shared expenses') }}
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-6">
            <x-input-label for="name" :value="__('Full Name')" />
            <x-text-input 
                id="name" 
                type="text" 
                name="name" 
                :value="old('name')" 
                required 
                autofocus 
                autocomplete="name"
                placeholder="John Doe" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mb-6">
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input 
                id="email" 
                type="email" 
                name="email" 
                :value="old('email')" 
                required 
                autocomplete="username"
                placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-6">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input 
                id="password" 
                type="password"
                name="password"
                required 
                autocomplete="new-password"
                placeholder="••••••••" />
            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                {{ __('At least 8 characters with uppercase, lowercase, and numbers') }}
            </p>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-8">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input 
                id="password_confirmation" 
                type="password"
                name="password_confirmation" 
                required 
                autocomplete="new-password"
                placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg mb-6">
            {{ __('Create Account') }}
        </button>

        <!-- Divider -->
        <div class="relative mb-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400">
                    {{ __('or') }}
                </span>
            </div>
        </div>

        <!-- Login Link -->
        <div class="text-center">
            <p class="text-gray-600 dark:text-gray-400 text-sm">
                {{ __('Already have an account?') }}
                <a href="{{ route('login') }}" class="font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors">
                    {{ __('Sign in here') }}
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
