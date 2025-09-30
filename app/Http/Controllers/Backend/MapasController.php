<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
Use \App\Models\Mapa; 

class MapasController extends Controller
{
    public function listarMapas()
    {
        $user = auth()->user();
        $poligonos = Mapa::where('user_id', $user->id)->get();
        return view('backend.mapa.listar', compact('poligonos'));
    }

    public function guardarPoligono(Request $request)
    {
        $user = auth()->user();
        $geojson = $request->input('geojson');
        $nombre = $request->input('nombre');
        $descripcion = $request->input('descripcion');

        $mapa = new Mapa();
        $mapa->user_id = $user->id;
        $mapa->geojson = json_encode($geojson);
        $mapa->nombre = $nombre;
        $mapa->descripcion = $descripcion;
        $mapa->save();

        return response()->json(['success' => true, 'id' => $mapa->id]);
    }
}
