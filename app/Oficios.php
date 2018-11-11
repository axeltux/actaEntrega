<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Oficios extends Model
{
    //use Notifiable;
  	protected $table = 'oficio';
  	protected $dateFormat = 'd-m-Y H:i:s';

	  /**
	   * The attributes that are mass assignable.
	   *
	   * @var array
	   */
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
