@extends('layouts.public')

@section('title', $categoria->CATEGORIA_NOMBRE)

@section('content')

    {{-- ============================================
         MINI HERO
    ============================================ --}}
    <section
        class="relative flex items-end min-h-[50vh] pt-16"
        style="background-image: url('{{ asset('hero_bg.jpg') }}'); background-size: cover; background-position: center;"
    >
        <div class="absolute inset-0 bg-black/50"></div>
        <div class="relative z-10 w-full max-w-6xl mx-auto px-6 pb-10 flex flex-col md:flex-row items-end gap-6">

            {{-- Imagen de la categoría --}}
            <img
                src="{{ asset('storage/img/categorias/' . $categoria->IMAGEN) }}"
                alt="{{ $categoria->CATEGORIA_NOMBRE }}"
                class="w-28 h-28 md:w-36 md:h-36 rounded-xl object-cover shadow-lg shrink-0 ring-2 ring-white/30"
            >

            {{-- Texto --}}
            <div class="flex flex-col gap-2">
                <p class="text-white/70 text-sm font-semibold uppercase tracking-widest">{{ $categoria->TIPO }}</p>
                <h1 class="text-3xl md:text-5xl font-bold text-white drop-shadow" style="font-family: 'Georgia', serif;">
                    {{ $categoria->CATEGORIA_NOMBRE }}
                </h1>
                @if($categoria->CATEGORIA_DESCRIPCION)
                <div class="text-white/80 text-sm leading-relaxed max-w-2xl line-clamp-3">
                    {!! strip_tags($categoria->CATEGORIA_DESCRIPCION) !!}
                </div>
                @endif
            </div>

        </div>
    </section>

    {{-- ============================================
         CONTENIDO
    ============================================ --}}
    <section class="py-16 bg-background">
        <div class="max-w-6xl mx-auto px-6">

            {{-- Si hay subcategorías --}}
            @if($hijas->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($hijas as $hija)
                    <a
                        href="{{ route('categorias.show', $hija->URL) }}"
                        class="relative rounded-xl overflow-hidden group ring-2 ring-transparent hover:ring-primary/50 transition-all duration-300"
                        style="aspect-ratio: 2/3;"
                    >
                        <img
                            src="{{ asset('storage/img/categorias/' . $hija->IMAGEN) }}"
                            alt="{{ $hija->CATEGORIA_NOMBRE }}"
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 px-3 py-3">
                            <p class="text-white font-semibold text-sm leading-snug">{{ $hija->CATEGORIA_NOMBRE }}</p>
                            @if($hija->CATEGORIA_DESCRIPCION)
                            <p class="text-white/70 text-xs mt-1 line-clamp-2">{!! strip_tags($hija->CATEGORIA_DESCRIPCION) !!}</p>
                            @endif
                        </div>
                    </a>
                    @endforeach
                </div>

            {{-- Si hay publicaciones --}}
            @elseif($publicaciones->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($publicaciones as $pub)
                    <a
                        href="{{ route('publicaciones.show', $pub->URL) }}"
                        class="relative rounded-xl overflow-hidden group ring-2 ring-transparent hover:ring-primary/50 transition-all duration-300 flex flex-col bg-white shadow-sm"
                    >
                        <div class="overflow-hidden shrink-0" style="aspect-ratio: 2/3;">
                            <img
                                src="{{ $pub->IMAGEN !== 'default.jpg' ? asset('storage/img/publicaciones/' . $pub->IMAGEN) : asset('img/default.jpg') }}"
                                alt="{{ $pub->PUBLICACION_TITULO }}"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                            >
                        </div>
                        <div class="px-3 py-3 flex flex-col gap-1">
                            <p class="text-primary font-semibold text-sm leading-snug">{{ $pub->PUBLICACION_TITULO }}</p>
                            @if($pub->PUBLICACION_RESUMEN)
                            <p class="text-gray-500 text-xs line-clamp-2">{{ $pub->PUBLICACION_RESUMEN }}</p>
                            @endif
                        </div>
                    </a>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $publicaciones->links() }}
                </div>

            @else
                <p class="text-gray-400 text-sm text-center">No hay contenido disponible en esta categoría.</p>
            @endif

        </div>
    </section>

@endsection