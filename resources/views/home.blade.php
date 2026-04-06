@extends('layouts.public')

@section('title', 'Inicio')

@section('content')

    {{-- ============================================
         HERO CON CARRUSEL DE DESTACADAS
    ============================================ --}}
    <section
        class="relative overflow-hidden min-h-screen flex items-center"
        style="background-image: url('{{ asset('hero_bg.jpg') }}'); background-size: cover; background-position: center;"
        x-data="{
            current: 0,
            total: {{ $destacadas->count() }},
            perPage() { return window.innerWidth >= 768 ? 4 : 1 },
            maxIndex() { return Math.max(0, this.total - this.perPage()) },
            startX: null,
            next() {
                this.current = this.current >= this.maxIndex() ? 0 : this.current + 1
            },
            prev() {
                this.current = this.current <= 0 ? this.maxIndex() : this.current - 1
            },
            touchStart(e) { this.startX = e.touches[0].clientX },
            touchEnd(e) {
                if (this.startX === null) return
                const diff = this.startX - e.changedTouches[0].clientX
                if (Math.abs(diff) > 50) diff > 0 ? this.next() : this.prev()
                this.startX = null
            }
        }"
    >
        {{-- Overlay oscuro --}}
        <div class="absolute inset-0 bg-black/50 z-0"></div>

        {{-- Contenido --}}
        <div class="relative z-10 w-full py-16">

            @if($destacadas->count() > 0)

            {{-- Wrapper --}}
            <div class="relative">

                {{-- Máscara izquierda --}}
                <div class="absolute left-0 top-0 bottom-0 w-16 md:w-24 bg-gradient-to-r from-black/60 to-transparent z-10 pointer-events-none"></div>
                {{-- Máscara derecha --}}
                <div class="absolute right-0 top-0 bottom-0 w-16 md:w-24 bg-gradient-to-l from-black/60 to-transparent z-10 pointer-events-none"></div>

                {{-- Botón anterior --}}
                <button
                    type="button"
                    x-on:click.stop="prev()"
                    class="absolute left-2 md:left-4 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/80 text-white rounded-full p-3 transition z-20"
                    aria-label="Anterior"
                >
                    <flux:icon.chevron-left class="size-5" />
                </button>

                {{-- Botón siguiente --}}
                <button
                    type="button"
                    x-on:click.stop="next()"
                    class="absolute right-2 md:right-4 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/80 text-white rounded-full p-3 transition z-20"
                    aria-label="Siguiente"
                >
                    <flux:icon.chevron-right class="size-5" />
                </button>

                {{-- Track --}}
                <div
                    class="overflow-hidden px-8 md:px-16"
                    @touchstart="touchStart($event)"
                    @touchend="touchEnd($event)"
                >
                    <div
                        class="flex gap-3 transition-transform duration-500 ease-in-out"
                        :style="`transform: translateX(calc(-${current} * (100% / ${perPage()}) - ${current} * 0.75rem))`"
                    >
                        @foreach($destacadas as $pub)
                        <a
                            href="{{ route('publicaciones.show', $pub->URL) }}"
                            class="relative shrink-0 rounded-xl overflow-hidden group transition-all duration-300 w-[85vw] md:w-[calc(25%-0.75rem)]"
                            style="aspect-ratio: 2/3;"
                        >
                            <img
                                src="{{ $pub->IMAGEN !== 'default.jpg' ? asset('storage/img/publicaciones/' . $pub->IMAGEN) : asset('img/default.jpg') }}"
                                alt="{{ $pub->PUBLICACION_TITULO }}"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                            >
                            {{-- Overlay título --}}
                            <div class="absolute inset-0 flex items-end bg-gradient-to-b from-transparent via-primary/80 to-black/80 px-3 py-3">
                                <h2 class="text-white text-lg text-center font-medium leading-snug w-full">
                                    {{ $pub->PUBLICACION_TITULO }}
                                </h2>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

            @else
            <p class="text-center text-white/60 text-sm">No hay publicaciones destacadas por el momento.</p>
            @endif

        </div>
    </section>

    {{-- ============================================
         BIENVENIDA
    ============================================ --}}
    <section class="py-16 md:py-24 bg-white">
        <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row items-center gap-10 md:gap-16">

            {{-- Columna imagen 1/3 --}}
            <div class="w-full md:w-1/3 shrink-0">
                <img
                    src="https://placehold.co/600x700"
                    alt="Tamashi - Centro Holístico"
                    class="w-full h-auto rounded-2xl object-cover shadow-lg"
                >
            </div>

            {{-- Columna texto 2/3 --}}
            <div class="w-full md:w-2/3 text-left">
                <p class="text-accent text-sm font-semibold uppercase tracking-widest mb-2">Centro Holístico y Terapéutico</p>
                <h2 class="text-4xl md:text-5xl font-bold text-primary mb-4" style="font-family: 'Georgia', serif;">
                    Bienvenido a Tamashi
                </h2>
                <div class="flex items-center gap-2 mb-6">
                    <span class="text-2xl">🌿</span>
                    <div class="h-px bg-primary/20 flex-1"></div>
                </div>
                <p class="text-gray-700 text-lg leading-relaxed mb-4">
                    En Tamashi ofrecemos un espacio de sanación, crecimiento espiritual y bienestar integral. Creemos que cada persona tiene la capacidad de transformar su vida cuando encuentra las herramientas y el acompañamiento adecuados.
                </p>
                <p class="text-gray-600 text-base leading-relaxed mb-4">
                    A través de nuestros cursos de Reiki japonés, terapias holísticas y sesiones de acompañamiento espiritual, te guiamos en un proceso profundo de sanación del pasado, para que puedas vivir plenamente el presente y construir un mejor futuro.
                </p>
                <p class="text-gray-600 text-base leading-relaxed mb-8">
                    Nuestro centro es un lugar de convivencia, aprendizaje y transformación, donde cada persona es recibida con respeto, amor y apertura, sin importar el punto en el que se encuentre en su camino.
                </p>
                <a
                    href="{{ route('contacto') }}"
                    class="inline-block bg-primary hover:bg-secondary text-white text-sm font-semibold px-6 py-3 rounded-full transition-colors duration-300"
                >
                    Contáctanos
                </a>
            </div>

        </div>
    </section>

    {{-- ============================================
         SECCIONES POR CATEGORÍA DESTACADA
    ============================================ --}}
    @foreach($categoriasDestacadas as $cat)
    @php
        $bgClasses = $loop->even ? 'bg-white' : 'bg-secondary/20';
        $textClasses = $loop->even ? 'text-primary' : 'text-primary';
    @endphp
    <section class="py-16 {{ $bgClasses }}">
        <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row gap-10 md:gap-16 items-start">

            {{-- Columna izquierda 1/3 --}}
            <div class="w-full md:w-1/3 shrink-0 flex flex-col items-start gap-4">
                <img
                    src="{{ asset('storage/img/categorias/' . $cat->IMAGEN) }}"
                    alt="{{ $cat->CATEGORIA_NOMBRE }}"
                    class="w-36 h-36 object-cover rounded-xl shadow-md"
                >
                <h3 class="text-2xl font-bold {{ $textClasses }}" style="font-family: 'Georgia', serif;">
                    {{ $cat->CATEGORIA_NOMBRE }}
                </h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    {{ $cat->CATEGORIA_DESCRIPCION }}
                </p>
                <a
                    href="{{ route('categorias.show', $cat->URL) }}"
                    class="inline-block bg-primary hover:bg-secondary text-white text-sm font-semibold px-5 py-2.5 rounded-full transition-colors duration-300"
                >
                    Ver más
                </a>
            </div>

            {{-- Columna derecha 2/3 --}}
            <div class="w-full md:w-2/3">
                @if($cat->publicacionesDestacadas->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @foreach($cat->publicacionesDestacadas->take(4) as $pub)
                    <a
                        href="{{ route('publicaciones.show', $pub->URL) }}"
                        class="relative rounded-xl overflow-hidden group ring-2 ring-transparent hover:ring-primary/50 transition-all duration-300"
                        style="aspect-ratio: 2/3;"
                    >
                        <img
                            src="{{ $pub->IMAGEN !== 'default.jpg' ? asset('storage/img/publicaciones/' . $pub->IMAGEN) : asset('img/default.jpg') }}"
                            alt="{{ $pub->PUBLICACION_TITULO }}"
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                        >
                        <div class="absolute bottom-0 left-0 right-0 px-2 py-1.5 bg-primary/60">
                            <p class="text-white text-xs font-medium leading-snug">
                                {{ $pub->PUBLICACION_TITULO }}
                            </p>
                        </div>
                    </a>
                    @endforeach
                </div>
                @else
                <p class="text-gray-400 text-sm">No hay publicaciones disponibles en esta categoría.</p>
                @endif
            </div>

        </div>
    </section>
    @endforeach

@endsection