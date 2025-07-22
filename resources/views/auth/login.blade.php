<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="max-w-md mx-auto">
        @csrf
        <h2 class="text-2xl font-bold mb-8 text-center text-accent tracking-tight">Bienvenido de nuevo</h2>
        <!-- Email Address -->
        <div class="mb-6">
            <label for="email" class="block mb-2 text-sm font-medium text-accent">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                class="bg-light border border-gray-300 text-gray-900 text-base rounded-lg focus:ring-accent focus:border-accent block w-full p-3 placeholder-gray-400 shadow-sm" placeholder="tucorreo@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <!-- Password -->
        <div class="mb-6">
            <label for="password" class="block mb-2 text-sm font-medium text-accent">Contraseña</label>
            <input id="password" name="password" type="password" required autocomplete="current-password"
                class="bg-light border border-gray-300 text-gray-900 text-base rounded-lg focus:ring-accent focus:border-accent block w-full p-3 placeholder-gray-400 shadow-sm" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <!-- Remember Me -->
        <div class="flex items-center mb-6">
            <input id="remember_me" name="remember" type="checkbox" class="w-4 h-4 text-accent bg-light border-gray-300 rounded focus:ring-accent">
            <label for="remember_me" class="ms-2 text-sm text-gray-600">Recordarme</label>
        </div>
        <div class="flex items-center justify-between mb-6">
            @if (Route::has('password.request'))
                <a class="text-sm text-accent hover:underline" href="{{ route('password.request') }}">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif
        </div>
        <button type="submit" class="w-full text-white bg-accent hover:bg-gold focus:ring-4 focus:outline-none focus:ring-accent font-semibold rounded-lg text-base px-5 py-3 text-center shadow-elegant transition-all duration-200">Entrar</button>
    </form>
</x-guest-layout>
