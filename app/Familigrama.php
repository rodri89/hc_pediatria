<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Familigrama extends Model
{
    protected $fillable = ['paciente_id','foto','activo'];
}
