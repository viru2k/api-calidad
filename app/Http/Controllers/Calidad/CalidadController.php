<?php

namespace App\Http\Controllers\Calidad;
use Illuminate\Support\Facades\DB; 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CalidadController extends ApiController
{
 

/* -------------------------------------------------------------------------- */
/*                      ENCABEZADO DE CONTROL DE CALIDAD                      */
/* -------------------------------------------------------------------------- */

  public function getCalidadControlEncabezado(Request $request)
  {      
    $res = DB::select( DB::raw("SELECT `id`, `calidad_titulo`, `calidad_descripcion`, `ficha_nro` FROM `calidad_control`
    "));
        return response()->json($res, "200");
  }


  public function setCalidadControlEncabezado(Request $request)
  {
    $id =    DB::table('calidad_control')->insertGetId([
      
      'calidad_titulo' => $request->calidad_titulo, 
      'calidad_descripcion' => $request->calidad_descripcion,    
      'ficha_nro' => $request->ficha_nro
  ]);    
    return response()->json($id, "200");  
  }

  
  public function putCalidadControlEncabezado(Request $request, $id)
  {
    $res =  DB::table('calidad_control')
    ->where('id', $id)
    ->update([
      'calidad_titulo' => $request->input('calidad_titulo'),
      'calidad_descripcion' => $request->input('calidad_descripcion'),
      'ficha_nro' => $request->input('ficha_nro')
      ]);
      
      return response()->json($res, "200");
  }
  

/* -------------------------------------------------------------------------- */
/*                      PARAMETROS DEL CONTROL DE CALIDAD                     */
/* -------------------------------------------------------------------------- */

  public function getCalidadControlParametros(Request $request)
  {      
    $res = DB::select( DB::raw("SELECT `id`, `parametro`, estado FROM `calidad_parametro`
    "));
        return response()->json($res, "200");
  }

  public function setCalidadControlParametros(Request $request)
  {
    $id =    DB::table('calidad_parametro')->insertGetId([
      'parametro' => $request->parametro,
      'estado' => $request->estado
  ]);    
    return response()->json($id, "200");  
  }


  public function putCalidadControlParametros(Request $request, $id)
  {
    $res =  DB::table('calidad_parametro')
    ->where('id', $id)
    ->update([
      'parametro' => $request->input('parametro'),
      'estado' => $request->input('estado')
      ]);
  
      return response()->json($res, "200");
  }

/* -------------------------------------------------------------------------- */
/*                   MEZCLA CONTROL DE CALIDAD Y PARAMETROS                   */
/* -------------------------------------------------------------------------- */

  public function getCalidadControlParametroControl(Request $request)
  {      
    $control_calidad_id =  $request->input('control_calidad_id');   

    $res = DB::select( DB::raw("SELECT calidad_control_parametro.id, calidad_control_parametro.parametro_id, calidad_control_parametro.control_calidad_id, calidad_control.calidad_titulo, calidad_control.calidad_descripcion, calidad_control.ficha_nro, calidad_parametro.parametro, calidad_parametro.estado 
    FROM calidad_control_parametro, calidad_parametro, calidad_control 
    WHERE calidad_control_parametro.parametro_id = calidad_parametro.id AND calidad_control_parametro.control_calidad_id = calidad_control.id AND calidad_control_parametro.control_calidad_id = :control_calidad_id
    "),
     array(                       
      'control_calidad_id' => $control_calidad_id
    )
    );
        return response()->json($res, "200");
  }


  public function setCalidadControlParametroControl(Request $request)
  {
    $id =    DB::table('calidad_control_parametro')->insertGetId([
      'parametro_id' => $request->parametro_id,
      'control_calidad_id' => $request->control_calidad_id
  ]);    
    return response()->json($id, "200");  
  }


  public function putCalidadControlParametroControl(Request $request, $id)
  {
    $res =  DB::table('calidad_control_parametro')
    ->where('id', $id)
    ->update([
      'parametro_id' => $request->input('parametro_id'),
      'control_calidad_id' => $request->input('control_calidad_id')
      ]);
  
      return response()->json($res, "200");
  }

/* -------------------------------------------------------------------------- */
/*             ALMACENO EL CONTRO REALIZADO UN PROCESO PRODUCTIVO             */
/* -------------------------------------------------------------------------- */

  public function setCalidadControlParametroControlValor(Request $request)
  {
    $i = 0;
    $r = $request;
try { 

$cont = count($request->all());
//var_dump($request);
  while($i< $cont) {
   // var_dump($res[$i]['parametro_id']);
  // var_dump($req[$i]);
 
   // echo $req[$i]['id'];
     $id =    DB::table('calidad_control_parametro_valor')->insertGetId([
      'calidad_control_parametro_id' => $r[$i]['parametro_id'],
      'calidad_valor' => $r[$i]['calidad_valor'],
      'usuario_modifica_id' => $r[$i]['usuario_modifica_id'],
      'es_no_conformidad' => $r[$i]['no_conformidad'],
      'tiene_accion_correctiva' => $r[$i]['es_accion_correctiva'],
      'es_no_conformidad_descripcion' => $r[$i]['es_no_conformidad_descripcion'],
      'tiene_accion_correctiva_descripcion' => $r[$i]['tiene_accion_correctiva_descripcion'],
      'fecha_carga' => $r[$i]['fecha'],
      'produccion_proceso_id' => $r[$i]['produccion_proceso_id']
  ]);    
      $i++;
  }
} catch (\Throwable $th) {
  return response()->json($th, "500");  
}

  
   return response()->json($id, "200");  
  }


/* -------------------------------------------------------------------------- */
/*                    DETALLE DE CONTROL POR ID DE PROCESO                    */
/* -------------------------------------------------------------------------- */

    public function getControlByProcesoId(Request $request)
    {
        $produccion_proceso_id =  $request->input('produccion_proceso_id');   

      $res = DB::select( DB::raw("SELECT calidad_control.id, calidad_control_parametro_id, calidad_valor, calidad_control_parametro_valor.usuario_modifica_id, es_no_conformidad, tiene_accion_correctiva, 
      fecha_carga, produccion_proceso_id, calidad_control.calidad_titulo, calidad_control.calidad_descripcion,  
      calidad_control.ficha_nro, calidad_parametro.parametro, users.nombreyapellido, produccion_proceso.lote, orden_produccion_detalle.fecha_produccion, 
      articulo.nombre as articulo_nombre 
      FROM  calidad_control, calidad_control_parametro, calidad_parametro , calidad_control_parametro_valor, produccion_proceso, orden_produccion_detalle, articulo, users 
      WHERE calidad_control.id = calidad_control_parametro.control_calidad_id AND calidad_parametro.id = calidad_control_parametro.parametro_id 
      AND calidad_control_parametro_valor.calidad_control_parametro_id = calidad_control_parametro.id AND calidad_control_parametro_valor.usuario_modifica_id = users.id 
      AND calidad_control_parametro_valor.produccion_proceso_id = produccion_proceso.id AND calidad_parametro.estado = 'ACTIVO'
      AND produccion_proceso.articulo_id = articulo.id AND orden_produccion_detalle.id = produccion_proceso.orden_produccion_detalle_id AND produccion_proceso.id = :produccion_proceso_id
      "), array(                       
            'produccion_proceso_id' => $produccion_proceso_id
          ));

          return response()->json($res, "200");
    }

    
    public function getControlByProcesoByDates(Request $request)
    {
      $tmp_fecha = str_replace('/', '-', $request->input('fecha_desde'));
      $fecha_desde =  date('Y-m-d H:i', strtotime($tmp_fecha));   
      $tmp_fecha = str_replace('/', '-', $request->input('fecha_hasta'));
      $fecha_hasta =  date('Y-m-d H:i', strtotime($tmp_fecha));   

      $res = DB::select( DB::raw("SELECT calidad_control.id, calidad_control_parametro_id, calidad_valor, calidad_control_parametro_valor.usuario_modifica_id, es_no_conformidad, tiene_accion_correctiva, 
      fecha_carga, produccion_proceso_id, calidad_control.calidad_titulo, calidad_control.calidad_descripcion,  
      calidad_control.ficha_nro, calidad_parametro.parametro, users.nombreyapellido, produccion_proceso.lote, orden_produccion_detalle.fecha_produccion, 
      articulo.nombre as articulo_nombre 
      FROM  calidad_control, calidad_control_parametro, calidad_parametro , calidad_control_parametro_valor, produccion_proceso, orden_produccion_detalle, articulo, users 
      WHERE calidad_control.id = calidad_control_parametro.control_calidad_id AND calidad_parametro.id = calidad_control_parametro.parametro_id 
      AND calidad_control_parametro_valor.calidad_control_parametro_id = calidad_control_parametro.id AND calidad_control_parametro_valor.usuario_modifica_id = users.id 
      AND calidad_control_parametro_valor.produccion_proceso_id = produccion_proceso.id AND calidad_parametro.estado = 'ACTIVO'
      AND produccion_proceso.articulo_id = articulo.id AND orden_produccion_detalle.id = produccion_proceso.orden_produccion_detalle_id AND orden_produccion_detalle.fecha_produccion 
      BETWEEN   :fecha_desde AND :fecha_hasta
      "), array(                       
            'fecha_desde' => $fecha_desde,
            'fecha_desde' => $fecha_hasta,
          ));

          return response()->json($res, "200");
    }

    public function delControlParametro(Request $request)
    {
      $id = $request->input('id');
    DB::table('calidad_control_parametro')->where('id', '=', $id)->delete();
    return response()->json($id, "200");
    }
}
