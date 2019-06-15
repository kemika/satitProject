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

    return view('manageStudents.index' , ['students' => $students,'query_fail' => ""]);
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
      return view('manageStudents.index' , ['students' => $students,'query_fail' => ""]);
  }

    public function add(Request $request)
    {
        $query_fail = "";

        $students  = new Student();
        $students->student_id=$request->input('studentID');
        $students->firstname=$request->input('firstname');
        $students->lastname=$request->input('lastname');
        $students->student_status=$request->input('status');

        try {
            // create or update some data
            $students->saveOrFail();

        }catch(\Illuminate\Database\QueryException $e){
            $query_fail = "Cannot add new student information. ID ".$students->student_id." may already exists or there is a problem with the database.";
        }


        $students  = Student::join('student_status','students.student_status','=','student_status.student_status')
            ->select('students.student_id','students.firstname','students.lastname','student_status.student_status_text')
            ->orderBy('students.student_id','asc')
            ->get();
        return view('manageStudents.index' , ['students' => $students,'query_fail' => $query_fail]);
    }

  public function graduate(Request $request)
  {
      //dd($request->input('studentID'));
      try{
        $student  = Student::all()->where('student_id', $request->input('studentID'))->first();
        //dd($student->student_id);
        $student->student_status=2;
        $student->save();
      }
      catch(\Exception $e){
         // do task when error
         return response()->json(['Status' => $e->getMessage()], 200);

      }
      return response()->json(['Status' => 'success'], 200);
/*
      $students  = Student::join('student_status','students.student_status','=','student_status.student_status')
      ->select('students.student_id','students.firstname','students.lastname','student_status.student_status_text')
      ->orderBy('students.student_id','asc')
      ->get();
      return view('manageStudents.index' , ['students' => $students]);*/
  }

  public function active(Request $request)
  {
      //dd($request->input('studentID'));
      try{
        $student  = Student::all()->where('student_id', $request->input('studentID'))->first();
        //dd($student->student_id);
        $student->student_status=0;
        $student->save();
      }
      catch(\Exception $e){
         // do task when error
         return response()->json(['Status' => $e->getMessage()], 200);

      }
      return response()->json(['Status' => 'success'], 200);
/*
      $students  = Student::join('student_status','students.student_status','=','student_status.student_status')
      ->select('students.student_id','students.firstname','students.lastname','student_status.student_status_text')
      ->orderBy('students.student_id','asc')
      ->get();
      return view('manageStudents.index' , ['students' => $students]);*/
  }


  // public function grade(){
  //   return view('grade.index');
  // }
}
