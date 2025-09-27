<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Planta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlantasController extends Controller
{
    public function listar()
    {
        $plantas = Planta::all();
        return view('backend.plantas.listar', compact('plantas'));
    }

    public function crear(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'cantidad_disponible' => 'required|integer|min:0',
            'fotografia' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $data = $request->all();

            if ($request->hasFile('fotografia')) {
                $image = $request->file('fotografia');
                $path = $image->store('plantas', 'public');
                $data['fotografia'] = $path;
            }

            Planta::create($data);

            return redirect()->route('plantas.listar')
                ->with('success', 'Planta creada correctamente');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error al crear planta: ' . $e->getMessage());
        }
    }

    public function editar(Request $request, $id)
    {
        $planta = Planta::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'cantidad_disponible' => 'required|integer|min:0',
            'fotografia' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $data = $request->all();

            if ($request->hasFile('fotografia')) {
                // Eliminar foto anterior si existe
                if ($planta->fotografia && Storage::disk('public')->exists($planta->fotografia)) {
                    Storage::disk('public')->delete($planta->fotografia);
                }

                $image = $request->file('fotografia');
                $path = $image->store('plantas', 'public');
                $data['fotografia'] = $path;
            }

            $planta->update($data);

            return redirect()->route('plantas.listar')
                ->with('success', 'Planta actualizada correctamente');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error al actualizar planta: ' . $e->getMessage());
        }
    }

    public function eliminar($id)
    {
        $planta = Planta::findOrFail($id);

        // Eliminar foto si existe
        if ($planta->fotografia && Storage::disk('public')->exists($planta->fotografia)) {
            Storage::disk('public')->delete($planta->fotografia);
        }

        $planta->delete();

        return redirect()->route('plantas.listar')
            ->with('success', 'Planta eliminada correctamente');
    }
    public function ver($id){
        $planta = Planta::findOrFail($id);
        return view('backend.plantas.ver', compact('planta'));
    }
}
