<?php

namespace App\Http\Controllers\Api\Salud360;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Las licencias de la historia clínica, para el panel de administración de la app.
 *
 * La licencia es el permiso del médico para entrar a la historia clínica: vencida o desactivada, no
 * entra, ni por la web ni por la app (ver `TobbAuthService::conLicencia`). Vive en esta base, en
 * `medico_licencias`, y se identifica por el usuario de pediatría.
 *
 * Hacia la app viajan con el **número de médico de turnosonlinebb**, que es como la app conoce a los
 * médicos. Los que todavía no están vinculados no se informan: no hay forma de nombrarlos del otro
 * lado, y tampoco pueden entrar.
 *
 * Solo para el administrador.
 *
 * Compatible con PHP 7.1 / Laravel 5.8.
 */
class LicenciaController extends Salud360Controller
{
    /** GET licencias */
    public function index(Request $request)
    {
        $user = $request->user();
        if ((int) $user->usuario_tipo !== self::TIPO_ADMIN) {
            return $this->error('Solo el administrador puede ver las licencias.', 403, 'sin_permiso');
        }

        $filas = DB::table('medico_licencias as l')
            ->join('users as u', 'u.id', '=', 'l.medico_user_id')
            ->where('u.usuario_tipo', self::TIPO_MEDICO)
            ->where('u.medico_id_tobb', '<>', 0)
            ->select('l.*', 'u.medico_id_tobb')
            ->orderBy('u.medico_id_tobb')
            ->get();

        $out = [];
        foreach ($filas as $l) {
            $out[] = $this->formatear($l);
        }
        return $this->ok(['licencias' => $out]);
    }

    /**
     * PUT licencias/{medicoIdTobb}  { vence, aviso_desde, importe, activo }
     *
     * Se identifica por el número de médico de turnos, que es el que tiene la app. Los campos que no
     * vengan no se tocan, para que editar uno no borre el resto.
     */
    public function guardar(Request $request, $medicoIdTobb)
    {
        $user = $request->user();
        if ((int) $user->usuario_tipo !== self::TIPO_ADMIN) {
            return $this->error('Solo el administrador puede cambiar las licencias.', 403, 'sin_permiso');
        }

        $medico = DB::table('users')
            ->where('medico_id_tobb', (int) $medicoIdTobb)
            ->where('usuario_tipo', self::TIPO_MEDICO)
            ->first();
        if ($medico === null) {
            return $this->error('No hay un médico de pediatría vinculado a ese número de turnos.', 404, 'no_encontrado');
        }

        $ahora = Carbon::now();
        $valores = ['updated_at' => $ahora];
        if ($request->has('vence')) {
            $valores['fecha_expiracion_licencia'] = $this->fecha($request->input('vence'));
        }
        if ($request->has('aviso_desde')) {
            $valores['fecha_aviso_expiracion'] = $this->fecha($request->input('aviso_desde'));
        }
        if ($request->has('importe')) {
            $valores['importe'] = (string) $request->input('importe');
        }
        if ($request->has('activo')) {
            $valores['activo'] = $request->input('activo') ? 1 : 0;
        }
        if (count($valores) === 1) {
            return $this->error('No hay nada para cambiar.', 422, 'datos');
        }

        $lic = DB::table('medico_licencias')->where('medico_user_id', $medico->id)->orderBy('id', 'desc')->first();
        if ($lic === null) {
            // Un médico sin licencia no puede entrar, así que dar de alta la fila es parte de habilitarlo.
            $valores = $valores + ColumnasLegacy::vacias('medico_licencias', [], ['fecha_expiracion_licencia', 'fecha_aviso_expiracion', 'importe']);
            $valores['medico_user_id'] = (int) $medico->id;
            $valores['created_at'] = $ahora;
            if (!isset($valores['activo'])) {
                $valores['activo'] = 1;
            }
            $id = DB::table('medico_licencias')->insertGetId($valores);
        } else {
            DB::table('medico_licencias')->where('id', $lic->id)->update($valores);
            $id = $lic->id;
        }

        $fila = DB::table('medico_licencias as l')
            ->join('users as u', 'u.id', '=', 'l.medico_user_id')
            ->where('l.id', $id)
            ->select('l.*', 'u.medico_id_tobb')
            ->first();
        return $this->ok(['licencia' => $this->formatear($fila)]);
    }

    private function formatear($l)
    {
        $hoy = Carbon::now()->toDateString();
        $vence = substr((string) $l->fecha_expiracion_licencia, 0, 10);
        $aviso = substr((string) $l->fecha_aviso_expiracion, 0, 10);
        return [
            'medico_id_tobb' => (int) $l->medico_id_tobb,
            'vence' => $vence,
            'aviso_desde' => $aviso,
            'importe' => (float) str_replace(',', '.', (string) $l->importe),
            'activo' => (int) $l->activo,
            // Resuelto acá para que las dos puntas usen la misma regla y la misma fecha de hoy.
            'vencida' => ($vence < $hoy || (int) $l->activo === 0) ? 1 : 0,
            'por_vencer' => ($aviso <= $hoy && $vence >= $hoy && (int) $l->activo === 1) ? 1 : 0,
        ];
    }

    /** `AAAA-MM-DD` o null si no es una fecha usable. */
    private function fecha($valor)
    {
        $texto = trim((string) $valor);
        return $texto === '' ? null : substr($texto, 0, 10);
    }
}
