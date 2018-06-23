<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Subject;

class ReportCardController extends Controller
{
  public function index(){
    $subjects = Subject::all();


    return view('reportCard.index' , ['subjects' => $subjects]);

  }
}
