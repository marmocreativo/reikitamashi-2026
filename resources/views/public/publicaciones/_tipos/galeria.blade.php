@php $publicacion->load('galeria'); @endphp

@php
    $heroSupertitulo = null;
    $heroImagen = null;
    $heroDescripcion = $publicacion->PUBLICACION_RESUMEN;
@endphp
@include('public.publicaciones._partials.mini_hero')

<section class="py-16 bg-background">
    <div class="max-w-6xl mx-auto px-6">
        @include('public.publicaciones._partials.galeria_lightbox', ['columnas' => 'grid-cols-2 md:grid-cols-6'])
    </div>
</section>