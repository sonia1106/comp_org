<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventario;
use App\Models\Planta;

class InventariosController extends Controller
{


    public function ver($id)
    {
        $planta = Planta::with('inventarios')->findOrFail($id);

        // Inventario solo del usuario autenticado
        $inventarioUsuario = $planta->inventarios()
            ->where('user_id', auth()->id())
            ->first();
   
        return view('backend.inventarios.ver', compact('planta', 'inventarioUsuario'));
    }
}
