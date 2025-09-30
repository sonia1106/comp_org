<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mapa extends Model
{
    //
    protected $fillable = ['user_id', 'geojson', 'nombre', 'descripcion'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
