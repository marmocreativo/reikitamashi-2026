<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactoController extends Controller
{
    public function index()
    {
        return view('public.contacto');
    }

    public function send(Request $request)
    {
        $data = $request->validate([
            'nombre'   => 'required|string|max:100',
            'email'    => 'required|email|max:150',
            'telefono' => 'nullable|string|max:20',
            'asunto'   => 'required|string|max:200',
            'mensaje'  => 'required|string|max:2000',
        ]);

        Log::info('Formulario de contacto recibido', $data);

        return redirect()->route('contacto')->with('success', '¡Mensaje recibido! Nos pondremos en contacto contigo pronto.');
    }
}