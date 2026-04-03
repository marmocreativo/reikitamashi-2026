@extends('layouts.public')

@section('title', 'Categorías')

@section('content')
    <section class="py-12 px-4 max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-center mb-10">Categorías</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @forelse ($categorias as $cat)
                <a href="{{ route('categorias.show', $cat->URL) }}"
                   class="group block rounded-xl overflow-hidden shadow hover:shadow-lg transition">
                    <div class="aspect-video overflow-hidden bg-zinc-100">
                        <img src="{{ asset('storage/img/categorias/' . $cat->IMAGEN) }}"
                             alt="{{ $cat->CATEGORIA_NOMBRE }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                    </div>
                    <div class="p-4">
                        <h2 class="text-lg font-semibold text-zinc-800">{{ $cat->CATEGORIA_NOMBRE }}</h2>
                        @if ($cat->CATEGORIA_DESCRIPCION)
                            <p class="text-sm text-zinc-500 mt-1 line-clamp-2">
                                {!! strip_tags($cat->CATEGORIA_DESCRIPCION) !!}
                            </p>
                        @endif
                    </div>
                </a>
            @empty
                <p class="col-span-3 text-center text-zinc-400">No hay categorías disponibles.</p>
            @endforelse
        </div>
    </section>
@endsection