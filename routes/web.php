<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\personasController;
use App\Http\Controllers\Backend\UsersController;
use App\Http\Controllers\Backend\VoluntariosController;
use App\Http\Controllers\Backend\PlantasController;
use App\Http\Controllers\Backend\InventariosController;
use App\Http\Controllers\Backend\ComprasController;




Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('backend.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/persona/listar', [personasController::class, 'listar'])->name('personas.listar');
Route::post('/personas/registrar', [personasController::class, 'registrar'])->name('personas.registrar');
Route::post('/personas/editar', [personasController::class, 'editar'])->name('personas.editar');
Route::delete('/personas/eliminar/{id}', [personasController::class, 'eliminar'])->name('personas.eliminar');
Route::get('/personas/crear-cuenta/{id}', [personasController::class, 'crearCuenta'])->name('personas.crearCuenta');
Route::post('/personas/{id}/asignar-rol', [PersonasController::class, 'asignarRol'])->name('personas.asignarRol');


Route::get('/usuarios/listar', [UsersController::class, 'listar'])->name('usuarios.listar');
Route::post('/usuarios/crear', [UsersController::class, 'crear'])->name('usuarios.crear');
Route::post('/usuarios/editar/{id}', [UsersController::class, 'editar'])->name('usuarios.editar');
Route::delete('/usuarios/eliminar/{id}', [UsersController::class, 'eliminar'])->name('usuarios.eliminar');
Route::post('/usuarios/{id}/asignar-rol', [UsersController::class, 'asignarRol'])->name('usuarios.asignarRol');
Route::post('/transaccion/crear', [UsersController::class, 'createTransaction'])->name('transaccion.crear');


Route::get('/voluntarios/listar', [VoluntariosController::class, 'listar'])->name('voluntarios.listar');
Route::post('/voluntarios/crear', [VoluntariosController::class, 'crear'])->name('voluntarios.crear');
Route::post('/voluntarios/editar/{id}', [VoluntariosController::class, 'editar'])->name('voluntarios.editar');
Route::delete('/voluntarios/eliminar/{id}', [VoluntariosController::class, 'eliminar'])->name('voluntarios.eliminar');



Route::get('/plantas/listar', [PlantasController::class, 'listar'])->name('plantas.listar');
Route::post('/plantas/crear', [PlantasController::class, 'crear'])->name('plantas.crear');
Route::post('/plantas/editar/{id}', [PlantasController::class, 'editar'])->name('plantas.editar');
Route::delete('/plantas/eliminar/{id}', [PlantasController::class, 'eliminar'])->name('plantas.eliminar');
Route::get('/plantas/{id}/ver', [PlantasController::class, 'ver'])->name('plantas.ver'); 


Route::get('/inventario/listar', [PlantasController::class, 'listarInventario'])->name('inventario.listar');
Route::post('/inventario/agregar/{id}', [PlantasController::class, 'agregarInventario'])->name('inventario.agregar');
Route::get('/inventario/{id}/ver', [InventariosController::class, 'ver'])->name('inventario.ver');

Route::get('/usuarios/comprar', [ComprasController::class, 'comprar'])->name('usuarios.comprar');
Route::post('/usuarios/comprar/{id}', [ComprasController::class, 'comprarPlanta'])->name('usuarios.comprarPlanta');
Route::post('/usuarios/carrito/agregar/{id}', [ComprasController::class, 'agregarAlCarrito'])->name('usuarios.carrito.agregar');
Route::get('/usuarios/carrito', [ComprasController::class, 'verCarrito'])->name('usuarios.carrito.ver');
Route::post('/usuarios/carrito/confirmar', [ComprasController::class, 'confirmarCompra'])->name('usuarios.carrito.confirmar');
Route::post('/usuarios/carrito/cancelar', [ComprasController::class, 'cancelarCompra'])->name('usuarios.carrito.cancelar');



require __DIR__.'/auth.php';
