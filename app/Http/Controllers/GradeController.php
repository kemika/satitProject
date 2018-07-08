<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Grade;
use App\Student;
use App\Student_Status;
class GradeController extends Controller
{

  public function boom(){
    dd(Student_Status::where('student_status_text','Inactive')->join('Students','Students.student_status','=','student_statuses.student_status')->select('Students.*','student_statuses.*')->get()[0]->student_status_text);
    return ;
  }
    //
}
