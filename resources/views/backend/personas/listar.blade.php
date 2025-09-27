@extends('backend.index')

@section('contenido')
    <div class="row">
        <div class="col-12">
            <!-- Mensajes de éxito/error -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    {{ session('info') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Lista de Personas</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#registrarPersona">
                            <i class="fas fa-user-plus mr-1"></i> Registrar Persona
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Nombre Completo</th>
                                <th>Correo</th>
                                <th>Teléfono</th>
                                <th>Rol</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($personas as $cont => $persona)
                            <tr>
                                <td>{{ $cont + 1 }}</td>
                                <td>{{ $persona->nombre }} {{ $persona->apellido }}</td>
                                <td>{{ $persona->email }}</td>
                                <td>{{ $persona->telefono }}</td>
                                <td>
                                    @if($persona->user)
                                        <form action="{{ route('personas.asignarRol', $persona->user->id) }}" method="POST">
                                            @csrf
                                            <select name="rol" class="form-control form-control-sm" onchange="this.form.submit()">
                                                <option value="administrador" {{ $persona->user->hasRole('administrador') ? 'selected' : '' }}>Administrador</option>
                                                <option value="usuario" {{ $persona->user->hasRole('usuario') ? 'selected' : '' }}>Usuario</option>
                                                <option value="voluntario" {{ $persona->user->hasRole('voluntario') ? 'selected' : '' }}>Voluntario</option>
                                            </select>
                                        </form>
                                    @else
                                        <span class="badge badge-secondary">Sin cuenta</span>
                                    @endif
                                </td>


                                <td>
                                    <!-- Botón Editar -->
                                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editarPersona{{ $persona->id }}" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <!-- Botón Crear Cuenta (dentro del foreach) -->
                                    @if(!$persona->user)
                                        <a href="{{ route('personas.crearCuenta', $persona->id) }}"
                                        class="btn btn-sm btn-success"
                                        onclick="return confirm('¿Estás seguro de que deseas crear una cuenta para {{ $persona->nombre }}?')">
                                            <i class="fas fa-user-plus"></i>
                                        </a>
                                    @else
                                        <span class="badge badge-info">
                                            <i class="fas fa-check-circle"></i> 
                                        </span>
                                    @endif


                                    <!-- Botón Eliminar -->
                                    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#eliminarPersona{{ $persona->id }}" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Editar -->
                            <div class="modal fade" id="editarPersona{{ $persona->id }}" tabindex="-1" role="dialog" aria-labelledby="editarPersonaLabel{{ $persona->id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form action="{{ route('personas.editar') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $persona->id }}">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editarPersonaLabel{{ $persona->id }}">Editar Persona</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <div class="form-group">
                                                            <label>Nombre</label>
                                                            <input type="text" class="form-control" name="nombre" value="{{ $persona->nombre }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <div class="form-group">
                                                            <label>Apellido</label>
                                                            <input type="text" class="form-control" name="apellido" value="{{ $persona->apellido }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Dirección</label>
                                                    <input type="text" class="form-control" name="direccion" value="{{ $persona->direccion }}">
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <div class="form-group">
                                                            <label>Teléfono</label>
                                                            <input type="text" class="form-control" name="telefono" value="{{ $persona->telefono }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <div class="form-group">
                                                            <label>Correo</label>
                                                            <input type="email" class="form-control" name="email" value="{{ $persona->email }}" required>
                                                        </div>
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

                            <!-- Modal Eliminar -->
                            <div class="modal fade" id="eliminarPersona{{ $persona->id }}" tabindex="-1" role="dialog" aria-labelledby="eliminarPersonaLabel{{ $persona->id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form action="{{ route('personas.eliminar', $persona->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="eliminarPersonaLabel{{ $persona->id }}">Eliminar Persona</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                ¿Estás seguro de que deseas eliminar a {{ $persona->nombre }} {{ $persona->apellido }}?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                <button type="submit" class="btn btn-danger">Eliminar</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>

    <!-- Modal Registrar Persona -->
    <div class="modal fade" id="registrarPersona" tabindex="-1" role="dialog" aria-labelledby="registrarPersonaLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('personas.registrar') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="registrarPersonaLabel">Registro de Persona</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label>Nombre</label>
                                    <input type="text" class="form-control" name="nombre" placeholder="Ingrese nombre" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label>Apellido</label>
                                    <input type="text" class="form-control" name="apellido" placeholder="Ingrese apellido" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Dirección</label>
                            <input type="text" class="form-control" name="direccion" placeholder="Ingrese dirección">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label>Teléfono</label>
                                    <input type="text" class="form-control" name="telefono" placeholder="Ingrese teléfono">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label>Correo</label>
                                    <input type="email" class="form-control" name="email" placeholder="Ingrese correo" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Registrar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>



@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejar el clic en el botón de crear cuenta
    $('button[data-target="#crearCuentaModal"]').on('click', function() {
        const personaId = $(this).data('persona-id');
        const personaNombre = $(this).closest('tr').find('td:nth-child(2)').text();

        // Configurar el modal
        $('#persona_id').val(personaId);
        $('#persona_nombre').val(personaNombre);
        $('#crearCuentaForm').attr('action', `/personas/guardar-cuenta/${personaId}`);
    });

    // Inicializar tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Cerrar alertas después de 5 segundos
    setTimeout(function() {
        $('.alert').alert('close');
    }, 5000);
});
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('.crear-cuenta-btn').on('click', function() {
            const personaId = $(this).data('id');
            const personaNombre = $(this).data('nombre');

            $('#persona_id').val(personaId);
            $('#modal-persona-nombre').text(personaNombre);
        });
        $('.asignar-rol-form').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const userId = form.data('user-id');
            const rol = form.find('button[name="rol"]:focus').val();
    
            $.ajax({
                url: `/personas/asignar-rol/${userId}`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    rol: rol
                },
                success: function(response) {
                    if(response.success) {
                        // Actualizar el badge con el nuevo rol
                        form.closest('.dropdown').find('.badge')
                            .removeClass('badge-primary badge-success')
                            .addClass(rol === 'usuario' ? 'badge-primary' : 'badge-success')
                            .text(response.rol);
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.message || 'Error desconocido');
                }
            });
        });
    
        // Manejar clic en el badge para abrir el dropdown
        $('.dropdown .badge').on('click', function(e) {
            e.preventDefault();
            $(this).next('.dropdown-menu').toggle();
        });

});

</script>

@endsection
