<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePacienteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pacientes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->string('apellido');
            $table->integer('dni');
            $table->string('telefono');
            $table->string('domicilio');            
            $table->string('mail');
            $table->date('fecha_nacimiento');              
            $table->string('obra_social');            
            $table->string('numero_afiliado');            
            $table->string('obra_social_plan'); 
            $table->string('obra_social_foto');
            $table->string('sexo');
            $table->string('nombre_padre');
            $table->string('nombre_madre');
            $table->integer('cantidad_hermanos');   
            $table->string('localidad');                 
            $table->integer('activo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pacientes');
    }
}
