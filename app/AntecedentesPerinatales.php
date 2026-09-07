<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AntecedentesPerinatales extends Model
{
    protected $fillable = ['consulta_id','paciente_id','embarazo','embarazo_controles','patologias','serologia1','serologia1_detalle','serologia3', 'serologia3_detalle','hisop_sbha','hisop_sbha_detalle','patologias_detalle','parto','parto_detalle','eg','peso','talla','pc','apgar','caida_cordon','meconio','gyf','fei','fei_anormal_detalle', 'vdrl', 'vdrl_detalle', 'chagas', 'chagas_detalle','oea','activo'];
}
