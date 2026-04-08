<?php

namespace App\Http\Controllers;

use App\Models\HistorialPago;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class HistorialPagoController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');
        $curso  = $request->get('curso');
        $anio   = $request->get('anio');

        $query = HistorialPago::query()->orderByDesc('FECHA');

        if ($buscar) {
            $query->where(function ($q) use ($buscar) {
                $q->where('NOMBRE', 'like', "%{$buscar}%")
                  ->orWhere('APELLIDOS', 'like', "%{$buscar}%");
            });
        }

        if ($curso) {
            $query->porCurso($curso);
        }

        if ($anio) {
            $query->porAnio($anio);
        }

        $pagos  = $query->paginate(20)->withQueryString();
        $cursos = HistorialPago::select('CURSO')->distinct()->orderBy('CURSO')->pluck('CURSO');
        $anios  = HistorialPago::select('ANIO')->distinct()->orderByDesc('ANIO')->pluck('ANIO');

        return view('admin.historial_pagos.index', compact('pagos', 'cursos', 'anios', 'buscar', 'curso', 'anio'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'NOMBRE'    => 'required|string|max:100',
            'APELLIDOS' => 'required|string|max:100',
            'CURSO'     => 'required|string|max:150',
            'FECHA'     => 'required|date',
            'MES'       => 'required|string|max:20',
            'ANIO'      => 'required|string|max:4',
            'IMPORTE'   => 'required|numeric|min:0',
            'NOTAS'     => 'nullable|string',
            'FORMA_PAGO'     => 'required|string|max:150',
        ]);
        $data['NOTAS'] = $data['NOTAS'] ?? '';

        HistorialPago::create($data);

        return redirect()->route('historial_pagos.index')->with('success', 'Pago registrado correctamente.');
    }

    public function show(int $id)
    {
        $pago = HistorialPago::findOrFail($id);

        return view('admin.historial_pagos.show', compact('pago'));
    }

    public function destroy(int $id)
    {
        HistorialPago::findOrFail($id)->delete();

        return redirect()->route('historial_pagos.index')->with('success', 'Registro eliminado.');
    }

    public function exportar(Request $request): StreamedResponse
    {
        $buscar = $request->get('buscar');
        $curso  = $request->get('curso');
        $anio   = $request->get('anio');

        $query = HistorialPago::query()->orderByDesc('FECHA');

        if ($buscar) {
            $query->where(function ($q) use ($buscar) {
                $q->where('NOMBRE', 'like', "%{$buscar}%")
                ->orWhere('APELLIDOS', 'like', "%{$buscar}%");
            });
        }

        if ($curso) $query->porCurso($curso);
        if ($anio)  $query->porAnio($anio);

        $pagos = $query->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Historial de Pagos');

        // Encabezados
        $sheet->fromArray([
            ['Nombre', 'Apellidos', 'Curso', 'Fecha', 'Mes', 'Año', 'Importe', 'Forma de Pago', 'Notas']
        ], null, 'A1');

        // Estilo encabezados
        $sheet->getStyle('A1:I1')->getFont()->setBold(true);
        $sheet->getStyle('A1:I1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFE2E8F0');

        // Datos
        $fila = 2;
        foreach ($pagos as $pago) {
            $sheet->fromArray([
                $pago->NOMBRE,
                $pago->APELLIDOS,
                $pago->CURSO,
                $pago->FECHA?->format('d/m/Y'),
                $pago->MES,
                $pago->ANIO,
                $pago->IMPORTE,
                $pago->FORMA_PAGO ?? '',
                $pago->NOTAS ?? '',
            ], null, "A{$fila}");
            $fila++;
        }

        // Ancho automático de columnas
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Formato moneda en columna G
        $sheet->getStyle("G2:G{$fila}")
            ->getNumberFormat()
            ->setFormatCode('#,##0.00');

        $nombreArchivo = 'historial-pagos-' . now()->format('Y-m-d') . '.xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $nombreArchivo, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}