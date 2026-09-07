<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAntecedentesPersonalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('antecedentes_personales', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('consulta_id');
            $table->foreign('consulta_id')->references('id')->on('consultas');
            $table->unsignedInteger('paciente_id');
            $table->foreign('paciente_id')->references('id')->on('pacientes');            
            $table->integer('numero');
            $table->integer('internaciones');
            $table->string('enfermedad_actual');            
            $table->integer('alergias');
            $table->string('alergia_detalle');
            $table->integer('qx');
            $table->string('qx_detalle');
            $table->integer('traumatismos');
            $table->string('traumatismos_detalle');
            $table->integer('transfusiones');
            $table->string('transfusiones_detalle');
            $table->integer('otro');
            $table->string('otro_detalle');
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
        Schema::dropIfExists('antecedentes_personales');
    }
}
