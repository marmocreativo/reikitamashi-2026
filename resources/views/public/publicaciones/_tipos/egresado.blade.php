@php $publicacion->load('metaDatos'); @endphp

{{-- Hero estilo perfil --}}
<section class="relative pt-16">

    {{-- Portada --}}
    <div
        class="w-full h-48 md:h-64 bg-cover bg-center"
        style="background-image: url('{{ asset('hero_bg.jpg') }}');"
    >
        <div class="absolute inset-0 bg-black/60 h-48 md:h-64 mt-16"></div>
    </div>

    {{-- Foto de perfil --}}
    <div class="max-w-6xl mx-auto px-6">
        {{-- Solo la foto sube sobre la portada --}}
        <div class="relative -mt-16 md:-mt-20 mb-2">
            <img
                src="{{ $publicacion->IMAGEN !== 'default.jpg' ? asset('storage/img/publicaciones/' . $publicacion->IMAGEN) : asset('img/default.jpg') }}"
                alt="{{ $publicacion->PUBLICACION_TITULO }}"
                class="w-28 h-28 md:w-36 md:h-36 rounded-full object-cover ring-4 ring-white shadow-xl"
            >
        </div>
        {{-- Título y resumen debajo, ya fuera de la portada --}}
        <div class="mt-3 mb-4">
            <h1 class="text-2xl md:text-3xl font-bold text-primary" style="font-family: 'Georgia', serif;">
                {{ $publicacion->PUBLICACION_TITULO }}
            </h1>
            @if($publicacion->PUBLICACION_RESUMEN)
            <p class="text-gray-500 text-sm mt-1">{{ $publicacion->PUBLICACION_RESUMEN }}</p>
            @endif
        </div>
    </div>
</section>

<section class="py-10 bg-background">
    <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row gap-8 items-start">

        {{-- Columna izquierda 1/4 metadatos --}}
        @if($publicacion->metaDatos->count() > 0)
        <div class="w-full md:w-1/4 shrink-0">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-4 py-3 bg-primary">
                    <p class="text-white text-sm font-bold uppercase tracking-wider">Información</p>
                </div>
                <table class="w-full text-sm">
                    @foreach($publicacion->metaDatos as $meta)
                        @if($meta->DATO_VALOR)
                        <tr class="border-b border-gray-100 last:border-0">
                            <td class="px-4 py-3">
                                <b class="text-primary text-uppercase">{{ $meta->DATO_NOMBRE }}</b><br>
                                {!! $meta->DATO_VALOR !!}
                            </td>
                        </tr>
                        @endif
                    @endforeach
                </table>
            </div>
        </div>
        @endif

        {{-- Columna derecha 3/4 --}}
        <div class="w-full md:w-3/4 flex flex-col gap-6">

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

        </div>

    </div>
</section>