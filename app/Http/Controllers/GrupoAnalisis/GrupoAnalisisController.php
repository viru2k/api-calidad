<?php

namespace App\Http\Controllers\GrupoAnalisis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\GrupoAnalisis; 
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

class GrupoAnalisisController extends ApiController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $unidad = GrupoAnalisis::all();
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
            'grupo_nombre' => 'required',
            'color' => 'required'
        ];

        $this->validate($request, $rules);  
        $unidad = GrupoAnalisis::create($request->all());
        return $this->showOne($unidad);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(GrupoAnalisis $unidad)
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
        $pmo = GrupoAnalisis::findOrFail($id);
        $pmo->fill($request->only([
            'grupo_nombre',
            'color'

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
}
