<section
    class="relative flex items-end min-h-[50vh] pt-16"
    style="background-image: url('{{ asset('hero_bg.jpg') }}'); background-size: cover; background-position: center;"
>
    <div class="absolute inset-0 bg-black/50"></div>
    <div class="relative z-10 w-full max-w-6xl mx-auto px-6 pb-10 flex flex-col md:flex-row items-end gap-6">

        @if(!empty($heroImagen))
        <img
            src="{{ $heroImagen }}"
            alt="{{ $publicacion->PUBLICACION_TITULO }}"
            class="w-28 h-28 md:w-36 md:h-36 rounded-xl object-cover shadow-lg shrink-0 ring-2 ring-white/30"
        >
        @endif

        <div class="flex flex-col gap-2">
            @if(!empty($heroSupertitulo))
            <p class="text-white/70 text-sm font-semibold uppercase tracking-widest">{{ $heroSupertitulo }}</p>
            @endif
            <h1 class="text-3xl md:text-5xl font-bold text-white drop-shadow" style="font-family: 'Georgia', serif;">
                {{ $publicacion->PUBLICACION_TITULO }}
            </h1>
            @if(!empty($heroDescripcion))
            <p class="text-white/80 text-sm leading-relaxed max-w-2xl line-clamp-3">
                {!! strip_tags($heroDescripcion) !!}
            </p>
            @endif
        </div>

    </div>
</section>