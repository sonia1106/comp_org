<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Planta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlantasController extends Controller
{
    // Mostrar todas las plantas (para admin/general)
    public function listar()
    {
        $plantas = Planta::with('user')->get();
        return view('backend.plantas.listar', compact('plantas'));
    }

    // Mostrar solo las plantas del usuario autenticado (inventario personal)
    public function listarInventario()
    {
        $plantas = Planta::with('user')
            ->where('user_id', Auth::id())
            ->get();

        return view('backend.inventarios.listar', compact('plantas'));
    }

    public function crear(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:plantas,nombre,NULL,id,user_id,' . auth()->id(),
            'precio' => 'required|numeric|min:0',
            'cantidad_disponible' => 'nullable|integer|min:0',
            'descripcion' => 'nullable|string',
            'fotografia' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();

        if ($request->hasFile('fotografia')) {
            $data['fotografia'] = $request->file('fotografia')->store('plantas', 'public');
        }

        Planta::create($data);

        return back()->with('success', 'Planta creada correctamente');
    }


    public function editar(Request $request, $id)
    {
        $planta = Planta::findOrFail($id);

        // Solo el dueño puede editar
        if ($planta->user_id !== Auth::id()) {
            return back()->with('error', 'No tienes permiso para editar esta planta.');
        }

        $request->validate([
            'nombre' => 'required|string|max:255|unique:plantas,nombre,' . $planta->id . ',id,user_id,' . Auth::id(),
            'precio' => 'required|numeric|min:0',
            'cantidad_disponible' => 'required|integer|min:0',
            'descripcion' => 'nullable|string',
            'fotografia' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('fotografia')) {
            $data['fotografia'] = $request->file('fotografia')->store('plantas', 'public');
        }

        $planta->update($data);

        $inventario = \App\Models\Inventario::firstOrNew([
            'user_id' => Auth::id(),
            'id_planta' => $planta->id,
        ]);

        $inventario->cantidad = $request->cantidad_disponible;
        $inventario->save();

        return back()->with('success', 'Planta e inventario actualizados correctamente');
    }


    public function eliminar($id)
    {
        $planta = Planta::findOrFail($id);

        if ($planta->user_id !== Auth::id()) {
            return back()->with('error', 'No tienes permiso para eliminar esta planta.');
        }

        $planta->delete();

        return back()->with('success', 'Planta eliminada correctamente');
    }

    public function agregarInventario(Request $request, $id)
    {
        $planta = Planta::findOrFail($id);

        if ($planta->user_id !== auth()->id()) {
            return back()->with('error', 'No tienes permiso para modificar esta planta.');
        }

        $request->validate([
            'cantidad' => 'required|integer|min:1',
        ]);

        // Actualizar en planta
        $planta->cantidad_disponible += $request->cantidad;
        $planta->save();

        // También guardar en inventario
        $inventario = \App\Models\Inventario::firstOrNew([
            'user_id' => auth()->id(),
            'id_planta' => $planta->id
        ]);

        $inventario->cantidad += $request->cantidad;
        $inventario->save();

        return back()->with('success', 'Inventario actualizado correctamente');
    }


    public function ver($id)
    {
        $planta = Planta::findOrFail($id);

        $plantasMismoNombre = Planta::where('nombre', $planta->nombre)
            ->pluck('id');

        $inventarios = \App\Models\Inventario::with('user')
            ->whereIn('id_planta', $plantasMismoNombre)
            ->get();

        return view('backend.plantas.ver', compact('planta', 'inventarios'));
    }

}
