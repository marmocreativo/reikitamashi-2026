<x-layouts::app :title="__('Publicaciones')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 p-6">

        {{-- Breadcrumbs --}}
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}" wire:navigate>Panel</flux:breadcrumbs.item>
            @if($categoria)
                <flux:breadcrumbs.item href="{{ route('admin.categorias.index') }}" wire:navigate>Categorías</flux:breadcrumbs.item>
                <flux:breadcrumbs.item href="{{ route('admin.categorias.hijas', $categoria) }}" wire:navigate>
                    {{ $categoria->CATEGORIA_NOMBRE }}
                </flux:breadcrumbs.item>
            @endif
            <flux:breadcrumbs.item>Publicaciones</flux:breadcrumbs.item>
        </flux:breadcrumbs>

        {{-- Encabezado --}}
        <div>
            <flux:heading size="xl">
                @if($categoria)
                    {{ $categoria->CATEGORIA_NOMBRE }}
                @else
                    Publicaciones
                @endif
            </flux:heading>
            <flux:text class="text-zinc-400">
                @if($categoria)
                    Publicaciones de esta categoría
                @else
                    Gestiona todas las publicaciones del sitio
                @endif
            </flux:text>
        </div>

        {{-- Barra de herramientas --}}
        <form method="GET" action="{{ route('admin.publicaciones.index') }}">

            <div class="flex flex-wrap items-center gap-2">

                {{-- Buscador --}}
                <div class="flex-1 min-w-48">
                    <flux:input
                        name="buscar"
                        placeholder="Buscar por título..."
                        value="{{ $buscar }}"
                        icon="magnifying-glass"
                        size="sm"
                    />
                </div>

                {{-- Filtro tipo --}}
                <flux:select name="tipo" size="sm" class="w-44" placeholder="Todos los tipos">
                    <flux:select.option value="">Todos los tipos</flux:select.option>
                    @foreach(\App\Models\Publicacion::TIPOS as $t)
                        <flux:select.option value="{{ $t }}" :selected="$tipo === $t">
                            {{ ucfirst(str_replace('_', ' ', $t)) }}
                        </flux:select.option>
                    @endforeach
                </flux:select>

                {{-- Filtro estado --}}
                <flux:select name="estado" size="sm" class="w-40" placeholder="Todos los estados">
                    <flux:select.option value="">Todos los estados</flux:select.option>
                    <flux:select.option value="activo" :selected="$estado === 'activo'">Activo</flux:select.option>
                    <flux:select.option value="inactivo" :selected="$estado === 'inactivo'">Inactivo</flux:select.option>
                </flux:select>

                {{-- Filtro categoría --}}
                <flux:select name="categoria" size="sm" class="w-52" placeholder="Todas las categorías">
                    <flux:select.option value="">Todas las categorías</flux:select.option>
                    @foreach($categorias as $cat)
                        <flux:select.option value="{{ $cat->ID_CATEGORIA }}" :selected="$categoriaId == $cat->ID_CATEGORIA">
                            {{ $cat->CATEGORIA_NOMBRE }}
                            <span class="text-zinc-400 text-xs">({{ $cat->TIPO }})</span>
                        </flux:select.option>
                    @endforeach
                </flux:select>

                <flux:button type="submit" size="sm" variant="ghost" icon="funnel">Filtrar</flux:button>

                @if($buscar || $tipo || $estado || $categoriaId)
                    <flux:button
                        href="{{ route('admin.publicaciones.index', $categoria ? ['categoria' => $categoria->ID_CATEGORIA] : []) }}"
                        size="sm"
                        variant="ghost"
                        icon="x-mark"
                        wire:navigate
                    >Limpiar</flux:button>
                @endif

                <div class="flex-1"></div>

                {{-- Volver a categoría --}}
                @if($categoria)
                    <flux:button
                        href="{{ route('admin.categorias.hijas', $categoria) }}"
                        size="sm"
                        variant="ghost"
                        icon="arrow-left"
                        wire:navigate
                    >Volver</flux:button>
                @endif

                {{-- Nueva publicación --}}
                <flux:button
                    href="{{ route('admin.publicaciones.create') }}"
                    size="sm"
                    variant="primary"
                    icon="plus"
                    wire:navigate
                >
                    Nueva publicación
                </flux:button>
            </div>
        </form>

        {{-- Contexto de categoría activa --}}
        @if($categoria)
            <div class="flex items-center gap-2 rounded-lg border border-blue-200 dark:border-blue-800 bg-blue-50 dark:bg-blue-900/20 px-4 py-2 text-sm text-blue-700 dark:text-blue-300">
                <flux:icon.folder-open class="size-4" />
                <span>Mostrando publicaciones de <strong>{{ $categoria->CATEGORIA_NOMBRE }}</strong></span>
                <a
                    href="{{ route('admin.publicaciones.index') }}"
                    class="ml-auto text-xs underline opacity-70 hover:opacity-100"
                    wire:navigate
                >Ver todas</a>
            </div>
        @endif

        {{-- Mensajes flash --}}
        @if(session('success'))
            <flux:callout variant="success" icon="check-circle" class="py-2">{{ session('success') }}</flux:callout>
        @endif
        @if(session('error'))
            <flux:callout variant="danger" icon="x-circle" class="py-2">{{ session('error') }}</flux:callout>
        @endif

        {{-- Tabla --}}
        <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 text-left text-xs uppercase tracking-wider text-zinc-500 dark:bg-zinc-800 dark:text-zinc-400">
                    <tr>
                        <th class="px-3 py-2">Imagen</th>
                        <th class="px-3 py-2">Título / URL</th>
                        <th class="px-3 py-2">Tipo</th>
                        <th class="px-3 py-2 text-center">Estado</th>
                        <th class="px-3 py-2 text-center">Orden</th>
                        <th class="px-3 py-2 text-center">★</th>
                        <th class="px-3 py-2">Registro</th>
                        <th class="px-3 py-2"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 bg-white dark:divide-zinc-700/60 dark:bg-zinc-900">
                    @forelse($publicaciones as $pub)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">

                            <td class="px-3 py-2">
                                <img
                                    src="{{ $pub->IMAGEN !== 'default.jpg' ? asset('storage/img/publicaciones/' . $pub->IMAGEN) : asset('img/default.jpg') }}"
                                    alt="{{ $pub->PUBLICACION_TITULO }}"
                                    class="h-9 w-9 rounded-lg object-cover"
                                />
                            </td>

                            <td class="px-3 py-2">
                                <span class="block font-medium leading-tight text-zinc-800 dark:text-white">
                                    {{ $pub->PUBLICACION_TITULO }}
                                </span>
                                <span class="text-xs text-zinc-400">/{{ $pub->URL }}</span>
                            </td>

                            <td class="px-3 py-2">
                                <flux:badge size="sm" variant="outline">
                                    {{ ucfirst(str_replace('_', ' ', $pub->TIPO)) }}
                                </flux:badge>
                            </td>

                            <td class="px-3 py-2 text-center">
                                @if($pub->ESTADO === 'activo')
                                    <flux:badge size="sm" color="green">Activo</flux:badge>
                                @else
                                    <flux:badge size="sm" color="red">Inactivo</flux:badge>
                                @endif
                            </td>

                            <td class="px-3 py-2 text-center text-xs text-zinc-500">{{ $pub->ORDEN }}</td>

                            <td class="px-3 py-2 text-center">
                                <form action="{{ route('admin.publicaciones.destacada', $pub) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" title="{{ $pub->DESTACADA ? 'Quitar destacada' : 'Marcar destacada' }}">
                                        @if($pub->DESTACADA)
                                            <flux:icon.star class="size-4 text-yellow-400" variant="solid" />
                                        @else
                                            <flux:icon.star class="size-4 text-zinc-300 hover:text-yellow-400 transition" />
                                        @endif
                                    </button>
                                </form>
                            </td>

                            <td class="px-3 py-2 text-xs text-zinc-500">
                                {{ $pub->FECHA_REGISTRO?->format('d/m/Y') }}
                            </td>

                            <td class="px-3 py-2">
                                <div class="flex items-center justify-end gap-1">
                                    <flux:button
                                        size="sm"
                                        variant="ghost"
                                        icon="pencil"
                                        href="{{ route('admin.publicaciones.edit', $pub) }}"
                                        wire:navigate
                                        title="Editar"
                                    />
                                    <form method="POST" action="{{ route('admin.publicaciones.destroy', $pub) }}"
                                        onsubmit="return confirm('¿Eliminar esta publicación?')">
                                        @csrf
                                        @method('DELETE')
                                        <flux:button
                                            size="sm"
                                            variant="ghost"
                                            icon="trash"
                                            type="submit"
                                            class="text-red-400 hover:text-red-600"
                                            title="Eliminar"
                                        />
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-10 text-center text-zinc-400">
                                No se encontraron publicaciones.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        <div>
            {{ $publicaciones->links() }}
        </div>

    </div>
</x-layouts::app>