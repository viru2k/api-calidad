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
      $res = DB::select( DB::raw("SELECT insumo.id, insumo.nombre, insumo.descripcion, unidad_id, insumo.usuario_modifica_id, cantidad_unitaria, 
      cantidad_empaque, precio_unitario, precio_empaque, stock_minimo, estado,  stock_promedio, stock_maximo, unidad.descripcion as unidad_descripcion, users.nombreyapellido ,grupo_analisis.id AS grupo_analisis_id, grupo_analisis.grupo_nombre, grupo_analisis.color       
      FROM insumo, unidad, users , grupo_analisis
      WHERE  insumo.unidad_id = unidad.id AND insumo.usuario_modifica_id = users.id AND  insumo.grupo_analisis_id = grupo_analisis.id"));
   
        return response()->json($res, "200");
   
    }

   

/* -------------------------------------------------------------------------- */
/*                             CREACION DE INSUMO                             */
/* -------------------------------------------------------------------------- */

    public function store(Request $request)
    {
      
      $id =    DB::table('insumo')->insertGetId([
        'nombre' => $request->nombre, 
        'descripcion' => $request->descripcion,        
        'unidad_id' => $request->unidad_id, 
        'grupo_analisis_id' => $request->grupo_analisis_id,      
        'usuario_modifica_id' => $request->usuario_modifica_id,             
        'cantidad_unitaria' => $request->cantidad_unitaria,    
        'cantidad_empaque' => $request->cantidad_empaque,    
        'precio_unitario' => $request->precio_unitario,
        'precio_empaque' => $request->precio_empaque,
        'stock_minimo' => $request->stock_minimo,
        'stock_promedio' => $request->stock_promedio,
        'stock_maximo' => $request->stock_maximo,
        'estado' => 'ACTIVO',    
        'created_at' => date("Y-m-d H:i:s"),
        'updated_at' => date("Y-m-d H:i:s")
    ]);    
    }

/* -------------------------------------------------------------------------- */
/*                       MODIFICACION DE INSUMO PRIMARIO                      */
/* -------------------------------------------------------------------------- */

    public function update(Request $request, $id)
    {
      
      $res =  DB::table('insumo')
      ->where('id', $id)
      ->update([
        'nombre' => $request->input('nombre'),
        'descripcion' => $request->input('descripcion'),
        'unidad_id' => $request->input('unidad_id'),
        'grupo_analisis_id' => $request->input('grupo_analisis_id'),
        'usuario_modifica_id' => $request->input('usuario_modifica_id'),               
        'cantidad_unitaria' => $request->input('cantidad_unitaria'),   
        'cantidad_empaque' => $request->input('cantidad_empaque'),   
        'precio_unitario' => $request->input('precio_unitario'),   
        'precio_empaque' => $request->input('precio_empaque'), 
        'stock_minimo' => $request->input('stock_minimo'),   
        'stock_promedio' => $request->input('stock_promedio'), 
        'stock_maximo' => $request->input('stock_maximo'), 
        'estado' => $request->input('estado'),   
        'updated_at' => date("Y-m-d H:i:s")]);
    
        return response()->json($res, "200");
    }


/* -------------------------------------------------------------------------- */
/*                         INGRESO DE INSUMOS A STOCK                         */
/* -------------------------------------------------------------------------- */


    public function setInsumoStock(Request $request)
    {
     

      
      $res =  $request;
      
      //echo $t[1]['insumo_id'];
      $array =  (array) $res;
    //  echo sizeof($array);
      $longitud = sizeof($array);
   //   echo $longitud;
  
 
    //for( $i = 0; $i<$longitud; $i++ ) {
      foreach ($request->request->all()  as $res) {
      $tmp_fecha = str_replace('/', '-', $res["fecha_ingreso"]);
      $fecha_ingreso =  date('Y-m-d H:i', strtotime($tmp_fecha));   
      $fecha_movimiento =  date('Y-m-d H:i', strtotime($tmp_fecha));   
      $tmp_fecha = str_replace('/', '-', $res["fecha_vencimiento"]);
      $fecha_vencimiento =  date('Y-m-d H:i', strtotime($tmp_fecha));   
      

//      echo $res[$i]["insumo_id"];
      $id =    DB::table('stock_movimiento')->insertGetId([          
        'insumo_id' =>  $res["insumo_id"], 
        'comprobante' => $res["comprobante"],    
        'lote' => $res["lote"],
        'fecha_vencimiento' => $fecha_vencimiento,
        'cantidad' => $res["cantidad"],    
        'cantidad_usada' => $res["cantidad_usada"],  
        'cantidad_existente' => $res["cantidad_existente"],    
        'importe_cotizacion_dolares' => $res["importe_cotizacion_dolares"],  
        'importe_dolares' => $res["importe_dolares"],  
        'importe_total_dolares' => $res["importe_total_dolares"],  
        'importe_unitario' => $res["importe_unitario"],  
        'importe_acumulado' => $res["importe_acumulado"],  
        'importe_total' => $res["importe_total"],
        'usuario_modifica_id' => $res["usuario_modifica_id"],  
        'fecha_ingreso' => $fecha_ingreso,    
        'fecha_movimiento' => $fecha_movimiento,  
        'ultimo_deposito_id' => $res["ultimo_deposito_id"],  
        'estado' => 'ACTIVO',    
        'created_at' => date("Y-m-d H:i:s"),
        'updated_at' => date("Y-m-d H:i:s")
    ]);   
    //echo $res["nombre"];
   //echo $t[$i]['insumo_id'];
  //var_dump($res);    
      }  

     


      return response()->json($id, "200");  
    }


/* -------------------------------------------------------------------------- */
/*                    MODIFICACION DE EXISTENCIA DE INSUMO                    */
/* -------------------------------------------------------------------------- */

    //TODO  CALCULAR LA CANTIDAD EXISTENTE BASANDOSE EN LA CANTIDAD

    public function updInsumoStock(Request $request, $id)
    {
      

      $tmp_fecha = str_replace('/', '-', $request->input('fecha_movimiento'));
      $fecha_movimiento =  date('Y-m-d H:i', strtotime($tmp_fecha));  
      
      $tmp_fecha = str_replace('/', '-', $request->input["fecha_vencimiento"]);
      $fecha_vencimiento =  date('Y-m-d H:i', strtotime($tmp_fecha));   

      $res =  DB::table('stock_movimiento')
      ->where('id', $id)
      ->update([  
        'insumo_id' => $request->input('insumo_id'),
        'comprobante' => $request->input('comprobante'),
        'lote' => $request->input('lote'),
        'fecha_vencimiento' => $fecha_vencimiento,
        'cantidad' => $request->input('cantidad'),
        'cantidad_usada' => $request->input('cantidad_usada'),
        'cantidad_existente' => $request->input('cantidad_existente'),
        'importe_unitario' => $request->input('importe_unitario'),
        'importe_acumulado' => $request->input('importe_acumulado'),
        'importe_cotizacion_dolares' => $request->input('importe_cotizacion_dolares'),
        'importe_dolares' => $request->input('importe_dolares'),
        'importe_total_dolares' => $request->input('importe_total_dolares'),
        'importe_total' => $request->input('importe_total'),
        'usuario_modifica_id' => $request->input('usuario_modifica_id'),        
        'fecha_movimiento' => $fecha_movimiento,
        'ultimo_deposito_id' => $request->input('ultimo_deposito_id'),       
        'estado' => $request->input('estado'),       
        'updated_at' => date("Y-m-d H:i:s")]);
        
        return response()->json($res, "200");
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

  $res = DB::select( DB::raw("SELECT stock_armado_producto.id, articulo_id, insumo_id, stock_armado_producto.cantidad, stock_armado_producto.usuario_modifica_id, stock_armado_producto.estado, articulo.nombre, insumo.nombre, insumo.cantidad_unitaria, insumo.cantidad_empaque, users.nombreyapellido 
  FROM stock_armado_producto, articulo, insumo, users
  WHERE stock_armado_producto.articulo_id = articulo.id AND stock_armado_producto.insumo_id = insumo.id AND stock_armado_producto.usuario_modifica_id = users.id AND stock_armado_producto.articulo_id =  :articulo_id 
   "), array(                       
        'articulo_id' => $articulo_id
      ));

      return response()->json($res, "200");
}

/* -------------------------------------------------------------------------- */
/*                        OBTENGO EL INSUMO POR ESTADO                        */
/* -------------------------------------------------------------------------- */

public function getStockInsumoByEstado(Request $request)
{
    $estado =  $request->input('estado');   

  $res = DB::select( DB::raw("SELECT stock_movimiento.id, insumo_id, comprobante, stock_movimiento.lote, cantidad, cantidad_usada, cantidad_existente,importe_unitario, importe_acumulado, importe_total, fecha_vencimiento,
   stock_movimiento.usuario_modifica_id, fecha_ingreso, fecha_movimiento, stock_movimiento.estado, insumo.nombre, insumo.cantidad_unitaria, insumo.cantidad_empaque  ,importe_cotizacion_dolares, importe_dolares, importe_total_dolares
  FROM stock_movimiento, insumo 
  WHERE stock_movimiento.insumo_id = insumo.id AND stock_movimiento.estado = :estado
   "), array(                       
        'estado' => $estado
      ));

      return response()->json($res, "200");
}



public function getStockInsumoByEstadoExistencia(Request $request)
{
    $estado =  $request->input('estado');   
    $condicion = $request->input('condicion');   
    if($condicion === 'SIN_MOVIMIENTO') {
      //echo $condicion;
      $res = DB::select( DB::raw("SELECT stock_movimiento.id, insumo_id, comprobante, stock_movimiento.lote, cantidad, cantidad_usada, cantidad_existente,importe_unitario, importe_acumulado, importe_total, stock_movimiento.usuario_modifica_id, 
      fecha_ingreso, fecha_movimiento, stock_movimiento.estado, insumo.nombre, insumo.cantidad_unitaria, insumo.cantidad_empaque  , importe_cotizacion_dolares, importe_cotizacion_dolares, importe_dolares, importe_total_dolares
      FROM stock_movimiento, insumo 
      WHERE stock_movimiento.insumo_id = insumo.id AND stock_movimiento.estado = :estado AND  cantidad_existente =  cantidad AND  cantidad_existente > 0
       "), array(                       
            'estado' => $estado
          ));
    }

    if($condicion === 'CON_EXISTENCIA') {
    //  echo $condicion;
      $res = DB::select( DB::raw("SELECT stock_movimiento.id, insumo_id, comprobante, stock_movimiento.lote, cantidad, cantidad_usada, cantidad_existente,importe_unitario, importe_acumulado,
       importe_total, stock_movimiento.usuario_modifica_id, fecha_ingreso, fecha_movimiento, stock_movimiento.estado, insumo.nombre, insumo.cantidad_unitaria, insumo.cantidad_empaque  ,importe_cotizacion_dolares,  importe_dolares, importe_total_dolares
      FROM stock_movimiento, insumo 
      WHERE stock_movimiento.insumo_id = insumo.id AND stock_movimiento.estado = :estado AND  cantidad_existente > 0
       "), array(                       
            'estado' => $estado
          ));
    }
  

      return response()->json($res, "200");
}

/* -------------------------------------------------------------------------- */
/*                     OBTENER EL MOVIMIENTO DE UN INSUMO                     */
/* -------------------------------------------------------------------------- */

public function getStockMovimientoByInsumoAndEstado(Request $request)
{
    $insumo_id =  $request->input('insumo_id');   
    $estado =  $request->input('estado');  

  $res = DB::select( DB::raw("SELECT stock_movimiento.id, insumo_id, comprobante, stock_movimiento.lote, cantidad, cantidad_usada, cantidad_existente ,
   importe_acumulado, importe_acumulado, importe_total, stock_movimiento.usuario_modifica_id, fecha_ingreso, fecha_movimiento, stock_movimiento.estado,insumo.nombre,  users.nombreyapellido  ,
    importe_cotizacion_dolares, importe_dolares, importe_total_dolares, stock_movimiento.ultimo_deposito_id, deposito.descripcion
  FROM stock_movimiento, insumo, users, deposito 
  WHERE deposito.id = stock_movimiento.ultimo_deposito_id AND stock_movimiento.insumo_id = insumo.id AND stock_movimiento.usuario_modifica_id = users.id  AND stock_movimiento.insumo_id = :insumo_id AND stock_movimiento.estado = :estado
   "), array(                       
        'insumo_id' => $insumo_id,
        'estado' => $estado,
      ));

      return response()->json($res, "200");
}



public function getStockMovimientoByEstadoConExistencia(Request $request)
{
     
    $estado =  $request->input('estado');  

  $res = DB::select( DB::raw("SELECT stock_movimiento.id, insumo_id, comprobante , stock_movimiento.lote, cantidad, cantidad_usada, cantidad_existente , importe_acumulado, importe_acumulado,
   importe_total, stock_movimiento.usuario_modifica_id, fecha_ingreso, fecha_movimiento, stock_movimiento.estado,insumo.nombre,  users.nombreyapellido  , importe_cotizacion_dolares, importe_dolares, importe_total_dolares
  FROM stock_movimiento, insumo, users 
  WHERE stock_movimiento.insumo_id = insumo.id AND stock_movimiento.usuario_modifica_id = users.id   AND stock_movimiento.estado = :estado AND stock_movimiento.cantidad_existente > 0
   "), array(                               
        'estado' => $estado
      ));

      return response()->json($res, "200");
}

/* -------------------------------------------------------------------------- */
/*                   OBTENGO LOS INSUMOS PARA UNA PRODUCCION                  */
/* -------------------------------------------------------------------------- */

public function getStockMovimientoByProduccion(Request $request)
{
    $produccion_proceso_id =  $request->input('produccion_proceso_id');   

  $res = DB::select( DB::raw("SELECT stock_movimiento_produccion.id,  comprobante, stock_movimiento.lote, produccion_proceso_id, stock_movimiento_id, stock_movimiento_produccion.cantidad_usada, stock_movimiento_produccion.cantidad_existente, 
  stock_movimiento_produccion.fecha_movimiento, 
  stock_movimiento_produccion.usuario_alta_id, produccion_proceso.id as produccion_proceso_id, produccion_proceso.lote, produccion_proceso.articulo_id, articulo.nombre, stock_movimiento.id as stock_movimiento_id,
  insumo.id as insumo_id, insumo.nombre,  users.nombreyapellido, produccion_proceso.cantidad_solicitada, produccion_proceso.cantidad_usada, produccion_proceso.cantidad_producida  , importe_cotizacion_dolares, importe_dolares, importe_total_dolares
  FROM stock_movimiento_produccion, produccion_proceso, stock_movimiento, users, articulo, insumo 
  WHERE produccion_proceso.id = stock_movimiento_produccion.produccion_proceso_id AND stock_movimiento_produccion.stock_movimiento_id = stock_movimiento.id 
  AND stock_movimiento_produccion.usuario_alta_id = users.id AND produccion_proceso.articulo_id = articulo.id
  AND stock_movimiento.insumo_id = insumo.id AND produccion_proceso.id = :produccion_proceso_id
   "), array(                       
        'produccion_proceso_id' => $produccion_proceso_id
      ));

      return response()->json($res, "200");
}


/* -------------------------------------------------------------------------- */
/*        OBTENGO LAS PRODUCCIONES DONDE SE ASOCIO EL INGRESO DE INSUMO       */
/* -------------------------------------------------------------------------- */



public function getStockMovimientoByMovimientoId(Request $request)
{
    $stock_movimiento_id =  $request->input('id');   

  $res = DB::select( DB::raw("SELECT stock_movimiento_produccion.id, stock_movimiento_produccion.produccion_proceso_id, stock_movimiento_produccion.stock_movimiento_id, stock_movimiento_produccion.cantidad_usada, 
  stock_movimiento_produccion.cantidad_existente, stock_movimiento_produccion.fecha_movimiento, stock_movimiento_produccion.usuario_alta_id, stock_movimiento.insumo_id, stock_movimiento.comprobante, 
  produccion_proceso.lote, stock_movimiento.cantidad as stock_movimiento_cantidad , stock_movimiento.cantidad_usada as  stock_movimiento_cantidad_usada, stock_movimiento.cantidad_existente as stock_movimiento_cantidad_existente, 
  stock_movimiento.fecha_ingreso, insumo.nombre, insumo.descripcion, users.nombreyapellido, produccion_proceso.articulo_id, produccion_proceso.orden_produccion_detalle_id as produccion_id, 
  articulo.nombre as articulo_nombre, articulo.descripcion   , importe_dolares, importe_total_dolares
  FROM stock_movimiento_produccion, stock_movimiento, insumo, users, produccion_proceso, articulo 
  WHERE  stock_movimiento_produccion.stock_movimiento_id = stock_movimiento.id AND stock_movimiento.insumo_id = insumo.id AND stock_movimiento_produccion.usuario_alta_id = users.id 
  AND stock_movimiento_produccion.produccion_proceso_id = produccion_proceso.id AND produccion_proceso.articulo_id = articulo.id AND stock_movimiento.id = :stock_movimiento_id
   "), array(                       
        'stock_movimiento_id' => $stock_movimiento_id
      ));

      return response()->json($res, "200");
}


/* -------------------------------------------------------------------------- */
/*                       OBTENGO LA EXISTENCIA DE STOCK DE TODOS LOS INSUMOS  */
/* -------------------------------------------------------------------------- */

public function getStockExistencia(Request $request)
{
  

  $res = DB::select( DB::raw("SELECT  insumo_id, SUM(cantidad) AS cantidad, SUM(cantidad_usada) AS cantidad_usada, SUM(cantidad_existente) AS cantidad_existente,
   SUM(importe_acumulado) AS importe_acumulado, SUM(importe_acumulado) AS importe_acumulado, SUM(importe_total) AS importe_total,insumo.nombre ,insumo.descripcion ,importe_cotizacion_dolares, importe_dolares, importe_total_dolares
  FROM stock_movimiento, insumo, users 
  WHERE stock_movimiento.insumo_id = insumo.id AND stock_movimiento.usuario_modifica_id = users.id    AND stock_movimiento.cantidad_existente > 0   GROUP by insumo_id 
ORDER BY insumo.nombre ASC
   "));

      return response()->json($res, "200");
}




/* -------------------------------------------------------------------------- */
/*                   OBTENGO EL INSUMO POR PERIODO DE FECHAS                  */
/* -------------------------------------------------------------------------- */

public function getStockInsumoByDate(Request $request)
{
    $tmp_fecha = str_replace('/', '-', $request->input('fecha_desde'));
    $fecha_desde =  date('Y-m-d H:i', strtotime($tmp_fecha));   
    $tmp_fecha = str_replace('/', '-', $request->input('fecha_hasta'));
    $fecha_hasta =  date('Y-m-d H:i', strtotime($tmp_fecha));    

  $res = DB::select( DB::raw("SELECT insumo_stock.id, insumo_stock.insumo_id, insumo_stock.fecha_ingreso, insumo_stock.fecha_finalizado, insumo_stock.cantidad, insumo_stock.cantidad_usada,
   insumo_stock.cantidad_existente, insumo_stock.unidad_id,
   insumo_stock.produccion_id, insumo_stock.usuario_alta_id, insumo_stock.estado, insumo_stock.created_at, insumo_stock.updated_at, users.nombreyapellido,
    insumo.descripcion insumo_descripcion, unidad.descripcion as unidad_descrpicion FROM insumo, unidad, users , insumo_stock LEFT JOIN produccion ON insumo_stock.produccion_id = produccion.id 
  WHERE insumo_stock.insumo_id = insumo.id AND insumo_stock.unidad_id = unidad.id AND insumo_stock.usuario_alta_id = users.id  AND    insumo_stock.fecha_ingreso BETWEEN   :fecha_desde  and :fecha_hasta
   "), array(                       
        'fecha_desde' => $fecha_desde,
        'fecha_hasta' => $fecha_hasta
      ));

      return response()->json($res, "200");
}

/* -------------------------------------------------------------------------- */
/*                 OBTENGO EL INSUMO DE PRODUCCION POR FECHAS                 */
/* -------------------------------------------------------------------------- */

public function getStockInsumoProduccionByDate(Request $request)
{
    $tmp_fecha = str_replace('/', '-', $request->input('fecha_desde'));
    $fecha_desde =  date('Y-m-d H:i', strtotime($tmp_fecha));   
    $tmp_fecha = str_replace('/', '-', $request->input('fecha_hasta'));
    $fecha_hasta =  date('Y-m-d H:i', strtotime($tmp_fecha));    

  $res = DB::select( DB::raw("SELECT insumo_stock.id, insumo_stock.insumo_id, insumo_stock.fecha_ingreso, insumo_stock.fecha_finalizado, insumo_stock.cantidad, insumo_stock.cantidad_usada, insumo_stock.cantidad_existente, insumo_stock.unidad_id, insumo_stock.produccion_id, insumo_stock.usuario_alta_id, insumo_stock.estado, insumo_stock.created_at, insumo_stock.updated_at, users.nombreyapellido, insumo.descripcion insumo_descripcion, unidad.descripcion as unidad_descripicion, articulo.descripcion as articulo_descripcion  FROM articulo, insumo, unidad, users , insumo_stock LEFT JOIN produccion ON insumo_stock.produccion_id = produccion.id 
  WHERE insumo_stock.insumo_id = insumo.id AND insumo_stock.unidad_id = unidad.id AND insumo_stock.usuario_alta_id = users.id AND produccion.articulo_id = articulo.id AND   produccion.fecha_produccion BETWEEN   :fecha_desde  and :fecha_hasta
   "), array(                       
        'fecha_desde' => $fecha_desde,
        'fecha_hasta' => $fecha_hasta
      ));

      return response()->json($res, "200");
}

/* -------------------------------------------------------------------------- */
/*        OBTENGO EL STOCK DE INSUMOS PARA ASOCIAR A UNA PRODUCCION           */
/* -------------------------------------------------------------------------- */

public function getStockByArmadoProducto(Request $request)
{
    $articulo_id =  $request->input('articulo_id');   
    $insumo_id =  $request->input('insumo_id');   

  $res = DB::select( DB::raw("SELECT stock_movimiento.id AS stock_movimiento_id, insumo.id AS insumo_id, nombre, descripcion,   cantidad_unitaria, cantidad_empaque, precio_unitario, precio_empaque,  
  insumo.estado ,stock_armado_producto.id AS stock_armado_producto_id , stock_armado_producto.cantidad AS stock_armado_producto_cantidad, comprobante, lote, stock_movimiento.cantidad ,  fecha_vencimiento,
  stock_movimiento.cantidad_usada, stock_movimiento.cantidad_existente, stock_movimiento.importe_acumulado, stock_movimiento.fecha_ingreso, stock_movimiento.fecha_movimiento ,importe_cotizacion_dolares, importe_dolares, importe_total_dolares
  FROM  insumo , stock_armado_producto, stock_movimiento 
  WHERE insumo.id = stock_armado_producto.insumo_id AND insumo.id = stock_movimiento.insumo_id  AND stock_armado_producto.articulo_id = :articulo_id AND stock_movimiento.insumo_id = :insumo_id AND stock_movimiento.cantidad_existente > 0 ORDER BY insumo.id DESC
   "), array(                       
        'articulo_id' => $articulo_id,
        'insumo_id' => $insumo_id
      ));

      return response()->json($res, "200");
}


    
}
