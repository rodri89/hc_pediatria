<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlimentacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alimentacions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('consulta_id');
            $table->foreign('consulta_id')->references('id')->on('consultas');
            $table->unsignedInteger('paciente_id');
            $table->foreign('paciente_id')->references('id')->on('pacientes');
            $table->integer('pecho');
            $table->string('pecho_detalle');
            $table->integer('leche_maternizada');
            $table->string('leche_maternizada_detalle');
            $table->integer('leche_vaca');
            $table->string('leche_vaca_detalle');            
            $table->string('dieta_tipo');            
            $table->string('dieta_comidas');
            $table->integer('hierro');
            $table->string('hierro_dosis');
            $table->integer('vitamina');
            $table->string('vitamina_dosis');
            $table->string('catarsis');    
            $table->string('somnia');    
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
        Schema::dropIfExists('alimentacions');
    }
}
