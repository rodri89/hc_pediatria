<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Vínculo del paciente con turnosonlinebb, equivalente a `users.medico_id_tobb`.
 *
 * Las bases de turnos y de pediatría son distintas y sus ids no coinciden; hasta ahora el único
 * puente era el documento, que acá no es único. Esta columna deja el vínculo explícito.
 *
 * Sin índice único a propósito: el valor 0 (sin vincular) se repite en miles de filas. La unicidad
 * la garantiza PacienteVinculoService dentro de una transacción.
 *
 * El hosting no corre migraciones, así que el servicio también la crea en caliente
 * (`PacienteVinculoService::asegurarColumna`). Esta migración queda para dejar registro en git.
 */
class AddPacienteIdTobbToPacientesTable extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('pacientes', 'paciente_id_tobb')) {
            return;
        }
        Schema::table('pacientes', function (Blueprint $table) {
            $table->unsignedInteger('paciente_id_tobb')->default(0)->after('id')->index();
        });
    }

    public function down()
    {
        if (!Schema::hasColumn('pacientes', 'paciente_id_tobb')) {
            return;
        }
        Schema::table('pacientes', function (Blueprint $table) {
            $table->dropColumn('paciente_id_tobb');
        });
    }
}
