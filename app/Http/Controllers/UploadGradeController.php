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
use App\Grade;

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
      $students = Student::all();
      $studentsID = Student::select('student_id')->get();
      $arr = array();

      date_default_timezone_set('Asia/Bangkok');
      $datetime = date("Y-m-d H:i:s");
      // dd($datetime);
      // dd($studentsID);
      // var_dump($studentsID);
      // print_r($studentsID);
      foreach ($studentsID as $studentID) {
        $arr[] = $studentID->student_id;
      }
      // print_r($arr);
      //
      // if (in_array("1111111111", $arr)) {
      //     echo "Got My";
      // }





      $arrayValidates = array();

      if ($request->hasFile('file')) {

        $fact = true;
        $factGrade = true;
        $factValidate = true;
        $factEmpty = true;
        $file = Input::file('file');
        $file_name = $file->getClientOriginalName();
        $file_type = \File::extension('files/'.$file_name);
        $file->move('files/', $file_name);
        $checkFileName = substr("$file_name", 0, 8);
        // dd($checkFileName);

        function validateGrade($data, $fied, $column,$factGrade, $row)
        {
          if ((preg_match("/^[0-3][.][0-9][0-9]$/",$data)) ||
            (preg_match("/^[0-4]$/",$data)) || (preg_match("/^[0-3][.][0-9]$/",$data))
            || $data == "4.0" || $data == "4.00" || $data == "S" ||
            $data == "I" || $data == "U" || $data == "N/A" || $data == "s"
            || $data == "i" || $data == "u" || $data == "n/a" || $data == "0/1"
            || $data == "No grade" || $data == "no grade" || $data == "No Grade") {
            $factGrade = false;
            $factEmpty = true;
          }
          if($factGrade){
            // echo "Field '$fied' is incorrect format at row '$column".($row+6)."'<br>";
            $text = "Field '$fied' is incorrect format at row '$column".($row+7)."'";
            // $arrayValidates[] = $text;
            $factGrade = true;
            $factValidate = false;
            $factEmpty = true;
            return $text;

          }
        }


        $importRow = count(\Excel::load('files/'.$file_name, function($reader) {})->get());
          // dd($importRow);
        if ($importRow < 5) {
          $fact = false;
          $text = "This file is not correct format. Please select another file!";
          $arrayValidates[] = $text;
          return view('uploadGrade.validate', compact('arrayValidates'));
        }
        else {
          $results = Excel::load('files/'.$file_name,function($reader){
            $reader->setHeaderRow(6);
            $reader->all();

          })->get();

          $resultsCourse = Excel::load('files/'.$file_name,function($reader){
            $reader->setHeaderRow(2);
          })->get();
          $courseID = $resultsCourse->getHeading()[1];
          // $resultsCo = Excel::selectSheetsByIndex(0)->load('files/'.$file_name)->get();

          $resultsGradeLevel = Excel::load('files/'.$file_name,function($reader){
            $reader->setHeaderRow(3);
          })->get();
          $gradeLevel = $resultsGradeLevel->getHeading()[1];

          $resultsYear = Excel::load('files/'.$file_name,function($reader){
            $reader->setHeaderRow(4);
          })->get();
          $year = $resultsYear->getHeading()[1];
          // dd($year);
          // dd($resultsCourse);


          // dd($results);
          // dd($results[0]->name);


          // echo $file_type;
          // echo "<br>";
          // echo $importRow;
          // echo "<br>";
          // dd(count($results));
          // dd($file_name);


          if($file_type == "xlsx" || $file_type == "xls"){
            if(count($results)==0){
              $fact = false;
              $text = "This file is empty";
              $arrayValidates[] = $text;
              return view('uploadGrade.validate', compact('arrayValidates'));
            }
            else {
              if ($checkFileName == "template") {
                for ($i = 0; $i < count($results); $i++) {
                  //----- Validate Student ID -------//
                  if($results[$i]->student_id == ""){
                    $text = "Field 'Student ID' is empty at row 'A".($i+7)."'";
                    $factValidate = false;
                    $factEmpty = false;
                    $arrayValidates[] = $text;
                  }
                  elseif($results[$i]->student_id != ""){
                    if (in_array($results[$i]->student_id, $arr)) {

                    }
                    else {
                      $text = "Field 'Student ID' doesn't have in database at row 'A".($i+7)."'";
                      $factValidate = false;
                      $factEmpty = false;
                      $arrayValidates[] = $text;
                    }
                  }

                    //----- Validate Student Name -------//
                    if($results[$i]->student_name == ""){
                      $text = "Field 'Student name' is empty at row 'B".($i+7)."'";
                      $factValidate = false;
                      $factEmpty = false;
                      $arrayValidates[] = $text;
                    }
                      if($courseID == ""){
                        $text = "Field 'Course' is empty at row 'B2'";
                        $factValidate = false;
                        $factEmpty = false;
                        $arrayValidates[] = $text;
                      }
                      if($year == ""){
                        $text = "Field 'Academic Year' is empty at row 'B4'";
                        $factValidate = false;
                        $factEmpty = false;
                        $arrayValidates[] = $text;
                      }
                }
              }
              else {
                if($fact){
                  for ($i = 0; $i < count($results); $i++) {
                    //----- Validate Student ID -------//
                    if($results[$i]->student_id == ""){
                      $text = "Field 'Student ID' is empty at row 'A".($i+7)."'";
                      $factValidate = false;
                      $factEmpty = false;
                      $arrayValidates[] = $text;
                    }
                    elseif($results[$i]->student_id != ""){
                      if (in_array($results[$i]->student_id, $arr)) {

                      }
                      else {
                        $text = "Field 'Student ID' doesn't have in database at row 'A".($i+7)."'";
                        $factValidate = false;
                        $factEmpty = false;
                        $arrayValidates[] = $text;
                      }
                    }

                      //----- Validate Student Name -------//
                      if($results[$i]->student_name == ""){
                        $text = "Field 'Student name' is empty at row 'B".($i+7)."'";
                        $factValidate = false;
                        $factEmpty = false;
                        $arrayValidates[] = $text;
                      }
                      if (!preg_match("/^[a-zA-Z ]*$/",$results[$i]->student_name)) {
                        // echo "Field 'Student name' is incorrect format at row 'B".($i+6)."'<br>";
                        $text = "Field 'Student name' is incorrect format at row 'B".($i+7)."'";
                        $factValidate = false;
                        $arrayValidates[] = $text;
                      }

                      if($courseID == ""){
                        $text = "Field 'Course' is empty at row 'B2'";
                        $factValidate = false;
                        $factEmpty = false;
                        $arrayValidates[] = $text;
                      }
                      if($year == ""){
                        $text = "Field 'Academic Year' is empty at row 'B4'";
                        $factValidate = false;
                        $factEmpty = false;
                        $arrayValidates[] = $text;
                      }

                      //----- Validate Q1 -------//
                      if($factEmpty){
                        $arrayValidates[] = validateGrade($results[$i]->q1, "Q1", "C", $factGrade, $i);
                      }

                      //----- Validate Q2 -------//
                      if($factEmpty){
                        $arrayValidates[] = validateGrade($results[$i]->q2, "Q2", "D", $factGrade, $i);
                      }


                      //----- Validate Q3 -------//
                      if($factEmpty){
                        $arrayValidates[] = validateGrade($results[$i]->q3, "Q3", "G", $factGrade, $i);
                      }


                      //----- Validate Q4 -------//
                      if($factEmpty){
                        $arrayValidates[] = validateGrade($results[$i]->q4, "Q4", "H", $factGrade, $i);
                      }


                    }

                }
              }
              if ($factValidate==TRUE) {
                // dd($results);
                if ($checkFileName == "template") {
                  for ($i = 0; $i < count($results); $i++) {
                    //-------------------- add Q1 -----------------
                    $grade = new Grade;
                		$grade->student_id = $results[$i]->student_id;
                		$grade->open_course_id = $courseID;
                		$grade->quater = '1';
                    $grade->semester = '1';
                    $grade->academic_year = $year;
                    $grade->datetime = $datetime;
                    $grade->grade = '0';
                    $grade->grade_status = '0';
                    $grade->data_status = '0';
                		$grade->save();

                    //-------------------- add Q2 -----------------
                    $grade = new Grade;
                		$grade->student_id = $results[$i]->student_id;
                		$grade->open_course_id = $courseID;
                		$grade->quater = '2';
                    $grade->semester = '1';
                    $grade->academic_year = $year;
                    $grade->datetime = $datetime;
                    $grade->grade = '0';
                    $grade->grade_status = '0';
                    $grade->data_status = '0';
                		$grade->save();

                    //-------------------- add Q3 -----------------
                    $grade = new Grade;
                		$grade->student_id = $results[$i]->student_id;
                		$grade->open_course_id = $courseID;
                		$grade->quater = '1';
                    $grade->semester = '2';
                    $grade->academic_year = $year;
                    $grade->datetime = $datetime;
                    $grade->grade = '0';
                    $grade->grade_status = '0';
                    $grade->data_status = '0';
                		$grade->save();

                    //-------------------- add Q4 -----------------
                    $grade = new Grade;
                		$grade->student_id = $results[$i]->student_id;
                		$grade->open_course_id = $courseID;
                		$grade->quater = '2';
                    $grade->semester = '2';
                    $grade->academic_year = $year;
                    $grade->datetime = $datetime;
                    $grade->grade = '0';
                    $grade->grade_status = '0';
                    $grade->data_status = '0';
                		$grade->save();


                  }
                }
                else {
                  for ($i = 0; $i < count($results); $i++) {
                    //-------------------- add Q1 -----------------
                    $grade = new Grade;
                		$grade->student_id = $results[$i]->student_id;
                		$grade->open_course_id = $courseID;
                		$grade->quater = '1';
                    $grade->semester = '1';
                    $grade->academic_year = $year;
                    $grade->datetime = $datetime;
                    // $grade->grade = $results[$i]->q1;
                    if($results[$i]->q1 == "No grade" ||
                    $results[$i]->q1 == "no grade" || $results[$i]->q1 == "no grade"){
                      $grade->grade_status = '0';
                      $grade->grade = '0';
                    }
                    elseif($results[$i]->q1 == "I" || $results[$i]->q1 == "i"){
                      $grade->grade_status = '1';
                      $grade->grade = '0';
                    }
                    elseif($results[$i]->q1 == "S" ||   $results[$i]->q1 == "s"){
                      $grade->grade_status = 2;
                      $grade->grade = '0';
                    }
                    elseif($results[$i]->q1 == "U" ||   $results[$i]->q1 == "u"){
                      $grade->grade_status = '3';
                      $grade->grade = '0';
                    }
                    elseif($results[$i]->q1 == "0/1"){
                      $grade->grade_status = '4';
                      $grade->grade = '1';
                    }
                    else {
                      $grade->grade_status = '5';
                      $grade->grade = $results[$i]->q1;
                    }
                    $grade->data_status = '0';
                		$grade->save();

                    //-------------------- add Q2 -----------------
                    $grade = new Grade;
                		$grade->student_id = $results[$i]->student_id;
                		$grade->open_course_id = $courseID;
                		$grade->quater = '2';
                    $grade->semester = '1';
                    $grade->academic_year = $year;
                    $grade->datetime = $datetime;
                    // $grade->grade = $results[$i]->q1;
                    if($results[$i]->q2 == "No grade" ||
                    $results[$i]->q2 == "no grade" || $results[$i]->q2 == "no grade"){
                      $grade->grade_status = '0';
                      $grade->grade = '0';
                    }
                    elseif($results[$i]->q2 == "I" || $results[$i]->q2 == "i"){
                      $grade->grade_status = '1';
                      $grade->grade = '0';
                    }
                    elseif($results[$i]->q2 == "S" ||   $results[$i]->q2 == "s"){
                      $grade->grade_status = 2;
                      $grade->grade = '0';
                    }
                    elseif($results[$i]->q2 == "U" ||   $results[$i]->q2 == "u"){
                      $grade->grade_status = '3';
                      $grade->grade = '0';
                    }
                    elseif($results[$i]->q2 == "0/1"){
                      $grade->grade_status = '4';
                      $grade->grade = '1';
                    }
                    else {
                      $grade->grade_status = '5';
                      $grade->grade = $results[$i]->q2;
                    }
                    $grade->data_status = '0';
                		$grade->save();

                    //-------------------- add Q3 -----------------
                    $grade = new Grade;
                		$grade->student_id = $results[$i]->student_id;
                		$grade->open_course_id = $courseID;
                		$grade->quater = '1';
                    $grade->semester = '2';
                    $grade->academic_year = $year;
                    $grade->datetime = $datetime;
                    // $grade->grade = $results[$i]->q1;
                    if($results[$i]->q3 == "No grade" ||
                    $results[$i]->q3 == "no grade" || $results[$i]->q3 == "no grade"){
                      $grade->grade_status = '0';
                      $grade->grade = '0';
                    }
                    elseif($results[$i]->q3 == "I" || $results[$i]->q3 == "i"){
                      $grade->grade_status = '1';
                      $grade->grade = '0';
                    }
                    elseif($results[$i]->q3 == "S" ||   $results[$i]->q3 == "s"){
                      $grade->grade_status = 2;
                      $grade->grade = '0';
                    }
                    elseif($results[$i]->q3 == "U" ||   $results[$i]->q3 == "u"){
                      $grade->grade_status = '3';
                      $grade->grade = '0';
                    }
                    elseif($results[$i]->q3 == "0/1"){
                      $grade->grade_status = '4';
                      $grade->grade = '1';
                    }
                    else {
                      $grade->grade_status = '5';
                      $grade->grade = $results[$i]->q3;
                    }
                    $grade->data_status = '0';
                		$grade->save();

                    //-------------------- add Q4 -----------------
                    $grade = new Grade;
                		$grade->student_id = $results[$i]->student_id;
                		$grade->open_course_id = $courseID;
                		$grade->quater = '2';
                    $grade->semester = '2';
                    $grade->academic_year = $year;
                    $grade->datetime = $datetime;
                    // $grade->grade = $results[$i]->q1;
                    if($results[$i]->q4 == "No grade" ||
                    $results[$i]->q4 == "no grade" || $results[$i]->q2 == "no grade"){
                      $grade->grade_status = '0';
                      $grade->grade = '0';
                    }
                    elseif($results[$i]->q4 == "I" || $results[$i]->q4 == "i"){
                      $grade->grade_status = '1';
                      $grade->grade = '0';
                    }
                    elseif($results[$i]->q4 == "S" ||   $results[$i]->q4 == "s"){
                      $grade->grade_status = 2;
                      $grade->grade = '0';
                    }
                    elseif($results[$i]->q4 == "U" ||   $results[$i]->q4 == "u"){
                      $grade->grade_status = '3';
                      $grade->grade = '0';
                    }
                    elseif($results[$i]->q4 == "0/1"){
                      $grade->grade_status = '4';
                      $grade->grade = '1';
                    }
                    else {
                      $grade->grade_status = '5';
                      $grade->grade = $results[$i]->q4;
                    }
                    $grade->data_status = '0';
                		$grade->save();


                  }
                }



                return view('uploadGrade.getUpload', compact('results'));
              }
              elseif ($factValidate==FALSE) {
                // var_dump($arrayValidates);
                return view('uploadGrade.validate', compact('arrayValidates'));
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


    public function exportExcel($type)
    {
      Excel::create('template_elective', function($excel) {

        $excel->sheet('Excel sheet', function($sheet) {

          $sheet->setOrientation('landscape');

          $sheet->setCellValue('A1', 'Teacher');
          $sheet->setCellValue('A2', 'Course');
          $sheet->setCellValue('A3', 'Grade level');
          $sheet->setCellValue('A4', 'Academic Year');
          $sheet->setCellValue('A6', 'Student_ID');
          $sheet->setCellValue('B5', 'If you split a class…');
          $sheet->setCellValue('B6', 'Student Name');
          $sheet->setCellValue('C5', '1st Semester');
          $sheet->setCellValue('C6', 'Q1');
          $sheet->setCellValue('D1', 'Do not worry about any calculations. The report cards will do them');
          $sheet->setCellValue('D2', 'automatically. You are only required to fill in the highlighted sections.');
          $sheet->setCellValue('D3', 'High school teachers, hover here for a special note');
          $sheet->setCellValue('D6', 'Q2');
          $sheet->setCellValue('E6', 'Sum 1');
          $sheet->setCellValue('F6', 'Sem 1');
          $sheet->setCellValue('G5', '2nd Semester');
          $sheet->setCellValue('G6', 'Q3');
          $sheet->setCellValue('H6', 'Q4');
          $sheet->setCellValue('I6', 'Sum 2');
          $sheet->setCellValue('J6', 'Sem 2');
          $sheet->setCellValue('K5', 'Grade');
          $sheet->setCellValue('K6', 'Average');
          $sheet->setCellValue('L5', 'Year');
          $sheet->setCellValue('L6', 'Grade');

          $sheet->setWidth(array(
              'A' => 12,
              'B' => 19,
              'M' => 9
          ));

          $sheet->setStyle(array(
              'font' => array(
                  'name'      =>  'Tw Cen MT',
                  'size'      =>  12,
                  'bold'      =>  false
              )
          ));

          $sheet->cell('B1', function($cell) {
              $cell->setBackground('#FFC300');
          });

          $sheet->cell('B5', function($cell) {
              $cell->setBackground('#FF9F68');
          });

          $sheet->cell('D3:H3', function($cell) {
              $cell->setBackground('#FF9F68');
          });


          $sheet->setBorder('C5:L6', 'thin');

          $sheet->mergeCells('C5:E5');
          $sheet->cell('C5:E5', function($cell) {
              $cell->setAlignment('center');
          });

          $sheet->mergeCells('G5:I5');
          $sheet->cell('G5:I5', function($cell) {
              $cell->setAlignment('center');
          });



        });

      })->export($type);
    }

    public function exportHeight($type)
    {
      Excel::create('HeightandWeight', function($excel) {

        $excel->sheet('Excel sheet', function($sheet) {

          $sheet->setOrientation('landscape');

          $sheet->setCellValue('A1', 'No.');
          $sheet->setCellValue('B1', 'Students ID');
          $sheet->setCellValue('C1', 'Students Name');
          $sheet->setCellValue('D1', 'S1 Height');
          $sheet->setCellValue('E1', 'S1 Weight');
          $sheet->setCellValue('F1', 'S2 Height');
          $sheet->setCellValue('G1', 'S2 Weight');


          $sheet->setWidth(array(
              'B' => 12,
              'C' => 19
          ));

          $sheet->setStyle(array(
              'font' => array(
                  'name'      =>  'Tw Cen MT',
                  'size'      =>  12,
                  'bold'      =>  false
              )
          ));

          $sheet->setBorder('A1:G1', 'thin');

          $sheet->cells('D1:G1', function($cells) {
              $cells->setAlignment('center');
              $cells->setValignment('center');
              $cells->setTextRotation(90);
            });

        });

      })->export($type);
    }

    public function exportComments($type)
    {
      Excel::create('Comments', function($excel) {

        $excel->sheet('Excel sheet', function($sheet) {

          $sheet->setOrientation('landscape');

          $sheet->setCellValue('A1', 'No.');
          $sheet->setCellValue('B1', 'Students ID');
          $sheet->setCellValue('C1', 'Students Name');
          $sheet->setCellValue('D1', 'Quarter 1');
          $sheet->setCellValue('E1', 'Quarter 2');
          $sheet->setCellValue('F1', 'Quarter 3');
          $sheet->setCellValue('G1', 'Quarter 4');


          $sheet->setWidth(array(
              'B' => 12,
              'C' => 19,
              'D' => 50,
              'E' => 50,
              'F' => 50,
              'G' => 50
          ));

          $sheet->setStyle(array(
              'font' => array(
                  'name'      =>  'Tw Cen MT',
                  'size'      =>  12,
                  'bold'      =>  false
              ),
              'borders' => [
                  'allborders' => [
                      'color' => [
                          'rgb' => 'DBDBDB'
                      ]
                  ]
              ]
          ));


        });

      })->export($type);
    }

    public function exportBehavior($type)
    {
      Excel::create('Behavior', function($excel) {

        $excel->sheet('Excel sheet', function($sheet) {

          $sheet->setOrientation('landscape');

          $sheet->setCellValue('A1', 'No.');
          $sheet->setCellValue('A2', 'Behavior');
          $sheet->setCellValue('A3', 'Students ID');
          $sheet->setCellValue('B3', 'Students Name');
          //-------- From Behavior table--------------------//
          $sheet->setCellValue('C1', '1');
          $sheet->setCellValue('C2', 'Attentive in class');
          $sheet->setCellValue('C3', 'Q1');
          $sheet->setCellValue('D3', 'Q2');
          $sheet->setCellValue('E3', 'Q3');
          $sheet->setCellValue('F3', 'Q4');


          $sheet->setWidth(array(
              'A' => 12,
              'B' => 12
          ));

          $sheet->setStyle(array(
              'font' => array(
                  'name'      =>  'Tw Cen MT',
                  'size'      =>  12,
                  'bold'      =>  false
              )
          ));


          $sheet->mergeCells('C1:F1');
          $sheet->cell('C1:F1', function($cell) {
              $cell->setAlignment('center');
          });

          $sheet->mergeCells('C2:F2');
          $sheet->cell('C2:F2', function($cell) {
              $cell->setAlignment('center');
          });

          $sheet->setBorder('A1:F20', 'thin');


        });

      })->export($type);
    }

    public function exportAttandance($type)
    {
      Excel::create('Attandance', function($excel) {

        $excel->sheet('Excel sheet', function($sheet) {

            $sheet->setOrientation('landscape');

            $sheet->setCellValue('A2', 'No.');
            $sheet->setCellValue('B2', 'Students ID');
            $sheet->setCellValue('C2', 'Students Name');
            $sheet->setCellValue('D2', '1st Semester');
            $sheet->setCellValue('I2', '2nd Semester');
            $sheet->setCellValue('C1', 'Attandance');
            //------------- From Attentance table ----------//
            $sheet->setCellValue('D1', 'Days Present');
            $sheet->setCellValue('E1', 'Late');
            $sheet->setCellValue('F1', 'Sick');
            $sheet->setCellValue('G1', 'Leave');
            $sheet->setCellValue('H1', 'Absent');
            $sheet->setCellValue('I1', 'Days Present S2');
            $sheet->setCellValue('J1', 'Late S2');
            $sheet->setCellValue('K1', 'Sick S2');
            $sheet->setCellValue('L1', 'Leave S2');
            $sheet->setCellValue('M1', 'Absent S2');


            $sheet->setWidth(array(
                'B' => 12,
                'C' => 19
            ));

            $sheet->setStyle(array(
                'font' => array(
                    'name'      =>  'Tw Cen MT',
                    'size'      =>  12,
                    'bold'      =>  false
                )
            ));

            $sheet->setBorder('A1:M2', 'thin');

            $sheet->cells('A1:AN1', function($cells) {
                $cells->setAlignment('center');
                $cells->setValignment('center');
                $cells->setTextRotation(90);
              });

            $sheet->mergeCells('D2:H2');
            $sheet->cell('D2:H2', function($cell) {
                $cell->setAlignment('center');
            });

            $sheet->mergeCells('I2:M2');
            $sheet->cell('I2:M2', function($cell) {
                $cell->setAlignment('center');
            });

            $sheet->cell('C1', function($cell) {
                $cell->setBackground('#FFC300');
            });


          });

      })->export($type);
    }

    public function exportActivities($type)
    {
      Excel::create('Activities', function($excel) {

        $excel->sheet('Excel sheet', function($sheet) {

          $sheet->setOrientation('landscape');

          $sheet->setCellValue('A1', 'Fill these cells in with S, U, or leave blank');
          $sheet->setCellValue('A3', 'No.');
          $sheet->setCellValue('B3', 'Students ID');
          $sheet->setCellValue('C3', 'Students Name');
          $sheet->setCellValue('D3', '1st Semester');
          $sheet->setCellValue('K3', '2nd Semester');
          $sheet->setCellValue('C2', 'Activities');
          //------------- From Attentance table ----------//
          $sheet->setCellValue('D2', 'Homeroom 5');
          $sheet->setCellValue('E2', 'Extra Curricular Activities 5');
          $sheet->setCellValue('F2', 'Guidance and Developmental Skills 5');
          $sheet->setCellValue('G2', 'Social Spirit 5');
          $sheet->setCellValue('H2', 'Shadowing');
          $sheet->setCellValue('I2', 'Newspaper');
          $sheet->setCellValue('J2', 'Yearbook');
          $sheet->setCellValue('K2', 'Homeroom 6');
          $sheet->setCellValue('L2', 'Extra Curricular Activities 6');
          $sheet->setCellValue('M2', 'Guidance and Developmental Skills 6');
          $sheet->setCellValue('N2', 'Social Spirit 6');
          $sheet->setCellValue('O2', 'Shadowing');
          $sheet->setCellValue('P2', 'Newspaper');
          $sheet->setCellValue('Q2', 'Yearbook');

          $sheet->setWidth(array(
              'B' => 12,
              'C' => 19
          ));

          $sheet->setHeight(array(
              'B' => 100
          ));

          $sheet->setStyle(array(
              'font' => array(
                  'name'      =>  'Tw Cen MT',
                  'size'      =>  12,
                  'bold'      =>  false
              )
          ));

          $sheet->getStyle('A2:Z2')->getAlignment()->setWrapText(true);

          $sheet->setBorder('A2:Q3', 'thin');

          $sheet->cells('A2:AN2', function($cells) {
              $cells->setAlignment('center');
              $cells->setValignment('center');
              $cells->setTextRotation(90);
            });

          $sheet->mergeCells('D3:J3');
          $sheet->cell('D3:J3', function($cell) {
              $cell->setAlignment('center');
          });

          $sheet->mergeCells('K3:Q3');
          $sheet->cell('K3:Q3', function($cell) {
              $cell->setAlignment('center');
          });

          $sheet->mergeCells('A1:D1');
          $sheet->cell('A1:D1', function($cell) {
              $cell->setAlignment('center');
          });

          $sheet->cell('C2', function($cell) {
              $cell->setBackground('#FFC300');
          });

          $sheet->cell('A1', function($cell) {
              $cell->setBackground('#95B3D7');
          });


        });

      })->export($type);
    }





}
