<x-layouts::app :title="'Historial de Pagos'">
    <div class="flex h-full w-full flex-1 flex-col gap-4 p-6">

        {{-- Breadcrumbs --}}
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}" wire:navigate>Panel</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Historial de Pagos</flux:breadcrumbs.item>
        </flux:breadcrumbs>

        {{-- Encabezado --}}
        <div>
            <flux:heading size="xl">Historial de Pagos</flux:heading>
            <flux:text class="text-zinc-400">Registro de pagos de cursos y terapias</flux:text>
        </div>

        {{-- Barra de herramientas --}}
        <form method="GET" action="{{ route('historial_pagos.index') }}">
            <div class="flex flex-wrap items-center gap-2">
                <div class="flex-1 min-w-48">
                    <flux:input
                        name="buscar"
                        value="{{ $buscar }}"
                        placeholder="Buscar por nombre o apellidos..."
                        icon="magnifying-glass"
                        size="sm"
                    />
                </div>

                <flux:select name="curso" size="sm" class="w-48" placeholder="Todos los cursos">
                    <flux:select.option value="">Todos los cursos</flux:select.option>
                    @foreach($cursos as $c)
                        <flux:select.option value="{{ $c }}" :selected="$curso === $c">{{ $c }}</flux:select.option>
                    @endforeach
                </flux:select>

                <flux:select name="anio" size="sm" class="w-32" placeholder="Todos los años">
                    <flux:select.option value="">Todos los años</flux:select.option>
                    @foreach($anios as $a)
                        <flux:select.option value="{{ $a }}" :selected="$anio === $a">{{ $a }}</flux:select.option>
                    @endforeach
                </flux:select>

                <flux:button type="submit" size="sm" variant="ghost" icon="funnel">Filtrar</flux:button>

                @if($buscar || $curso || $anio)
                    <flux:button href="{{ route('historial_pagos.index') }}" size="sm" variant="ghost" icon="x-mark" wire:navigate>Limpiar</flux:button>
                @endif
                <flux:button
                    href="{{ route('historial_pagos.exportar', array_filter(['buscar' => $buscar, 'curso' => $curso, 'anio' => $anio])) }}"
                    size="sm"
                    variant="ghost"
                    icon="arrow-down-tray"
                >
                    Exportar Excel
                </flux:button>
            </div>
        </form>

        {{-- Alerta --}}
        @if(session('success'))
            <flux:callout variant="success" icon="check-circle" class="py-2">{{ session('success') }}</flux:callout>
        @endif

        {{-- Dos columnas --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- Columna izquierda: tabla --}}
            <div class="lg:col-span-2 flex flex-col gap-3">
                <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
                    <table class="w-full text-sm">
                        <thead class="bg-zinc-50 text-left text-xs uppercase tracking-wider text-zinc-500 dark:bg-zinc-800 dark:text-zinc-400">
                            <tr>
                                <th class="px-3 py-2">Nombre</th>
                                <th class="px-3 py-2">Curso</th>
                                <th class="px-3 py-2">Mes / Año</th>
                                <th class="px-3 py-2">Fecha</th>
                                <th class="px-3 py-2 text-right">Importe</th>
                                <th class="px-3 py-2"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 bg-white dark:divide-zinc-700/60 dark:bg-zinc-900">
                            @forelse($pagos as $pago)
                                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                                    <td class="px-3 py-2">
                                        <a href="{{ route('historial_pagos.show', $pago->ID) }}"
                                            class="font-medium text-zinc-800 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition">
                                            {{ $pago->nombre_completo }}
                                        </a>
                                    </td>
                                    <td class="px-3 py-2 text-zinc-600 dark:text-zinc-300 text-xs">{{ $pago->CURSO }}</td>
                                    <td class="px-3 py-2 text-zinc-500 text-xs">{{ $pago->MES }} {{ $pago->ANIO }}</td>
                                    <td class="px-3 py-2 text-zinc-500 text-xs">{{ \Carbon\Carbon::parse($pago->FECHA)->format('d/m/Y') }}</td>
                                    <td class="px-3 py-2 text-right font-medium text-zinc-700 dark:text-zinc-200 text-xs">
                                        ${{ number_format($pago->IMPORTE, 2) }}
                                    </td>
                                    <td class="px-3 py-2 text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <flux:button
                                                size="sm"
                                                variant="ghost"
                                                icon="eye"
                                                href="{{ route('historial_pagos.show', $pago->ID) }}"
                                                wire:navigate
                                                title="Ver detalles"
                                            />
                                            <form method="POST" action="{{ route('historial_pagos.destroy', $pago->ID) }}"
                                                onsubmit="return confirm('¿Eliminar este registro?')">
                                                @csrf
                                                @method('DELETE')
                                                <flux:button type="submit" variant="ghost" size="sm" icon="trash" class="text-red-400 hover:text-red-600" />
                                            </form>
                                        </div>
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

                <div>{{ $pagos->links() }}</div>
            </div>

            {{-- Columna derecha: formulario --}}
            <div>
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4 flex flex-col gap-4">
                    <div>
                        <flux:heading size="sm">Registrar pago</flux:heading>
                        <flux:text class="text-zinc-400 text-xs">Completa los datos del nuevo registro</flux:text>
                    </div>

                    <form method="POST" action="{{ route('historial_pagos.store') }}" class="flex flex-col gap-4">
                        @csrf

                        {{-- 1. Nombre | Apellidos --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <flux:field>
                                <flux:label>Nombre <span class="text-red-500">*</span></flux:label>
                                <flux:input name="NOMBRE" value="{{ old('NOMBRE') }}" required />
                                <flux:error name="NOMBRE" />
                            </flux:field>

                            <flux:field>
                                <flux:label>Apellidos <span class="text-red-500">*</span></flux:label>
                                <flux:input name="APELLIDOS" value="{{ old('APELLIDOS') }}" required />
                                <flux:error name="APELLIDOS" />
                            </flux:field>
                        </div>

                        {{-- 2. Curso --}}
                        <flux:field>
                            <flux:label>Curso <span class="text-red-500">*</span></flux:label>
                            <flux:input name="CURSO" value="{{ old('CURSO') }}" required />
                            <flux:error name="CURSO" />
                        </flux:field>

                        {{-- 3. Importe | Fecha --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <flux:field>
                                <flux:label>Importe <span class="text-red-500">*</span></flux:label>
                                <flux:input type="number" step="0.01" name="IMPORTE" value="{{ old('IMPORTE') }}" required />
                                <flux:error name="IMPORTE" />
                            </flux:field>

                            <flux:field>
                                <flux:label>Fecha de pago <span class="text-red-500">*</span></flux:label>
                                <flux:input type="date" name="FECHA" value="{{ old('FECHA', date('Y-m-d')) }}" required />
                                <flux:error name="FECHA" />
                            </flux:field>
                        </div>

                        {{-- 4. Mes | Año --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <flux:field>
                                <flux:label>Mes <span class="text-red-500">*</span></flux:label>
                                <flux:select name="MES">
                                    @foreach(['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'] as $mes)
                                        <flux:select.option value="{{ $mes }}" :selected="old('MES', now()->translatedFormat('F')) === $mes">
                                            {{ $mes }}
                                        </flux:select.option>
                                    @endforeach
                                </flux:select>
                                <flux:error name="MES" />
                            </flux:field>

                            <flux:field>
                                <flux:label>Año <span class="text-red-500">*</span></flux:label>
                                <flux:input name="ANIO" value="{{ old('ANIO', date('Y')) }}" maxlength="4" required />
                                <flux:error name="ANIO" />
                            </flux:field>
                        </div>

                        {{-- 5. Forma de pago --}}
                        <flux:field>
                            <flux:label>Forma de pago <span class="text-red-500">*</span></flux:label>
                            <flux:select name="FORMA_PAGO" required>
                                <flux:select.option value="">Selecciona una opción</flux:select.option>
                                <flux:select.option value="Depósito" :selected="old('FORMA_PAGO') === 'Depósito'">Depósito</flux:select.option>
                                <flux:select.option value="Efectivo" :selected="old('FORMA_PAGO') === 'Efectivo'">Efectivo</flux:select.option>
                                <flux:select.option value="Otro" :selected="old('FORMA_PAGO') === 'Otro'">Otro</flux:select.option>
                            </flux:select>
                            <flux:error name="FORMA_PAGO" />
                        </flux:field>

                        {{-- 6. Notas --}}
                        <flux:field>
                            <flux:label>Notas</flux:label>
                            <flux:textarea name="NOTAS" rows="2">{{ old('NOTAS') }}</flux:textarea>
                            <flux:error name="NOTAS" />
                        </flux:field>

                        {{-- Botón --}}
                        <flux:button type="submit" variant="primary" icon="plus" class="w-full">
                            Registrar pago
                        </flux:button>
                    </form>
                </div>
            </div>

        </div>

    </div>
</x-layouts::app>