<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DesarrolloMadurativo extends Model
{
    protected $fillable = ['mes', 'tipo', 'descripcion','activo'];
}
