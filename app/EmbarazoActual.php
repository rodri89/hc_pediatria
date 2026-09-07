<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmbarazoActual extends Model
{
        protected $fillable = ['paciente_id', 'consulta_id','obstetra', 'eg', 'n_controles', 'serologia_1', 'serologia_1_detalle', 'serologia_2', 'serologia_2_detalle' ,'hisop_sbhb', 'hisop_sbhb_detalle', 'ptog','ptog_detalle', 'vacunas', 'parto', 'cesarea_detalle', 'ecografia', 'observaciones', 'activo'];
}
