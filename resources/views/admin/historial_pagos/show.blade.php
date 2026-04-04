<x-layouts::app :title="'Detalle de Pago'">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6 max-w-2xl">

        <div class="flex items-center gap-3">
            <flux:button icon="arrow-left" variant="ghost" href="{{ route('historial_pagos.index') }}" wire:navigate />
            <flux:heading size="xl">Detalle de Pago</flux:heading>
        </div>

        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 divide-y divide-zinc-100 dark:divide-zinc-800">

            @php
                $campos = [
                    'Nombre completo' => $pago->nombre_completo,
                    'Curso'           => $pago->CURSO,
                    'Fecha'           => \Carbon\Carbon::parse($pago->FECHA)->format('d/m/Y'),
                    'Mes'             => $pago->MES,
                    'Año'             => $pago->ANIO,
                    'Importe'         => '$' . number_format($pago->IMPORTE, 2),
                    'Notas'           => $pago->NOTAS ?: '—',
                ];
            @endphp

            @foreach($campos as $label => $valor)
                <div class="flex items-start gap-4 px-6 py-4">
                    <flux:text class="w-40 flex-shrink-0 text-zinc-400 text-sm">{{ $label }}</flux:text>
                    <flux:text class="font-medium text-zinc-800 dark:text-zinc-100">{{ $valor }}</flux:text>
                </div>
            @endforeach

        </div>

        <div class="flex justify-end">
            <form method="POST" action="{{ route('historial_pagos.destroy', $pago->ID) }}"
                  onsubmit="return confirm('¿Eliminar este registro permanentemente?')">
                @csrf
                @method('DELETE')
                <flux:button type="submit" variant="danger" icon="trash">Eliminar registro</flux:button>
            </form>
        </div>

    </div>
</x-layouts::app>