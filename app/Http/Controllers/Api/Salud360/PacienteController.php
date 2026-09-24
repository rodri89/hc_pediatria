<?php

namespace App\Http\Controllers\Api\Salud360;

use App\Services\Salud360\PacienteVinculoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Pacientes de pediatría vistos desde la app.
 *
 * La app manda la ficha completa y acá se decide a qué paciente de pediatría corresponde. Manda la
 * ficha y no solo un identificador porque ya la tiene: así pediatría no necesita consultarle a
 * turnosonlinebb por cada paciente nuevo, que sería una segunda llamada de red en el camino crítico.
 */
class PacienteController extends Salud360Controller
{
    /** @var PacienteVinculoService */
    private $vinculos;

    public function __construct(PacienteVinculoService $vinculos)
    {
        $this->vinculos = $vinculos;
    }

    /** POST pacientes/resolver */
    public function resolver(Request $request)
    {
        $medico = $this->medico($request);
        $datos = $request->all();

        $paciente = $this->vinculos->resolver($medico, $datos);
        if ($paciente !== null) {
            return $this->ok([
                'paciente' => $this->formatearPaciente($paciente),
                'creado' => $this->vinculos->creado(),
                'vinculado_por' => $this->vinculos->vinculadoPor(),
            ], $this->vinculos->creado() ? 201 : 200);
        }

        switch ($this->vinculos->error()) {
            case 'dni_invalido':
                return $this->error('El paciente necesita un DNI numérico válido para registrarlo en pediatría.', 422, 'dni_invalido');
            case 'paciente_ambiguo':
                return $this->error(
                    'Hay más de un paciente con ese DNI en pediatría. Elegí cuál corresponde.',
                    409,
                    'paciente_ambiguo',
                    ['candidatos' => $this->vinculos->candidatos()]
                );
            case 'paciente_ya_vinculado':
                return $this->error(
                    'Ese paciente de pediatría ya está vinculado con otro paciente de turnos.',
                    409,
                    'paciente_ya_vinculado',
                    ['candidatos' => $this->vinculos->candidatos()]
                );
            case 'paciente_inexistente':
                return $this->error('El paciente elegido no existe en pediatría.', 404, 'no_encontrado');
            default:
                return $this->error('No se pudo resolver el paciente.', 422, 'paciente');
        }
    }

    /** GET pacientes/{id} */
    public function show(Request $request, $id)
    {
        $medico = $this->medico($request);
        $paciente = DB::table('pacientes')->where('id', (int) $id)->where('activo', 1)->first();
        if ($paciente === null) {
            return $this->error('Paciente no encontrado.', 404, 'no_encontrado');
        }
        if (!$this->atiende($medico->id, $paciente->id)) {
            return $this->error('Ese paciente no está en tu listado.', 403, 'sin_permiso');
        }
        return $this->ok(['paciente' => $this->formatearPaciente($paciente)]);
    }

    /** El médico solo ve pacientes de su cartera, igual que en la web. */
    private function atiende($medicoUserId, $pacienteId)
    {
        return DB::table('medico_pacientes')
            ->where('medico_user_id', $medicoUserId)
            ->where('paciente_id', $pacienteId)
            ->where('activo', 1)
            ->exists();
    }
}
