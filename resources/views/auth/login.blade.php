<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Logo -->
    <div class="text-center mb-8">
        <img src="{{ asset('images/MS2_Cash_Flow_Logo.png') }}" 
             alt="MS² Cash Flow" 
             class="h-16 w-auto mx-auto mb-4">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Bem-vindo de volta!</h1>
        <p class="text-gray-600 text-sm">Entre na sua conta para continuar</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="E-mail" />
            <x-text-input id="email" 
                         class="block mt-1 w-full border-2 border-gray-300 focus:border-yellow-400 focus:ring focus:ring-yellow-200 focus:ring-opacity-50 rounded-lg px-4 py-3" 
                         type="email" 
                         name="email" 
                         :value="old('email')" 
                         required 
                         autofocus 
                         autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" value="Senha" />
            <x-text-input id="password" 
                         class="block mt-1 w-full border-2 border-gray-300 focus:border-yellow-400 focus:ring focus:ring-yellow-200 focus:ring-opacity-50 rounded-lg px-4 py-3"
                         type="password"
                         name="password"
                         required 
                         autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" 
                       type="checkbox" 
                       class="rounded border-gray-300 text-yellow-400 shadow-sm focus:ring-yellow-500" 
                       name="remember">
                <span class="ms-2 text-sm text-gray-600">Lembrar de mim</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-yellow-600 hover:text-yellow-800 underline" 
                   href="{{ route('password.request') }}">
                    Esqueceu a senha?
                </a>
            @endif
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="text-sm text-gray-600 hover:text-gray-900" 
               href="{{ route('register') }}">
                Não tem conta? <span class="text-yellow-600 font-semibold">Cadastre-se</span>
            </a>

            <button type="submit" 
                    class="ms-3 px-6 py-3 bg-gradient-to-r from-yellow-400 to-yellow-500 text-white font-semibold rounded-lg shadow-lg hover:from-yellow-500 hover:to-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transform transition hover:scale-105">
                Entrar
            </button>
        </div>
    </form>
</x-guest-layout>