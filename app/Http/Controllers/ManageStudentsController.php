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

  public function upload(){
      return view('manageStudents.upload',[]);
  }

  // This is for position of information in each line of upload data
  const ID_POS = 0; // ID position
  const NAME_POS = 1; // Name position
  const LAST_NAME_POS = 2; // Last name position

  public function uploadresults(Request $request){
      $data = $request->input('data');

      $error_flag = true; // Assume error the beginning
      $message = "";
      $count = 1;

      $results = [];

      // For each input data line
      for ($line_tok = strtok($data, "\n\r");
           $line_tok !== false;
           $line_tok = strtok("\n\r")) {
          $result = [];
          $result['error'] = false;

          // Split line to parts
          $info = explode(",",$line_tok);
          if(count($info) == 3){

              // Trim data and remove all white space from id
              $id = preg_replace("/[\"\t\x0B ]/", "", $info[self::ID_POS]);
              $name = trim($info[self::NAME_POS],"\"\0\t\x0B ");
              $lastName = trim($info[self::LAST_NAME_POS],"\"\0\t\x0B ");

              // Check if the first data is similar to some form of ID by checking if there is a number
              if(strpbrk($id,'0123456789') !== false){

                  $students  = new Student();
                  $students->student_id=$id;
                  $students->firstname=$name;
                  $students->lastname=$lastName;
                  $students->student_status="0";

                  try {
                      // create or update some data
                      $students->saveOrFail();

                      $error_flag = false;
                      $message = $id . " is added successfully.";
                  }catch(\Illuminate\Database\QueryException $e){
                      $message = "Line ".$count.":".$line_tok.": Cannot add new student information. ID ".$students->student_id." may already exists or there is a problem with the database.";
                  }

              }else{
                  // ID does not contain number this should not be an ID
                  $message = "Line ".$count.":".$line_tok.": ID does not contain number.";
              }
          }else{
              // Not correct data number
              $message = "Line ".$count.":".$line_tok.": Data has too few or too many entries.";
          }

          $result['error'] = $error_flag;
          $result['message'] = $message;
          array_push($results,$result);
          $count++;
      }

      return view('manageStudents.uploadresults',['results' => $results]);
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
