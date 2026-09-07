<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedicoSecretaria extends Model
{
    protected $fillable = ['medico_user_id', 'secretaria_user_id','activo'];
}
