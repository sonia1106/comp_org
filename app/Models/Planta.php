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
    
    public function inventarios()
    {
        return $this->hasMany(Inventario::class, 'id_planta');
    }
}
