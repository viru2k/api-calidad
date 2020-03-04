<?php

namespace App\Http\Controllers\Calidad;
use Illuminate\Support\Facades\DB; 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CalidadController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
     

        $res = DB::select( DB::raw("   SELECT id, referencia_iso, referencia_descripcion, referencia_revision, created_at, updated_at, usuario_alta_id FROM calidad_tipo_control "));
   
        return response()->json($res, "200");
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postCalidad(Request $request)
    {
        $id =    DB::table('calidad_tipo_control')->insertGetId([
        
            'referencia_iso' => $request->referencia_iso, 
            'referencia_descripcion' => $request->referencia_descripcion,        
            'referencia_revision' => $request->referencia_revision,    
            'usuario_alta_id' => $request->usuario_alta_id,                 
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);    
          return response()->json($id, "200");
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
        $res =  DB::table('calidad_tipo_control')
        ->where('id', $id)
        ->update([
          'referencia_iso' => $request->input('referencia_iso'),
          'referencia_descripcion' =>  $request->input('referencia_descripcion'),   
          'referencia_revision' =>  $request->input('referencia_revision'),   
          'usuario_alta_id' =>  $request->input('usuario_alta_id'),    
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

    public function getCalidadByFecha(Request $request)
    {
    
      $tmp_fecha = str_replace('/', '-', $request->input('fecha_desde'));
      $fecha_desde =  date('Y-m-d H:i', strtotime($tmp_fecha));   
      $tmp_fecha = str_replace('/', '-', $request->input('fecha_hasta'));
      $fecha_hasta =  date('Y-m-d H:i', strtotime($tmp_fecha));  
     
    
     
      $res = DB::select( DB::raw("SELECT  calidad_tipo_control.referencia_iso, calidad_tipo_control.referencia_descripcion ,  calidad_tipo_control.referencia_revision, calidad_control.descripcion as  calidad_control_descripcion, calidad_producto.producto_nombre, calidad_producto.fecha_control, calidad_producto.hora, calidad_producto.tiene_no_conformidad, calidad_producto.observacion, calidad_dato_relevado.calidad_dato_nombre, calidad_dato_relevado_valor.calidad_dato_valor, users.id as usuario_id, users.nombreyapellido 
      FROM calidad_control, calidad_tipo_control, calidad_producto, calidad_dato_relevado, calidad_dato_relevado_valor, users 
      WHERE calidad_control.calidad_tipo_control_id = calidad_tipo_control.id AND calidad_producto.calidad_control_id = calidad_control.id AND calidad_dato_relevado_valor.calidad_producto_id = calidad_producto.id AND calidad_dato_relevado_valor.calidad_dato_relevado_id = calidad_dato_relevado.id AND calidad_producto.usuario_alta_id = users.id  AND calidad_producto.fecha_control BETWEEN   :fecha_desde  and :fecha_hasta
      
       "), array(                       
            'fecha_desde' => $fecha_desde,
            'fecha_hasta' => $fecha_hasta
          ));
    
          return response()->json($res, "200");
    }

/* -------------------------------------------------------------------------- */
/*             OBTENGO LOS PARAMETROS DE PRODUCCION SEGUN CONTROL             */
/* -------------------------------------------------------------------------- */

// TODO REALIZAR UNA CONSULTA DONDE TRAIGA LA PRODUCCION  POR DIA, POR TURNO
    // SELECT  calidad_tipo_control.referencia_iso, calidad_tipo_control.referencia_descripcion ,  calidad_tipo_control.referencia_revision, calidad_control.descripcion as  calidad_control_descripcion, calidad_producto.producto_nombre, calidad_producto.fecha_control, calidad_producto.hora, calidad_producto.tiene_no_conformidad, calidad_producto.observacion, calidad_dato_relevado.calidad_dato_nombre, calidad_dato_relevado_valor.calidad_dato_valor, users.id as usuario_id, users.nombreyapellido FROM calidad_control, calidad_tipo_control, calidad_producto, calidad_dato_relevado, calidad_dato_relevado_valor, users WHERE calidad_control.calidad_tipo_control_id = calidad_tipo_control.id AND calidad_producto.calidad_control_id = calidad_control.id AND calidad_dato_relevado_valor.calidad_producto_id = calidad_producto.id AND calidad_dato_relevado_valor.calidad_dato_relevado_id = calidad_dato_relevado.id AND calidad_producto.usuario_alta_id = users.id 
}
