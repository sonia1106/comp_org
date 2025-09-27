@extends('backend.index')
@section('contenido')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Mi Inventario de Plantas</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Planta</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Total</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inventario as $item)
                        <tr>
                            <td>{{ $item->planta->id }}</td>
                            <td>{{ $item->planta->nombre }}</td>
                            <td>{{ $item->cantidad }}</td>
                            <td>${{ number_format($item->planta->precio, 2) }}</td>
                            <td>${{ number_format($item->planta->precio * $item->cantidad, 2) }}</td>
                            <td>
                                <!-- Aquí puedes agregar acciones para tu inventario -->
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
