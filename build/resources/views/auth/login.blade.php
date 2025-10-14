<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - السفير</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-r from-cyan-400 to-blue-500">

    <div class="w-full max-w-md bg-white dark:bg-gray-800 shadow-lg rounded-xl p-8">

        <!-- عنوان -->
        <h2 class="text-center text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6">
            👔 تسجيل الدخول
        </h2>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Form -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-4">
                <x-input-label for="email" :value="__('البريد الإلكتروني')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email"
                              name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mb-4">
                <x-input-label for="password" :value="__('كلمة المرور')" />
                <x-text-input id="password" class="block mt-1 w-full"
                              type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me + Forgot Password -->
            <div class="flex items-center justify-between mb-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox"
                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                           name="remember">
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('تذكرني') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm text-indigo-600 hover:underline" href="{{ route('password.request') }}">
                        {{ __('هل نسيت كلمة المرور؟') }}
                    </a>
                @endif
            </div>

            <!-- Submit Button -->
            <x-primary-button class="w-full justify-center">
                {{ __('تسجيل الدخول') }}
            </x-primary-button>
        </form>
    </div>

</body>
</html>
