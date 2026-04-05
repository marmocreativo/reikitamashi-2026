<x-layouts::app :title="__('Editar Publicación')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 p-6">

        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}" wire:navigate>Panel</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('admin.publicaciones.index') }}" wire:navigate>Publicaciones</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{ $publicacion->PUBLICACION_TITULO }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>

        <div>
            <flux:heading size="xl">{{ $publicacion->PUBLICACION_TITULO }}</flux:heading>
            <flux:text class="text-zinc-400">Editar publicación</flux:text>
        </div>

        <div x-data="{ tab: '{{ request('tab', 'general') }}' }">

            {{-- Tab buttons --}}
            <div class="flex gap-1 border-b border-zinc-200 dark:border-zinc-700 mb-6">
                <button type="button"
                    class="px-4 py-2 text-sm font-medium transition border-b-2 -mb-px"
                    :class="tab === 'general'
                        ? 'border-zinc-800 dark:border-white text-zinc-900 dark:text-white'
                        : 'border-transparent text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300'"
                    x-on:click="tab = 'general'"
                >General</button>

                <button type="button"
                    class="px-4 py-2 text-sm font-medium transition border-b-2 -mb-px"
                    :class="tab === 'galeria'
                        ? 'border-zinc-800 dark:border-white text-zinc-900 dark:text-white'
                        : 'border-transparent text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300'"
                    x-on:click="tab = 'galeria'"
                >
                    Galería
                    @if($publicacion->galeria->isNotEmpty())
                        <span class="ml-1 text-xs text-zinc-400">({{ $publicacion->galeria->count() }})</span>
                    @endif
                </button>

                <button type="button"
                    class="px-4 py-2 text-sm font-medium transition border-b-2 -mb-px"
                    :class="tab === 'metadatos'
                        ? 'border-zinc-800 dark:border-white text-zinc-900 dark:text-white'
                        : 'border-transparent text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300'"
                    x-on:click="tab = 'metadatos'"
                >
                    Metadatos
                    @if($publicacion->metaDatos->isNotEmpty())
                        <span class="ml-1 text-xs text-zinc-400">({{ $publicacion->metaDatos->count() }})</span>
                    @endif
                </button>
            </div>

            {{-- Tab: General --}}
            <div x-show="tab === 'general'">
                <form method="POST" action="{{ route('admin.publicaciones.update', $publicacion) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @include('admin.publicaciones._form', [
                        'publicacion'           => $publicacion,
                        'tipos'                 => $tipos,
                        'categorias'            => $categorias,
                        'categoriaSeleccionada' => $categoriaSeleccionada,
                    ])
                </form>
            </div>

            {{-- Tab: Galería --}}
            <div x-show="tab === 'galeria'"
                x-data="{
                    galeriaDropover: false,
                    galeriaOnDrop(e) {
                        this.galeriaDropover = false;
                        this.$refs.galeriaInput.files = e.dataTransfer.files;
                        this.$refs.galeriaForm.submit();
                    },
                }"
            >
                <div class="flex flex-col gap-4">

                    @if(session('success_galeria'))
                        <flux:callout variant="success" icon="check-circle" class="py-2">{{ session('success_galeria') }}</flux:callout>
                    @endif

                    <form
                        method="POST"
                        action="{{ route('admin.publicaciones.galeria.store', $publicacion) }}"
                        enctype="multipart/form-data"
                        x-ref="galeriaForm"
                    >
                        @csrf
                        <label
                            class="flex flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-zinc-300 dark:border-zinc-600 bg-zinc-50 dark:bg-zinc-800/50 cursor-pointer transition hover:border-zinc-400 p-6 text-center"
                            :class="galeriaDropover ? 'border-blue-400 bg-blue-50 dark:bg-blue-900/20' : ''"
                            x-on:dragover.prevent="galeriaDropover = true"
                            x-on:dragleave.prevent="galeriaDropover = false"
                            x-on:drop.prevent="galeriaOnDrop($event)"
                        >
                            <flux:icon.photo class="size-8 text-zinc-400" />
                            <span class="text-sm text-zinc-500">
                                Arrastra imágenes o <span class="text-blue-500 underline">selecciona</span>
                            </span>
                            <span class="text-xs text-zinc-400">Varias a la vez — JPG, PNG, WebP, máx. 5 MB c/u</span>
                            <input
                                type="file"
                                name="imagenes[]"
                                accept="image/*"
                                multiple
                                class="hidden"
                                x-ref="galeriaInput"
                                x-on:change="$refs.galeriaForm.submit()"
                            />
                        </label>
                        @error('imagenes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        @error('imagenes.*') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </form>

                    @if($publicacion->galeria->isNotEmpty())
                        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-2">
                            @foreach($publicacion->galeria as $img)
                                <div class="relative group rounded-lg overflow-hidden border border-zinc-200 dark:border-zinc-700">
                                    <img
                                        src="{{ asset('storage/img/publicaciones/' . $img->GALERIA_ARCHIVO) }}"
                                        class="w-full aspect-square object-cover"
                                    />
                                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                        <form method="POST" action="{{ route('admin.publicaciones.galeria.destroy', [$publicacion, $img]) }}"
                                            onsubmit="return confirm('¿Eliminar?')">
                                            @csrf
                                            @method('DELETE')
                                            <flux:button type="submit" variant="danger" size="sm" icon="trash" />
                                        </form>
                                    </div>
                                    <div class="p-1 bg-white dark:bg-zinc-900 text-center">
                                        <input
                                            type="number"
                                            value="{{ $img->ORDEN }}"
                                            min="0"
                                            class="w-10 text-center text-xs border border-zinc-300 dark:border-zinc-700 dark:bg-zinc-800 rounded px-1"
                                            onchange="actualizarOrden({{ $img->ID_GALERIA }}, this.value, '{{ route('admin.publicaciones.galeria.orden', [$publicacion, $img]) }}')"
                                        />
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-zinc-400">Sin imágenes en la galería todavía.</p>
                    @endif

                </div>
            </div>

            {{-- Tab: Metadatos --}}
            <div x-show="tab === 'metadatos'">
                <div class="flex flex-col gap-4 max-w-2xl">

                    @if(session('success_meta'))
                        <flux:callout variant="success" icon="check-circle" class="py-2">{{ session('success_meta') }}</flux:callout>
                    @endif

                    <form method="POST" action="{{ route('admin.publicaciones.metadatos.store', $publicacion) }}"
                        class="flex flex-wrap gap-3 items-end">
                        @csrf
                        <div class="flex flex-col gap-1 flex-1 min-w-40">
                            <flux:label>Nombre <span class="text-red-500">*</span></flux:label>
                            <flux:input name="DATO_NOMBRE" value="{{ old('DATO_NOMBRE') }}" placeholder="ej: precio, duracion, nivel" />
                            @error('DATO_NOMBRE') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>
                        <div class="flex flex-col gap-1 flex-1 min-w-48">
                            <flux:label>Valor <span class="text-red-500">*</span></flux:label>
                            <flux:input name="DATO_VALOR" value="{{ old('DATO_VALOR') }}" placeholder="ej: $1,500, 8 horas" />
                            @error('DATO_VALOR') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>
                        <flux:button type="submit" variant="primary" icon="plus">Guardar</flux:button>
                    </form>
                    <p class="text-xs text-zinc-400 -mt-2">Si el nombre ya existe, se actualizará su valor.</p>

                    @if($publicacion->metaDatos->isNotEmpty())
                        <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
                            <table class="w-full text-sm">
                                <thead class="bg-zinc-50 dark:bg-zinc-800 text-zinc-500 dark:text-zinc-400 uppercase text-xs tracking-wider">
                                    <tr>
                                        <th class="px-3 py-2 w-1/3">Nombre</th>
                                        <th class="px-3 py-2">Valor</th>
                                        <th class="px-3 py-2"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                                    @foreach($publicacion->metaDatos as $meta)
                                        <tr class="bg-white dark:bg-zinc-900">
                                            <td class="px-3 py-2 font-mono text-xs text-zinc-600 dark:text-zinc-300">{{ $meta->DATO_NOMBRE }}</td>
                                            <td class="px-3 py-2 text-zinc-600 dark:text-zinc-300 break-all">{{ $meta->DATO_VALOR }}</td>
                                            <td class="px-3 py-2 text-right">
                                                <form method="POST" action="{{ route('admin.publicaciones.metadatos.destroy', [$publicacion, $meta->DATO_NOMBRE]) }}"
                                                    onsubmit="return confirm('¿Eliminar este metadato?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <flux:button type="submit" variant="ghost" size="sm" icon="trash" class="text-red-400 hover:text-red-600" />
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-sm text-zinc-400">Sin metadatos todavía.</p>
                    @endif

                </div>
            </div>

        </div>

    </div>

    @push('scripts')
    <script>
    function actualizarOrden(id, orden, url) {
        fetch(url, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ orden: parseInt(orden) }),
        });
    }
    </script>
    @endpush

</x-layouts::app>