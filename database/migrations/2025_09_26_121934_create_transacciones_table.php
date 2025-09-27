<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transacciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_planta')->constrained('plantas');
            $table->foreignId('id_comprador')->constrained('users');
            $table->foreignId('id_vendedor')->constrained('users');
            $table->integer('cantidad');
            $table->decimal('precio_total', 10, 2);
            $table->enum('estado', ['pendiente', 'completada', 'cancelada'])->default('pendiente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transacciones');
    }
};
