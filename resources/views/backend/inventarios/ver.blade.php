@extends('backend.index')
@section('contenido')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Detalles de tu Inventario: {{ $planta->nombre }}</h3>
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
                        <p><strong>Descripción:</strong> {{ $planta->descripcion ?? 'Sin descripción' }}</p>
                        <p><strong>Precio:</strong> Bs. {{ number_format($planta->precio, 2) }}</p>
                        <p><strong>Dueño Original:</strong> {{ $planta->user->name }}</p>
                    </div>
                </div>

                <hr>
                <h5>Tu Inventario</h5>
                @if($inventarioUsuario)
                    <p><strong>Cantidad:</strong> {{ $inventarioUsuario->cantidad }}</p>
                    <p><strong>Última actualización:</strong> {{ $inventarioUsuario->updated_at->format('d/m/Y H:i') }}</p>
                @else
                    <p class="text-danger">No tienes esta planta en tu inventario.</p>
                @endif

                <a href="{{ route('inventario.listar') }}" class="btn btn-secondary mt-3">Volver a mi inventario</a>
            </div>
        </div>
    </div>
</div>
@endsection
