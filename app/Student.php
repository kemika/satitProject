<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
  public $table  = 'students';
    //
  public $primaryKey  = 'student_id';
  protected $keyType = 'string';
}
