<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDesarrolloMadurativoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('desarrollo_madurativos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mes');
            $table->string('tipo'); // motor grueso, motor fino, psicoanalisis, lengauaje
            $table->string('descripcion');
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
        Schema::dropIfExists('desarrollo_madurativos');
    }
}
