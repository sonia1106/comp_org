<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventario;
use App\Models\Planta;



class InventariosController extends Controller
{
     public function listar()
    {
        $inventario = Inventario::with('planta')
            ->where('user_id', auth()->id())
            ->get();

        return view('backend.inventario.listar', compact('inventario'));
    }

    public function agregar(Request $request, $plantaId)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:1'
        ]);

        $inventario = Inventario::firstOrNew([
            'user_id' => auth()->id(),
            'id_planta' => $plantaId
        ]);

        $inventario->cantidad = $request->cantidad;
        $inventario->save();

        return redirect()->back()
            ->with('success', 'Planta agregada al inventario');
    }
        public function ver($id)
    {
        $planta = Planta::findOrFail($id);
        return view('backend.plantas.ver', compact('planta'));
    }
}
