<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
  public $table = "teachers";
  public $primaryKey  = 'teacher_id';
  protected $keyType = 'string';
}
