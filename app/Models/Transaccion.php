<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaccion extends Model
{
    protected $table = 'transacciones';
    
    protected $guarded = [];

     public function planta()
    {
        return $this->belongsTo(Planta::class, 'id_planta');
    }

    public function comprador()
    {
        return $this->belongsTo(User::class, 'id_comprador');
    }

    public function vendedor()
    {
        return $this->belongsTo(User::class, 'id_vendedor');
    }
}
