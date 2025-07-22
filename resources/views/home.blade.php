@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-elegant-gradient flex items-center justify-center py-12 px-4">
    <div class="max-w-xl w-full space-y-8">
        <div class="bg-white shadow-elegant rounded-2xl p-8">
            <h1 class="text-3xl font-bold text-accent mb-4">Bienvenido al Comparador de Hipotecas</h1>
            <p class="text-gray-700 mb-6">Compara productos hipotecarios de diferentes bancos y encuentra la mejor opción para ti. Simula, guarda tus favoritos y gestiona tu perfil de forma sencilla y segura.</p>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('login') }}" class="w-full sm:w-auto text-white bg-accent hover:bg-gold font-semibold rounded-lg px-6 py-3 text-center shadow-elegant transition">Iniciar sesión</a>
                <a href="{{ route('register') }}" class="w-full sm:w-auto text-accent border border-accent hover:bg-accent hover:text-white font-semibold rounded-lg px-6 py-3 text-center shadow-elegant transition">Crear cuenta</a>
            </div>
        </div>
    </div>
</div>
@endsection
