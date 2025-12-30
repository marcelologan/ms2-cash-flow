<x-guest-layout>
    <!-- Logo -->
    <div class="text-center mb-8">
        <img src="{{ asset('images/MS2_Cash_Flow_Logo.png') }}" 
             alt="MS² Cash Flow" 
             class="h-16 w-auto mx-auto mb-4">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Crie sua conta</h1>
        <p class="text-gray-600 text-sm">Comece a gerenciar suas finanças hoje</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" value="Nome completo" />
            <x-text-input id="name" 
                         class="block mt-1 w-full border-2 border-gray-300 focus:border-yellow-400 focus:ring focus:ring-yellow-200 focus:ring-opacity-50 rounded-lg px-4 py-3" 
                         type="text" 
                         name="name" 
                         :value="old('name')" 
                         required 
                         autofocus 
                         autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" value="E-mail" />
            <x-text-input id="email" 
                         class="block mt-1 w-full border-2 border-gray-300 focus:border-yellow-400 focus:ring focus:ring-yellow-200 focus:ring-opacity-50 rounded-lg px-4 py-3" 
                         type="email" 
                         name="email" 
                         :value="old('email')" 
                         required 
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
                         autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Confirmar senha" />
            <x-text-input id="password_confirmation" 
                         class="block mt-1 w-full border-2 border-gray-300 focus:border-yellow-400 focus:ring focus:ring-yellow-200 focus:ring-opacity-50 rounded-lg px-4 py-3"
                         type="password"
                         name="password_confirmation" 
                         required 
                         autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="text-sm text-gray-600 hover:text-gray-900" 
               href="{{ route('login') }}">
                Já tem conta? <span class="text-yellow-600 font-semibold">Faça login</span>
            </a>

            <button type="submit" 
                    class="ms-4 px-6 py-3 bg-gradient-to-r from-yellow-400 to-yellow-500 text-white font-semibold rounded-lg shadow-lg hover:from-yellow-500 hover:to-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transform transition hover:scale-105">
                Criar conta
            </button>
        </div>
    </form>
</x-guest-layout>