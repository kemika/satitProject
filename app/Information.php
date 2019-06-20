<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
  public $table  = 'information';
  public $primaryKey  = 'director_full_name';
  public $active_year = 'active_year';
  protected $keyType = 'string';
}
