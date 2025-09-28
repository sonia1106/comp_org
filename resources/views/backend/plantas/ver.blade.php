@extends('backend.index')
@section('contenido')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Detalles de la Planta: {{ $planta->nombre }}</h3>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-4">
                        @if($planta->fotografia)
                            <img src="{{ asset('storage/' . $planta->fotografia) }}" 
                                 alt="{{ $planta->nombre }}" 
                                 class="img-fluid img-thumbnail">
                        @else
                            <p><em>Sin foto disponible</em></p>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <h4>{{ $planta->nombre }}</h4>
                    </div>
                </div>

                <hr>
                <h5>Inventario de Usuarios</h5>
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Cantidad en Inventario</th>
                            <th>Precios</th>
                            <th>Última Actualización</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inventarios as $inventario)
                            <tr>
                                <td>{{ $inventario->user->name }}</td>
                                <td>{{ $inventario->cantidad }}</td>
                                <td>{{ $inventario->planta->precio }}Bs</td>
                                <td>{{ $inventario->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">Ningún usuario tiene esta planta en inventario</td>
                            </tr>
                        @endforelse
                    </tbody>

                    <tfoot>
                        <tr>
                            <th>Total</th>
                            <th>{{ $inventarios->sum('cantidad') }}</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>

                <a href="{{ route('plantas.listar') }}" class="btn btn-secondary mt-3">Volver a la lista</a>
            </div>
        </div>
    </div>
</div>
@endsection
