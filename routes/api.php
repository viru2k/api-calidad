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

Route::name('articulo')->post('articulo',         'Articulos\ArticuloController@postArticulo');
Route::name('articulo')->get('articulo/by/fecha', 'Articulos\ArticuloController@getArticuloByFecha');
Route::name('articulo')->put('articulo/{id}',     'Articulos\ArticuloController@putArticulo'); 



/* -------------------------------------------------------------------------- */
/*                              RUTAS DE ARTICULO CONFECCION                            */
/* -------------------------------------------------------------------------- */

Route::name('articulo-confeccion')->post('articulo/confeccion',         'Articulos\ArticuloController@setArticuloConfeccion');
Route::name('articulo-confeccion')->get('articulo/confeccion', 'Articulos\ArticuloController@getArticuloConfeccionByArticuloId');
Route::name('articulo-confeccion')->put('articulo/confeccion/{id}',     'Articulos\ArticuloController@updateArticuloConfeccion'); 
Route::name('articulo-confeccion')->get('articulos/confeccion/borrar',     'Articulos\ArticuloController@delArticuloConfeccion'); 


/* -------------------------------------------------------------------------- */
/*                         RUTAS DE CONTROL DE CALIDAD                        */
/* -------------------------------------------------------------------------- */


Route::name('calidad')->post('calidad/tipocontrol', 'Calidad\CalidadController@postCalidad');
Route::name('calidad')->get('calidad/control/by/fecha', 'Calidad\CalidadController@getCalidadByFecha');
Route::name('calidad')->put('calidad/{id}', 'Calidad\CalidadController@putCalidad'); 

/* -------------------------------------------------------------------------- */
/*                              RUTAS DE INSUMOS                              */
/* -------------------------------------------------------------------------- */


//Route::name('insumos-consulta')->post('insumos', 'Insumos\InsumoController@postInsumos');
Route::name('insumos-consulta')->get('insumos/by/fecha', 'Insumos\InsumoController@getInsumosByFecha');
Route::name('insumos-consulta')->get('insumos/by/articulo', 'Insumos\InsumoController@getInsumosByArticulo');
Route::name('insumos-consulta')->put('insumos/{id}', 'Insumos\InsumoController@putInsumos'); 

Route::name('insumos-consulta')->get('insumos/stock/by/estado', 'Insumos\InsumoController@getStockInsumoByEstado');
Route::name('insumos-consulta')->get('insumos/stock/by/produccion', 'Insumos\InsumoController@getStockInsumoByProduccion');
Route::name('insumos-consulta')->get('insumos/stock/by/dates', 'Insumos\InsumoController@getStockInsumoByDate');
Route::name('insumos-consulta')->get('insumos/stock/produccion/by/dates', 'Insumos\InsumoController@getStockInsumoProduccionByDate');


/* -------------------------------------------------------------------------- */
/*                             RUTAS DE PRODUCCION                            */
/* -------------------------------------------------------------------------- */

Route::name('produccion-consulta')->get('produccion/movimiento/stock', 'Produccion\ProduccionController@getStockProduccion');
Route::name('produccion-consulta')->get('produccion/by/dates', 'Produccion\ProduccionController@getProduccionStockByDates');
Route::name('produccion-consulta')->put('produccion/{id}', 'Produccion\ProduccionController@updProduccionStock'); 
Route::name('produccion-consulta')->post('produccion/stock', 'Produccion\ProduccionController@setProduccionStock');
Route::name('produccion-consulta')->post('produccion/orden/pedido', 'Produccion\ProduccionController@setOrdenPedido'); 
Route::name('produccion-consulta')->get('produccion/orden/pedido/estado', 'Produccion\ProduccionController@getOrdenPedidoEstado');
Route::name('produccion-consulta')->get('produccion/orden/pedido/by/id', 'Produccion\ProduccionController@getOrdenPedidoDetalleById');
Route::name('produccion-consulta')->get('produccion/orden/pedido/by/estado', 'Produccion\ProduccionController@getOrdenPedidoDetalleByEstado');
Route::name('produccion-consulta')->get('produccion/orden/pedido/estado/editar', 'Produccion\ProduccionController@updOrdenPedido');
Route::name('produccion-consulta')->post('produccion/crear', 'Produccion\ProduccionController@setProduccion');
Route::name('produccion-consulta')->get('produccion/asociar/orden/pedido/articulo', 'Produccion\ProduccionController@getProduccionByOrdenPedido'); 
Route::name('produccion-consulta')->get('produccion/asociar/orden/pedido/articulo/todos', 'Produccion\ProduccionController@getProduccionByOrdenPedidoTodos');
Route::name('produccion-consulta')->get('produccion/articulo/insumo', 'Produccion\ProduccionController@getInsumosByArticuloId');




/** FILE MANAGER **/
Route::name('archivos')->post('/multiuploads/estudios', 'Upload\UploadController@showUploadFile');
Route::name('archivos')->post('/multiuploads/estudios/datos', 'Upload\UploadController@showUploadFileDatos');
Route::name('archivos')->post('/multiuploads/texto', 'Files\FilesController@createTestTextFile'); 
Route::name('archivos')->post('/multiuploads/texto/cirugia', 'Files\FilesController@createTestTextFileCirugia'); 
Route::name('archivos')->get('/multiuploads/estudios/verimagen', 'Upload\UploadController@getEstudioImagenes'); 

/** CHAT **/
