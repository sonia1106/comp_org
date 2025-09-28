<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Planta;
use App\Models\Transaccion;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


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
        session()->forget('carrito');
        return redirect()->route('usuarios.comprar')->with('info', 'Compra cancelada');
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

        $dataQR = "Pago de compra en Plantas Online\nUsuario: " . auth()->user()->name . "\nTotal: $" . number_format($totalGeneral, 2);

        $qr = base64_encode(QrCode::format('png')->size(250)->generate($dataQR));

        return view('usuarios.qr', compact('qr', 'totalGeneral'));
    }

}
