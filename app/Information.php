<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
  public $table  = 'information';
  public $primaryKey  = 'director_full_name';
  protected $keyType = 'string';
}
