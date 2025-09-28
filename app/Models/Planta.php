<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Planta extends Model
{
    protected $guarded = [];

    protected $casts = [
        'precio' => 'decimal:2',
        'cantidad_disponible' => 'integer'
    ];
     public function transacciones()
    {
        return $this->hasMany(Transaccion::class);
    }

     public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function inventarios()
    {
        return $this->hasMany(Inventario::class, 'id_planta');
    }
    public function getCantidadDisponibleAttribute()
    {
        return $this->inventarios()->sum('cantidad');
    }
}
