<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lotes extends Model
{
	//Definimos a que tabla apunta el modelo
    protected $table = 'cred_lote';
    protected $fillable = [
	      //
	  ];

  	/**
   	* The attributes that should be hidden for arrays.
   	*
   	* @var array
   	*/
	protected $hidden = [
	     //
	];
}
