@extends('layouts.public')

@section('title', 'Inicio')

@section('content')

    {{-- ============================================
         HERO / SLIDER
    ============================================ --}}
    <section
        class="relative overflow-hidden bg-black"
        x-data="{
            current: 0,
            slides: [0, 1, 2],
            autoplay: null,
            start() {
                this.autoplay = setInterval(() => this.next(), 5000)
            },
            next() {
                this.current = (this.current + 1) % this.slides.length
            },
            prev() {
                this.current = (this.current - 1 + this.slides.length) % this.slides.length
            }
        }"
        x-init="start()"
    >
        {{-- Slides --}}
        <div class="relative h-[520px] md:h-[680px]">

            {{-- Slide 1 --}}
            <div
                class="absolute inset-0 transition-opacity duration-700"
                x-bind:class="current === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0'"
            >
                <img
                    src="{{ asset('https://placehold.co/1600x700') }}"
                    alt="Slide 1"
                    class="w-full h-full object-cover"
                >
                <div class="absolute inset-0 bg-black/40 flex flex-col items-center justify-center text-center px-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Tamashi" class="h-32 w-auto mb-4 drop-shadow-lg">
                    <h1 class="text-white text-4xl md:text-5xl font-bold drop-shadow mb-2" style="font-family: 'Georgia', serif;">
                        Tamashi
                    </h1>
                    <p class="text-white text-xl md:text-2xl font-light drop-shadow mb-1">Cursos de Reiki japonés</p>
                    <p class="text-white text-base md:text-lg font-light drop-shadow">Armoniza tu energía</p>
                </div>
            </div>

            {{-- Slide 2 --}}
            <div
                class="absolute inset-0 transition-opacity duration-700"
                x-bind:class="current === 1 ? 'opacity-100 z-10' : 'opacity-0 z-0'"
            >
                <img
                    src="{{ asset('https://placehold.co/1600x700') }}"
                    alt="Slide 2"
                    class="w-full h-full object-cover"
                >
                <div class="absolute inset-0 bg-black/40 flex flex-col items-center justify-center text-center px-4">
                    <p class="text-white text-3xl md:text-4xl font-semibold drop-shadow">Centro Holístico y Terapéutico</p>
                </div>
            </div>

            {{-- Slide 3 --}}
            <div
                class="absolute inset-0 transition-opacity duration-700"
                x-bind:class="current === 2 ? 'opacity-100 z-10' : 'opacity-0 z-0'"
            >
                <img
                    src="{{ asset('https://placehold.co/1600x700') }}"
                    alt="Slide 3"
                    class="w-full h-full object-cover"
                >
                <div class="absolute inset-0 bg-black/40 flex flex-col items-center justify-center text-center px-4">
                    <p class="text-white text-3xl md:text-4xl font-semibold drop-shadow">Sanación del pasado, bienestar en el presente</p>
                </div>
            </div>

        </div>

        {{-- Botón anterior --}}
        <button
            x-on:click="prev()"
            class="absolute left-4 top-1/2 -translate-y-1/2 z-20 bg-black/40 hover:bg-black/60 text-white rounded-full p-2 transition"
            aria-label="Anterior"
        >
            <flux:icon.chevron-left class="size-6" />
        </button>

        {{-- Botón siguiente --}}
        <button
            x-on:click="next()"
            class="absolute right-4 top-1/2 -translate-y-1/2 z-20 bg-black/40 hover:bg-black/60 text-white rounded-full p-2 transition"
            aria-label="Siguiente"
        >
            <flux:icon.chevron-right class="size-6" />
        </button>

        {{-- Dots --}}
        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 z-20 flex gap-2">
            <template x-for="i in slides" :key="i">
                <button
                    x-on:click="current = i"
                    x-bind:class="current === i ? 'bg-white scale-110' : 'bg-white/50'"
                    class="w-2.5 h-2.5 rounded-full transition-all duration-300"
                    :aria-label="'Ir al slide ' + (i + 1)"
                ></button>
            </template>
        </div>
    </section>

    {{-- ============================================
         BIENVENIDA
    ============================================ --}}
    <section class="py-16 bg-white text-center">
        <div class="max-w-2xl mx-auto px-4">
            <h2 class="text-3xl font-semibold text-gray-800 mb-4">Bienvenido a Tamashi</h2>
            <div class="flex justify-center mb-4">
                <span class="text-green-600 text-3xl">🌿</span>
            </div>
            <p class="text-gray-600 text-base leading-relaxed">
                Ofrecemos ayuda espiritual y un lugar de convivencia
            </p>
            <p class="text-gray-500 text-sm mt-2 leading-relaxed">
                Sanación de pasado, para vivir bien el presente, y ser mejores en el futuro
            </p>
        </div>
    </section>

    {{-- ============================================
         CURSOS REIKI
    ============================================ --}}
    <section class="py-16 bg-purple-800">
        <div class="max-w-6xl mx-auto px-4">

            <h2 class="text-white text-2xl font-semibold text-center mb-2">Reiki Japonés</h2>
            <div class="border-b border-purple-500 w-48 mx-auto mb-10"></div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                @php
                    $cursos = [
                        [
                            'imagen'      => 'https://placehold.co/600x400',
                            'titulo'      => 'Reiki Japonés Nivel I — Despertar a la luz',
                            'descripcion' => 'En este módulo del nivel 1 de nuestros cursos de reiki, tendremos el...',
                            'url'         => '#',
                        ],
                        [
                            'imagen'      => 'https://placehold.co/600x400',
                            'titulo'      => 'Reiki Japonés Módulo II',
                            'descripcion' => 'En el Módulo II de nuestros cursos de reiki, tendremos...',
                            'url'         => '#',
                        ],
                        [
                            'imagen'      => 'https://placehold.co/600x400',
                            'titulo'      => 'Reiki Japonés Nivel 2 — Servidor de Luz',
                            'descripcion' => 'En el nivel 2 de nuestros cursos de reiki, tendremos...',
                            'url'         => '#',
                        ],
                        [
                            'imagen'      => 'https://placehold.co/600x400',
                            'titulo'      => 'Reiki Japonés Nivel 3 — Portador de Luz',
                            'descripcion' => 'El nivel 3 de nuestros cursos de reiki, tendremos el...',
                            'url'         => '#',
                        ],
                    ];
                @endphp

                @foreach ($cursos as $curso)
                    <a href="{{ $curso['url'] }}" class="bg-white rounded-lg overflow-hidden shadow hover:shadow-lg transition group">
                        <img
                            src="{{ asset($curso['imagen']) }}"
                            alt="{{ $curso['titulo'] }}"
                            class="w-full h-40 object-cover group-hover:scale-105 transition-transform duration-300"
                        >
                        <div class="p-4">
                            <h3 class="text-purple-700 text-sm font-semibold leading-snug mb-2 group-hover:text-purple-900 transition">
                                {{ $curso['titulo'] }}
                            </h3>
                            <p class="text-gray-500 text-xs leading-relaxed">
                                {{ $curso['descripcion'] }}
                            </p>
                        </div>
                    </a>
                @endforeach

            </div>
        </div>
    </section>

    {{-- ============================================
         TERAPIAS
    ============================================ --}}
    <section class="py-16 bg-white">
        <div class="max-w-6xl mx-auto px-4">

            <h2 class="text-gray-800 text-2xl font-semibold text-center mb-2">Terapias</h2>
            <div class="border-b border-gray-300 w-24 mx-auto mb-10"></div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                @php
                    $terapias = [
                        [
                            'imagen'      => 'https://placehold.co/600x400',
                            'titulo'      => 'Barras de Access',
                            'descripcion' => 'Comienza a crear tu vida con Facilidad, Gozo y Gloria...',
                            'url'         => '#',
                        ],
                        [
                            'imagen'      => 'https://placehold.co/600x400',
                            'titulo'      => 'Ceremonias Holísticas de Cumpleaños Individuales',
                            'descripcion' => 'Ceremonias Holísticas de Cumpleaños Individuales: un espacio sagrado dedicado exclusivamente...',
                            'url'         => '#',
                        ],
                        [
                            'imagen'      => 'https://placehold.co/600x400',
                            'titulo'      => 'Reiki Japonés',
                            'descripcion' => 'Una sesión de Reiki puede durar aproximadamente cuarenta y cinco...',
                            'url'         => '#',
                        ],
                        [
                            'imagen'      => 'https://placehold.co/600x400',
                            'titulo'      => 'Registros Akáshicos',
                            'descripcion' => 'Los Registros Akáshicos son una base de información ilimitada del...',
                            'url'         => '#',
                        ],
                    ];
                @endphp

                @foreach ($terapias as $terapia)
                    <a href="{{ $terapia['url'] }}" class="border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition group">
                        <img
                            src="{{ asset($terapia['imagen']) }}"
                            alt="{{ $terapia['titulo'] }}"
                            class="w-full h-40 object-cover group-hover:scale-105 transition-transform duration-300"
                        >
                        <div class="p-4">
                            <h3 class="text-purple-700 text-sm font-semibold leading-snug mb-2 group-hover:text-purple-900 transition">
                                {{ $terapia['titulo'] }}
                            </h3>
                            <p class="text-gray-500 text-xs leading-relaxed">
                                {{ $terapia['descripcion'] }}
                            </p>
                        </div>
                    </a>
                @endforeach

            </div>
        </div>
    </section>

@endsection