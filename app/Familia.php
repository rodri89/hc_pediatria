<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Familia extends Model
{
    protected $fillable = ['paciente_id', 'consulta_id','mama', 'mama_edad', 'mama_ocupacion', 'papa', 'papa_edad', 'papa_ocupacion', 'bebe' ,'eg', 'fpp', 'hermanos','activo'];
}
