<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCpEmbarazoActualTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('embarazo_actuals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('consulta_id');
            $table->foreign('consulta_id')->references('id')->on('consultas');
            $table->unsignedInteger('paciente_id');
            $table->foreign('paciente_id')->references('id')->on('pacientes');
            $table->string('obstetra');
            $table->string('eg');
            $table->string('n_controles');
            $table->integer('serologia_1');
            $table->string('serologia_1_detalle');
            $table->integer('serologia_2');
            $table->string('serologia_2_detalle');
            $table->integer('hisop_sbhb');
            $table->string('hisop_sbhb_detalle');
            $table->integer('ptog');
            $table->string('ptog_detalle');
            $table->string('vacunas');
            $table->integer('parto');
            $table->string('cesarea_detalle');
            $table->string('ecografia');
            $table->string('observaciones');
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
        Schema::dropIfExists('embarazo_actuals');
    }
}
