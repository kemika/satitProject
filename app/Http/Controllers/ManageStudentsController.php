<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Student;
use App\Student_Status;
class ManageStudentsController extends Controller
{

  public function __construct() {
    $this->middleware('auth');
  }

  public function index(){
    $students  = Student::join('student_status','students.student_status','=','student_status.student_status')
    ->select('students.student_id','students.firstname','students.lastname','student_status.student_status_text')
    ->orderBy('students.student_id','asc')
    ->get();

    return view('manageStudents.index' , ['students' => $students]);
  }


  public function update(Request $request)
  {

      //dd($request->input('studentID'));
      $student  = Student::all()->where('student_id', $request->input('studentID'))->first();
      //dd($student->student_id);
      $student->firstname=$request->input('firstname');
      $student->lastname=$request->input('lastname');
      $student->student_status=$request->input('status');

      $student->save();

      $students  = Student::join('student_status','students.student_status','=','student_status.student_status')
      ->select('students.student_id','students.firstname','students.lastname','student_status.student_status_text')
      ->orderBy('students.student_id','asc')
      ->get();
      return view('manageStudents.index' , ['students' => $students]);
  }


  // public function grade(){
  //   return view('grade.index');
  // }
}
