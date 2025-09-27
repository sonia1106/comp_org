@extends('backend.index')

@section('contenido')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Detalles de la Planta</h3>
                <div class="card-tools">
                    <a href="{{ route('plantas.listar') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        @if($planta->fotografia)
                            <img src="{{ asset('storage/' . $planta->fotografia) }}" 
                                 alt="{{ $planta->nombre }}" 
                                 class="img-fluid rounded">
                        @else
                            <img src="{{ asset('backend/img/plant-placeholder.jpg') }}"
                                 alt="Sin imagen"
                                 class="img-fluid">
                        @endif
                    </div>
                    <div class="col-md-8">
                        <h4>Detalles</h4>
                        <h4>{{ $planta->nombre }}</h4>
                        <p><strong>Descripción:</strong> {{ $planta->descripcion ?? 'No hay descripción' }}</p>
                        <p><strong>Precio:</strong> ${{ number_format($planta->precio, 2) }}</p>
                        <p><strong>Cantidad disponible:</strong> {{ $planta->cantidad_disponible }}</p>

                        <form action="{{ route('inventario.agregar', $planta->id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Cantidad para inventario</label>
                                <input type="number" name="cantidad" class="form-control" min="1" value="1">
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-cart-plus"></i> Agregar a mi inventario
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('plantas.listar') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
                <a href="{{ route('plantas.editar', $planta->id) }}" class="btn btn-primary" data-toggle="modal" data-target="#editarPlantaModal{{ $planta->id }}">
                    <i class="fas fa-edit"></i> Editar
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Información adicional</h3>
            </div>
            <div class="card-body">
                <!-- Aquí puedes agregar más información sobre la planta -->
                <div class="widget-user-header bg-info">
                    <h3 class="widget-user-username">{{ $planta->nombre }}</h3>
                    <h5 class="widget-user-desc">Planta #{{ $planta->id }}</h5>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-sm-4 border-right">
                            <div class="description-block">
                                <h5 class="description-header">${{ number_format($planta->precio, 2) }}</h5>
                                <span class="description-text">PRECIO</span>
                            </div>
                        </div>
                        <div class="col-sm-4 border-right">
                            <div class="description-block">
                                <h5 class="description-header">{{ $planta->cantidad_disponible }}</h5>
                                <span class="description-text">DISPONIBLES</span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="description-block">
                                <h5 class="description-header">
                                    {{ $planta->inventarios->where('user_id', auth()->id())->sum('cantidad') }}

                                </h5>
                                <span class="description-text">EN TU INVENTARIO</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection