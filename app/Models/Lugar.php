<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lugar extends Model
{
     protected $guarded = [];
    public function actualizaciones()
    {
        return $this->hasMany(Actualiza::class);
    }
}
