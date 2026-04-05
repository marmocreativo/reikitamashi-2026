<x-layouts::app :title="'Detalle de Pago'">
    <div class="flex h-full w-full flex-1 flex-col gap-4 p-6">

        {{-- Breadcrumbs --}}
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}" wire:navigate>Panel</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('historial_pagos.index') }}" wire:navigate>Historial de Pagos</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{ $pago->nombre_completo }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>

        {{-- Encabezado --}}
        <div>
            <flux:heading size="xl">Detalle de Pago</flux:heading>
            <flux:text class="text-zinc-400">Comprobante de pago #{{ $pago->ID }}</flux:text>
        </div>

        {{-- Toolbar --}}
        <div class="flex items-center gap-2">
            <flux:button size="sm" variant="ghost" icon="arrow-left" href="{{ route('historial_pagos.index') }}" wire:navigate>
                Volver
            </flux:button>
            <div class="flex-1"></div>
            <flux:button size="sm" variant="ghost" icon="camera" onclick="compartirRecibo()">
                Compartir
            </flux:button>
            <form method="POST" action="{{ route('historial_pagos.destroy', $pago->ID) }}"
                onsubmit="return confirm('¿Eliminar este registro permanentemente?')">
                @csrf
                @method('DELETE')
                <flux:button type="submit" size="sm" variant="ghost" icon="trash" class="text-red-400 hover:text-red-600">
                    Eliminar
                </flux:button>
            </form>
        </div>

        {{-- Recibo --}}
        <div class="flex justify-center">
            <div
                id="recibo"
                class="w-full max-w-md rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 overflow-hidden shadow-sm"
            >
                {{-- Cabecera del recibo --}}
                <div class="bg-zinc-900 dark:bg-zinc-800 px-8 py-6 text-center">
                    <p class="text-xs uppercase tracking-widest text-zinc-400 mb-1">Tamashi</p>
                    <h2 class="text-xl font-semibold text-white">Comprobante de Pago</h2>
                    <p class="text-zinc-400 text-xs mt-1">#{{ str_pad($pago->ID, 6, '0', STR_PAD_LEFT) }}</p>
                </div>

                {{-- Importe destacado --}}
                <div class="bg-zinc-50 dark:bg-zinc-800/50 px-8 py-5 text-center border-b border-zinc-200 dark:border-zinc-700">
                    <p class="text-xs text-zinc-400 uppercase tracking-wider mb-1">Importe pagado</p>
                    <p class="text-4xl font-bold text-zinc-900 dark:text-white">
                        ${{ number_format($pago->IMPORTE, 2) }}
                    </p>
                </div>

                {{-- Datos del pago --}}
                <div class="px-8 py-4 divide-y divide-zinc-100 dark:divide-zinc-800">

                    <div class="flex justify-between py-3">
                        <span class="text-xs text-zinc-400 uppercase tracking-wide">Alumno</span>
                        <span class="text-sm font-medium text-zinc-800 dark:text-zinc-100">{{ $pago->nombre_completo }}</span>
                    </div>

                    <div class="flex justify-between py-3">
                        <span class="text-xs text-zinc-400 uppercase tracking-wide">Curso</span>
                        <span class="text-sm font-medium text-zinc-800 dark:text-zinc-100">{{ $pago->CURSO }}</span>
                    </div>

                    <div class="flex justify-between py-3">
                        <span class="text-xs text-zinc-400 uppercase tracking-wide">Periodo</span>
                        <span class="text-sm font-medium text-zinc-800 dark:text-zinc-100">{{ $pago->MES }} {{ $pago->ANIO }}</span>
                    </div>

                    <div class="flex justify-between py-3">
                        <span class="text-xs text-zinc-400 uppercase tracking-wide">Fecha</span>
                        <span class="text-sm font-medium text-zinc-800 dark:text-zinc-100">
                            {{ \Carbon\Carbon::parse($pago->FECHA)->format('d/m/Y') }}
                        </span>
                    </div>

                    @if($pago->NOTAS)
                        <div class="flex justify-between py-3">
                            <span class="text-xs text-zinc-400 uppercase tracking-wide">Notas</span>
                            <span class="text-sm text-zinc-600 dark:text-zinc-300 text-right max-w-48">{{ $pago->NOTAS }}</span>
                        </div>
                    @endif

                </div>

                {{-- Pie del recibo --}}
                <div class="px-8 py-4 bg-zinc-50 dark:bg-zinc-800/50 border-t border-zinc-200 dark:border-zinc-700 text-center">
                    <p class="text-xs text-zinc-400">Generado el {{ now()->format('d/m/Y H:i') }}</p>
                </div>

            </div>
        </div>

    </div>

    @push('scripts')
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script>
    async function compartirRecibo() {
        const recibo = document.getElementById('recibo');

        const canvas = await html2canvas(recibo, {
            backgroundColor: '#ffffff',
            scale: 2,
            useCORS: true,
        });

        canvas.toBlob(async (blob) => {
            const archivo = new File([blob], 'recibo-{{ $pago->ID }}.png', { type: 'image/png' });

            if (navigator.share && navigator.canShare({ files: [archivo] })) {
                try {
                    await navigator.share({
                        title: 'Comprobante de pago',
                        text: 'Comprobante de pago de {{ $pago->nombre_completo }}',
                        files: [archivo],
                    });
                } catch (e) {
                    if (e.name !== 'AbortError') descargar(canvas);
                }
            } else {
                descargar(canvas);
            }
        }, 'image/png');
    }

    function descargar(canvas) {
        const link = document.createElement('a');
        link.download = 'recibo-{{ $pago->ID }}.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
    }
    </script>
    @endpush

</x-layouts::app>