<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Persona;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class VoluntariosController extends Controller
{
    public function listar()
    {
        // Obtener solo usuarios con rol "voluntario"
        $voluntarios = User::with('persona')
            ->whereHas('roles', function($query) {
                $query->where('name', 'voluntario');
            })
            ->get();

        return view('backend.voluntarios.listar', compact('voluntarios'));
    }

    public function crear(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|string|min:8|confirmed',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
        ]);

        try {
            // Crear persona (si no existe)
            $persona = Persona::firstOrCreate(
                ['email' => $request->email],
                [
                    'nombre' => $request->name,
                    'apellido' => $request->apellido,
                    'telefono' => $request->telefono,
                    'direccion' => $request->direccion,
                ]
            );

            // Crear usuario
            $usuario = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'persona_id' => $persona->id,
            ]);

            // Asignar rol "voluntario" automáticamente
            $usuario->assignRole('voluntario');

            return redirect()->route('voluntarios.listar')
                ->with('success', 'Voluntario creado correctamente');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error al crear voluntario: ' . $e->getMessage());
        }
    }

    public function editar(Request $request, $id)
    {
        $voluntario = User::with('persona')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$voluntario->id,
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
        ]);

        try {
            // Actualizar persona
            $voluntario->persona->update([
                'nombre' => $request->name,
                'apellido' => $request->apellido,
                'email' => $request->email,
                'telefono' => $request->telefono,
                'direccion' => $request->direccion,
            ]);

            // Actualizar usuario
            $voluntario->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            return redirect()->route('voluntarios.listar')
                ->with('success', 'Voluntario actualizado correctamente');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error al actualizar voluntario: ' . $e->getMessage());
        }
    }

    public function eliminar($id)
    {
        $voluntario = User::findOrFail($id);
        $voluntario->delete();

        return redirect()->route('voluntarios.listar')
            ->with('success', 'Voluntario eliminado correctamente');
    }
}
