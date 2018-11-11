<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Cerys extends Model
{
  use Notifiable;
  protected $table = 'cerys';
  protected $dateFormat = 'd-m-Y H:i:s';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'Numero', 'Nombre',
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
