<?php

/*
|--------------------------------------------------------------------------
| API Salud 360 de la historia clínica de pediatría
|--------------------------------------------------------------------------
|
| turnosonlinebb es la identidad única de Salud 360. Esta API no tiene login
| ni emite tokens: recibe el token de turnosonlinebb, lo valida contra su
| `auth/perfil` y ubica al médico local por `users.medico_id_tobb`.
|
*/

return [

    /** URL base de turnosonlinebb, sin barra final. */
    'tobb_url' => env('SALUD360_TOBB_URL', 'https://turnosonlinebb.com'),

    /** Segundos de espera al consultar el perfil en turnosonlinebb. */
    'tobb_timeout' => (int) env('SALUD360_TOBB_TIMEOUT', 8),

    /** Minutos que vale una sesión validada antes de volver a preguntarle a turnosonlinebb. */
    'cache_minutos' => (int) env('SALUD360_CACHE_MINUTOS', 10),

    /**
     * Horas que se sigue aceptando una sesión ya validada cuando turnosonlinebb no responde.
     * Evita que una caída de turnos deje al pediatra sin historia clínica en pleno consultorio.
     * En 0 se desactiva el modo degradado.
     */
    'gracia_horas' => (int) env('SALUD360_GRACIA_HORAS', 24),

    /** Código de esta historia clínica en `salud360_medico_hc` de turnosonlinebb. */
    'hc_codigo' => 'pediatria',

    /**
     * Crear el usuario local cuando el médico llega con pediatría habilitada en turnosonlinebb
     * pero todavía no existe en esta base. Apagado a propósito: ese usuario también habilita el
     * login web de pediatría, así que conviene que lo cree una persona.
     */
    'autoprovision' => (bool) env('SALUD360_AUTOPROVISION', false),

];
