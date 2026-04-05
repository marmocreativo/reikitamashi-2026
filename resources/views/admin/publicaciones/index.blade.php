<x-layouts::app>
    <div class="flex items-center justify-between mb-6">
        <flux:heading size="xl">Publicaciones</flux:heading>
        <flux:button href="{{ route('admin.publicaciones.create') }}" variant="primary" icon="plus">
            Nueva publicación
        </flux:button>
    </div>

    {{-- Filtros --}}
    <form method="GET" action="{{ route('admin.publicaciones.index') }}" class="flex flex-wrap gap-3 mb-6">
    @if($categoria)
        <input type="hidden" name="categoria" value="{{ $categoria }}">
    @endif
        <flux:input name="buscar" placeholder="Buscar por título..." value="{{ $buscar }}" class="w-64" />

        <flux:select name="tipo" placeholder="Todos los tipos">
            <flux:select.option value="">Todos los tipos</flux:select.option>
            @foreach (\App\Models\Publicacion::TIPOS as $t)
                <flux:select.option value="{{ $t }}" :selected="$tipo === $t">{{ $t }}</flux:select.option>
            @endforeach
        </flux:select>

        <flux:select name="estado" placeholder="Todos los estados">
            <flux:select.option value="">Todos los estados</flux:select.option>
            <flux:select.option value="activo" :selected="$estado === 'activo'">Activo</flux:select.option>
            <flux:select.option value="inactivo" :selected="$estado === 'inactivo'">Inactivo</flux:select.option>
        </flux:select>

        <flux:button type="submit" variant="filled">Filtrar</flux:button>
        <flux:button href="{{ route('admin.publicaciones.index') }}">Limpiar</flux:button>
    </form>

    {{-- Mensajes flash --}}
    @if (session('success'))
        <flux:callout variant="success" class="mb-4">{{ session('success') }}</flux:callout>
    @endif
    @if (session('error'))
        <flux:callout variant="danger" class="mb-4">{{ session('error') }}</flux:callout>
    @endif

    {{-- Tabla --}}
    <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">
        <table class="w-full text-sm text-left">
            <thead class="bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3">Imagen</th>
                    <th class="px-4 py-3">Título</th>
                    <th class="px-4 py-3">Tipo</th>
                    <th class="px-4 py-3">Estado</th>
                    <th class="px-4 py-3">Orden</th>
                    <th class="px-4 py-3">Destacada</th>
                    <th class="px-4 py-3">Fecha registro</th>
                    <th class="px-4 py-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                @forelse ($publicaciones as $pub)
                    <tr class="bg-white dark:bg-zinc-900 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
                        <td class="px-4 py-3">
                            <img
                                src="{{ $pub->IMAGEN !== 'default.jpg' ? asset('storage/img/publicaciones/' . $pub->IMAGEN) : asset('img/default.jpg') }}"
                                alt="{{ $pub->PUBLICACION_TITULO }}"
                                class="w-14 h-14 object-cover rounded-lg"
                            >
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-medium text-zinc-900 dark:text-zinc-100">{{ $pub->PUBLICACION_TITULO }}</div>
                            <div class="text-xs text-zinc-400">{{ $pub->URL }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <flux:badge>{{ $pub->TIPO }}</flux:badge>
                        </td>
                        <td class="px-4 py-3">
                            <flux:badge variant="{{ $pub->ESTADO === 'activo' ? 'lime' : 'zinc' }}">
                                {{ $pub->ESTADO }}
                            </flux:badge>
                        </td>
                        <td class="px-4 py-3 text-zinc-600 dark:text-zinc-300">{{ $pub->ORDEN }}</td>
                        <td class="px-4 py-3 text-zinc-600 dark:text-zinc-300">
                            <form action="{{ route('admin.publicaciones.destacada', $pub) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" title="{{ $pub->DESTACADA ? 'Quitar destacada' : 'Marcar como destacada' }}">
                                    @if($pub->DESTACADA)
                                        <flux:icon.star class="size-5 text-accent" variant="solid" />
                                    @else
                                        <flux:icon.star class="size-5 text-zinc-300 hover:text-accent transition" />
                                    @endif
                                </button>
                            </form>
                        </td>
                        <td class="px-4 py-3 text-zinc-600 dark:text-zinc-300">{{ $pub->FECHA_REGISTRO?->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                <flux:button href="{{ route('admin.publicaciones.edit', $pub) }}" size="sm" variant="filled" icon="pencil">
                                    Editar
                                </flux:button>
                                <form method="POST" action="{{ route('admin.publicaciones.destroy', $pub) }}"
                                    onsubmit="return confirm('¿Eliminar esta publicación?')">
                                    @csrf
                                    @method('DELETE')
                                    <flux:button type="submit" size="sm" variant="danger" icon="trash">
                                        Eliminar
                                    </flux:button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-zinc-400">
                            No se encontraron publicaciones.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $publicaciones->links() }}
    </div>
</x-layouts::app>