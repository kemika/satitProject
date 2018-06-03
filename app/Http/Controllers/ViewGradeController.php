<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Subject;

class ViewGradeController extends Controller
{
  public function index(){
    $subjects = Subject::all();
  

    return view('grade.index' , ['subjects' => $subjects]);

  }
}
