<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Grade;
class GradeController extends Controller
{

  public function boom(){
    return Grade::all();
  }
    //
}
