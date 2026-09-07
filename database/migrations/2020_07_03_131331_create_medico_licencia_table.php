<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedicoLicenciaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medico_licencias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('medico_user_id');
            $table->date('fecha_aviso_expiracion');
            $table->date('fecha_expiracion_licencia');
            $table->string('importe');
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
        Schema::dropIfExists('medico_licencias');
    }
}
