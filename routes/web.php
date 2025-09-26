<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\personasController;

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
Route::get('/personas/eliminar/{id}', [personasController::class, 'eliminar'])->name('personas.eliminar');



require __DIR__.'/auth.php';
