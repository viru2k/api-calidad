<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Deposito extends Model
{
    

  
    protected $table = 'deposito';

   protected $fillable = [
       
        'id',
        'descripcion'
        
    
    ];

}
