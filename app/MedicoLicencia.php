<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedicoLicencia extends Model
{
    protected $fillable = ['medico_user_id','fecha_aviso_expiracion','fecha_expiracion_licencia', 'importe', 'activo'];
}
