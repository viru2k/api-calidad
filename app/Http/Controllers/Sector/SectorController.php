<?php

namespace App\Http\Controllers\Sector;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\Sector; 
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

class SectorController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Sector = Sector::all();
        return $this->showAll($Sector);
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
        $Sector = Sector::create($request->all());
        return $this->showOne($Sector);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Sector $Sector)
    {
        return $this->showOne($Sector);
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
        $pmo = Sector::findOrFail($id);
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


    public function getGrupoByIdGrupo(Request $request)
    {
        $grupo_trabajo_id = $request->input('grupo_trabajo_id');
    
        try {
        $res = DB::select( DB::raw("SELECT grupo.grupo_nombre, users.email, users.nombreyapellido , grupo.id , grupo_trabajo.id as grupo_trabajo_id, users.id usuario_id  
        FROM grupo , grupo_trabajo, users 
        WHERE grupo.id = grupo_trabajo.grupo_id AND grupo_trabajo.usuario_id = users.id AND grupo.id = :grupo_id ORDER BY  users.nombreyapellido  ASC
         "), array(                       
        'grupo_id' => $grupo_trabajo_id
        ));
        } catch (\Throwable $th) {
        return response()->json('ERROR INTERNO DEL SERVIDOR '.$th, "500");
        }
      return response()->json($res, "200");
}


public function getGrupo(Request $request)
{
    $grupo_trabajo_id = $request->input('grupo_trabajo_id');

    try {
    $res = DB::select( DB::raw("SELECT grupo.grupo_nombre, grupo.id   FROM grupo 
     "));
    } catch (\Throwable $th) {
    return response()->json('ERROR INTERNO DEL SERVIDOR '.$th, "500");
    }
  return response()->json($res, "200");
}


    
public function setGrupo(Request $request){

    $id =    DB::table('grupo')->insertGetId([
      'grupo_nombre' => $request->grupo_nombre 
  ]);   
    return response()->json($id, "200");  
}

public function setGrupoTrabajo(Request $request){

    $id =    DB::table('grupo_trabajo')->insertGetId([
      'usuario_id' => $request->usuario_id, 
      'grupo_id' => $request->grupo_id 
  ]);   
    return response()->json($id, "200");  
}


public function updGrupo(Request $request, $id){
    $res =  DB::table('grupo')
    ->where('id', $request->input('id'))  
    ->update([
      'grupo_nombre' => $request->input('grupo_nombre')
     ]);
  
      return response()->json($res, "200");
  }

  
public function updGrupoTrabajo(Request $request, $id){
    $res =  DB::table('grupo_trabajo')
    ->where('id', $request->input('id'))  
    ->update([
      'usuario_id' => $request->input('usuario_id'),
      'grupo_id' => $request->input('grupo_id')
      ]);
  
      return response()->json($res, "200");
  }


  public function delGrupoTrabajo(Request $request){
    $id = $request->input('id');
    $res =  DB::table('grupo_trabajo')->delete($id);
    return response()->json($res, "200");
  }

}
