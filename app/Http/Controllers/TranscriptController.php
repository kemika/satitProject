<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\School_Days;
use App\SystemConstant;
use DB;
use App\Offered_Courses;

use Illuminate\Support\Facades\Log;
use PDF;
use App\Student;
use App\Student_Grade_Level;
use App\Academic_Year;
use App\Teacher;
use App\Homeroom;
use App\Grade;
use App\Activity_Record;
use App\Grade_Status;
use App\Information;
use App\Physical_Record;
use App\Behavior_Type;
use App\Behavior_Record;
use App\Attendance_Record;
use App\Teacher_Comment;
use auth;
use File;
use ZipArchive;
use Response;

class TranscriptController extends Controller
{

  public function index()
  {

    $academic_years = Academic_Year::groupBy('academic_year')->distinct('academic_year')->orderBy('academic_year' , 'desc')->get();
    $rooms = Academic_Year::orderBy('grade_level')->get();
    return view('transcript.room_list', ['academic_years' => $academic_years, 'rooms' => $rooms]);

  }


  public function studentList($classroom_id)
  {

            $room = Academic_Year::where('classroom_id', $classroom_id)
                ->select('academic_year.*')
                ->first();

            $students = Student_Grade_Level::where('classroom_id', $classroom_id)
                ->select('student_grade_levels.*')
                ->join('students', 'students.student_id', 'student_grade_levels.student_id')
                ->select('student_grade_levels.*', 'students.*')
                ->get();
            return view('transcript.student_list', ['students' => $students, 'room' => $room]);






  }

  public function exportTranscript($student_id,$download_all, $folder_name)
  {



    $grades = Grade::where('grades.student_id',$student_id)
    ->where('grades.data_status' , '1')
    ->join('student_grade_levels','student_grade_levels.student_id','grades.student_id')
    ->select('grades.*','student_grade_levels.*')
    ->join('students','students.student_id','student_grade_levels.student_id')
    ->select('grades.*','student_grade_levels.*','students.*')
    ->leftJoin('offered_courses','offered_courses.open_course_id','grades.open_course_id')
    ->select('grades.*','student_grade_levels.*','students.*','offered_courses.*')
    ->leftJoin('curriculums' ,function($j){
      $j->on('curriculums.course_id','offered_courses.course_id');
      $j->on('curriculums.curriculum_year','offered_courses.curriculum_year');
    })
    ->select('grades.*','student_grade_levels.*','students.*','offered_courses.*','curriculums.*')

    ->orderBy('academic_year', 'desc')
    ->orderBy('course_name')
    ->get();


    $activity_records = Activity_Record::where('activity_records.student_id',$student_id)
    // ->where('activity_records.data_status' , '1')
    ->join('student_grade_levels','student_grade_levels.student_id','activity_records.student_id')
    ->select('activity_records.*','student_grade_levels.*')
    ->join('students','students.student_id','student_grade_levels.student_id')
    ->select('activity_records.*','student_grade_levels.*','students.*')
    ->leftJoin('offered_courses','offered_courses.open_course_id','activity_records.open_course_id')
    ->select('activity_records.*','student_grade_levels.*','students.*','offered_courses.*')
    ->leftJoin('curriculums' ,function($j){
      $j->on('curriculums.course_id','offered_courses.course_id');
      $j->on('curriculums.curriculum_year','offered_courses.curriculum_year');
    })
    ->select('activity_records.*','student_grade_levels.*','students.*','offered_courses.*','curriculums.*')
    ->join('grade_status','activity_records.grade_status','grade_status.grade_status')
    ->select('activity_records.*','student_grade_levels.*','students.*','offered_courses.*','curriculums.*','grade_status.*')
    ->orderBy('academic_year', 'desc')
    ->get();




    $year = Grade::where('grades.student_id',$student_id)
    ->join('student_grade_levels','student_grade_levels.student_id','grades.student_id')
    ->select('grades.*','student_grade_levels.*')
    ->join('students','students.student_id','student_grade_levels.student_id')
    ->select('grades.*','student_grade_levels.*','students.*')
    ->orderBy('academic_year', 'desc')
    ->first();

    $academic_year = Academic_Year::where('classroom_id',$year->classroom_id)
    ->first();

    $student = Student::where('students.student_id',$student_id)->first();





    $g = self::convertDataToForm($grades->toArray(),$academic_year->grade_level,$academic_year->academic_year,$activity_records->toArray());
    $keys = array_keys($g);
    $grade_column1 = $g[$keys[0]];
    $grade_column2 = $g[$keys[1]];
    $grade_column3 = $g[$keys[2]];

    $grade_column1['year'] = $keys[0];
    $grade_column2['year'] = $keys[1];
    $grade_column3['year'] = $keys[2];





    $count =  max( [count($grade_column1['grade']),count($grade_column2['grade']),count($grade_column3['grade'])] );

    $grade_column1['grade'] = self::getGradeToForm($count,$grade_column1['grade']);
    $grade_column2['grade'] = self::getGradeToForm($count,$grade_column2['grade']);
    $grade_column3['grade']= self::getGradeToForm($count,$grade_column3['grade']);


    $count_ac =  max( [count($grade_column1['activity']),count($grade_column2['activity']),count($grade_column3['activity'])] );
    $grade_column1['activity'] = self::getActivityToForm($count_ac,$grade_column1['activity']);
    $grade_column2['activity'] = self::getActivityToForm($count_ac,$grade_column2['activity']);
    $grade_column3['activity']= self::getActivityToForm($count_ac,$grade_column3['activity']);









    // dd($grade_column1,$grade_column2,$grade_column3,$count,$count_ac);

    // dd($grade_column1,$grade_column2,$grade_column3);

    $gpax = self::getGPAX($grade_column1,$grade_column2,$grade_column3);
    if($academic_year->grade_level < 4){
      $pdf = PDF::loadView('transcript.formGrade1-3',['grade_column1' => $grade_column1 ,'grade_column2' => $grade_column2,'grade_column3' => $grade_column3,'count'=> $count,'count_ac'=> $count_ac,'student'=> $student->toArray(),'gpax' => $gpax]);
    }
    else if($academic_year->grade_level >= 4 && $academic_year->grade_level <= 6){
      $start = 4;
      $pdf = PDF::loadView('transcript.formGrade4-6',['grade_column1' => $grade_column1 ,'grade_column2' => $grade_column2,'grade_column3' => $grade_column3,'count'=> $count,'count_ac'=> $count_ac,'student'=> $student->toArray(),'gpax' => $gpax]);

    }
    else if($academic_year->grade_level >= 7 && $academic_year->grade_level <= 9){
      $pdf = PDF::loadView('transcript.formGrade7-9jap',['grade_column1' => $grade_column1 ,'grade_column2' => $grade_column2,'grade_column3' => $grade_column3,'count'=> $count,'count_ac'=> $count_ac,'student'=> $student->toArray(),'gpax' => $gpax]);
    }
    else if($academic_year->grade_level >= 10 && $academic_year->grade_level <= 12){
      $pdf = PDF::loadView('transcript.formGrade10-12',['grade_column1' => $grade_column1 ,'grade_column2' => $grade_column2,'grade_column3' => $grade_column3,'count'=> $count,'count_ac'=> $count_ac,'student'=> $student->toArray(),'gpax' => $gpax]);

    }

    $pdf->setPaper('a4', 'potrait');
    if (!$download_all) {
        return $pdf->stream();
    } else {
        $file_name = $student->student_id;

        $pdf->save(public_path('transript_file_to_zip/' . $folder_name . '/' . $file_name . '.pdf', true));

    }
    return ;






  }

  public function exportPDFAll($classroom_id, $academic_year)
  {
      $students = Student_Grade_Level::where('classroom_id', $classroom_id)
          ->select('student_grade_levels.*')
          ->join('students', 'students.student_id', 'student_grade_levels.student_id')
          ->select('student_grade_levels.*', 'students.*')
          ->get();


      $room = Academic_Year::where('academic_year', $academic_year)
          ->where('classroom_id', $classroom_id)
          ->select('academic_year.*')
          ->first();
      if ($room->room < 10) {
          $folder_name = $room->grade_level . '0' . $room->room . '_' . date("Y-m-d-H-i-s");
      } else {
          $folder_name = $room->grade_level . $room->room . '_' . date("Y-m-d-H-i-s");
      }

      // Create output path for pdf file if it doesn't exists
      $path = public_path() . '/transript_file_to_zip' . '/' . $folder_name. '/';

      if (!File::exists($path)) {
          File::makeDirectory($path, $mode = 0777, true, true);
      }

      // Create output path for zip file if it doesn't exists
      $zip_output_path = public_path() . '/zipPDF_transcript'.'/';
      if(!File::exists($zip_output_path)) {
          File::makeDirectory($zip_output_path, $mode = 0777, true, true);
      }

      foreach ($students as $student) {
          self::exportTranscript($student->student_id, 1, $folder_name);
      }

//         for($i =0 ; $i < 3;$i ++){
//           self::exportPDF($students[$i]->student_id, $academic_year, 1,$folder_name);
//         }

      // Zip File Name
      $zipFileName = $folder_name . '.zip';

      // Create ZipArchive Obj
      $zip = new ZipArchive;
      if ($zip->open($zip_output_path . $zipFileName, ZipArchive::CREATE) === TRUE) {

          foreach ($students as $student) {
              if($zip->addFile($path . $student->student_id . '.pdf',$student->firstname . "_" . $student->lastname ."_". $student->student_id . '.pdf')){
               //   Log::info("Adding ".$path . $student->student_id . '.pdf'. " to zip");
              }else{
                  Log::error("Problem adding ".$path . $student->student_id);
              }
          }
//            for($i = 0; $i < 3; $i++){
//                if($zip->addFile($path . $students[$i]->student_id . '.pdf',$students[$i]->student_id . '.pdf')){
//                    Log::info("Adding ".$path . $students[$i]->student_id . '.pdf'. " to zip");
//                }else{
//                    Log::error("Problem adding ".$path . $students[$i]->student_id);
//                }
//            }
          //Log::info("Done adding files to zip");

          $zip->close();
      }else{
          Log::error("Problem open zip file");
      }

      // Set Header
      $headers = array();
      $filetopath = $zip_output_path . $zipFileName;
      //Log::info("Sending ".$filetopath." to users.");
      // Create Download Response
      if ((file_exists($filetopath))) {
          return response()->download($filetopath, $zipFileName, $headers);
      }
  }

  public function convertDataToForm($grades,$grade_level,$academic_year,$activity_records)

  {

    $dataConverted = array();
    $start = 1;
    if($grade_level >= 4 && $grade_level <= 6){
      $start = 4;

    }
    else if($grade_level >= 7 && $grade_level <= 9){
      $start = 7;
    }
    else if($grade_level >= 10 && $grade_level <= 12){
      $start = 10;
    }


    $s =$start;
    for($i = $start ; $i <= $grade_level ;$i++){
      $diff = $grade_level - $i;
      $academic_year_index = $academic_year-$diff;
      $dataConverted[$academic_year_index] = array('grade' => array(),'activity' => array(),'grade_level' => $s);
      $s+=1;
    }
    $j = 1;
    for($i = count($dataConverted) ; $i < 3 ; $i ++){
      $a = 3 - count($dataConverted);
      $b = $academic_year + $j;
      $j +=1;
      $dataConverted[$b] = array('grade' => array(),'activity' => array(),'grade_level' => $s);
      $s+=1;



    }




    foreach ($grades as $d) {

      array_push($dataConverted[$d['academic_year']]['grade'],$d);
    }

    foreach ($activity_records as $d) {

      array_push($dataConverted[$d['academic_year']]['activity'],$d);
    }


    foreach ($dataConverted as $key => $value) {

      $dataConverted[$key]['grade'] =  self::converFormat($dataConverted[$key]['grade']);

      // code...
    }


    // dd($dataConverted);


    foreach ($dataConverted as $key => $value) {
      if(count($value['grade']) != 0){
        foreach ($value['grade'] as $v) {
                    $dataConverted[$v['academic_year']]['grade'][$v['course_id']]['sem_grade'] =self::getSemGrade($v['grade_quarter1'],$v['grade_quarter2'],$v['grade_quarter3']);

        }
      }
    }
    // foreach ($dataConverted as $data) {
    //
    //   foreach ($data as $key => $value) {
    //
    //
    //     // array_push($ar,array_key_exists('grade_quarter1',$value));
    //     if(count($data['grade']) != 0){
    //
    //
    //       foreach ($value as $v) {
    //
    //
    //         if(array_key_exists('grade_quarter1',$v)){
    //
    //           $dataConverted[$v['academic_year']]['grade'][$v['course_id']]['sem_grade'] =self::getSemGrade($v['grade_quarter1'],$v['grade_quarter2'],$v['grade_quarter3']);
    //
    //         }
    //       }
    //     }
    //
    //   }
    //
    //
    //
    // }



    foreach ($dataConverted as $key => $value) {

      $dataConverted[$key]['avg_grade'] = self::getGPA($value)['gpa'];
      $dataConverted[$key]['avg_grade'] = sprintf('%.2f', $dataConverted[$key]['avg_grade']);
      $dataConverted[$key]['total_credits'] = self::getGPA($value)['credits'];
      $dataConverted[$key]['cumulative'] = self::getGPA($value)['cumulative'];
    }








    return $dataConverted;







  }

  public function converFormat($grades){

    $check = array();
    $result = array();
    foreach ($grades as $g) {

      if(in_array($g['course_id'],$check)){
        $result[$g['course_id']]['grade_quarter'.$g['quarter']] = $g['grade'];

      }
      else{
        $a = array(
          'student_id' => $g['student_id'],
          'open_course_id' => $g['open_course_id'],
          'grade_quarter1' => '-',
          'grade_quarter2' => '-',
          'grade_quarter3' => '-',
          'semester' => $g['semester'],
          'academic_year' => $g['academic_year'],
          'grade_status' => $g['grade_status'],
          'firstname' => $g['firstname'],
          'lastname' => $g['lastname'],
          'curriculum_year' => $g['curriculum_year'],
          'course_id' => $g['course_id'],
          'course_name' => $g['course_name'],
          'credits' => $g['credits']

        );
        array_push($check , $g['course_id']);

        $result[$g['course_id']] = $a;

        $result[$g['course_id']]['grade_quarter'.$g['quarter']] = $g['grade'];
      }


    }




    return $result;


  }

  public function exportTranscriptPDF(){
    $pdf = PDF::loadView('transcript.formGrade1-3');
    $pdf->setPaper('a4', 'potrait');
    return $pdf->stream();

  }

  public function getGPA($grades){
    $gpa = 0;
    $total_credit = 0;
    $total_point = 0;
    foreach ($grades['grade'] as $d) {

      if(is_numeric($d['sem_grade'])){
        $total_credit += $d['credits'];
        $total_point += ($d['credits']*$d['sem_grade']);
      }

    }
    if($total_credit != 0){
      $gpa = $total_point / $total_credit;
      return array('gpa' => $gpa ,'credits' =>$total_credit,'cumulative' => $total_point);
    }
    else{
      $gpa = '';
    }


    return array('gpa' => $gpa ,'credits' =>$total_credit,'cumulative' => '');


  }

  public function getSemGrade($q1,$q2,$q3){
    if(is_numeric($q1) && is_numeric($q2) && is_numeric($q3) ){
      $grade = ($q1 +$q2 + $q3)/3;
      if ($grade < 1.00) {
          return 0;
      } elseif ($grade < 1.25) {
          return 1;
      } elseif ($grade < 1.75) {
          return 1.5;
      } elseif ($grade < 2.25) {
          return 2;
      } elseif ($grade < 2.75) {
          return 2.5;
      } elseif ($grade < 3.25) {
          return 3;
      } elseif ($grade < 3.75) {
          return 3.5;
      }

      return 4;

    }
    return '';

  }

  public function getGradeToForm($count,$grades){
    $b = [];
    foreach ($grades as $v) {
      array_push($b,$v);
    }

    for($i = count($b) ; $i < $count ; $i ++){
      array_push($b,array(
        'student_id' => '',
        'open_course_id' => '',
        'grade_quarter1' => '',
        'grade_quarter2' => '',
        'grade_quarter3' => '',
        'semester' => '',
        'academic_year' => '',
        'grade_status' => '',
        'firstname' => '',
        'lastname' => '',
        'curriculum_year' => '',
        'course_id' => '',
        'course_name' => '',
        'credits' => '',
        'sem_grade'=> '')
      );


    }
    return $b;

  }



  public function getActivityToForm($count,$grades){
    $b = [];
    foreach ($grades as $v) {
      array_push($b,$v);
    }

    for($i = count($b) ; $i < $count ; $i ++){
      array_push($b,array(
        'course_name' => '',
        'grade_status_text'=> '-')
      );


    }
    return $b;

  }

  public function getGPAX($grades1 ,$grades2 ,$grades3){
    $grades = [$grades1,$grades2,$grades3];

    $total_credit = 0;
    $total_point = 0;
    foreach ($grades as $g) {

      if(is_numeric($g['avg_grade'])){
        $total_credit += $g['total_credits'];
        $total_point += $g['avg_grade']*$g['total_credits'];
        // %totalbob
      }
    }

    if($total_credit != 0){
      return sprintf('%.2f', $total_point / $total_credit);
    }
    return '';


  }





}
