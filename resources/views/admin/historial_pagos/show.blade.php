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
                <div class="relative px-8 py-10 text-center overflow-hidden" style="background-color: #7800da;">
                    {{-- Imagen de fondo --}}
                    <div class="absolute inset-0 bg-cover bg-center opacity-40"
                        style="background-image: url('{{ asset('hero_bg.jpg') }}')">
                    </div>
                    {{-- Overlay negro --}}
                    <div class="absolute inset-0" style="background-color: rgba(156,105,184,0.15);"></div>
                    {{-- Contenido --}}
                    <div class="relative z-10">
                        <p class="text-xs uppercase tracking-widest text-zinc-300 mb-1">Tamashi</p>
                        <h2 class="text-2xl font-semibold text-white">¡Gracias por tu pago!</h2>
                        <p class="text-zinc-400 text-xs mt-1">#{{ str_pad($pago->ID, 6, '0', STR_PAD_LEFT) }}</p>
                    </div>
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
<script>
async function compartirRecibo() {
    const pago = {
        id: '{{ str_pad($pago->ID, 6, "0", STR_PAD_LEFT) }}',
        nombre: '{{ addslashes($pago->nombre_completo) }}',
        curso: '{{ addslashes($pago->CURSO) }}',
        periodo: '{{ $pago->MES }} {{ $pago->ANIO }}',
        fecha: '{{ \Carbon\Carbon::parse($pago->FECHA)->format("d/m/Y") }}',
        importe: '${{ number_format($pago->IMPORTE, 2) }}',
        notas: '{{ addslashes($pago->NOTAS ?? "") }}',
        formaPago: '{{ addslashes($pago->FORMA_PAGO ?? "") }}',
        generado: '{{ now()->format("d/m/Y H:i") }}',
    };

    const W = 480, H = pago.notas ? 640 : 580;
    const canvas = document.createElement('canvas');
    canvas.width  = W * 2;
    canvas.height = H * 2;
    const ctx = canvas.getContext('2d');
    ctx.scale(2, 2);

    // ── Fondo blanco ──
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, W, H);

    // ── Cabecera con imagen de fondo ──
    const headerH = 110;
    const img = new Image();
    img.crossOrigin = 'anonymous';

    await new Promise(resolve => {
        img.onload = resolve;
        img.onerror = resolve; // si falla la imagen igual dibujamos
        img.src = '{{ asset("hero_bg.jpg") }}';
    });

    // Recorte centrado de la imagen
    ctx.save();
    ctx.beginPath();
    ctx.rect(0, 0, W, headerH);
    ctx.clip();
    if (img.naturalWidth) {
        const scale = Math.max(W / img.naturalWidth, headerH / img.naturalHeight);
        const dw = img.naturalWidth * scale;
        const dh = img.naturalHeight * scale;
        ctx.drawImage(img, (W - dw) / 2, (headerH - dh) / 2, dw, dh);
    }
    // Overlay oscuro
    ctx.fillStyle = 'rgba(156,105,184,0.15)';
    ctx.fillRect(0, 0, W, headerH);
    ctx.restore();

    // Texto cabecera
    ctx.fillStyle = '#a1a1aa';
    ctx.font = '500 10px system-ui, sans-serif';
    ctx.textAlign = 'center';
    ctx.fillText('TAMASHI', W / 2, 32);

    ctx.fillStyle = '#ffffff';
    ctx.font = 'bold 20px system-ui, sans-serif';
    ctx.fillText('¡Gracias por tu pago!', W / 2, 62);

    ctx.fillStyle = '#71717a';
    ctx.font = '11px system-ui, sans-serif';
    ctx.fillText('#' + pago.id, W / 2, 84);

    // ── Banda importe ──
    const importeY = headerH;
    const importeH = 80;
    ctx.fillStyle = '#f4f4f5';
    ctx.fillRect(0, importeY, W, importeH);

    ctx.fillStyle = '#71717a';
    ctx.font = '10px system-ui, sans-serif';
    ctx.textAlign = 'center';
    ctx.fillText('IMPORTE PAGADO', W / 2, importeY + 24);

    ctx.fillStyle = '#18181b';
    ctx.font = 'bold 34px system-ui, sans-serif';
    ctx.fillText(pago.importe, W / 2, importeY + 62);

    // ── Separador ──
    ctx.strokeStyle = '#e4e4e7';
    ctx.lineWidth = 1;
    ctx.beginPath();
    ctx.moveTo(0, importeY + importeH);
    ctx.lineTo(W, importeY + importeH);
    ctx.stroke();

    // ── Filas de datos ──
    const filas = [
        ['Alumno',      pago.nombre],
        ['Curso',       pago.curso],
        ['Periodo',     pago.periodo],
        ['Fecha',       pago.fecha],
        ['Forma de pago', pago.formaPago],
    ];
    if (pago.notas) filas.push(['Notas', pago.notas]);

    const filaH = 46;
    let y = importeY + importeH;

    filas.forEach(([label, valor], i) => {
        if (i > 0) {
            ctx.strokeStyle = '#f4f4f5';
            ctx.lineWidth = 1;
            ctx.beginPath();
            ctx.moveTo(32, y);
            ctx.lineTo(W - 32, y);
            ctx.stroke();
        }

        ctx.fillStyle = '#a1a1aa';
        ctx.font = '10px system-ui, sans-serif';
        ctx.textAlign = 'left';
        ctx.fillText(label.toUpperCase(), 32, y + 20);

        ctx.fillStyle = '#18181b';
        ctx.font = '500 13px system-ui, sans-serif';
        ctx.textAlign = 'right';
        // Truncar valores muy largos
        let v = valor;
        while (ctx.measureText(v).width > W - 80 && v.length > 3) {
            v = v.slice(0, -4) + '…';
        }
        ctx.fillText(v, W - 32, y + 20);

        y += filaH;
    });

    // ── Pie ──
    const pieY = y + 8;
    ctx.fillStyle = '#f4f4f5';
    ctx.fillRect(0, pieY, W, H - pieY);

    ctx.strokeStyle = '#e4e4e7';
    ctx.lineWidth = 1;
    ctx.beginPath();
    ctx.moveTo(0, pieY);
    ctx.lineTo(W, pieY);
    ctx.stroke();

    ctx.fillStyle = '#a1a1aa';
    ctx.font = '10px system-ui, sans-serif';
    ctx.textAlign = 'center';
    ctx.fillText('Generado el ' + pago.generado, W / 2, pieY + 22);

    // ── Compartir o descargar ──
    canvas.toBlob(async (blob) => {
        const archivo = new File([blob], 'recibo-' + pago.id + '.png', { type: 'image/png' });

        if (navigator.share && navigator.canShare && navigator.canShare({ files: [archivo] })) {
            try {
                await navigator.share({
                    title: 'Comprobante de pago',
                    text: 'Comprobante de pago de ' + pago.nombre,
                    files: [archivo],
                });
            } catch (e) {
                if (e.name !== 'AbortError') descargar(canvas, pago.id);
            }
        } else {
            descargar(canvas, pago.id);
        }
    }, 'image/png');
}

function descargar(canvas, id) {
    const link = document.createElement('a');
    link.download = 'recibo-' + id + '.png';
    link.href = canvas.toDataURL('image/png');
    link.click();
}
</script>
@endpush

</x-layouts::app>