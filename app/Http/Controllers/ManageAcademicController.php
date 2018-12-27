<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Academic_Year;

class ManageAcademicController extends Controller
{
  public function index(){
    $academic  = Academic_Year::orderBy('academic_year', 'desc')->groupBy('curriculum_year')->first();
    return view('manageAcademic.index' , ['cur_year' => $academic->academic_year]);
  }

  public function editAcademicYear(){
    return view('manageAcademic.academicTable');
  }
}
