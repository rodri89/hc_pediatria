<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExamenFisico extends Model
{
    protected $fillable = ['paciente_id', 'consulta_id', 'peso', 'peso_percentil', 'talla', 'talla_percentil', 'pc', 'pc_percentil', 'ipd', 'ta', 'imc', 'imc_percentil', 'nota','activo'];
}
