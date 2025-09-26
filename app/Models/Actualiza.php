<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Actualiza extends Model
{
    public function lugar()
    {
        return $this->belongsTo(Lugar::class, 'id_lugar');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}
