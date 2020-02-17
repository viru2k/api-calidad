<?php

namespace App\Http\Controllers\Insumos;
use Illuminate\Support\Facades\DB; 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class InsumoController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

/* -------------------------------------------------------------------------- */
/*                      OBTENCION DE INSUMOS POR ARTICULO                     */
/* -------------------------------------------------------------------------- */



public function getInsumosByArticulo(Request $request)
{
    $articulo_id =  $request->input('articulo_id');   

  $horario = DB::select( DB::raw("SELECT articulo_confeccion.id, articulo_confeccion.articulo_id, articulo_confeccion.insumo_id, articulo_confeccion.cantidad, articulo_confeccion.VOLUMEN, articulo_confeccion.unidad, articulo_confeccion.created_at, articulo_confeccion.updated_at, articulo.descripcion, insumo.id as insumo_id, insumo.descripcion as insumo_descripcion   
  FROM articulo_confeccion, articulo, insumo, unidad 
  WHERE articulo.id = articulo_confeccion.articulo_id AND insumo.id = articulo_confeccion.insumo_id AND articulo.unidad_id = unidad.id AND articulo_confeccion.articulo_id =  :articulo_id 
   "), array(                       
        'articulo_id' => $articulo_id
      ));

  return $horario;
}

public function getStockInsumoByEstado(Request $request)
{
    $estado =  $request->input('estado');   

  $horario = DB::select( DB::raw("SELECT insumo_stock.id, insumo_stock.insumo_id, insumo_stock.fecha_ingreso, insumo_stock.fecha_finalizado, insumo_stock.cantidad, insumo_stock.cantidad_usada, insumo_stock.cantidad_existente, insumo_stock.unidad_id, insumo_stock.produccion_id, insumo_stock.usuario_alta_id, insumo_stock.estado, insumo_stock.created_at, insumo_stock.updated_at, users.nombreyapellido, insumo.descripcion insumo_descripcion, unidad.descripcion as unidad_descrpicion FROM insumo, unidad, users , insumo_stock LEFT JOIN produccion ON insumo_stock.produccion_id = produccion.id 
  WHERE insumo_stock.insumo_id = insumo.id AND insumo_stock.unidad_id = unidad.id AND insumo_stock.usuario_alta_id = users.id  AND insumo_stock.estado = :estado
   "), array(                       
        'estado' => $estado
      ));

  return $horario;
}


public function getStockInsumoByProduccion(Request $request)
{

    $produccion_id =  $request->input('produccion_id');  
 

  $horario = DB::select( DB::raw("SELECT insumo_stock.id, insumo_stock.insumo_id, insumo_stock.fecha_ingreso, insumo_stock.fecha_finalizado, insumo_stock.cantidad, insumo_stock.cantidad_usada, insumo_stock.cantidad_existente, insumo_stock.unidad_id, insumo_stock.produccion_id, insumo_stock.usuario_alta_id, insumo_stock.estado, insumo_stock.created_at, insumo_stock.updated_at, users.nombreyapellido, insumo.descripcion insumo_descripcion, unidad.descripcion as unidad_descrpicion FROM insumo, unidad, users , insumo_stock LEFT JOIN produccion ON insumo_stock.produccion_id = produccion.id 
  WHERE insumo_stock.insumo_id = insumo.id AND insumo_stock.unidad_id = unidad.id AND insumo_stock.usuario_alta_id = users.id  AND  insumo_stock.produccion_id	=  :produccion_id  AND  insumo_stock.estado = 'ACTIVO'
   "), array(                       
        'produccion_id' => $produccion_id
      ));

  return $horario;
}

public function getStockInsumoByDate(Request $request)
{
    $tmp_fecha = str_replace('/', '-', $request->input('fecha_desde'));
    $fecha_desde =  date('Y-m-d H:i', strtotime($tmp_fecha));   
    $tmp_fecha = str_replace('/', '-', $request->input('fecha_hasta'));
    $fecha_hasta =  date('Y-m-d H:i', strtotime($tmp_fecha));    

  $horario = DB::select( DB::raw("SELECT insumo_stock.id, insumo_stock.insumo_id, insumo_stock.fecha_ingreso, insumo_stock.fecha_finalizado, insumo_stock.cantidad, insumo_stock.cantidad_usada, insumo_stock.cantidad_existente, insumo_stock.unidad_id, insumo_stock.produccion_id, insumo_stock.usuario_alta_id, insumo_stock.estado, insumo_stock.created_at, insumo_stock.updated_at, users.nombreyapellido, insumo.descripcion insumo_descripcion, unidad.descripcion as unidad_descrpicion FROM insumo, unidad, users , insumo_stock LEFT JOIN produccion ON insumo_stock.produccion_id = produccion.id 
  WHERE insumo_stock.insumo_id = insumo.id AND insumo_stock.unidad_id = unidad.id AND insumo_stock.usuario_alta_id = users.id  AND    insumo_stock.fecha_ingreso BETWEEN   :fecha_desde  and :fecha_hasta
   "), array(                       
        'fecha_desde' => $fecha_desde,
        'fecha_hasta' => $fecha_hasta
      ));

  return $horario;
}

public function getStockInsumoProduccionByDate(Request $request)
{
    $tmp_fecha = str_replace('/', '-', $request->input('fecha_desde'));
    $fecha_desde =  date('Y-m-d H:i', strtotime($tmp_fecha));   
    $tmp_fecha = str_replace('/', '-', $request->input('fecha_hasta'));
    $fecha_hasta =  date('Y-m-d H:i', strtotime($tmp_fecha));    

  $horario = DB::select( DB::raw("SELECT insumo_stock.id, insumo_stock.insumo_id, insumo_stock.fecha_ingreso, insumo_stock.fecha_finalizado, insumo_stock.cantidad, insumo_stock.cantidad_usada, insumo_stock.cantidad_existente, insumo_stock.unidad_id, insumo_stock.produccion_id, insumo_stock.usuario_alta_id, insumo_stock.estado, insumo_stock.created_at, insumo_stock.updated_at, users.nombreyapellido, insumo.descripcion insumo_descripcion, unidad.descripcion as unidad_descripicion, articulo.descripcion as articulo_descripcion  FROM articulo, insumo, unidad, users , insumo_stock LEFT JOIN produccion ON insumo_stock.produccion_id = produccion.id 
  WHERE insumo_stock.insumo_id = insumo.id AND insumo_stock.unidad_id = unidad.id AND insumo_stock.usuario_alta_id = users.id AND produccion.articulo_id = articulo.id AND   produccion.fecha_produccion BETWEEN   :fecha_desde  and :fecha_hasta
   "), array(                       
        'fecha_desde' => $fecha_desde,
        'fecha_hasta' => $fecha_hasta
      ));

  return $horario;
}
    // SELECT * FROM insumo, unidad, users , insumo_stock LEFT JOIN produccion ON insumo_stock.produccion_id = produccion.id WHERE insumo_stock.insumo_id = insumo.id AND insumo_stock.unidad_id = unidad.id AND insumo_stock.usuario_alta_id = users.id
}
