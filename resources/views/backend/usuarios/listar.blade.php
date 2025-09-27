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
                <h3 class="card-title">Lista de Usuarios</h3>
                <div class="card-tools">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#crearUsuario">
                        <i class="fas fa-user-plus"></i> Crear Usuario
                    </button>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Nombre Completo</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Rol</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usuarios as $cont => $usuario)
                        <tr>
                            <td>{{ $cont + 1 }}</td>
                            <td>{{ $usuario->name }} {{ $usuario->persona->apellido ?? '' }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td>{{ $usuario->persona->telefono ?? 'Sin teléfono' }}</td>
                            <td>
                                <form action="{{ route('usuarios.asignarRol', $usuario->id) }}" method="POST">
                                    @csrf
                                    <select name="rol" class="form-control form-control-sm" onchange="this.form.submit()">
                                        @foreach($roles as $rol)
                                            <option value="{{ $rol->name }}" {{ $usuario->hasRole($rol->name) ? 'selected' : '' }}>
                                                {{ ucfirst($rol->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editarUsuario{{ $usuario->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('usuarios.eliminar', $usuario->id) }}" method="POST" style="display:inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar usuario?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <!-- Modal Editar Usuario -->
                        <div class="modal fade" id="editarUsuario{{ $usuario->id }}" tabindex="-1" role="dialog">
                            <div class="modal-dialog">
                                <form action="{{ route('usuarios.editar', $usuario->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Editar Usuario</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Nombre</label>
                                                        <input type="text" class="form-control" name="name"
                                                               value="{{ $usuario->persona->nombre ?? $usuario->name }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Apellido</label>
                                                        <input type="text" class="form-control" name="apellido"
                                                               value="{{ $usuario->persona->apellido ?? '' }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="email" class="form-control" name="email"
                                                       value="{{ $usuario->email }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Teléfono</label>
                                                <input type="text" class="form-control" name="telefono"
                                                       value="{{ $usuario->persona->telefono ?? '' }}">
                                            </div>
                                            <div class="form-group">
                                                <label>Dirección</label>
                                                <input type="text" class="form-control" name="direccion"
                                                       value="{{ $usuario->persona->direccion ?? '' }}">
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

<!-- Modal Crear Usuario -->
<div class="modal fade" id="crearUsuario" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <form action="{{ route('usuarios.crear') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Crear Nuevo Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nombre</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Apellido</label>
                                <input type="text" name="apellido" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Teléfono</label>
                        <input type="text" name="telefono" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Dirección</label>
                        <input type="text" name="direccion" class="form-control">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Contraseña</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Confirmar Contraseña</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
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
