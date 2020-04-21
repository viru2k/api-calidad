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
Route::resource('produccion', 'Produccion\ProduccionController'); 
Route::resource('calidad', 'Calidad\CalidadController'); 

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

//Route::name('articulo')->post('articulo',         'Articulos\ArticuloController@postArticulo');
//Route::name('articulo')->put('articulo/{id}',     'Articulos\ArticuloController@putArticulo'); 



/* -------------------------------------------------------------------------- */
/*                              RUTAS DE ARTICULO CONFECCION                  */
/* -------------------------------------------------------------------------- */

/* ------------------------ ARTICULOS CON PROPIEDADES ------------------------ */
Route::name('articulo-confeccion')->get('articulos',         'Articulos\ArticuloController@getArticulos');
/* ------------------------ ARTICULO CON PROPIEDADES ------------------------ */
Route::name('articulo-confeccion')->get('articulo',         'Articulos\ArticuloController@getArticulo');
/* ----------------- INSERTO LAS PROPIEDADES DE UN ARTICULO ----------------- */
Route::name('articulo-confeccion')->post('articulo/propiedades',         'Articulos\ArticuloController@setArticuloPropiedades');
/* ---------------- ACTUALIZO LAS PROPIEDADES DE UN ARTICULO ---------------- */
Route::name('articulo-confeccion')->put('articulo/propiedades/{id}',     'Articulos\ArticuloController@updateArticuloPropiedades'); 

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

/* ------------------ OBTENGO EL ARMADO DEL PRODUCTO POR ID ----------------- */
Route::name('produccion-consulta')->get('produccion/producto/confeccion', 'Produccion\ProduccionController@produccionArmadoDeProductoById');
/* --------------- ACTUALIZO EL ARMADO DE PRODUCTO CON INSUMO --------------- */
Route::name('produccion-consulta')->put('produccion/producto/confeccion/{id}', 'Produccion\ProduccionController@updateStockArmadoProducto'); 
/* -------------- CONFECCIONO EL ARMADO DEL PRODUCTO CON INSUMO ------------- */
Route::name('produccion-consulta')->post('produccion/producto/confeccion', 'Produccion\ProduccionController@setStockArmadoProducto');

Route::name('produccion-consulta')->get('produccion/producto/confeccion/eliminar', 'Produccion\ProduccionController@delStockArmadoProducto');

/* -------------------------------------------------------------------------- */
/*                            PENDIENTES DE VALIDAR                           */
/* -------------------------------------------------------------------------- */

Route::name('produccion-consulta')->get('produccion/sector/carga', 'Produccion\ProduccionController@getSectorProduccion');
Route::name('produccion-consulta')->get('produccion/movimiento/stock', 'Produccion\ProduccionController@getStockProduccion');
Route::name('produccion-consulta')->get('produccion/by/dates', 'Produccion\ProduccionController@getProduccionStockByDates');
Route::name('produccion-consulta')->put('produccion/{id}', 'Produccion\ProduccionController@updProduccionStock'); 
Route::name('produccion-consulta')->post('produccion/stock', 'Produccion\ProduccionController@setProduccionStock');
Route::name('produccion-consulta')->post('produccion/orden/produccion', 'Produccion\ProduccionController@setOrdenProduccion'); 
Route::name('produccion-consulta')->get('produccion/orden/produccion/estado', 'Produccion\ProduccionController@getOrdenProduccionEstado');
Route::name('produccion-consulta')->get('produccion/orden/produccion/by/id', 'Produccion\ProduccionController@getOrdenProduccionDetalleById');
Route::name('produccion-consulta')->get('produccion/orden/produccion/by/estado', 'Produccion\ProduccionController@getOrdenProduccionDetalleByEstado');
Route::name('produccion-consulta')->get('produccion/orden/produccion/estado/editar', 'Produccion\ProduccionController@updOrdenProduccion');
Route::name('produccion-consulta')->post('produccion/crear', 'Produccion\ProduccionController@setProduccion');
Route::name('produccion-consulta')->get('produccion/asociar/orden/produccion/articulo', 'Produccion\ProduccionController@getProduccionByOrdenProduccion'); 
Route::name('produccion-consulta')->get('produccion/asociar/orden/produccion/articulo/todos', 'Produccion\ProduccionController@getProduccionByOrdenProduccionTodos');
Route::name('produccion-consulta')->get('produccion/articulo/insumo', 'Produccion\ProduccionController@getInsumosByArticuloId');




/** FILE MANAGER **/
Route::name('archivos')->post('/multiuploads/estudios', 'Upload\UploadController@showUploadFile');
Route::name('archivos')->post('/multiuploads/estudios/datos', 'Upload\UploadController@showUploadFileDatos');
Route::name('archivos')->post('/multiuploads/texto', 'Files\FilesController@createTestTextFile'); 
Route::name('archivos')->post('/multiuploads/texto/cirugia', 'Files\FilesController@createTestTextFileCirugia'); 
Route::name('archivos')->get('/multiuploads/estudios/verimagen', 'Upload\UploadController@getEstudioImagenes'); 

/** CHAT **/
