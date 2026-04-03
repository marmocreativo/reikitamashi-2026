@extends('layouts.public')

@section('title', $publicacion->PUBLICACION_TITULO)

@section('content')
    <article class="py-12 px-4 max-w-4xl mx-auto">

        {{-- Imagen destacada --}}
        @if ($publicacion->IMAGEN && $publicacion->IMAGEN !== 'default.jpg')
            <div class="mb-8 rounded-xl overflow-hidden aspect-video">
                <img src="{{ asset('storage/img/publicaciones/' . $publicacion->IMAGEN) }}"
                     alt="{{ $publicacion->PUBLICACION_TITULO }}"
                     class="w-full h-full object-cover" />
            </div>
        @endif

        {{-- Título --}}
        <h1 class="text-3xl font-bold text-zinc-800 mb-4">{{ $publicacion->PUBLICACION_TITULO }}</h1>

        {{-- Resumen --}}
        @if ($publicacion->PUBLICACION_RESUMEN)
            <p class="text-lg text-zinc-500 mb-6 border-l-4 border-zinc-300 pl-4">
                {{ $publicacion->PUBLICACION_RESUMEN }}
            </p>
        @endif

        {{-- Contenido --}}
        @if ($publicacion->PUBLICACION_CONTENIDO)
            <div class="prose prose-zinc max-w-none">
                {!! $publicacion->PUBLICACION_CONTENIDO !!}
            </div>
        @endif

    </article>
@endsection