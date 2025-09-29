@extends('backend.index')
@section('contenido')
<div class="container">
    <h2 class="mb-4">Plantas disponibles</h2>

    <div class="row">
        @foreach($plantas as $planta)
            @if($planta->user_id !== auth()->id())
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        @if($planta->fotografia)
                            <img src="{{ asset('storage/' . $planta->fotografia) }}"
                                 class="card-img-top"
                                 alt="{{ $planta->nombre }}"
                                 style="height: 200px; object-fit: cover;">
                        @else
                            <img src="https://via.placeholder.com/200x200?text=Sin+Foto"
                                 class="card-img-top"
                                 alt="Sin foto">
                        @endif

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $planta->nombre }}</h5>
                            <p class="card-text">{{ $planta->descripcion ?? 'Sin descripción' }}</p>
                            <p class="mb-1"><strong>Precio:</strong>{{ number_format($planta->precio, 2) }} Bs</p>
                            <p class="mb-3"><strong>Stock:</strong> {{ $planta->cantidad_disponible }}</p>

                            <!-- Form para agregar al carrito -->
                            <form action="{{ route('usuarios.carrito.agregar', $planta->id) }}" method="POST" class="mt-auto">
                                @csrf
                                <div class="input-group mb-2">
                                    <input type="number" name="cantidad" class="form-control" min="1" max="{{ $planta->cantidad_disponible }}" required>
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-cart-plus"></i> Agregar
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <!-- Botón para ver carrito -->
    <div class="mt-4 text-right">
        <a href="{{ route('usuarios.carrito.ver') }}" class="btn btn-primary">
            <i class="fas fa-shopping-cart"></i> Ver carrito / Comprar
        </a>
    </div>
</div>
@endsection
