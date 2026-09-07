<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AntecedentesObstetrico extends Model
{
    protected $fillable = ['paciente_id', 'consulta_id', 'g', 'p', 'a','descripcion','activo'];
}