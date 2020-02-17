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

/* -------------------------------------------------------------------------- */
/*                               RUTAS GENERICAS                              */
/* -------------------------------------------------------------------------- */

Route::resource('unidad', 'Unidad\UnidadController'); 
Route::resource('articulos', 'Articulos\ArticuloController'); 
Route::resource('produccion', 'Produccion\ProduccionController'); 
Route::resource('calidad', 'Calidad\CalidadController'); 
Route::resource('sector', 'Sector\SectorController'); 
Route::resource('insumos', 'Insumos\InsumoController'); 

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


Route::name('insumos-consulta')->post('insumos', 'Insumos\InsumoController@postInsumos');
Route::name('insumos-consulta')->get('insumos/by/fecha', 'Insumos\InsumoController@getInsumosByFecha');
Route::name('insumos-consulta')->get('insumos/by/articulo', 'Insumos\InsumoController@getInsumosByArticulo');
Route::name('insumos-consulta')->put('insumos/{id}', 'Insumos\InsumoController@putInsumos'); 

Route::name('insumos-consulta')->get('insumos/stock/by/estado', 'Insumos\InsumoController@getStockInsumoByEstado');
Route::name('insumos-consulta')->get('insumos/stock/by/produccion', 'Insumos\InsumoController@getStockInsumoByProduccion');
Route::name('insumos-consulta')->get('insumos/stock/by/dates', 'Insumos\InsumoController@getStockInsumoByDate');
Route::name('insumos-consulta')->get('insumos/stock/produccion/by/dates', 'Insumos\InsumoController@getStockInsumoProduccionByDate');





/** FILE MANAGER **/
Route::name('archivos')->post('/multiuploads/estudios', 'Upload\UploadController@showUploadFile');
Route::name('archivos')->post('/multiuploads/estudios/datos', 'Upload\UploadController@showUploadFileDatos');
Route::name('archivos')->post('/multiuploads/texto', 'Files\FilesController@createTestTextFile'); 
Route::name('archivos')->post('/multiuploads/texto/cirugia', 'Files\FilesController@createTestTextFileCirugia'); 
Route::name('archivos')->get('/multiuploads/estudios/verimagen', 'Upload\UploadController@getEstudioImagenes'); 

/** CHAT **/
