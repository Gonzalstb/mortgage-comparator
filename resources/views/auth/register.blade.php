<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="max-w-md mx-auto">
        @csrf
        <h2 class="text-2xl font-bold mb-8 text-center text-accent tracking-tight">Crear cuenta</h2>
        <!-- Name -->
        <div class="mb-6">
            <label for="name" class="block mb-2 text-sm font-medium text-accent">Nombre</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name"
                class="bg-light border border-gray-300 text-gray-900 text-base rounded-lg focus:ring-accent focus:border-accent block w-full p-3 placeholder-gray-400 shadow-sm" placeholder="Tu nombre" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>
        <!-- Email Address -->
        <div class="mb-6">
            <label for="email" class="block mb-2 text-sm font-medium text-accent">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username"
                class="bg-light border border-gray-300 text-gray-900 text-base rounded-lg focus:ring-accent focus:border-accent block w-full p-3 placeholder-gray-400 shadow-sm" placeholder="tucorreo@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <!-- Password -->
        <div class="mb-6">
            <label for="password" class="block mb-2 text-sm font-medium text-accent">Contraseña</label>
            <input id="password" name="password" type="password" required autocomplete="new-password"
                class="bg-light border border-gray-300 text-gray-900 text-base rounded-lg focus:ring-accent focus:border-accent block w-full p-3 placeholder-gray-400 shadow-sm" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <!-- Confirm Password -->
        <div class="mb-6">
            <label for="password_confirmation" class="block mb-2 text-sm font-medium text-accent">Confirmar contraseña</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                class="bg-light border border-gray-300 text-gray-900 text-base rounded-lg focus:ring-accent focus:border-accent block w-full p-3 placeholder-gray-400 shadow-sm" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>
        <div class="flex items-center justify-between mb-6">
            <a class="text-sm text-accent hover:underline" href="{{ route('login') }}">
                ¿Ya tienes cuenta?
            </a>
        </div>
        <button type="submit" class="w-full text-white bg-accent hover:bg-gold focus:ring-4 focus:outline-none focus:ring-accent font-semibold rounded-lg text-base px-5 py-3 text-center shadow-elegant transition-all duration-200">Registrarse</button>
    </form>
</x-guest-layout>
