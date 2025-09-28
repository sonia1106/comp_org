@extends('backend.index')
@section('contenido')
<div class="row">
    <div class="col-12">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Lista de Plantas</h3>
                <div class="card-tools">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#crearPlantaModal">
                        <i class="fas fa-plus"></i> Nueva Planta
                    </button>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Foto</th>
                            <th>Nombre</th>
                            <th>Dueño</th>
                            <th>Inventario</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($plantas as $cont => $planta)
                        <tr>
                            <td>{{ $cont + 1 }}</td>
                            <td>
                                @if($planta->fotografia)
                                    <img src="{{ asset('storage/' . $planta->fotografia) }}"
                                         alt="{{ $planta->nombre }}"
                                         class="img-thumbnail"
                                         style="max-width: 50px; max-height: 50px;">
                                @else
                                    Sin foto
                                @endif
                            </td>
                            <td>{{ $planta->nombre }}</td>
                            <td>{{ $planta->user->name }}</td>
                            <td>{{ $planta->cantidad_disponible }}</td>
                            <td>
                                <a href="{{ route('plantas.ver', $planta->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editarPlantaModal{{ $planta->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('plantas.eliminar', $planta->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar planta?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                <!-- Botón para abrir modal -->
                                @if($planta->user_id == auth()->id())
                                    <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#agregarInventarioModal{{ $planta->id }}">
                                        <i class="fas fa-plus"></i> Agregar Inventario
                                    </button>

                                    <!-- Modal Agregar Inventario -->
                                    <div class="modal fade" id="agregarInventarioModal{{ $planta->id }}" tabindex="-1" role="dialog" aria-labelledby="agregarInventarioLabel{{ $planta->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form action="{{ route('inventario.agregar', $planta->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="agregarInventarioLabel{{ $planta->id }}">Agregar Inventario - {{ $planta->nombre }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="cantidad{{ $planta->id }}">Cantidad</label>
                                                            <input type="number" name="cantidad" id="cantidad{{ $planta->id }}" class="form-control" min="1" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                        <button type="submit" class="btn btn-success">Agregar</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endif

                            </td>
                        </tr>

                        <!-- Modal Editar Planta -->
                        <div class="modal fade" id="editarPlantaModal{{ $planta->id }}" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-lg">
                                <form action="{{ route('plantas.editar', $planta->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('POST')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Editar Planta</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Nombre</label>
                                                        <input type="text" name="nombre" class="form-control" value="{{ $planta->nombre }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Precio</label>
                                                        <input type="number" step="0.01" name="precio" class="form-control" value="{{ $planta->precio }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Descripción</label>
                                                <textarea name="descripcion" class="form-control" rows="3">{{ $planta->descripcion }}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Cantidad Disponible</label>
                                                <input type="number" name="cantidad_disponible" class="form-control" value="{{ $planta->cantidad_disponible }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Fotografía Actual</label>
                                                @if($planta->fotografia)
                                                    <div class="mb-2">
                                                        <img src="{{ asset('storage/' . $planta->fotografia) }}"
                                                             alt="{{ $planta->nombre }}"
                                                             class="img-thumbnail"
                                                             style="max-width: 100px; max-height: 100px;">
                                                    </div>
                                                @else
                                                    <p>Sin foto</p>
                                                @endif
                                                <div class="custom-file">
                                                    <input type="file" name="fotografia" class="custom-file-input" id="fotografiaInput{{ $planta->id }}">
                                                    <label class="custom-file-label" for="fotografiaInput{{ $planta->id }}">Elegir nuevo archivo</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Crear Planta -->
<div class="modal fade" id="crearPlantaModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('plantas.crear') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Crear Nueva Planta</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nombre</label>
                                <input type="text" name="nombre" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Precio</label>
                                <input type="number" step="0.01" name="precio" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Fotografía</label>
                        <div class="custom-file">
                            <input type="file" name="fotografia" class="custom-file-input" id="fotografiaInput">
                            <label class="custom-file-label" for="fotografiaInput">Elegir archivo</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Solución más directa que debería funcionar en cualquier caso
document.addEventListener('DOMContentLoaded', function() {
    // Aplicar a todos los inputs existentes
    document.querySelectorAll('.custom-file-input').forEach(function(input) {
        input.addEventListener('change', function() {
            var fileName = this.value.split('\\').pop();
            this.nextElementSibling.textContent = fileName || 'Elegir archivo';
            this.nextElementSibling.classList.toggle('selected', !!fileName);
        });

        // Inicializar con el valor actual
        var fileName = input.value.split('\\').pop();
        input.nextElementSibling.textContent = fileName || 'Elegir archivo';
    });

    // Para modales que se crean después
    setInterval(function() {
        document.querySelectorAll('.custom-file-input:not([data-initialized])').forEach(function(input) {
            input.setAttribute('data-initialized', 'true');
            input.addEventListener('change', function() {
                var fileName = this.value.split('\\').pop();
                this.nextElementSibling.textContent = fileName || 'Elegir archivo';
                this.nextElementSibling.classList.toggle('selected', !!fileName);
            });
        });
    }, 300);
});
</script>
@endsection





