@extends('layouts.public')

@section('title', $categoria->CATEGORIA_NOMBRE)

@section('content')
    <section class="py-12 px-4 max-w-6xl mx-auto">

        {{-- Encabezado de la categoría --}}
        <div class="mb-10">
            <h1 class="text-3xl font-bold text-zinc-800">{{ $categoria->CATEGORIA_NOMBRE }}</h1>
            @if ($categoria->CATEGORIA_DESCRIPCION)
                <div class="mt-4 text-zinc-600 prose max-w-none">
                    {!! $categoria->CATEGORIA_DESCRIPCION !!}
                </div>
            @endif
        </div>

        {{-- Si hay hijas: mostrar grid de subcategorías --}}
        @if ($hijas->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @foreach ($hijas as $hija)
                    <a href="{{ route('categorias.show', $hija->URL) }}"
                       class="group block rounded-xl overflow-hidden shadow hover:shadow-lg transition">
                        <div class="aspect-video overflow-hidden bg-zinc-100">
                            <img src="{{ asset('storage/img/categorias/' . $hija->IMAGEN) }}"
                                 alt="{{ $hija->CATEGORIA_NOMBRE }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                        </div>
                        <div class="p-4">
                            <h2 class="text-lg font-semibold text-zinc-800">{{ $hija->CATEGORIA_NOMBRE }}</h2>
                            @if ($hija->CATEGORIA_DESCRIPCION)
                                <p class="text-sm text-zinc-500 mt-1 line-clamp-2">
                                    {!! strip_tags($hija->CATEGORIA_DESCRIPCION) !!}
                                </p>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>

        {{-- Si no hay hijas: mostrar publicaciones --}}
        @else
            @if ($publicaciones->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    @foreach ($publicaciones as $pub)
                        <a href="{{ route('publicaciones.show', $pub->URL) }}"
                           class="group block rounded-xl overflow-hidden shadow hover:shadow-lg transition">
                            <div class="aspect-video overflow-hidden bg-zinc-100">
                                <img src="{{ asset('storage/img/publicaciones/' . $pub->IMAGEN) }}"
                                     alt="{{ $pub->PUBLICACION_TITULO }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                            </div>
                            <div class="p-4">
                                <h2 class="text-lg font-semibold text-zinc-800">{{ $pub->PUBLICACION_TITULO }}</h2>
                                @if ($pub->PUBLICACION_RESUMEN)
                                    <p class="text-sm text-zinc-500 mt-1 line-clamp-2">{{ $pub->PUBLICACION_RESUMEN }}</p>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $publicaciones->links() }}
                </div>
            @else
                <p class="text-center text-zinc-400">No hay publicaciones en esta categoría.</p>
            @endif
        @endif

    </section>
@endsection