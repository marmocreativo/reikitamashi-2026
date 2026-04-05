@php $publicacion->load('galeria', 'metaDatos'); @endphp

@php
    $heroSupertitulo = $publicacion->TIPO;
    $heroImagen = null;
    $heroDescripcion = null;
@endphp
@include('public.publicaciones._partials.mini_hero')

<section class="py-16 bg-background">
    <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row gap-8 items-start">

        {{-- Columna izquierda 3/4 --}}
        <div class="w-full md:w-3/4 flex flex-col gap-8">

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

            {{-- Galería --}}
            @if($publicacion->galeria->count() > 0)
            <div>
                <h2 class="text-xl font-bold text-primary mb-4" style="font-family: 'Georgia', serif;">Galería</h2>
                @include('public.publicaciones._partials.galeria_lightbox', ['columnas' => 'grid-cols-2 md:grid-cols-4'])
            </div>
            @endif

        </div>

        {{-- Columna derecha 1/4 --}}
        <div class="w-full md:w-1/4 shrink-0 flex flex-col gap-6 rounded-2xl overflow-hidden">

            {{-- Imagen --}}
            @if($publicacion->IMAGEN && $publicacion->IMAGEN !== 'default.jpg')
            <img
                src="{{ asset('storage/img/publicaciones/' . $publicacion->IMAGEN) }}"
                alt="{{ $publicacion->PUBLICACION_TITULO }}"
                class="w-full rounded-xl object-cover shadow-md"
                style="aspect-ratio: 3/4;"
            >
            @endif

            {{-- Metadatos --}}
            @if($publicacion->metaDatos->count() > 0)
            <div class="bg-secondary/10 rounded-xl overflow-hidden">
                <table class="w-full text-sm">
                    @foreach($publicacion->metaDatos as $meta)
                        @if($meta->DATO_VALOR)
                        <tr class="border-b border-secondary/20 last:border-0">
                            <td class="px-4 py-3 font-bold text-primary align-top w-2/5">{{ $meta->DATO_NOMBRE }}</td>
                            <td class="px-4 py-3 text-gray-600 align-top">{!! $meta->DATO_VALOR !!}</td>
                        </tr>
                        @endif
                    @endforeach
                </table>
            </div>
            @endif

            {{-- Formulario WhatsApp --}}
            <div class="bg-secondary rounded-xl px-4 py-5">
                @include('public.publicaciones._partials.whatsapp_form')
            </div>

        </div>
    </div>
</section>