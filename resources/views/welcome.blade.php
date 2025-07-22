{{-- Landing page moderna inspirada en la referencia, adaptada al branding del comparador de hipotecas --}}
@extends('layouts.landing')
@section('content')
    {{-- Header --}}
    <header class="w-full flex items-center justify-between py-4 px-6 bg-white/80 backdrop-blur border-b border-gray-100 shadow-sm fixed top-0 left-0 z-30">
        <div class="flex items-center gap-3">
            <x-application-logo class="w-10 h-10 text-accent" />
            <span class="text-2xl font-extrabold bg-gradient-to-r from-accent to-gold bg-clip-text text-transparent tracking-tight">Comparador de Hipotecas</span>
        </div>
        <nav class="hidden md:flex gap-8 text-gray-700 font-medium">
            <a href="#como-funciona" class="hover:text-accent transition">¿Cómo funciona?</a>
            <a href="#ventajas" class="hover:text-accent transition">Ventajas</a>
            <a href="#contacto" class="hover:text-accent transition">Contacto</a>
        </nav>
        <div class="flex gap-2">
            <a href="{{ route('login') }}" class="hidden md:inline-block">
                <x-secondary-button class="px-5 py-2 rounded-lg">Iniciar sesión</x-secondary-button>
            </a>
            <a href="{{ route('register') }}" class="hidden md:inline-block">
                <x-primary-button class="px-5 py-2 rounded-lg bg-accent hover:bg-gold">Registrarse</x-primary-button>
            </a>
            <a href="{{ route('simulations.create') }}" class="md:ml-4">
                <x-primary-button class="px-5 py-2 rounded-lg bg-accent hover:bg-gold">Simular mi hipoteca</x-primary-button>
            </a>
        </div>
    </header>

    {{-- Hero principal --}}
    <section class="w-full min-h-[90vh] pt-32 pb-12 px-4 bg-gradient-to-br from-[#f5f6fa] via-white to-[#e9eaf3] dark:from-[#0a0a0a] dark:via-[#161615] dark:to-[#23232b] flex items-center justify-center">
        <div class="w-full max-w-7xl flex flex-col md:flex-row items-center justify-center gap-12">
            {{-- Columna izquierda: mensaje principal --}}
            <div class="flex-1 flex flex-col items-start justify-center max-w-xl gap-6">
                <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 dark:text-white leading-tight mb-2">Compara hipotecas y <span class="bg-gradient-to-r from-accent to-gold bg-clip-text text-transparent">encuentra la mejor opción</span> para ti</h1>
                <p class="text-lg sm:text-xl text-gray-700 dark:text-gray-200 font-medium mb-2">Ahorra tiempo y dinero comparando condiciones de decenas de bancos en segundos. Simula, analiza y elige la hipoteca que más se adapta a tus necesidades.</p>
                <ul class="flex flex-col gap-2 text-base text-gray-600 dark:text-gray-300 mb-4">
                    <li class="flex items-center gap-2"><span class="text-accent font-bold">✓</span> Comparación imparcial y transparente</li>
                    <li class="flex items-center gap-2"><span class="text-accent font-bold">✓</span> Simulación personalizada en 1 minuto</li>
                    <li class="flex items-center gap-2"><span class="text-accent font-bold">✓</span> Sin compromiso, 100% gratuito</li>
                </ul>
                <div class="flex gap-4 mt-2">
                    <a href="{{ route('simulations.create') }}">
                        <x-primary-button class="px-8 py-4 text-lg rounded-xl bg-accent hover:bg-gold shadow-elegant font-bold uppercase tracking-wide">Simular mi hipoteca</x-primary-button>
                    </a>
                    <a href="{{ route('register') }}">
                        <x-secondary-button class="px-8 py-4 text-lg rounded-xl font-bold uppercase tracking-wide">Registrarse</x-secondary-button>
                    </a>
                </div>
            </div>
            {{-- Columna derecha: formulario rápido --}}
            <div class="flex-1 flex justify-center items-center w-full mt-12 md:mt-0">
                <form class="bg-white/90 dark:bg-[#18181c] shadow-elegant border border-gray-200 dark:border-gray-700 rounded-2xl p-8 w-full max-w-md flex flex-col gap-4 backdrop-blur">
                    <h2 class="text-2xl font-bold text-center text-accent mb-2">Comienza tu simulación</h2>
                    <input type="text" placeholder="Nombre" class="bg-light border border-gray-300 text-gray-900 text-base rounded-lg focus:ring-accent focus:border-accent block w-full p-3 placeholder-gray-400 shadow-sm" required />
                    <input type="email" placeholder="Email" class="bg-light border border-gray-300 text-gray-900 text-base rounded-lg focus:ring-accent focus:border-accent block w-full p-3 placeholder-gray-400 shadow-sm" required />
                    <input type="number" placeholder="Importe deseado (€)" class="bg-light border border-gray-300 text-gray-900 text-base rounded-lg focus:ring-accent focus:border-accent block w-full p-3 placeholder-gray-400 shadow-sm" required />
                    <input type="number" placeholder="Años de hipoteca" class="bg-light border border-gray-300 text-gray-900 text-base rounded-lg focus:ring-accent focus:border-accent block w-full p-3 placeholder-gray-400 shadow-sm" required />
                    <button type="submit" class="w-full text-white bg-accent hover:bg-gold focus:ring-4 focus:outline-none focus:ring-accent font-semibold rounded-lg text-base px-5 py-3 text-center shadow-elegant transition-all duration-200 mt-2">Simular ahora</button>
                    <span class="text-xs text-gray-400 text-center mt-2">Sin spam. No compartimos tus datos.</span>
                </form>
            </div>
        </div>
    </section>

    {{-- Sección de ventajas (ancla) --}}
    <section id="ventajas" class="max-w-5xl mx-auto py-16 px-4 flex flex-col md:flex-row gap-12 items-center">
        <div class="flex-1">
            <h3 class="text-2xl font-bold mb-4 text-accent">¿Por qué usar nuestro comparador?</h3>
            <ul class="list-disc pl-6 text-gray-700 dark:text-gray-200 space-y-2">
                <li>Acceso a ofertas de los principales bancos en España</li>
                <li>Simulaciones realistas y personalizadas</li>
                <li>Sin comisiones ni letra pequeña</li>
                <li>Atención personalizada si lo necesitas</li>
            </ul>
        </div>
        <div class="flex-1 flex justify-center">
            <img src="/images/ventajas-hipoteca.svg" alt="Ventajas de comparar hipotecas" class="w-80 h-auto rounded-xl shadow-lg hidden md:block" />
        </div>
    </section>

    {{-- Footer --}}
    <footer id="contacto" class="w-full py-8 bg-gray-50 dark:bg-[#18181c] border-t border-gray-200 dark:border-gray-700 text-center text-gray-500 text-sm mt-8">
        &copy; {{ date('Y') }} Comparador de Hipotecas. Todos los derechos reservados.
    </footer>
@endsection
