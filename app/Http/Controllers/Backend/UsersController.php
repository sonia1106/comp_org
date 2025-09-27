<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Transaccion;
use App\Models\Persona;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Response\QrCodeResponse;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function listar()
    {
        $usuarios = User::with(['roles', 'persona'])->get();
        $roles = Role::all();
        $personas = Persona::all();
        return view('backend.usuarios.listar', compact('usuarios', 'roles', 'personas'));
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
            // Crear la persona primero
            $persona = Persona::create([
                'nombre' => $request->name,
                'apellido' => $request->apellido,
                'email' => $request->email,
                'telefono' => $request->telefono,
                'direccion' => $request->direccion,
            ]);

            // Crear el usuario con el ID de la persona
            $usuario = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'persona_id' => $persona->id,
            ]);

            // Asignar rol por defecto
            $usuario->assignRole('usuario');

            return redirect()->route('usuarios.listar')
                ->with('success', 'Usuario creado correctamente');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error al crear usuario: ' . $e->getMessage());
        }
    }

    public function editar(Request $request, $id)
    {
        $usuario = User::with('persona')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$usuario->id,
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
        ]);

        try {
            // Actualizar la persona asociada
            if ($usuario->persona) {
                $usuario->persona->update([
                    'nombre' => $request->name,
                    'apellido' => $request->apellido,
                    'email' => $request->email,
                    'telefono' => $request->telefono,
                    'direccion' => $request->direccion,
                ]);
            }

            // Actualizar el usuario
            $usuario->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            return redirect()->route('usuarios.listar')
                ->with('success', 'Usuario actualizado correctamente');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error al actualizar usuario: ' . $e->getMessage());
        }
    }

    public function eliminar($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->delete();
        return redirect()->route('usuarios.listar')
            ->with('success', 'Usuario eliminado correctamente');
    }

    public function asignarRol(Request $request, $id)
    {
        $request->validate([
            'rol' => 'required|string|exists:roles,name'
        ]);

        $usuario = User::findOrFail($id);
        $usuario->syncRoles([$request->rol]);

        return redirect()->route('usuarios.listar')
            ->with('success', 'Rol actualizado correctamente');
    }

    public function createTransaction(Request $request)
    {
        $transaccion = Transaccion::create([
            'id_planta' => $request->id_planta,
            'id_comprador' => auth()->id(),
            'id_vendedor' => $request->id_vendedor,
            'cantidad' => $request->cantidad,
            'precio_total' => $request->precio_total,
            'estado' => 'pendiente',
        ]);

        $qrInfo = 'Transacción ID: ' . $transaccion->id . ', Monto: ' . $transaccion->precio_total;
        $transaccion->update(['qr_info' => $qrInfo]);

        $qrCode = QrCode::create($qrInfo)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
            ->setSize(300)
            ->setMargin(10)
            ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));

        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        $result->saveToFile(public_path('qrcodes/' . $transaccion->id . '.png'));

        return new QrCodeResponse($result);
    }
}
