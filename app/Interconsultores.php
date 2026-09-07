<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Interconsultores extends Model
{
    protected $fillable = ['user_id','nombre', 'apellido','especialidad', 'direccion', 'telefono_particular', 'telefono_consultorio','activo'];
}
