<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Catarsis extends Model
{
    protected $fillable = ['paciente_id','consulta_id', 'descripcion','activo'];
}
