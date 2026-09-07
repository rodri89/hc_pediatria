<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
     protected $fillable = ['nombre', 'apellido', 'dni','telefono','domicilio','mail','fecha_nacimiento','obra_social','numero_afiliado','obra_social_plan', 'obra_social_foto','nombre_padre','nombre_madre','cantidad_hermanos', 'localidad','sexo','activo'];
}
