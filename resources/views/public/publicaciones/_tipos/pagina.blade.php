@php $publicacion->load('galeria'); @endphp

@php
    $heroSupertitulo = null;
    $heroImagen = $publicacion->IMAGEN !== 'default.jpg' ? asset('storage/img/publicaciones/' . $publicacion->IMAGEN) : null;
    $heroDescripcion = null;
@endphp
@include('public.publicaciones._partials.mini_hero')

<section class="py-16 bg-background">
    <div class="max-w-4xl mx-auto px-6 flex flex-col gap-8">

        @if($publicacion->PUBLICACION_RESUMEN)
        <p class="text-lg text-gray-500 border-l-4 border-primary/40 pl-4 leading-relaxed">
            {{ $publicacion->PUBLICACION_RESUMEN }}
        </p>
        @endif

        @if($publicacion->PUBLICACION_CONTENIDO)
        <div class="prose prose-zinc max-w-none">
            {!! $publicacion->PUBLICACION_CONTENIDO !!}
        </div>
        @endif

        @if($publicacion->galeria->count() > 0)
        <div>
            <h2 class="text-xl font-bold text-primary mb-4" style="font-family: 'Georgia', serif;">Galería</h2>
            @include('public.publicaciones._partials.galeria_lightbox', ['columnas' => 'grid-cols-2 md:grid-cols-4'])
        </div>
        @endif

    </div>
</section>