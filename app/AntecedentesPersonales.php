<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AntecedentesPersonales extends Model
{
     protected $fillable = ['consulta_id', 'paciente_id', 'numero','enfermedad_actual','internaciones', 'alergias', 'alergia_detalle', 'qx', 'qx_detalle', 'traumatismos', 'traumatismos_detalle', 'transfusiones', 'transfusiones_detalle', 'otro', 'otro_detalle','activo'];
}
