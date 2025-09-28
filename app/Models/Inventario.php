<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected $table = 'inventario';

    protected $fillable = [
        'user_id',
        'id_planta',
        'cantidad',
    ];

    public function planta()
    {
        return $this->belongsTo(Planta::class, 'id_planta');
    }
        
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
