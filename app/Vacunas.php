<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vacunas extends Model
{
    protected $fillable = ['nombre', 'cantidad_dosis', 'activo'];
}
