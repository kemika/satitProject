<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Student;
class ManageStudentsController extends Controller
{
  public function index(){
    $students  = Student::all();

    return view('manageStudents.index' , ['students' => $students]);
  }
}
