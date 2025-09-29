<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Planta;
use App\Models\Transaccion;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\Image\GdImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Support\Facades\Log;




use Illuminate\Http\Request;

class ComprasController extends Controller
{
    public function comprar()
    {
        $plantas = Planta::all();
        return view('backend.usuarios.comprar', compact('plantas'));
    }

    public function agregarAlCarrito(Request $request, $id)
    {
        $planta = Planta::findOrFail($id);

        $carrito = session()->get('carrito', []);

        $carrito[$id] = [
            'id' => $planta->id,
            'nombre' => $planta->nombre,
            'precio' => $planta->precio,
            'cantidad' => ($carrito[$id]['cantidad'] ?? 0) + $request->cantidad,
            'vendedor' => $planta->user_id,
        ];

        session()->put('carrito', $carrito);

        return back()->with('success', 'Planta agregada al carrito');
    }

    public function verCarrito()
    {
        return view('backend.usuarios.carrito');
    }


    public function cancelarCompra()
{
    $carrito = session()->get('carrito', []);

    if ($carrito && count($carrito) > 0) {
        // Obtener las transacciones pendientes relacionadas con este carrito
        $transacciones = Transaccion::where('id_comprador', auth()->id())
                                    ->where('estado', 'pendiente')
                                    ->get();

        if ($transacciones->isNotEmpty()) {
            foreach ($transacciones as $transaccion) {
                $transaccion->estado = 'cancelada';
                $transaccion->save();
            }
        }
    }

    // Vaciar carrito
    session()->forget('carrito');

    return redirect()->route('usuarios.comprar')->with('info', 'Compra cancelada y las transacciones pendientes fueron actualizadas a canceladas.');
}

    public function confirmarCompra()
    {
        $carrito = session()->get('carrito', []);
        if (!$carrito || count($carrito) == 0) {
            return redirect()->route('usuarios.comprar')->with('error', 'Tu carrito está vacío');
        }

        $transacciones = [];
        $totalGeneral = 0;
        foreach ($carrito as $item) {
            $transaccion = Transaccion::create([
                'id_planta'   => $item['id'],
                'id_comprador'=> auth()->id(),
                'id_vendedor' => $item['vendedor'],
                'cantidad'    => $item['cantidad'],
                'precio_total'=> $item['cantidad'] * $item['precio'],
                'estado'      => 'pendiente',
            ]);
            $transacciones[] = $transaccion;
            $totalGeneral += $item['cantidad'] * $item['precio'];
        }

        session()->forget('carrito');

        // Generar código QR con la información
        $qr = QrCode::size(250)->generate(
            "Pago de compra\nUsuario: " . auth()->user()->name .
            "\nTotal: $" . number_format($totalGeneral, 2)
        );

        return view('backend.usuarios.qr', compact('qr', 'totalGeneral'));
    }

    public function pagoConfirmado(Request $request)
    {
        $transacciones = Transaccion::where('id_comprador', auth()->id())
                                    ->where('estado', 'pendiente')
                                    ->get();

        if ($transacciones->isEmpty()) {
            return back()->with('error', 'No hay transacciones pendientes.');
        }

        foreach ($transacciones as $transaccion) {
            // Cambiar estado a confirmado
            $transaccion->estado = 'completada';
            $transaccion->save();

            // Actualizar inventario del comprador
            $inventario = \App\Models\Inventario::firstOrNew([
                'user_id' => auth()->id(),
                'id_planta' => $transaccion->id_planta
            ]);

            $inventario->cantidad -= $transaccion->cantidad;
            $inventario->save();
        }

        return redirect()->route('usuarios.comprar')->with('success', 'Pago confirmado y tu inventario ha sido actualizado.');
    }

}
