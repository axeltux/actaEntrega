<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CredHistorico extends Model
{
     //Definimos a que tabla apunta el modelo
    protected $table = 'cred_historico';
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
