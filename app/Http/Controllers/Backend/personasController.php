<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;


class personasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function listar()
    {
        $personas = Persona::with(['user' => function($query) {
        $query->with('roles');
         }])->get();
        $roles = Role::all();
        return view('backend.personas.listar', compact('personas', 'roles'));
    }

    public function registrar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'nullable|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:personas',
        ]);

        Persona::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'email' => $request->email,
        ]);

        return redirect()->route('personas.listar');
    }

    public function editar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'nullable|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:personas,email,'.$request->id,
        ]);

        Persona::where('id', $request->id)->update([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'email' => $request->email,
        ]);

        return redirect()->route('personas.listar');
    }

    public function eliminar($id)
    {
        $persona = Persona::findOrFail($id);
        $persona->delete();
        return redirect()->route('personas.listar');
    }

    public function crearCuenta($id)
    {
        $persona = Persona::findOrFail($id);

        if ($persona->user) {
            return redirect()->route('personas.listar')
                ->with('info', 'La persona ya tiene una cuenta asignada.');
        }

        User::create([
            'name' => $persona->nombre . ' ' . $persona->apellido,
            'email' => $persona->email,
            'password' => Hash::make($persona->telefono),
            'persona_id' => $persona->id,
        ]);

        return redirect()->route('personas.listar');
    }

    public function asignarRol(Request $request, $userId)
    {
        $request->validate([
            'rol' => 'required|string|in:administrador,usuario,voluntario'
        ]);

        $user = User::findOrFail($userId);

        // Quitar roles anteriores y asignar el nuevo
        $user->syncRoles([$request->rol]);

        return redirect()->route('personas.listar');
    }




}