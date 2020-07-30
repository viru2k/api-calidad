<?php

namespace App\Http\Controllers\Deposito;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\Deposito; 
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

class DepositoController extends ApiController
{
  /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $unidad = Deposito::all();
        return $this->showAll($unidad);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'descripcion' => 'required'
        ];

        $this->validate($request, $rules);  
        $unidad = Deposito::create($request->all());
        return $this->showOne($unidad);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Deposito $unidad)
    {
        return $this->showOne($unidad);
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
        $pmo = Deposito::findOrFail($id);
        $pmo->fill($request->only([
            'descripcion'

    ]));

   if ($pmo->isClean()) {
        return $this->errorRepsonse('Se debe especificar al menos un valor', 422);
    }
   $pmo->save();
    return $this->showOne($pmo);
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
/*                       GUARDO EL MOVIMIENTO DEL INSUMO                      */
/* -------------------------------------------------------------------------- */


public function setMovimientoDeposito(Request $request){


    $tmp_fecha = str_replace('/', '-', $request->fecha_movimiento);
    $fecha_movimiento =  date('Y-m-d H:i', strtotime($tmp_fecha));  
    
  
    $id =    DB::table('deposito_movimiento')->insertGetId([    
      'deposito_id' => $deposito_id,        
      'stock_movimiento_id' => $request->stock_movimiento_id , 
      'usuario_modifica_id' => $request->usuario_modifica_id,         
      'fecha_movimiento' => $fecha_movimiento
  ]);    
    
  // guardo el request en una variable para iterar
  $t = $request->OrdenProduccionDetalle;
  
    return response()->json($id, "200");  
  }


/* -------------------------------------------------------------------------- */
/*              OBTENGO LOS MOVIMIENTOS PARA UNA LOTE DE INSUMOS              */
/* -------------------------------------------------------------------------- */

  public function getInsumoMovimientoDepositoByStockMovimiento(Request $request)
  {
    $stock_movimiento_id = $request->input('stock_movimiento_id');
    $res = DB::select( DB::raw("SELECT deposito_movimiento.id, deposito_movimiento.deposito_id, deposito_movimiento.stock_movimiento_id, deposito_movimiento.usuario_modifica_id, deposito_movimiento.fecha_movimiento, deposito.descripcion, stock_movimiento.fecha_ingreso, stock_movimiento.comprobante, stock_movimiento.lote, insumo.nombre, insumo.id AS insumo_id, users.nombreyapellido  
    FROM `deposito_movimiento`, deposito, stock_movimiento, insumo, users 
    WHERE deposito_movimiento.deposito_id = deposito.id AND deposito_movimiento.stock_movimiento_id = stock_movimiento.id 
    AND stock_movimiento.insumo_id = insumo.id AND deposito_movimiento.usuario_modifica_id = users.id AND stock_movimiento.cantidad_existente > 0 AND stock_movimiento_id = :stock_movimiento_id
    
    "), array(                       
          'stock_movimiento_id' => $stock_movimiento_id          
        ));
  
        return response()->json($res, "200");
  }

/* -------------------------------------------------------------------------- */
/*                    OBTENGO LOS MOVIMIENTOS DE UN INSUMO                    */
/* -------------------------------------------------------------------------- */

  public function getInsumoMovimientoDepositoByInsumoId(Request $request)
  {
    $insumo_id = $request->input('insumo_id');
    $res = DB::select( DB::raw("SELECT deposito_movimiento.id, deposito_movimiento.deposito_id, deposito_movimiento.stock_movimiento_id, deposito_movimiento.usuario_modifica_id, deposito_movimiento.fecha_movimiento, deposito.descripcion, stock_movimiento.fecha_ingreso, stock_movimiento.comprobante, stock_movimiento.lote, insumo.nombre, insumo.id AS insumo_id, users.nombreyapellido  
    FROM `deposito_movimiento`, deposito, stock_movimiento, insumo, users 
    WHERE deposito_movimiento.deposito_id = deposito.id AND deposito_movimiento.stock_movimiento_id = stock_movimiento.id AND stock_movimiento.insumo_id = insumo.id AND deposito_movimiento.usuario_modifica_id = users.id AND stock_movimiento.cantidad_existente > 0 AND insumo_id = :insumo_id
    
    "), array(                       
          'insumo_id' => $insumo_id          
        ));
  
        return response()->json($res, "200");
  }


}
