<?php

namespace App\Http\Controllers\Articulos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\Articulo; 
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

class ArticuloController extends ApiController
{
  
/* -------------------------------------------------------------------------- */
/*                            OBTENGO LOS ARTICULOS                           */
/* -------------------------------------------------------------------------- */

    public function index()
    {
      $res = DB::select( DB::raw("SELECT articulo.id ,articulo.nombre, articulo.descripcion , unidad.descripcion as unidad_descripcion, articulo.unidad_id ,articulo.usuario_modifica_id FROM articulo, unidad WHERE articulo.unidad_id = unidad.id
       "));
    
          return response()->json($res, "200");
    }

/* -------------------------------------------------------------------------- */
/*                             GUARDO UN ARTICULO                             */
/* -------------------------------------------------------------------------- */
    
    public function store(Request $request)
    {
       
      $id =    DB::table('articulo')->insertGetId([
        'nombre' => $request->descripnombrecion, 
        'descripcion' => $request->descripcion, 
        'unidad_id' => $request->unidad_id,        
        'usuario_modifica_id'=> $request->usuario_modifica_id,        
        'created_at' => date("Y-m-d H:i:s"),
        'updated_at' => date("Y-m-d H:i:s")
    ]);    
      return response()->json($id, "200");
    }


    
/* -------------------------------------------------------------------------- */
/*                            ACTUALIZO UN ARTICULO                           */
/* -------------------------------------------------------------------------- */

public function update(Request $request, $id)
{
   
    $res =  DB::table('articulo')
    ->where('id', $id)
    ->update([
      'nombre' => $request->input('nombre'),
      'descripcion' => $request->input('descripcion'),
      'unidad_id' =>  $request->input('unidad_id'),   
      'usuario_modifica_id' =>  $request->input('usuario_modifica_id'),           
      'updated_at' => date("Y-m-d H:i:s")]);

      return response()->json($res, "200");
}

/* -------------------------------------------------------------------------- */
/*                             OBTENGO UN ARTICULO CON SUS PROPIEDADES        */
/* -------------------------------------------------------------------------- */

public function  getArticulo(Request $request)
{
    $articulo_id = $request->input('articulo_id');
 
  $res = DB::select( DB::raw("SELECT articulo.nombre, articulo.descripcion, articulo_propiedades.unidades, articulo_propiedades.pallet_pisos, articulo_propiedades.pallet_pack, articulo_propiedades.volumen, unidad.descripcion as unidad_descripcion, users.nombreyapellido 
                  FROM articulo_propiedades, articulo, unidad, users 
                  WHERE articulo_propiedades.articulo_id = articulo.id AND articulo.unidad_id = unidad.id AND articulo.usuario_modifica_id = users.id AND articulo.id = :articulo_id
   "), array(                       
    'articulo_id' => $articulo_id
  ));

      return response()->json($res, "200");
}



/* -------------------------------------------------------------------------- */
/*                  OBTENGO LOS ARTICULOS CON SUS PROPIEDADES                 */
/* -------------------------------------------------------------------------- */

public function  getArticulos(Request $request)
{    
 
  $res = DB::select( DB::raw("SELECT articulo.nombre, articulo.descripcion, articulo_propiedades.unidades, articulo_propiedades.pallet_pisos, articulo_propiedades.pallet_pack, articulo_propiedades.volumen, unidad.descripcion as unidad_descripcion, users.nombreyapellido 
                  FROM articulo_propiedades, articulo, unidad, users 
                  WHERE articulo_propiedades.articulo_id = articulo.id AND articulo.unidad_id = unidad.id AND articulo.usuario_modifica_id = users.id
   "));

      return response()->json($res, "200");
}






/* -------------------------------------------------------------------------- */
/*                   INSERTO UNA PROPIEDADES PARA UN ARTICULO                  */
/* -------------------------------------------------------------------------- */

    public function setArticuloPropiedades(Request $request){
    
      $id =    DB::table('articulo_propiedades')->insertGetId([
        'articulo_id' => $request->articulo_id, 
        'unidades' => $request->unidades,        
        'pallet_pisos' => $request->pallet_pisos, 
        'pallet_pack' => $request->pallet_pack,         
        'volumen' => $request->volumen,         
        'usuario_modifica_id' => $request->usuario_modifica_id,         
        'created_at' => date("Y-m-d H:i:s"),
        'updated_at' => date("Y-m-d H:i:s")
    ]);    
      return response()->json($id, "200");  
    }


/* -------------------------------------------------------------------------- */
/*                   MODIFICO UNA PROPIEDAD PARA UN ARTICULO                  */
/* -------------------------------------------------------------------------- */


public function updateArticuloPropiedades(Request $request, $id)
{
   
    $res =  DB::table('articulo_propiedades')
    ->where('id', $id)
    ->update([
      'articulo_id' => $request->input('articulo_id'),
      'unidades' => $request->input('unidades'),
      'pallet_pisos' =>  $request->input('pallet_pisos'),   
      'pallet_pack' =>  $request->input('pallet_pack'),  
      'volumen' =>  $request->input('volumen'),            
      'usuario_modifica_id' =>  $request->input('usuario_modifica_id'),           
      'updated_at' => date("Y-m-d H:i:s")]);

      return response()->json($res, "200");
}


}

