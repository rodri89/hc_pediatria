<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alimentacion extends Model
{
     protected $fillable = ['paciente_id','consulta_id', 'pecho', 'pecho_detalle', 'leche_maternizada', 'leche_maternizada_detalle', 'leche_vaca', 'leche_vaca_detalle', 'dieta_tipo', 'dieta_comidas', 'hierro', 'hierro_dosis', 'vitamina', 'vitamina_dosis', 'catarsis', 'somnia','activo'];
}
        