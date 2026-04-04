<x-layouts::app :title="'Historial de Pagos'">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">

        {{-- Encabezado --}}
        <div class="flex items-center justify-between">
            <flux:heading size="xl">Historial de Pagos</flux:heading>
        </div>

        {{-- Alertas --}}
        @if(session('success'))
            <flux:callout variant="success" icon="check-circle">{{ session('success') }}</flux:callout>
        @endif

        {{-- Filtros --}}
        <form method="GET" action="{{ route('historial_pagos.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex flex-col gap-1">
                <flux:label>Buscar nombre</flux:label>
                <flux:input type="text" name="buscar" value="{{ $buscar }}" placeholder="Nombre o apellidos..." />
            </div>

            <div class="flex flex-col gap-1">
                <flux:label>Curso</flux:label>
                <flux:select name="curso">
                    <flux:select.option value="">Todos</flux:select.option>
                    @foreach($cursos as $c)
                        <flux:select.option value="{{ $c }}" :selected="$curso === $c">{{ $c }}</flux:select.option>
                    @endforeach
                </flux:select>
            </div>

            <div class="flex flex-col gap-1">
                <flux:label>Año</flux:label>
                <flux:select name="anio">
                    <flux:select.option value="">Todos</flux:select.option>
                    @foreach($anios as $a)
                        <flux:select.option value="{{ $a }}" :selected="$anio === $a">{{ $a }}</flux:select.option>
                    @endforeach
                </flux:select>
            </div>

            <flux:button type="submit" variant="primary" icon="magnifying-glass">Buscar</flux:button>
            <flux:button href="{{ route('historial_pagos.index') }}" variant="ghost">Limpiar</flux:button>
        </form>

        {{-- Formulario nuevo registro --}}
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-6">
            <flux:heading size="lg" class="mb-4">Registrar pago</flux:heading>

            <form method="POST" action="{{ route('historial_pagos.store') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @csrf
                <div>
                    <flux:label>Nombre *</flux:label>
                    <flux:input type="text" name="NOMBRE" value="{{ old('NOMBRE') }}" required />
                    @error('NOMBRE') <flux:text class="text-red-500 text-xs">{{ $message }}</flux:text> @enderror
                </div>
                <div>
                    <flux:label>Apellidos *</flux:label>
                    <flux:input type="text" name="APELLIDOS" value="{{ old('APELLIDOS') }}" required />
                    @error('APELLIDOS') <flux:text class="text-red-500 text-xs">{{ $message }}</flux:text> @enderror
                </div>
                <div>
                    <flux:label>Curso *</flux:label>
                    <flux:input type="text" name="CURSO" value="{{ old('CURSO') }}" required />
                    @error('CURSO') <flux:text class="text-red-500 text-xs">{{ $message }}</flux:text> @enderror
                </div>
                <div>
                    <flux:label>Importe *</flux:label>
                    <flux:input type="number" step="0.01" name="IMPORTE" value="{{ old('IMPORTE') }}" required />
                    @error('IMPORTE') <flux:text class="text-red-500 text-xs">{{ $message }}</flux:text> @enderror
                </div>
                <div>
                    <flux:label>Fecha *</flux:label>
                    <flux:input type="date" name="FECHA" value="{{ old('FECHA', date('Y-m-d')) }}" required />
                    @error('FECHA') <flux:text class="text-red-500 text-xs">{{ $message }}</flux:text> @enderror
                </div>
                <div>
                    <flux:label>Mes *</flux:label>
                    <flux:select name="MES">
                        @foreach(['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'] as $mes)
                            <flux:select.option value="{{ $mes }}" :selected="old('MES') === $mes">{{ $mes }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    @error('MES') <flux:text class="text-red-500 text-xs">{{ $message }}</flux:text> @enderror
                </div>
                <div>
                    <flux:label>Año *</flux:label>
                    <flux:input type="text" name="ANIO" value="{{ old('ANIO', date('Y')) }}" maxlength="4" required />
                    @error('ANIO') <flux:text class="text-red-500 text-xs">{{ $message }}</flux:text> @enderror
                </div>
                <div>
                    <flux:label>Notas</flux:label>
                    <flux:input type="text" name="NOTAS" value="{{ old('NOTAS') }}" />
                </div>
                <div class="sm:col-span-2 lg:col-span-4 flex justify-end">
                    <flux:button type="submit" variant="primary" icon="plus">Registrar pago</flux:button>
                </div>
            </form>
        </div>

        {{-- Tabla de resultados --}}
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 overflow-hidden">
            <table class="w-full text-sm text-left">
                <thead class="bg-zinc-50 dark:bg-zinc-800 text-zinc-500 dark:text-zinc-400 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-4 py-3 font-medium">Nombre</th>
                        <th class="px-4 py-3 font-medium">Curso</th>
                        <th class="px-4 py-3 font-medium">Mes / Año</th>
                        <th class="px-4 py-3 font-medium">Fecha</th>
                        <th class="px-4 py-3 font-medium">Importe</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @forelse($pagos as $pago)
                        <tr class="bg-white dark:bg-zinc-900 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition">
                            <td class="px-4 py-3">
                                <a href="{{ route('historial_pagos.show', $pago->ID) }}" class="font-medium hover:text-violet-600 transition">
                                    {{ $pago->nombre_completo }}
                                </a>
                            </td>
                            <td class="px-4 py-3 text-zinc-600 dark:text-zinc-300">{{ $pago->CURSO }}</td>
                            <td class="px-4 py-3 text-zinc-600 dark:text-zinc-300">{{ $pago->MES }} {{ $pago->ANIO }}</td>
                            <td class="px-4 py-3 text-zinc-600 dark:text-zinc-300">{{ \Carbon\Carbon::parse($pago->FECHA)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-zinc-600 dark:text-zinc-300">${{ number_format($pago->IMPORTE, 2) }}</td>
                            <td class="px-4 py-3">
                                <form method="POST" action="{{ route('historial_pagos.destroy', $pago->ID) }}"
                                    onsubmit="return confirm('¿Eliminar este registro?')">
                                    @csrf
                                    @method('DELETE')
                                    <flux:button type="submit" variant="danger" size="sm" icon="trash">Eliminar</flux:button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-zinc-400">
                                No hay registros que mostrar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        <div>
            {{ $pagos->links() }}
        </div>

        {{-- Paginación --}}
        <div>
            {{ $pagos->links() }}
        </div>

    </div>
</x-layouts::app>