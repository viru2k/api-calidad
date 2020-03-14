<?php

namespace App\Http\Controllers\Articulos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\Articulo; 
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

class ArticuloController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $res = DB::select( DB::raw("SELECT articulo.id ,articulo.descripcion , unidad.descripcion as unidad_descripcion, articulo.unidad_id, botellas, pisos, pack, litros FROM articulo, unidad WHERE articulo.unidad_id = unidad.id
       "));
    
          return response()->json($res, "200");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
      $id =    DB::table('articulo')->insertGetId([
        
        'descripcion' => $request->descripcion, 
        'unidad_id' => $request->unidad_id,        
        'botellas'=> $request->botellas,
        'pisos'=> $request->pisos,
        'pack'=> $request->pack,
        'litros'=> $request->litros,
        'created_at' => date("Y-m-d H:i:s"),
        'updated_at' => date("Y-m-d H:i:s")
    ]);    
      return response()->json($id, "200");
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

    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       
        $res =  DB::table('articulo')
        ->where('id', $id)
        ->update([
          'descripcion' => $request->input('descripcion'),
          'unidad_id' =>  $request->input('unidad_id'),   
          'botellas' =>  $request->input('botellas'),   
          'pisos' =>  $request->input('pisos'),   
          'pack' =>  $request->input('pack'),   
          'litros' =>  $request->input('litros'),   
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


    public function getArticuloConfeccionByArticuloId(Request $request)
    {
        $articulo_id = $request->input('articulo_id');
     
      $res = DB::select( DB::raw("SELECT articulo_confeccion.id, articulo_confeccion.cantidad , articulo_confeccion.VOLUMEN, articulo_confeccion.unidad, articulo.id as articulo_id, articulo.descripcion as articulo_descripcion , botellas, pisos, pack, litros,  insumo.descripcion as insumo_descripcion, insumo.id as insumo_id, unidad.descripcion as unidad_descripcion ,unidad.id as unidad_id  
      FROM articulo_confeccion, articulo, insumo, unidad 
      WHERE  articulo_confeccion.articulo_id = articulo.id AND articulo_confeccion.insumo_id = insumo.id AND insumo.unidad_id = unidad.id AND  articulo_confeccion.articulo_id = :articulo_id
       "), array(                       
        'articulo_id' => $articulo_id
      ));
    
          return response()->json($res, "200");
    }

    public function setArticuloConfeccion(Request $request){



      $id =    DB::table('articulo_confeccion')->insertGetId([
        
        'articulo_id' => $request->articulo_id, 
        'insumo_id' => $request->insumo_id,        
        'cantidad' => $request->cantidad, 
        'VOLUMEN' => $request->VOLUMEN, 
        'unidad' => $request->unidad,         
        'created_at' => date("Y-m-d H:i:s"),
        'updated_at' => date("Y-m-d H:i:s")
    ]);    
      return response()->json($id, "200");  
    }

    public function updateArticuloConfeccion(Request $request)
    {
 

      $res =  DB::table('articulo_confeccion')
      ->where('id', $id)
      ->update([
        'articulo_id' => $request->input('articulo_id'),
        'insumo_id' => $request->input('insumo_id'),
        'cantidad' => $request->input('cantidad'),
        'VOLUMEN' => $request->input('VOLUMEN'),
        'unidad' => $request->input('unidad'),        
        'updated_at' => date("Y-m-d H:i:s")]);

        return response()->json($res, "200");
    }

    public function delArticuloConfeccion(Request $request){
      $id = $request->input('id');
     $res =  DB::table('articulo_confeccion')->delete($id);
      
     // echo '¡ass';
      return response()->json($res, "200");
    }
}

