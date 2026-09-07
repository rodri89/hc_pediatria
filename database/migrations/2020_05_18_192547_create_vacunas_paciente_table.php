<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVacunasPacienteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vacunas_pacientes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('paciente_id');
            $table->foreign('paciente_id')->references('id')->on('pacientes');
            $table->unsignedInteger('vacuna_id');
            $table->foreign('vacuna_id')->references('id')->on('vacunas');
            $table->string('nombre');
            $table->integer('edad_meses');
            $table->integer('estado');
            $table->string('vacuna_view_id');
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
        Schema::dropIfExists('vacunas_pacientes');
    }
}
