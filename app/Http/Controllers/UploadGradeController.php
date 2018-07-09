<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Excel;
use App\Subject;
use App\Teaching;
use App\GPA;
use Auth;
use App\Teacher;
use App\Student;
use App\Room;
use App\WaitApprove;
use Illuminate\Support\Facades\Input;

class UploadGradeController extends Controller
{
  public function index(){

    return view('uploadGrade.index');
  }



    public function upload()
    {
        return view('uploadGrade.upload');
    }


    // public function import(Request $request)
    // {
    //   if($request->hasfFile('file')){
    //     $path = $request->file('file')->getRealpath();
    //     $data = Excel::load($path, function($reader){})->get();
    //       if (!empty($data) && $data->count()) {
    //         foreach($variable as $key => $value){
    //           $waitApprove = new WaitApprove();
    //           $waitApprove->name = $value->name;
    //           $waitApprove->email = $value->email;
    //           $waitApprove->save();
    //         }
    //       }
    //   }
    //   return back();
    //
    // }

    public function getUpload(Request $request)
    {


      if ($request->hasFile('file')) {
        $arrayValidate = array();
        $fact = true;
        $factGrade = true;
        $factValidate = true;
        $factEmpty = true;
        $file = Input::file('file');
        $file_name = $file->getClientOriginalName();
        $file_type = \File::extension('files/'.$file_name);
        $file->move('files/', $file_name);

        function validateGrade($data, $fied, $column,$factGrade, $row)
        {
          if ((preg_match("/^[0-3][.][0-9][0-9]$/",$data)) ||
            (preg_match("/^[0-4]$/",$data)) || $data == "4.00") {
            $factGrade = false;
            $factEmpty = true;
          }
          if($factGrade){
            echo "Field '$fied' is incorrect format at row '$column".($row+6)."'<br>";
            $text = "Field '$fied' is incorrect format at row '$column".($row+6)."'<br>";
            $factGrade = true;
            $factValidate = false;
            $factEmpty = true;
            $arrayValidate[] = $text;
          }
        }


        $importRow = count(\Excel::load('files/'.$file_name, function($reader) {})->get());
        if ($importRow < 4) {
          $fact = false;
          echo "This file is not correct format. Please select another file!";
        }
        else {
          $results = Excel::load('files/'.$file_name,function($reader){
            $reader->setHeaderRow(5);
            $reader->all();

          })->get();

          $resultsCourse = Excel::load('files/'.$file_name,function($reader){
            $reader->setHeaderRow(2);

          })->get();
          // $resultsCo = Excel::selectSheetsByIndex(0)->load('files/'.$file_name)->get();

          // dd($resultsCourse->getHeading());


          // dd($results);
          // dd($results[0]->name);


          echo $file_type;
          echo "<br>";
          echo $importRow;
          echo "<br>";


          if($file_type == "xlsx" || $file_type == "xls"){
            if(count($results)==0){
              $fact = false;
              echo "This file is empty";
            }
            else {
              if($fact){
                for ($i = 0; $i < count($results); $i++) {
                  echo "Student Name: ".$results[$i]->student_name;
                  echo "<br>";
                  echo "Q1: ".$results[$i]->q1;
                  echo "<br>";
                  echo "Q2: ".$results[$i]->q2;
                  echo "<br>";
                  echo "sum_1: ".$results[$i]->sum_1;
                  echo "<br>";
                  echo "sem_1: ".$results[$i]->sem_1;
                  echo "<br>";
                  echo "Q3: ".$results[$i]->q3;
                  echo "<br>";
                  echo "Q4: ".$results[$i]->q4;
                  echo "<br>";
                  echo "sum_2: ".$results[$i]->sum_2;
                  echo "<br>";
                  echo "sem_2: ".$results[$i]->sem_2;
                  echo "<br>";
                  echo "average: ".$results[$i]->average;
                  echo "<br>";
                  echo "grade: ".$results[$i]->grade;
                  echo "<br>";
                  echo "=======================================================";
                  echo "<br>";


                    //----- Validate Student Name -------//
                    if($results[$i]->student_name == ""){
                      echo "Field 'Student name' is empty at row 'B".($i+6)."'<br>";
                      $factValidate = false;
                      $factEmpty = false;
                    }
                    if (!preg_match("/^[a-zA-Z ]*$/",$results[$i]->student_name)) {
                      echo "Field 'Student name' is incorrect format at row 'B".($i+6)."'<br>";
                      $factValidate = false;
                    }

                    //----- Validate Q1 -------//
                    if($results[$i]->q1 == ""){
                      echo "Field 'Q1' is empty at row 'C".($i+6)."'<br>";
                      $factEmpty = false;
                      $factValidate = false;
                    }
                    if($factEmpty){
                      validateGrade($results[$i]->q1, "Q1", "C", $factGrade, $i);
                    }

                    //----- Validate Q2 -------//
                    if($results[$i]->q2 == ""){
                      echo "Field 'Q2' is empty at row 'D".($i+6)."'<br>";
                      $factEmpty = false;
                      $factValidate = false;
                    }
                    if($factEmpty){
                      validateGrade($results[$i]->q2, "Q2", "D", $factGrade, $i);
                    }


                    //----- Validate Sum 1 -------//
                    if($results[$i]->sum_1 == ""){
                      echo "Field 'Sum 1' is empty at row 'E".($i+6)."'<br>";
                      $factEmpty = false;
                      $factValidate = false;
                    }
                    if($factEmpty){
                      validateGrade($results[$i]->sum_1, "Sum 1", "E", $factGrade, $i);
                    }


                    //----- Validate Sem 1 -------//
                    if($results[$i]->sem_1 == ""){
                      echo "Field 'Sem 1' name is empty at row 'F".($i+6)."'<br>";
                      $factEmpty = false;
                      $factValidate = false;
                    }
                    if($factEmpty){
                      validateGrade($results[$i]->sem_1, "Sem 1", "F", $factGrade, $i);
                    }


                    //----- Validate Q3 -------//
                    if($results[$i]->q3 == ""){
                      echo "Field 'Q3' is empty at row 'G".($i+6)."'<br>";
                      $factEmpty = false;
                      $factValidate = false;
                    }
                    if($factEmpty){
                      validateGrade($results[$i]->q3, "Q3", "G", $factGrade, $i);
                    }


                    //----- Validate Q4 -------//
                    if($results[$i]->q4 == ""){
                      echo "Field 'Q4' is empty at row 'H".($i+6)."'<br>";
                      $factEmpty = false;
                      $factValidate = false;
                    }
                    if($factEmpty){
                      validateGrade($results[$i]->q4, "Q4", "H", $factGrade, $i);
                    }


                    //----- Validate Sum  -------//
                    if($results[$i]->sum_2 == ""){
                      echo "Field 'Sum 2' is empty at row 'I".($i+6)."'<br>";
                      $factEmpty = false;
                      $factValidate = false;
                    }
                    if($factEmpty){
                      validateGrade($results[$i]->sum_2, "Sum 2", "I", $factGrade, $i);
                    }


                    //----- Validate Sem 2 -------//
                    if($results[$i]->sem_2 == ""){
                      echo "Field 'Sem 2' is empty at row 'J".($i+6)."'<br>";
                      $factEmpty = false;
                      $factValidate = false;
                    }
                    if($factEmpty){
                      validateGrade($results[$i]->sem_2, "Sem 2", "J", $factGrade, $i);
                    }


                    //----- Validate Grade Average -------//
                    if($results[$i]->average == ""){
                      echo "Field 'Grade Average' is empty at row 'K".($i+6)."'<br>";
                      $factEmpty = false;
                      $factValidate = false;
                    }
                    if($factEmpty){
                      validateGrade($results[$i]->average, "Grade Average", "K", $factGrade, $i);
                    }


                    //----- Validate Year Grade -------//
                    if($results[$i]->grade == ""){
                      echo "Field 'Year Grade' is empty at row 'L".($i+6)."'<br>";
                      $factEmpty = false;
                      $factValidate = false;
                    }
                    if($factEmpty){
                      validateGrade($results[$i]->grade, "Year Grade", "L", $factGrade, $i);
                    }

                  }
                  if ($factValidate==TRUE) {
                    return view('uploadGrade.getUpload', compact('results'));
                  }
                  elseif ($factValidate==FALSE) {
                    var_dump($arrayValidate);
                  }


              }
            }

          }
          else{
            $fact = false;
            echo "Your file's type is not xlsx or xls. Please select another file!";
          }
        }

	     }

       elseif ($request->hasFile('file')==FALSE) {
         dd("Please Select File");
       }


    }


}
