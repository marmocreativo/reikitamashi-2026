<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use App\Models\Paciente;
use Illuminate\Http\Request;

class AdminConsultasController extends Controller
{
    public function store(Request $request, Paciente $paciente)
    {
        $data = $request->validate([
            'FECHA_CONSULTA' => 'required|date',
            'SINTOMAS'       => 'nullable|string',
            'TRATAMIENTO'    => 'nullable|string',
            'NOTAS'          => 'nullable|string',
        ]);

        $data['ID_PACIENTE']   = $paciente->ID_PACIENTE;
        $data['FECHA_REGISTRO'] = now();

        Consulta::create($data);

        return redirect()->route('admin.pacientes.show', $paciente)->with('success', 'Consulta registrada correctamente.');
    }

    public function edit(Paciente $paciente, Consulta $consulta)
    {
        return view('admin.pacientes.consultas.edit', compact('paciente', 'consulta'));
    }

    public function update(Request $request, Paciente $paciente, Consulta $consulta)
    {
        $data = $request->validate([
            'FECHA_CONSULTA' => 'required|date',
            'SINTOMAS'       => 'nullable|string',
            'TRATAMIENTO'    => 'nullable|string',
            'NOTAS'          => 'nullable|string',
        ]);

        $consulta->update($data);

        return redirect()->route('admin.pacientes.show', $paciente)->with('success', 'Consulta actualizada correctamente.');
    }

    public function destroy(Paciente $paciente, Consulta $consulta)
    {
        $consulta->delete();

        return redirect()->route('admin.pacientes.show', $paciente)->with('success', 'Consulta eliminada.');
    }
}