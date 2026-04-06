<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;

class AdminPacientesController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');
        $estado = $request->get('estado');

        $query = Paciente::query()->orderBy('APELLIDOS')->orderBy('NOMBRE');

        if ($buscar) {
            $query->buscar($buscar);
        }

        if ($estado) {
            $query->where('ESTADO', $estado);
        }

        $pacientes = $query->withCount('consultas')->paginate(20)->withQueryString();

        return view('admin.pacientes.index', compact('pacientes', 'buscar', 'estado'));
    }

    public function create()
    {
        return view('admin.pacientes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'NOMBRE'           => 'required|string|max:100',
            'APELLIDOS'        => 'required|string|max:100',
            'FECHA_NACIMIENTO' => 'nullable|date',
            'SEXO'             => 'nullable|in:masculino,femenino,otro',
            'TELEFONO'         => 'nullable|string|max:20',
            'EMAIL'            => 'nullable|email|max:150',
            'DIRECCION'        => 'nullable|string',
            'NOTAS'            => 'nullable|string',
            'ESTADO'           => 'required|in:activo,inactivo',
        ]);

        $data['FECHA_REGISTRO'] = now();

        Paciente::create($data);

        return redirect()->route('admin.pacientes.index')->with('success', 'Paciente registrado correctamente.');
    }

    public function show(Paciente $paciente)
    {
        $paciente->load('consultas');

        return view('admin.pacientes.show', compact('paciente'));
    }

    public function edit(Paciente $paciente)
    {
        return view('admin.pacientes.edit', compact('paciente'));
    }

    public function update(Request $request, Paciente $paciente)
    {
        $data = $request->validate([
            'NOMBRE'           => 'required|string|max:100',
            'APELLIDOS'        => 'required|string|max:100',
            'FECHA_NACIMIENTO' => 'nullable|date',
            'SEXO'             => 'nullable|in:masculino,femenino,otro',
            'TELEFONO'         => 'nullable|string|max:20',
            'EMAIL'            => 'nullable|email|max:150',
            'DIRECCION'        => 'nullable|string',
            'NOTAS'            => 'nullable|string',
            'ESTADO'           => 'required|in:activo,inactivo',
        ]);

        $paciente->update($data);

        return redirect()->route('admin.pacientes.show', $paciente)->with('success', 'Paciente actualizado correctamente.');
    }

    public function destroy(Paciente $paciente)
    {
        $paciente->delete(); // cascade elimina sus consultas

        return redirect()->route('admin.pacientes.index')->with('success', 'Paciente eliminado correctamente.');
    }
}