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
use App\Teacher_Comment;
use App\Physical_Record;
use App\Behavior_Record;
use App\Attendance_Record;
use App\Activity_Record;
use App\Room;
use App\Academic_Year;
use App\Grade_Status;
use App\WaitApprove;
use Illuminate\Support\Facades\Input;
use App\Grade;
use App\Offered_Courses;

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
    //   if($request->hasfFile('file')){getUpload
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


    public function getUploadComments(Request $request)
    {

      // dd($datetime);
      // dd($studentsID);
      // var_dump($studentsID);
      // print_r($studentsID);




      //$stdArray = $tempsss->unwrap($studentsID);

      // print_r($arr);
      //
      // if (in_array("1111111111", $arr)) {
      //     echo "Got My";
      // }

      if ($request->hasFile('file')) {
        $errorArray = array();

        foreach($request->file as $file){

          $finalResult = array();
          $errorDetail = array();
          $fact = true;
          $factGrade = true;
          $factValidate = true;
          $factEmpty = true;
          //$file = Input::file('file');
          $file_name = $file->getClientOriginalName();
          $file_type = \File::extension('files/'.$file_name);
          $file->move('files/', $file_name);
          $checkFileName = substr("$file_name", 0, 8);


          $getAcademicYear = Excel::load('files/'.$file_name,function($reader){
            $reader->setHeaderRow(1);
          })->get();


          $getGradeLevel = Excel::load('files/'.$file_name,function($reader){
            $reader->setHeaderRow(2);
          })->get();

          $getRoom = Excel::load('files/'.$file_name,function($reader){
            $reader->setHeaderRow(3);
          })->get();

          $results = Excel::load('files/'.$file_name,function($reader){
            $reader->setHeaderRow(4);
            $reader->all();
          })->get();

          $year = $getAcademicYear->getHeading()[1];
          $gradeLevel = $getGradeLevel->getHeading()[1];
          $room = $getRoom->getHeading()[1];

          $checkYear = Academic_Year::where('academic_year.academic_year',$year)->first();
          $checkRoom = Academic_Year::where('academic_year.room',$room)->first();
          $checkGradeLevel = Academic_Year::where('academic_year.grade_level',$gradeLevel)->first();



          if(!is_int($year)){
            $errorDetail["year"] = "Invalid type of Academic Year";
          }
          else{

          }

          $students = Student::all();
          $studentsID = Student::Join('student_grade_levels','student_grade_levels.student_id','=','students.student_id')
              ->Join('academic_year','academic_year.classroom_id','=','student_grade_levels.classroom_id')
              ->where('academic_year.academic_year',$year)
              ->where('academic_year.room',$room)
              ->where('academic_year.grade_level',$gradeLevel)
              ->select('students.student_id','students.firstname','students.lastname')
              ->get();

          $stdArray = array();
          $stdName = array();

          date_default_timezone_set('Asia/Bangkok');
          $datetime = date("Y-m-d H:i:s");

          foreach ($studentsID as $studentID) {
            $stdArray[] = $studentID->student_id;
            $stdName[(String) ($studentID->student_id)] = $studentID->firstname." ".$studentID->lastname;
          }


          for ($i = 0; $i < count($results); $i++) {
            if(in_array($results[$i]->students_id,$stdArray)){

              if($stdName[(String) ($results[$i]->students_id)] === $results[$i]->students_name){
                for ($j = 1; $j <= 4; $j++) {
                  $qComment = "quater_".$j;
                  if($j == 1 || $j == 2){
                    $semester = 1;
                  }
                  else if($j == 3 || $j == 4){
                    $semester = 2;
                  }
                  if($results[$i]->$qComment != ""){
                    $comment = new Teacher_Comment;
                    $comment->student_id = $results[$i]->students_id;
                    $comment->quater = $j;
                    $comment->comment = $results[$i]->$qComment;
                    $comment->semester = $semester;
                    $comment->academic_year = $year;
                    $comment->datetime = $datetime;
                    $finalResult[] = $comment;
                  }
                }
              }
              else if ($stdName[$results[$i]->students_id] !== $results[$i]->students_name){
                $errorDetail[(String) ($results[$i]->students_id)] = $results[$i]->students_id." This student ID doesn't match with student name";
              }
            }
            else if (!in_array($results[$i]->students_id,$stdArray)){
              $errorDetail[(String) ($results[$i]->students_id)] = $results[$i]->students_id." This Student ID doesn't exist in this room";
            }

          }
          if(count($errorDetail) <= 0){
            foreach($finalResult as $result){
              $result->save();
            }
            $errorDetail["Status"] = "upload file Academic_Year : ".$year." Grade Level : ".$gradeLevel." Room : ".$room." success";

          }
          else {
            $errorDetail["Status"] = "upload file Academic_Year : ".$year." Grade Level : ".$gradeLevel." Room : ".$room." error";
            /*
            foreach($errorDetail as $key => $value){
              print_r("Student ID : ".$key." got error => ".$value."</br>");
            }*/

          }
          $errorArray[] = $errorDetail;


        }

      }


      return view('uploadGrade.upload' , ['errorDetail' => $errorArray]);

    } // END upload Comment

    public function getUploadHeightAndWeight(Request $request)
    {

      // dd($datetime);
      // dd($studentsID);
      // var_dump($studentsID);
      // print_r($studentsID);


      //$stdArray = $tempsss->unwrap($studentsID);

      // print_r($arr);
      //
      // if (in_array("1111111111", $arr)) {
      //     echo "Got My";
      // }

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
      }


      $getAcademicYear = Excel::load('files/'.$file_name,function($reader){
        $reader->setHeaderRow(1);
      })->get();


      $getGradeLevel = Excel::load('files/'.$file_name,function($reader){
        $reader->setHeaderRow(2);
      })->get();

      $getRoom = Excel::load('files/'.$file_name,function($reader){
        $reader->setHeaderRow(3);
      })->get();

      $results = Excel::load('files/'.$file_name,function($reader){
        $reader->setHeaderRow(4);
        $reader->all();
      })->get();

      $year = $getAcademicYear->getHeading()[1];
      $gradeLevel = $getGradeLevel->getHeading()[1];
      $room = $getRoom->getHeading()[1];

      $students = Student::all();
      $studentsID = Student::Join('student_grade_levels','student_grade_levels.student_id','=','students.student_id')
          ->Join('academic_year','academic_year.classroom_id','=','student_grade_levels.classroom_id')
          ->where('academic_year.academic_year',$year)
          ->where('academic_year.room',$room)
          ->where('academic_year.grade_level',$gradeLevel)
          ->select('students.student_id')
          ->get();
      $stdArray = array();

      date_default_timezone_set('Asia/Bangkok');
      $datetime = date("Y-m-d H:i:s");

      foreach ($studentsID as $studentID) {
        $stdArray[] = $studentID->student_id;
      }

      for ($i = 0; $i < count($results); $i++) {
        if(in_array($results[$i]->students_id,$stdArray)){
              $physical = new Physical_Record;
              $physical->student_id = $results[$i]->students_id;
              $physical->weight = $results[$i]->s1_weight;
              $physical->height = $results[$i]->s1_height;
              $physical->semester = 1;
              $physical->academic_year = $year;
              $physical->datetime = $datetime;
              $physical->data_status = 1;
              $physical->save();

              $physical = new Physical_Record;
              $physical->student_id = $results[$i]->students_id;
              $physical->weight = $results[$i]->s2_weight;
              $physical->height = $results[$i]->s2_height;
              $physical->semester = 2;
              $physical->academic_year = $year;
              $physical->datetime = $datetime;
              $physical->data_status = 1;
              $physical->save();
        }

      }
      $redi  = 'temp/test'.$results[0]->s2_weight;
      return redirect($redi);

    } // END upload HeightAndWeight

    public function getUploadBehavior(Request $request)
    {

      // dd($datetime);
      // dd($studentsID);
      // var_dump($studentsID);
      // print_r($studentsID);




      //$stdArray = $tempsss->unwrap($studentsID);

      // print_r($arr);
      //
      // if (in_array("1111111111", $arr)) {
      //     echo "Got My";
      // }

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
      }


      $getAcademicYear = Excel::load('files/'.$file_name,function($reader){
        $reader->setHeaderRow(1);
      })->get();


      $getGradeLevel = Excel::load('files/'.$file_name,function($reader){
        $reader->setHeaderRow(2);
      })->get();

      $getRoom = Excel::load('files/'.$file_name,function($reader){
        $reader->setHeaderRow(3);
      })->get();

      $results = Excel::load('files/'.$file_name,function($reader){
        $reader->setHeaderRow(6);
        $reader->all();
      })->get();

      $year = $getAcademicYear->getHeading()[1];
      $gradeLevel = $getGradeLevel->getHeading()[1];
      $room = $getRoom->getHeading()[1];

      $students = Student::all();
      $studentsID = Student::Join('student_grade_levels','student_grade_levels.student_id','=','students.student_id')
          ->Join('academic_year','academic_year.classroom_id','=','student_grade_levels.classroom_id')
          ->where('academic_year.academic_year',$year)
          ->where('academic_year.room',$room)
          ->where('academic_year.grade_level',$gradeLevel)
          ->select('students.student_id')
          ->get();
      $stdArray = array();

      date_default_timezone_set('Asia/Bangkok');
      $datetime = date("Y-m-d H:i:s");

      foreach ($studentsID as $studentID) {
        $stdArray[] = $studentID->student_id;
      }

      for ($i = 0; $i < count($results); $i++) {
        if(in_array($results[$i]->students_id,$stdArray)){
          for ($j = 1; $j <= 4; $j++) {
            $qBehavior = "q".$j;
            if($j == 1 || $j == 2){
              $semester = 1;
            }
            else if($j == 3 || $j == 4){
              $semester = 2;
            }
            if($results[$i]->$qBehavior != ""){
              $behavior = new Behavior_Record;
              $behavior->student_id = $results[$i]->students_id;
              $behavior->quater = $j;
              $behavior->behavior_type = $results[$i]->$qBehavior;
              $behavior->semester = $semester;
              $behavior->academic_year = $year;
              $behavior->datetime = $datetime;
              $behavior->data_status = 1;
              $behavior->save();
            }
          }
        }

      }
      $redi  = 'temp/test';
      return redirect($redi);

    } // END upload Behavior



    public function getUploadAttendance(Request $request)
    {

      // dd($datetime);
      // dd($studentsID);
      // var_dump($studentsID);
      // print_r($studentsID);


      //$stdArray = $tempsss->unwrap($studentsID);

      // print_r($arr);
      //
      // if (in_array("1111111111", $arr)) {
      //     echo "Got My";
      // }

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
      }


      $getAcademicYear = Excel::load('files/'.$file_name,function($reader){
        $reader->setHeaderRow(1);
      })->get();


      $getGradeLevel = Excel::load('files/'.$file_name,function($reader){
        $reader->setHeaderRow(2);
      })->get();

      $getRoom = Excel::load('files/'.$file_name,function($reader){
        $reader->setHeaderRow(3);
      })->get();

      $results = Excel::load('files/'.$file_name,function($reader){
        $reader->setHeaderRow(4);
        $reader->all();
      })->get();

      $resultsStudent = Excel::load('files/'.$file_name,function($reader){
        $reader->setHeaderRow(5);
        $reader->all();
      })->get();



      $year = $getAcademicYear->getHeading()[1];
      $gradeLevel = $getGradeLevel->getHeading()[1];
      $room = $getRoom->getHeading()[1];

      $students = Student::all();
      $studentsID = Student::Join('student_grade_levels','student_grade_levels.student_id','=','students.student_id')
          ->Join('academic_year','academic_year.classroom_id','=','student_grade_levels.classroom_id')
          ->where('academic_year.academic_year',$year)
          ->where('academic_year.room',$room)
          ->where('academic_year.grade_level',$gradeLevel)
          ->select('students.student_id')
          ->get();
      $stdArray = array();

      date_default_timezone_set('Asia/Bangkok');
      $datetime = date("Y-m-d H:i:s");

      foreach ($studentsID as $studentID) {
        $stdArray[] = $studentID->student_id;
      }

      for ($i = 0; $i < count($resultsStudent); $i++) {
        if(in_array($resultsStudent[$i]->students_id,$stdArray)){
              $attendance = new Attendance_Record;
              $attendance->student_id = $resultsStudent[$i]->students_id;
              $attendance->late = $results[$i+1]->late;
              $attendance->absent = $results[$i+1]->absent;
              $attendance->leave = $results[$i+1]->leave;
              $attendance->sick = $results[$i+1]->sick;
              $attendance->semester = 1;
              $attendance->academic_year = $year;
              $attendance->datetime = $datetime;
              $attendance->data_status = 1;
              $attendance->save();

              $attendance = new Attendance_Record;
              $attendance->student_id = $resultsStudent[$i]->students_id;
              $attendance->late = $results[$i+1]->late_s2;
              $attendance->absent = $results[$i+1]->absent_s2;
              $attendance->leave = $results[$i+1]->leave_s2;
              $attendance->sick = $results[$i+1]->sick_s2;
              $attendance->semester = 2;
              $attendance->academic_year = $year;
              $attendance->datetime = $datetime;
              $attendance->data_status = 1;
              $attendance->save();
        }

      }
      $redi  = 'temp/test'.$results[1]->sick_s2;
      return redirect($redi);

    } // END upload Attendance


    public function getUploadActivities(Request $request)
    {

      // dd($datetime);
      // dd($studentsID);
      // var_dump($studentsID);
      // print_r($studentsID);


      //$stdArray = $tempsss->unwrap($studentsID);

      // print_r($arr);
      //
      // if (in_array("1111111111", $arr)) {
      //     echo "Got My";
      // }

      if ($request->hasFile('file')) {
        $errorArray = array();

        foreach($request->file as $file){

          $fact = true;
          $factGrade = true;
          $factValidate = true;
          $factEmpty = true;
          //$file = Input::file('file');
          $file_name = $file->getClientOriginalName();
          $file_type = \File::extension('files/'.$file_name);
          $file->move('files/', $file_name);
          $checkFileName = substr("$file_name", 0, 8);

                $getAcademicYear = Excel::load('files/'.$file_name,function($reader){
                  $reader->setHeaderRow(1);
                })->get();


                $getGradeLevel = Excel::load('files/'.$file_name,function($reader){
                  $reader->setHeaderRow(2);
                })->get();

                $getRoom = Excel::load('files/'.$file_name,function($reader){
                  $reader->setHeaderRow(3);
                })->get();

                $resultsStudent = Excel::load('files/'.$file_name,function($reader){
                  $reader->setHeaderRow(7);
                  $reader->all();
                })->get();


                $flipped = array_flip($resultsStudent->getHeading());
                $indexSecSem = $flipped["2st_semester"];

              //  for($i = 0; $i < count($resultsSemester); $i++)

                $resultsFirst = Excel::load('files/'.$file_name,function($reader) use($indexSecSem){
                  $reader->setHeaderRow(5);
                  //$reader->limitColumns(7);
                  $reader->limitColumns($indexSecSem);
                  $reader->skipColumns(2);
                  $reader->all();
                })->get();

                $resultsSecond = Excel::load('files/'.$file_name,function($reader) use($indexSecSem){
                  $reader->setHeaderRow(5);
                  //$reader->limitColumns(7);
                  $reader->skipColumns($indexSecSem);
                  $reader->all();
                })->get();

              //  dd($resultsFirst);

                $courseIDFirst = array();
                $courseIDSec = array();
                foreach($resultsFirst[0] as $key => $val){
                  if($key !== 0){
                    /*
                    //$id = strtoupper($key);
                    $id = $key;
                    $id = explode("_", $id);
                    $slice = array_slice($id, 0,-2);
                    foreach($slice as $key2 => $str){
                      $slice[$key2] = ucfirst($str);;
                    }
                    $res = join(' ', $slice);
                    //print_r(array_slice($id, -2));
          */
                    $courseIDFirst[$key] = $val;

                  }
                }

                foreach($resultsSecond[0] as $key => $val){
                  if($key !== 0){
                    $courseIDSec[$key] = $val;
                  }
                }



                //dd($courseIDSec);
                $year = $getAcademicYear->getHeading()[1];
                $gradeLevel = $getGradeLevel->getHeading()[1];
                $room = $getRoom->getHeading()[1];

                $courses  = Academic_Year::Join('offered_courses','offered_courses.classroom_id','=','academic_year.classroom_id')
                        ->Join('curriculums', function($join)
                                     {
                                         $join->on('curriculums.course_id', '=', 'offered_courses.course_id');
                                         $join->on('curriculums.curriculum_year','=', 'offered_courses.curriculum_year');
                                     })
                        ->where('academic_year.academic_year',$year)
                        ->where('academic_year.room',$room)
                        ->where('academic_year.grade_level',$gradeLevel)
                        ->where('curriculums.is_activity',true)
                        ->select('offered_courses.open_course_id','offered_courses.semester','offered_courses.course_id','curriculums.course_name')
                        ->get();

                $courseArr = array();

                foreach($courses as $course){
                    $courseArr[$course->course_id." ".$course->semester] = $course->open_course_id;
                }
                //dd($courseArr);

                $students = Student::all();
                $studentsID = Student::Join('student_grade_levels','student_grade_levels.student_id','=','students.student_id')
                    ->Join('academic_year','academic_year.classroom_id','=','student_grade_levels.classroom_id')
                    ->where('academic_year.academic_year',$year)
                    ->where('academic_year.room',$room)
                    ->where('academic_year.grade_level',$gradeLevel)
                    ->select('students.student_id')
                    ->get();
                $stdArray = array();

                $gradeStatus = Grade_Status::all();
                $GStatusArr = array();
                foreach($gradeStatus as $status){
                  $statusArr[$status->grade_status_text] = $status->grade_status;
                }


                date_default_timezone_set('Asia/Bangkok');
                $datetime = date("Y-m-d H:i:s");

                foreach ($studentsID as $studentID) {
                  $stdArray[] = $studentID->student_id;
                }

                for ($i = 0; $i < count($resultsStudent); $i++) {
                  if(in_array($resultsStudent[$i]->students_id,$stdArray)){

                        foreach($courseIDFirst as $key => $id){
                          $resUp = strtoupper($resultsFirst[$i+2]->$key);
                          if(array_key_exists($resUp, $statusArr)){
                            $activity = new Activity_Record;
                            $activity->student_id = $resultsStudent[$i]->students_id;
                            $activity->open_course_id = $courseArr[$id." 1"];
                            $activity->grade_status = $statusArr[$resUp];
                            $activity->semester = 1;
                            $activity->academic_year = $year;
                            $activity->datetime = $datetime;
                            $activity->data_status = 1;
                            $activity->save();

                          }
                        }

                        foreach($courseIDSec as $key => $id){
                          $resUp = strtoupper($resultsSecond[$i+2]->$key);

                          if(array_key_exists($resUp, $statusArr)){
                            $activity = new Activity_Record;
                            $activity->student_id = $resultsStudent[$i]->students_id;
                            $activity->open_course_id = $courseArr[$id." 2"];
                            $activity->grade_status = $statusArr[$resUp];
                            $activity->semester = 1;
                            $activity->academic_year = $year;
                            $activity->datetime = $datetime;
                            $activity->data_status = 1;
                            $activity->save();

                          }

                        }

                  }

                }
        }

      }


      $redi  = '/upload';
      return redirect($redi);

    } // END upload Activity


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
            $factValidate = true;
          }
          elseif($factGrade && $data != ""){
            // echo "Field '$fied' is incorrect format at row '$column".($row+6)."'<br>";
            dd('aaaa');
            $text = "Field '$fied' is incorrect format at row '$column".($row+7)."'";
            // $arrayValidates[] = $text;
            $factGrade = true;
            $factValidate = false;
            $factEmpty = true;
            $factValidate = false;
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
          $getCourseID = $resultsCourse->getHeading()[1];
          // $resultsCo = Excel::selectSheetsByIndex(0)->load('files/'.$file_name)->get();
          $getCourseID = strtoupper ($getCourseID );
          //$courseID = "1";
          $getCourseID = str_replace("_"," ","$getCourseID");

          $courseID = Offered_Courses::where('offered_courses.course_id',$getCourseID)
          ->select('offered_courses.*')
          ->first();
          //dd($courseID->open_course_id);

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


           //dd($results);
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
                        $arrayValidates[] = validateGrade($results[$i]->q1, "Q1", "C", $factGrade, $i);

                      //----- Validate Q2 -------//
                        $arrayValidates[] = validateGrade($results[$i]->q2, "Q2", "D", $factGrade, $i);


                      //----- Validate Q3 -------//
                        $arrayValidates[] = validateGrade($results[$i]->q3, "Q3", "G", $factGrade, $i);


                      //----- Validate Q4 -------//
                        $arrayValidates[] = validateGrade($results[$i]->q4, "Q4", "H", $factGrade, $i);


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
                		$grade->open_course_id = $courseID->open_course_id;
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
                		$grade->open_course_id = $courseID->open_course_id;
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
                		$grade->open_course_id = $courseID->open_course_id;
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
                		$grade->open_course_id = $courseID->open_course_id;
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
                		$grade->open_course_id = $courseID->open_course_id;
                		$grade->quater = '1';
                    $grade->semester = '1';
                    $grade->academic_year = $year;
                    $grade->datetime = $datetime;
                    // $grade->grade = $results[$i]->q1;
                    if($results[$i]->q1 != ""){
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
                    }

                    //-------------------- add Q2 -----------------
                    $grade = new Grade;
                		$grade->student_id = $results[$i]->student_id;
                		$grade->open_course_id = $courseID->open_course_id;
                		$grade->quater = '2';
                    $grade->semester = '1';
                    $grade->academic_year = $year;
                    $grade->datetime = $datetime;
                    // $grade->grade = $results[$i]->q1;
                    if($results[$i]->q2 != ""){
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
                    }

                    //-------------------- add Q3 -----------------
                    $grade = new Grade;
                		$grade->student_id = $results[$i]->student_id;
                		$grade->open_course_id = $courseID->open_course_id;
                		$grade->quater = '1';
                    $grade->semester = '2';
                    $grade->academic_year = $year;
                    $grade->datetime = $datetime;
                    // $grade->grade = $results[$i]->q1;
                    if($results[$i]->q3 != ""){
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
                    }

                    //-------------------- add Q4 -----------------
                    $grade = new Grade;
                		$grade->student_id = $results[$i]->student_id;
                		$grade->open_course_id = $courseID->open_course_id;
                		$grade->quater = '2';
                    $grade->semester = '2';
                    $grade->academic_year = $year;
                    $grade->datetime = $datetime;
                    // $grade->grade = $results[$i]->q1;
                    if($results[$i]->q4 != ""){
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
                }



                return view('uploadGrade.getUpload', compact('results'));
              }
              elseif ($factValidate==FALSE) {
                //var_dump($arrayValidates);
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


       elseif (!($request->hasFile('file'))) {
         dd("Please Select File");
       }
    }

}
