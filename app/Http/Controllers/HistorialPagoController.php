<?php

namespace App\Http\Controllers;

use App\Models\HistorialPago;
use Illuminate\Http\Request;

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
        ]);

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
}