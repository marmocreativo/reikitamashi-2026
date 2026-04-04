<x-layouts::app>
    <div class="flex items-center gap-4 mb-6">
        <flux:button href="{{ route('admin.publicaciones.index') }}" variant="ghost" icon="arrow-left" />
        <flux:heading size="xl">Editar: {{ $publicacion->PUBLICACION_TITULO }}</flux:heading>
    </div>

    @include('admin.publicaciones._form', [
        'action' => route('admin.publicaciones.update', $publicacion),
        'method' => 'PUT',
        'publicacion' => $publicacion,
        'tipos' => $tipos,
    ])

    {{-- Galería --}}
    <div class="mt-10">
        <flux:heading size="lg" class="mb-4">Galería de imágenes</flux:heading>

        @if(session('success_galeria'))
            <flux:callout variant="success" icon="check-circle" class="mb-4">{{ session('success_galeria') }}</flux:callout>
        @endif

        {{-- Imágenes actuales --}}
        @if($publicacion->galeria->isNotEmpty())
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 mb-6">
                @foreach($publicacion->galeria as $img)
                    <div class="relative group rounded-lg overflow-hidden border border-zinc-200 dark:border-zinc-700">
                        <img
                            src="{{ asset('storage/img/publicaciones/' . $img->GALERIA_ARCHIVO) }}"
                            alt="Imagen galería"
                            class="w-full aspect-video object-cover"
                        />
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-2">
                            <form method="POST" action="{{ route('admin.publicaciones.galeria.destroy', [$publicacion, $img]) }}"
                                onsubmit="return confirm('¿Eliminar esta imagen?')">
                                @csrf
                                @method('DELETE')
                                <flux:button type="submit" variant="danger" size="sm" icon="trash" />
                            </form>
                        </div>
                        <div class="p-1 bg-white dark:bg-zinc-900 text-xs text-zinc-400 text-center">
                            Orden:
                            <input
                                type="number"
                                value="{{ $img->ORDEN }}"
                                min="0"
                                class="w-12 text-center border border-zinc-300 rounded px-1"
                                onchange="actualizarOrden({{ $img->ID_GALERIA }}, this.value, '{{ route('admin.publicaciones.galeria.orden', [$publicacion, $img]) }}')"
                            />
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-zinc-400 text-sm mb-6">Esta publicación no tiene imágenes en la galería.</p>
        @endif

        {{-- Subir nuevas imágenes --}}
        <form method="POST"
            action="{{ route('admin.publicaciones.galeria.store', $publicacion) }}"
            enctype="multipart/form-data"
            class="flex flex-col gap-3">
            @csrf
            <flux:label>Agregar imágenes (puedes seleccionar varias)</flux:label>
            <input type="file" name="imagenes[]" accept="image/*" multiple
                class="block w-full text-sm text-zinc-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100" />
            @error('imagenes') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
            @error('imagenes.*') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
            <div>
                <flux:button type="submit" variant="primary" icon="arrow-up-tray">Subir imágenes</flux:button>
            </div>
        </form>
    </div>

    {{-- Metadatos --}}
    <div class="mt-10">
        <flux:heading size="lg" class="mb-4">Metadatos</flux:heading>

        @if(session('success_meta'))
            <flux:callout variant="success" icon="check-circle" class="mb-4">{{ session('success_meta') }}</flux:callout>
        @endif

        {{-- Metadatos existentes --}}
        @if($publicacion->metaDatos->isNotEmpty())
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 overflow-hidden mb-6">
                <table class="w-full text-sm text-left">
                    <thead class="bg-zinc-50 dark:bg-zinc-800 text-zinc-500 dark:text-zinc-400 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-4 py-3 font-medium w-1/3">Nombre</th>
                            <th class="px-4 py-3 font-medium">Valor</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        @foreach($publicacion->metaDatos as $meta)
                            <tr class="bg-white dark:bg-zinc-900">
                                <td class="px-4 py-3 font-mono text-xs text-zinc-600 dark:text-zinc-300">{{ $meta->DATO_NOMBRE }}</td>
                                <td class="px-4 py-3 text-zinc-600 dark:text-zinc-300 break-all">{{ $meta->DATO_VALOR }}</td>
                                <td class="px-4 py-3">
                                    <form method="POST" action="{{ route('admin.publicaciones.metadatos.destroy', [$publicacion, $meta->DATO_NOMBRE]) }}"
                                        onsubmit="return confirm('¿Eliminar este metadato?')">
                                        @csrf
                                        @method('DELETE')
                                        <flux:button type="submit" variant="danger" size="sm" icon="trash" />
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-zinc-400 text-sm mb-6">Esta publicación no tiene metadatos.</p>
        @endif

        {{-- Agregar / actualizar metadato --}}
        <form method="POST" action="{{ route('admin.publicaciones.metadatos.store', $publicacion) }}"
            class="flex flex-wrap gap-3 items-end">
            @csrf
            <div class="flex flex-col gap-1 flex-1 min-w-40">
                <flux:label>Nombre <span class="text-red-500">*</span></flux:label>
                <flux:input type="text" name="DATO_NOMBRE" value="{{ old('DATO_NOMBRE') }}"
                            placeholder="ej: precio, duracion, nivel" />
                @error('DATO_NOMBRE') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
            </div>
            <div class="flex flex-col gap-1 flex-1 min-w-60">
                <flux:label>Valor <span class="text-red-500">*</span></flux:label>
                <flux:input type="text" name="DATO_VALOR" value="{{ old('DATO_VALOR') }}"
                            placeholder="ej: $1,500, 8 horas, Principiante" />
                @error('DATO_VALOR') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
            </div>
            <flux:button type="submit" variant="primary" icon="plus">Guardar metadato</flux:button>
        </form>
        <p class="text-xs text-zinc-400 mt-2">Si el nombre ya existe, se actualizará su valor.</p>
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