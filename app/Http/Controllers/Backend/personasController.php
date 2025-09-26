<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Persona;
class personasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function listar()
    {
        $personas = Persona::get();
        return view('backend.personas.listar', compact(array('personas')));
    }

    public function registrar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:personas',
        ]);

        Persona::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'email' => $request->email
        ]);

        return redirect()->route('personas.listar');
    }

    public function editar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:personas,email,'.$request->id,
        ]);

        Persona::where('id', $request->id)->update([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'email' => $request->email
        ]);

        return redirect()->route('personas.listar');
    }

    public function eliminar($id)
    {
        $persona = Persona::findOrFail($id);
        $persona->delete();
        return redirect()->route('personas.listar');
    }

}
