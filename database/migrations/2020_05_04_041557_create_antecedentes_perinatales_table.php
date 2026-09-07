<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAntecedentesPerinatalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('antecedentes_perinatales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('consulta_id');
            $table->foreign('consulta_id')->references('id')->on('consultas');
            $table->unsignedInteger('paciente_id');
            $table->foreign('paciente_id')->references('id')->on('pacientes');
            $table->string('embarazo');
            $table->integer('embarazo_controles');
            $table->integer('patologias');
            $table->integer('hisop_sbha');
            $table->string('hisop_sbha_detalle');
            $table->integer('serologia1');
            $table->string('serologia1_detalle');
            $table->integer('serologia3');
            $table->string('serologia3_detalle');
            $table->string('patologias_detalle');
            $table->string('parto');
            $table->string('parto_detalle');
            $table->string('eg');
            $table->string('peso');
            $table->string('talla');
            $table->string('pc');
            $table->string('apgar');
            $table->integer('caida_cordon');
            $table->integer('meconio');
            $table->string('gyf');
            $table->integer('fei');
            $table->string('fei_anormal_detalle');
            $table->integer('vdrl');
            $table->string('vdrl_detalle');
            $table->integer('chagas');
            $table->string('chagas_detalle');
            $table->integer('oea');
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
        Schema::dropIfExists('antecedentes_perinatales');
    }
}
