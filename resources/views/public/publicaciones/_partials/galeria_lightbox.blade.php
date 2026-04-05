@if($publicacion->galeria->count() > 0)
<div
    x-data="{
        lightbox: false,
        current: 0,
        images: {{ $publicacion->galeria->pluck('GALERIA_ARCHIVO')->toJson() }},
        open(index) { this.current = index; this.lightbox = true },
        prev() { this.current = this.current <= 0 ? this.images.length - 1 : this.current - 1 },
        next() { this.current = this.current >= this.images.length - 1 ? 0 : this.current + 1 }
    }"
>
    {{-- Grid de miniaturas --}}
    <div class="grid gap-2 {{ $columnas ?? 'grid-cols-2 md:grid-cols-4' }}">
        @foreach($publicacion->galeria as $i => $img)
        <button
            type="button"
            class="overflow-hidden rounded-lg aspect-square group"
            @click="open({{ $i }})"
        >
            <img
                src="{{ asset('storage/img/publicaciones/' . $img->GALERIA_ARCHIVO) }}"
                alt="Imagen {{ $i + 1 }}"
                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
            >
        </button>
        @endforeach
    </div>

    {{-- Lightbox --}}
    <template x-teleport="body">
        <div
            x-show="lightbox"
            x-cloak
            class="fixed inset-0 z-[9999] bg-black flex items-center justify-center"
            @keydown.escape.window="lightbox = false"
        >
            {{-- Fondo clickeable para cerrar --}}
            <div
                class="absolute inset-0 bg-black cursor-pointer"
                @click="lightbox = false"
            ></div>

            {{-- Botón cerrar --}}
            <button
                type="button"
                class="absolute top-5 right-5 z-[9999] bg-white/20 hover:bg-white/40 text-white rounded-full p-2 transition"
                @click.stop="lightbox = false"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>

            {{-- Botón anterior --}}
            <button
                type="button"
                class="absolute left-5 top-1/2 -translate-y-1/2 z-[9999] bg-white/20 hover:bg-white/40 text-white rounded-full p-3 transition"
                @click.stop="prev()"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
            </button>

            {{-- Imagen --}}
            <div class="relative z-[9999] max-h-[85vh] max-w-[85vw] pointer-events-none">
                <img
                    :src="`/storage/img/publicaciones/${images[current]}`"
                    class="max-h-[85vh] max-w-[85vw] rounded-lg object-contain shadow-2xl"
                >
            </div>

            {{-- Botón siguiente --}}
            <button
                type="button"
                class="absolute right-5 top-1/2 -translate-y-1/2 z-[9999] bg-white/20 hover:bg-white/40 text-white rounded-full p-3 transition"
                @click.stop="next()"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            </button>

            {{-- Contador --}}
            <div class="absolute bottom-5 left-1/2 -translate-x-1/2 z-[9999] bg-black/50 text-white text-sm px-3 py-1 rounded-full">
                <span x-text="current + 1"></span> / <span x-text="images.length"></span>
            </div>
        </div>
    </template>
</div>
@endif