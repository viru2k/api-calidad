<?php

use Illuminate\Http\Request;
//require "../vendor/autoload.php";
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


Route::post('oauth/token','\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');
//Auth::routes(['register' => false]);


/* -------------------------------------------------------------------------- */
/*                              RUTAS DE USUARIOS                             */
/* -------------------------------------------------------------------------- */


Route::name('user-info')->get('user/password', 'User\UserController@getPassword'); 
Route::name('user-info')->get('user/info/menu', 'User\UserController@getUserDataAndMenu'); 
Route::name('user-info')->get('user/menu', 'User\UserController@getMenu');
Route::name('user-info')->post('user/menu/add/{id}', 'User\UserController@agregarMenuUsuario');
Route::name('user-info')->delete('user/menu/{id}', 'User\UserController@borrarMenuUsuario');
Route::name('user-info')->get('user/listado', 'User\UserController@getUsuarios');
Route::name('user-info')->post('user/crear', 'User\UserController@CrearUsuario');
Route::name('user-info')->put('user/editar/{id}', 'User\UserController@EditarUsuario');
Route::name('user-info')->put('user/editar/password/{id}', 'User\UserController@EditarUsuarioPassword');
Route::resource('user', 'User\UserController');
Route::resource('medico', 'Medico\MedicoController');

/* -------------------------------------------------------------------------- */
/*                               RUTAS GENERICAS                              */
/* -------------------------------------------------------------------------- */

Route::resource('unidad', 'Unidad\UnidadController'); 
Route::resource('articulos', 'Articulos\ArticuloController'); 
Route::resource('produccion', 'Produccion\ProduccionController'); 
Route::resource('calidad', 'Calidad\CalidadController'); 
Route::resource('sector', 'Sector\SectorController'); 

/* -------------------------------------------------------------------------- */
/*                             RUTAS DE PRODUCCION                            */
/* -------------------------------------------------------------------------- */



Route::name('produccion')->post('produccion', 'Produccion\ProduccionController@postProduccion');
Route::name('produccion')->get('produccion/by/fecha', 'Produccion\ProduccionController@getProduccionByFecha');
Route::name('produccion')->put('produccion/{id}', 'Produccion\ProduccionController@putProduccion'); 


/* -------------------------------------------------------------------------- */
/*                              RUTAS DE ARTICULO                             */
/* -------------------------------------------------------------------------- */

Route::name('articulo')->post('articulo',         'Articulo\ArticuloController@postArticulo');
Route::name('articulo')->get('articulo/by/fecha', 'Articulo\ArticuloController@getArticuloByFecha');
Route::name('articulo')->put('articulo/{id}',     'Articulo\ArticuloController@putArticulo'); 


/* -------------------------------------------------------------------------- */
/*                         RUTAS DE CONTROL DE CALIDAD                        */
/* -------------------------------------------------------------------------- */


Route::name('calidad')->post('calidad', 'Calidad\CalidadController@postCalidad');
Route::name('calidad')->get('calidad/by/fecha', 'Calidad\CalidadController@getCalidadByFecha');
Route::name('calidad')->put('produccion/{id}', 'Calidad\CalidadController@putCalidad'); 

/* -------------------------------------------------------------------------- */
/*                              RUTAS DE INSUMOS                              */
/* -------------------------------------------------------------------------- */


Route::name('insumos')->post('insumos', 'Insumos\InsumosController@postInsumos');
Route::name('insumos')->get('insumos/by/fecha', 'Insumos\InsumosController@getInsumosByFecha');
Route::name('insumos')->put('insumos/{id}', 'Insumos\InsumosController@putInsumos'); 




/** FILE MANAGER **/
Route::name('archivos')->post('/multiuploads/estudios', 'Upload\UploadController@showUploadFile');
Route::name('archivos')->post('/multiuploads/estudios/datos', 'Upload\UploadController@showUploadFileDatos');
Route::name('archivos')->post('/multiuploads/texto', 'Files\FilesController@createTestTextFile'); 
Route::name('archivos')->post('/multiuploads/texto/cirugia', 'Files\FilesController@createTestTextFileCirugia'); 
Route::name('archivos')->get('/multiuploads/estudios/verimagen', 'Upload\UploadController@getEstudioImagenes'); 

/** CHAT **/
