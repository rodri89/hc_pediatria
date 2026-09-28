<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| API Salud 360 (app de médicos)
|--------------------------------------------------------------------------
| turnosonlinebb es la identidad única: acá no hay login ni tokens propios.
| El middleware `salud360` valida el token de turnos y resuelve el médico local
| por `users.medico_id_tobb`. Ver md/API_SALUD360_PEDIATRIA.md.
*/
Route::group(['prefix' => 'salud360', 'namespace' => 'Api\Salud360', 'middleware' => ['salud360']], function () {
    Route::get('auth/perfil', 'AuthController@perfil');

    // Pacientes: la app manda la ficha y acá se resuelve a qué paciente de pediatría corresponde.
    Route::post('pacientes/resolver', 'PacienteController@resolver');
    Route::get('pacientes/{id}', 'PacienteController@show');

    // Consultas. Las rutas literales van antes de las que capturan {id}.
    Route::get('consultas', 'ConsultaController@index');
    Route::post('consultas/abrir', 'ConsultaController@abrir');
    Route::get('consultas/{id}', 'ConsultaController@show');
    Route::put('consultas/{id}/secciones', 'ConsultaController@guardarSecciones');
    Route::put('consultas/{id}/examen', 'ConsultaController@guardarExamen');
    Route::put('consultas/{id}/registros', 'ConsultaController@guardarRegistros');
    Route::post('consultas/{id}/cerrar', 'ConsultaController@cerrar');
    Route::post('consultas/{id}/reabrir', 'ConsultaController@reabrir');

    // Fotos y archivos adjuntos. Uno por pedido y en multiparte; el borrado es baja lógica.
    Route::get('consultas/{id}/fotos', 'FotoController@index');
    Route::post('consultas/{id}/fotos', 'FotoController@store');
    Route::get('fotos/{tipo}/{id}/archivo', 'FotoController@archivo');
    Route::delete('fotos/{tipo}/{id}', 'FotoController@destroy');

    Route::delete('consultas/{id}', 'ConsultaController@destroy');
});
