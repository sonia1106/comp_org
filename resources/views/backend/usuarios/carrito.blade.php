@extends('backend.index')
@section('contenido')
<div class="container">
    <h2 class="mb-4">Mi Carrito</h2>

    @if(session('carrito') && count(session('carrito')) > 0)
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Planta</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach(session('carrito') as $item)
                    @php $subtotal = $item['cantidad'] * $item['precio']; @endphp
                    @php $total += $subtotal; @endphp
                    <tr>
                        <td>{{ $item['nombre'] }}</td>
                        <td>{{ $item['cantidad'] }}</td>
                        <td>{{ number_format($item['precio'], 2) }} Bs</td>
                        <td>{{ number_format($subtotal, 2) }}Bs</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3">Total</th>
                    <th>{{ number_format($total, 2) }} Bs</th>
                </tr>
            </tfoot>
        </table>

        <form action="{{ route('usuarios.carrito.confirmar') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-success">Confirmar compra</button>
        </form>

        <form action="{{ route('usuarios.carrito.cancelar') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-danger">Cancelar</button>
        </form>
    @else
        <p>No tienes productos en el carrito.</p>
    @endif
</div>
@endsection
