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
}
