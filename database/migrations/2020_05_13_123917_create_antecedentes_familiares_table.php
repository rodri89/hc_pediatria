<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAntecedentesFamiliaresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('antecedentes_familiares', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('consulta_id');
            $table->foreign('consulta_id')->references('id')->on('consultas');
            $table->unsignedInteger('paciente_id');
            $table->foreign('paciente_id')->references('id')->on('pacientes');
            $table->integer('hta');
            $table->string('hta_detalle');
            $table->integer('dbt');
            $table->string('dbt_detalle');
            $table->integer('asma');
            $table->string('asma_detalle');
            $table->integer('alergia');
            $table->string('alergia_detalle');
            $table->integer('enf_cv');
            $table->string('enf_cv_detalle');
            $table->integer('muerte_subita');
            $table->string('muerte_subita_detalle');
            $table->integer('enf_celiaca');
            $table->string('enf_celiaca_detalle');
            $table->integer('enf_tiroideas');
            $table->string('enf_tiroideas_detalle');
            $table->integer('enf_neurologicas');
            $table->string('enf_neurologicas_detalle');
            $table->integer('convulsion_febril');
            $table->string('convulsion_febril_detalle');
            $table->integer('enf_psiquiatrica');
            $table->string('enf_psiquiatrica_detalle');
            $table->integer('enf_oh');
            $table->string('enf_oh_detalle');
            $table->integer('tabaquismo');
            $table->string('tabaquismo_detalle');
            $table->integer('otro');
            $table->string('otro_detalle');
            $table->string('nota');
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
        Schema::dropIfExists('antecedentes_familiares');
    }
}
