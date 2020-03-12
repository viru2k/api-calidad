<?php

namespace App\Http\Controllers\Produccion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\Articulo; 
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

class ProduccionController extends Controller
{
/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Articulo = Articulo::all();
        return $this->showAll($Articulo);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Articulo $Articulo)
    {
        return $this->showOne($Articulo);
    }

/* -------------------------------------------------------------------------- */
/*                             CREO LA PRODUCCION                             */
/* -------------------------------------------------------------------------- */

    public function setProduccion(Request $request)
    {
        $tmp_fecha = str_replace('/', '-', $request->fecha_produccion);
        $fecha_produccion =  date('Y-m-d H:i', strtotime($tmp_fecha));   
     

      $produccion_id =    DB::table('produccion')->insertGetId([
        
        'orden_pedido_articulos_id' => $request->orden_pedido_articulos_id,        
        'articulo_id' => $request->articulo_id,         
        'fecha_produccion' => $fecha_produccion, 
        'unidad_id' => $request->unidad_id,        
        'cantidad_botella' => $request->cantidad_botella, 
        'cantidad_litros' => $request->cantidad_litros, 
        'sector_id' => $request->sector_id, 
        'usuario_alta_id' => $request->usuario_alta_id, 
        'usuario_modifica_id' => $request->usuario_alta_id, 
        'created_at' => date("Y-m-d H:i:s"),
        'updated_at' => date("Y-m-d H:i:s")
    ]);    


    $stock_id =    DB::table('produccion_stock')->insertGetId([
        
      'produccion_id' => $produccion_id, 
      'fecha_ingreso' => $fecha_produccion, 
      'cantidad_original' => $fecha_produccion,  
      'cantidad_original' => $request->cantidad_botella, 
      'cantidad_salida' => '0', 
      'existencia' => $request->cantidad_botella, 
      'usuario_alta_id' => $request->usuario_alta_id, 
      'created_at' => date("Y-m-d H:i:s"),
      'updated_at' => date("Y-m-d H:i:s")
  ]);    

      return response()->json($stock_id, "200");  
    }


/* -------------------------------------------------------------------------- */
/*                           ACTUALIZO LA PRODUCCION                          */
/* -------------------------------------------------------------------------- */

    public function update(Request $request, $id)
    {

        $tmp_fecha = str_replace('/', '-', $request->input('fecha_produccion'));
        $fecha_produccion =  date('Y-m-d H:i', strtotime($tmp_fecha));   
      
      $res =  DB::table('produccion')
      ->where('id', $id)
      ->update([
        'fecha_produccion' => $fecha_produccion,
        'descripcion' => $request->input('es_habilitado'),
        'unidad_id' => $request->input('unidad_id'),
        'cantidad_botella' => $request->input('cantidad_botella'),
        'cantidad_litros' => $request->input('cantidad_litros'),
        'sector_id' => $request->input('sector_id'),
        'usuario_alta_id' => $request->input('usuario_alta'),
        'updated_at' => date("Y-m-d H:i:s")]);

        return response()->json($res, "200");
    }

/* -------------------------------------------------------------------------- */
/*                        INGRESO LA PRODUCCION DE STOCK                       */
/* -------------------------------------------------------------------------- */

    public function setProduccionStock(Request $request)
    {
        $tmp_fecha = str_replace('/', '-', $request->fecha_ingreso);
        $fecha_ingreso =  date('Y-m-d H:i', strtotime($tmp_fecha));   
        $tmp_fecha = str_replace('/', '-', $request->fecha_egreso);
        $fecha_egreso =  date('Y-m-d H:i', strtotime($tmp_fecha));   
      $id =    DB::table('produccion_stock')->insertGetId([
        
        'produccion_id' => $request->produccion_id, 
        'fecha_egreso' => $fecha_egreso, 
        'fecha_ingreso' => $fecha_ingreso, 
        'fecha_pedido' => $fecha_pedido, 
        'unidad_id' => $request->unidad_id,        
        'cantidad_original' => $request->cantidad_original, 
        'cantidad_salida' => $request->cantidad_salida, 
        'existencia' => $request->existencia, 
        'sector_id' => $request->sector_id, 
        'usuario_alta_id' => $request->usuario_alta_id, 
        'created_at' => date("Y-m-d H:i:s"),
        'updated_at' => date("Y-m-d H:i:s")
    ]);    


      return response()->json($turno, "200");  
    }

/* -------------------------------------------------------------------------- */
/*                           ACTUALIZO LA PRODUCCION                          */
/* -------------------------------------------------------------------------- */

//TODO  CALCULAR LA EXISTENCIA BASANDOSE EN LA CANTIDAD DE SALIDA
    public function updProduccionStock(Request $request, $id)
    {

        $tmp_fecha = str_replace('/', '-', $request->fecha_ingreso);
        $fecha_ingreso =  date('Y-m-d H:i', strtotime($tmp_fecha));   
        $tmp_fecha = str_replace('/', '-', $request->fecha_egreso);
        $fecha_egreso =  date('Y-m-d H:i', strtotime($tmp_fecha));
      
      $res =  DB::table('produccion_stock')
      ->where('id', $id)
      ->update([
        'fecha_ingreso' => $fecha_ingreso,
        'fecha_egreso' => $fecha_egreso,
        'cantidad_original' => $request->input('cantidad_original'),
        'cantidad_salida' => $request->input('cantidad_salida'),
        'existencia' => $request->input('existencia'),
        'usuario_alta_id' => $request->input('usuario_alta_id'),
        'updated_at' => date("Y-m-d H:i:s")]);

        return response()->json($res, "200");
    }

/* -------------------------------------------------------------------------- */
/*    OBTENGO EL STOCK DE PRODUCCION SIEMPRE QUE LA EXISTENCIA SE MAYOR A 0   */
/* -------------------------------------------------------------------------- */

    public function getStockProduccion(Request $request)
    {
    
        //$produccion_id =  $request->input('produccion_id');  
     
    
      $res = DB::select( DB::raw("SELECT articulo.descripcion as articulo_descripcion, produccion.orden_pedido, produccion.id as produccion_id, produccion_stock.id as produccion_stock_id, produccion.fecha_produccion, produccion.fecha_pedido, produccion.cantidad_botella, produccion.cantidad_litros, unidad.descripcion as unidad_descripcion, users.nombreyapellido, sector.nombre AS sector_nombre, produccion_stock.fecha_ingreso, produccion_stock.fecha_egreso, produccion_stock.cantidad_original, produccion_stock.cantidad_salida, produccion_stock.existencia , produccion_movimiento.fecha_movimiento, produccion_movimiento.cantidad_salida as produccion_movimiento_cantidad_salida
      FROM `produccion_stock`,produccion_movimiento,  produccion, unidad, users, sector, articulo 
      WHERE  produccion.id = produccion_stock.produccion_id AND produccion.unidad_id = unidad.id AND produccion_movimiento.produccion_stock_id = produccion_stock.id AND produccion_stock.usuario_alta_id = users.id AND sector.id = produccion.sector_id AND produccion.articulo_id = articulo.id AND produccion_stock.existencia > 0
      
       "));
    
    return response()->json($res, "200");
    }

/* -------------------------------------------------------------------------- */
/*            OBTENGO LA PRODUCCION REALIZADA POR PERIODO DE FECHA            */
/* -------------------------------------------------------------------------- */

//TODO  FALTA REALIZAR LA CONSULTA

public function getProduccionStockByDates(Request $request)
{

  $tmp_fecha = str_replace('/', '-', $request->input('fecha_desde'));
  $fecha_desde =  date('Y-m-d H:i', strtotime($tmp_fecha));   
  $tmp_fecha = str_replace('/', '-', $request->input('fecha_hasta'));
  $fecha_hasta =  date('Y-m-d H:i', strtotime($tmp_fecha));  
 

 
  $res = DB::select( DB::raw("SELECT articulo.descripcion as articulo_descripcion, produccion.orden_pedido, produccion.id as produccion_id, produccion_stock.id as produccion_stock_id, produccion.fecha_produccion, produccion.fecha_pedido, produccion.cantidad_botella, produccion.cantidad_litros, unidad.descripcion as unidad_descripcion, users.nombreyapellido, sector.nombre AS sector_nombre, produccion_stock.fecha_ingreso, produccion_stock.fecha_egreso, produccion_stock.cantidad_original, produccion_stock.cantidad_salida, produccion_stock.existencia , produccion_movimiento.fecha_movimiento, produccion_movimiento.cantidad_salida as produccion_movimiento_cantidad_salida
  FROM `produccion_stock`,produccion_movimiento,  produccion, unidad, users, sector, articulo 
  WHERE  produccion.id = produccion_stock.produccion_id AND produccion.unidad_id = unidad.id AND produccion_movimiento.produccion_stock_id = produccion_stock.id AND produccion_stock.usuario_alta_id = users.id AND sector.id = produccion.sector_id AND produccion.articulo_id = articulo.id AND produccion.fecha_produccion BETWEEN   :fecha_desde  and :fecha_hasta
  
   "), array(                       
        'fecha_desde' => $fecha_desde,
        'fecha_hasta' => $fecha_hasta
      ));

      return response()->json($res, "200");
}

/* -------------------------------------------------------------------------- */
/*          CREO LA ORDEN DE PEDIDO Y AGREGO EL LISTADO DE PRODUCTOS          */
/* -------------------------------------------------------------------------- */

public function setOrdenPedido(Request $request)
{
    $tmp_fecha = str_replace('/', '-', $request->fecha);
    $fecha =  date('Y-m-d H:i', strtotime($tmp_fecha));  

  $id =    DB::table('orden_pedido')->insertGetId([
    
    'fecha_pedido' => $request->fecha_pedido, 
    'usuario_id' => $request->usuario_id,  
    'estado' => 'ACTIVO',  
    'created_at' => date("Y-m-d H:i:s"),
    'updated_at' => date("Y-m-d H:i:s")
]);    

  $t = $request->articulo;

  foreach ($t as $res) {
    if($res["cantidad"] != 0){
      DB::table('orden_pedido_articulos')->insertGetId([            
        'orden_pedido_id' => $id,
        'articulo_id' => $res["id"],          
        'cantidad' => $res["cantidad"],       
        'usuario_id' => $request->usuario_id,         
        'created_at' => date("Y-m-d H:i:s"),
        'updated_at' => date("Y-m-d H:i:s")    
    ]);      
    }       
  }
 return response()->json($id, "200");  
}


/* -------------------------------------------------------------------------- */
/*                  OBTENGO LAS ORDENES DE PEDIDO INCOMPLETAS                 */
/* -------------------------------------------------------------------------- */

public function getOrdenPedidoEstado(Request $request)
  {
    $estado = $request->input('estado');
    $res = DB::select( DB::raw("SELECT orden_pedido.id, fecha_pedido, estado, users.id as usuario_id, users.nombreyapellido FROM orden_pedido,users WHERE orden_pedido.usuario_id = users.id and estado = :estado ORDER BY fecha_pedido ASC
    
    "), array(                       
          'estado' => $estado
        ));

        return response()->json($res, "200");
  }

/* -------------------------------------------------------------------------- */
/*              OBTENGO LA PRODUCCION ASOCIADA A ORDEN DE PEDIDO              */
/* -------------------------------------------------------------------------- */

public function  getOrdenPedidoDetalleByEstado(Request $request)
  {
    $estado = $request->input('estado');
    $res = DB::select( DB::raw("SELECT articulo.id as articulo_id, orden_pedido.id, fecha_pedido, orden_pedido_articulos.id as orden_pedido_articulos_id, orden_pedido_articulos.cantidad, articulo.descripcion , users.nombreyapellido 
    FROM `orden_pedido`, orden_pedido_articulos, articulo, users 
    WHERE orden_pedido_articulos.orden_pedido_id = orden_pedido.id AND orden_pedido_articulos.articulo_id = articulo.id AND orden_pedido.usuario_id = users.id AND orden_pedido.estado = :estado ORDER BY fecha_pedido ASC
    
    "), array(                       
          'estado' => $estado          
        ));

        return response()->json($res, "200");
  }

  public function getOrdenPedidoDetalleById(Request $request)
  {
    $id = $request->input('id');
    $res = DB::select( DB::raw("SELECT orden_pedido.id, fecha_pedido, orden_pedido_articulos.id as orden_pedido_articulos_id, orden_pedido_articulos.cantidad, articulo.descripcion , users.nombreyapellido 
    FROM `orden_pedido`, orden_pedido_articulos, articulo, users 
    WHERE orden_pedido_articulos.orden_pedido_id = orden_pedido.id AND orden_pedido_articulos.articulo_id = articulo.id AND orden_pedido.usuario_id = users.id AND orden_pedido.id = :id
    
    "), array(                       
          'id' => $id          
        ));

        return response()->json($res, "200");
  }

/* -------------------------------------------------------------------------- */
/*     OBTENGO EL LISTADO DE PRODUCCION GENERADA PARA UNA ORDEN DE PEDIDO     */
/* -------------------------------------------------------------------------- */

  public function getProduccionByOrdenPedido(Request $request)
  {
    $id = $request->input('id');
    $res = DB::select( DB::raw("SELECT orden_pedido.id, fecha_pedido, orden_pedido_articulos.id as orden_pedido_articulos_id, orden_pedido_articulos.cantidad, articulo.descripcion , users.nombreyapellido, produccion.id as produccion_id, produccion.fecha_produccion, produccion.cantidad_botella, produccion.cantidad_litros, produccion_stock.fecha_egreso, produccion_stock.fecha_ingreso, produccion_stock.cantidad_original, produccion_stock.cantidad_salida, produccion_stock.existencia, produccion_stock.id as produccion_stock_id
    FROM `orden_pedido`, orden_pedido_articulos, articulo,produccion_stock,produccion, users 
    WHERE orden_pedido_articulos.orden_pedido_id = orden_pedido.id AND orden_pedido_articulos.articulo_id = articulo.id AND produccion.orden_pedido_articulos_id = orden_pedido_articulos.id  AND  produccion_stock.produccion_id = produccion.id AND produccion_stock.usuario_alta_id = users.id AND orden_pedido.id  = :id
    
    "), array(                       
          'id' => $id          
        ));

        return response()->json($res, "200");
  }

/* -------------------------------------------------------------------------- */
/*                   ACTUALIZAR EL ESTADO DE ORDEN DE PEDIDO                  */
/* -------------------------------------------------------------------------- */

public function updOrdenPedido(Request $request)
{
  
  $res =  DB::table('orden_pedido')
  ->where('id', $request->input('id'))
  ->update([
    'estado' => $request->input('estado'),   
    'updated_at' => date("Y-m-d H:i:s")]);

    return response()->json($res, "200");
}

}
