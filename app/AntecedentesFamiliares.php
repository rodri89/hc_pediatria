<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AntecedentesFamiliares extends Model
{
    protected $fillable = ['paciente_id','consulta_id','hta', 'hta_detalle', 'dbt', 'dbt_detalle', 'asma', 'asma_detalle', 'alergia', 'alergia_detalle', 'enf_cv', 'enf_cv_detalle', 'muerte_subita', 'muerte_subita_detalle', 'enf_celiaca', 'enf_celiaca_detalle', 'enf_tiroideas', 'enf_tiroideas_detalle', 'enf_neurologicas', 'enf_neurologicas_detalle', 'convulsion_febril', 'convulsion_febril_detalle', 'enf_psiquiatrica', 'enf_psiquiatrica_detalle', 'enf_oh', 'enf_oh_detalle', 'tabaquismo', 'tabaquismo_detalle', 'otro', 'otro_detalle', 'nota','activo'];
}
