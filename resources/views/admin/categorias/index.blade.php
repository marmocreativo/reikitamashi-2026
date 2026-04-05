<x-layouts::app :title="__('Categorías')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 p-6">

        {{-- Breadcrumbs --}}
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}" wire:navigate>
                Panel
            </flux:breadcrumbs.item>
            @isset($padre)
                <flux:breadcrumbs.item href="{{ route('admin.categorias.index') }}" wire:navigate>
                    Categorías
                </flux:breadcrumbs.item>
                <flux:breadcrumbs.item>{{ $padre->CATEGORIA_NOMBRE }}</flux:breadcrumbs.item>
            @else
                <flux:breadcrumbs.item>Categorías</flux:breadcrumbs.item>
            @endisset
        </flux:breadcrumbs>

        {{-- Encabezado --}}
        <div>
            @isset($padre)
                <flux:heading size="xl">{{ $padre->CATEGORIA_NOMBRE }}</flux:heading>
                <flux:text class="text-zinc-400">Subcategorías de esta sección</flux:text>
            @else
                <flux:heading size="xl">Categorías</flux:heading>
                <flux:text class="text-zinc-400">Gestiona las categorías y su jerarquía</flux:text>
            @endisset
        </div>

        {{-- Barra de herramientas --}}
        <div class="flex flex-wrap items-center justify-between gap-3">

            {{-- Filtros de tipo --}}
            <div class="flex flex-wrap gap-1.5">
                <flux:button
                    href="{{ route('admin.categorias.index') }}"
                    variant="{{ !isset($tipo) || !$tipo ? 'primary' : 'ghost' }}"
                    size="sm"
                    wire:navigate
                >
                    Todos
                </flux:button>

                @foreach(\App\Models\Publicacion::TIPOS as $t)
                    <flux:button
                        href="{{ route('admin.categorias.index', ['tipo' => $t]) }}"
                        variant="{{ (isset($tipo) && $tipo === $t) ? 'primary' : 'ghost' }}"
                        size="sm"
                        wire:navigate
                    >
                        {{ ucfirst(str_replace('_', ' ', $t)) }}
                    </flux:button>
                @endforeach
            </div>

            {{-- Acción principal --}}
            <flux:button
                icon="plus"
                variant="primary"
                size="sm"
                href="{{ route('admin.categorias.create', array_filter([
                    'padre' => isset($padre) ? $padre->ID_CATEGORIA : null,
                    'tipo'  => isset($tipo) && $tipo ? $tipo : (isset($padre) ? $padre->TIPO : null),
                ])) }}"
                wire:navigate
            >
                Nueva categoría
            </flux:button>
        </div>

        {{-- Mensajes --}}
        @if(session('success'))
            <flux:callout variant="success" icon="check-circle" class="py-2">
                {{ session('success') }}
            </flux:callout>
        @endif

        @if(session('error'))
            <flux:callout variant="danger" icon="x-circle" class="py-2">
                {{ session('error') }}
            </flux:callout>
        @endif

        {{-- Tabla --}}
        <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 text-left text-xs uppercase tracking-wider text-zinc-500 dark:bg-zinc-800 dark:text-zinc-400">
                    <tr>
                        <th class="px-3 py-2">Imagen</th>
                        <th class="px-3 py-2">Nombre / URL</th>
                        <th class="px-3 py-2">Tipo</th>
                        <th class="px-3 py-2 text-center">Visible</th>
                        <th class="px-3 py-2 text-center">Estado</th>
                        <th class="px-3 py-2 text-center">Orden</th>
                        <th class="px-3 py-2 text-center">★</th>
                        <th class="px-3 py-2"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 bg-white dark:divide-zinc-700/60 dark:bg-zinc-900">
                    @forelse ($categorias as $categoria)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">

                            {{-- Imagen --}}
                            <td class="px-3 py-2">
                                <img
                                    src="{{ asset('storage/img/categorias/' . $categoria->IMAGEN) }}"
                                    alt="{{ $categoria->CATEGORIA_NOMBRE }}"
                                    class="h-9 w-9 rounded-lg object-cover"
                                />
                            </td>

                            {{-- Nombre + URL --}}
                            <td class="px-3 py-2">
                                <span class="block font-medium leading-tight text-zinc-800 dark:text-white">
                                    {{ $categoria->CATEGORIA_NOMBRE }}
                                </span>
                                <span class="text-xs text-zinc-400">/{{ $categoria->URL }}</span>
                            </td>

                            {{-- Tipo --}}
                            <td class="px-3 py-2">
                                <flux:badge size="sm" variant="outline">
                                    {{ ucfirst(str_replace('_', ' ', $categoria->TIPO)) }}
                                </flux:badge>
                            </td>

                            {{-- Visible --}}
                            <td class="px-3 py-2 text-center">
                                @if($categoria->VISIBLE === 'visible')
                                    <flux:badge size="sm" color="green">Sí</flux:badge>
                                @else
                                    <flux:badge size="sm" color="zinc">No</flux:badge>
                                @endif
                            </td>

                            {{-- Estado --}}
                            <td class="px-3 py-2 text-center">
                                @if($categoria->ESTADO === 'activo')
                                    <flux:badge size="sm" color="green">Activo</flux:badge>
                                @else
                                    <flux:badge size="sm" color="red">Inactivo</flux:badge>
                                @endif
                            </td>

                            {{-- Orden --}}
                            <td class="px-3 py-2 text-center text-xs text-zinc-500 dark:text-zinc-400">
                                {{ $categoria->ORDEN }}
                            </td>

                            {{-- Destacada --}}
                            <td class="px-3 py-2 text-center">
                                <form action="{{ route('admin.categorias.destacada', $categoria) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" title="{{ $categoria->DESTACADA ? 'Quitar destacada' : 'Marcar destacada' }}">
                                        @if($categoria->DESTACADA)
                                            <flux:icon.star class="size-4 text-yellow-400" variant="solid" />
                                        @else
                                            <flux:icon.star class="size-4 text-zinc-300 hover:text-yellow-400 transition" />
                                        @endif
                                    </button>
                                </form>
                            </td>

                            {{-- Acciones --}}
                            <td class="px-3 py-2">
                                <div class="flex items-center justify-end gap-1">

                                    <flux:button
                                        size="sm"
                                        variant="ghost"
                                        icon="folder-open"
                                        href="{{ route('admin.categorias.hijas', $categoria) }}"
                                        wire:navigate
                                        title="Subcategorías"
                                    >
                                        @if($categoria->hijas_count > 0)
                                            <span class="text-xs text-zinc-500">{{ $categoria->hijas_count }}</span>
                                        @endif
                                    </flux:button>

                                    <flux:button
                                        size="sm"
                                        variant="ghost"
                                        icon="document-text"
                                        href="{{ route('admin.publicaciones.index', ['categoria' => $categoria->ID_CATEGORIA]) }}"
                                        wire:navigate
                                        title="Ver publicaciones"
                                    />

                                    <flux:button
                                        size="sm"
                                        variant="ghost"
                                        icon="pencil"
                                        href="{{ route('admin.categorias.edit', $categoria) }}"
                                        wire:navigate
                                        title="Editar"
                                    />

                                    <form method="POST" action="{{ route('admin.categorias.destroy', $categoria) }}">
                                        @csrf
                                        @method('DELETE')
                                        <flux:button
                                            size="sm"
                                            variant="ghost"
                                            icon="trash"
                                            type="submit"
                                            class="text-red-400 hover:text-red-600"
                                            onclick="return confirm('¿Eliminar esta categoría?')"
                                            title="Eliminar"
                                        />
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-10 text-center text-zinc-400">
                                No hay categorías registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-layouts::app>