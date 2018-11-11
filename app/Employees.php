<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{
  	//Definimos a que base de datos se conecta
  	protected $connection = 'cayas';
    //Definimos a que tabla apunta el modelo
    protected $table = 'employees';
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
