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
                <h3 class="card-title">Lista de Voluntarios</h3>
                <div class="card-tools">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#crearVoluntario">
                        <i class="fas fa-user-plus"></i> Nuevo Voluntario
                    </button>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Nombre Completo</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($voluntarios as $conta => $voluntario)
                        <tr>
                            <td>{{ $voluntario->$conta +1 }}</td>
                            <td>{{ $voluntario->persona->nombre }} {{ $voluntario->persona->apellido }}</td>
                            <td>{{ $voluntario->email }}</td>
                            <td>{{ $voluntario->persona->telefono ?? 'No registrado' }}</td>
                            <td>
                                <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editarVoluntario{{ $voluntario->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('voluntarios.eliminar', $voluntario->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar voluntario?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Modal Editar Voluntario -->
                        <div class="modal fade" id="editarVoluntario{{ $voluntario->id }}" tabindex="-1" role="dialog">
                            <div class="modal-dialog">
                                <form action="{{ route('voluntarios.editar', $voluntario->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Editar Voluntario</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Nombre</label>
                                                        <input type="text" class="form-control" name="name" value="{{ $voluntario->persona->nombre }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Apellido</label>
                                                        <input type="text" class="form-control" name="apellido" value="{{ $voluntario->persona->apellido }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="email" class="form-control" name="email" value="{{ $voluntario->email }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Teléfono</label>
                                                <input type="text" class="form-control" name="telefono" value="{{ $voluntario->persona->telefono }}">
                                            </div>
                                            <div class="form-group">
                                                <label>Dirección</label>
                                                <input type="text" class="form-control" name="direccion" value="{{ $voluntario->persona->direccion }}">
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
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Crear Voluntario -->
<div class="modal fade" id="crearVoluntario" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <form action="{{ route('voluntarios.crear') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Nuevo Voluntario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nombre</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Apellido</label>
                                <input type="text" class="form-control" name="apellido" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="form-group">
                        <label>Teléfono</label>
                        <input type="text" class="form-control" name="telefono">
                    </div>
                    <div class="form-group">
                        <label>Dirección</label>
                        <input type="text" class="form-control" name="direccion">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Contraseña</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Confirmar Contraseña</label>
                                <input type="password" class="form-control" name="password_confirmation" required>
                            </div>
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
