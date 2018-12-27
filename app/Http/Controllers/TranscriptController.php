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

  public function exportTranscript($student_id)
  {

    $grades = Grade::where('grades.student_id',$student_id)
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



    self::convertDataToForm($grades->toArray(),$academic_year->grade_level,$academic_year->academic_year,$activity_records);






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



    for($i = $start ; $i <= $grade_level ;$i++){
      $diff = $grade_level - $i;
      $academic_year_index = $academic_year-$diff;
      $dataConverted[$academic_year_index] = array('grade' => array(),'activity' => array());
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

    dd($dataConverted,'Boom');







  }

  public function converFormat($grades){

    $check = array();
    $result = array();
    foreach ($grades as $g) {

      if(in_array($g['course_id'],$check)){
        $result[$g['course_id']]['grade_quater'.$g['quater']] = $g['grade'];

      }
      else{
        $a = array(
          'student_id' => $g['student_id'],
          'open_course_id' => $g['open_course_id'],
          'grade_quater1' => 0,
          'grade_quater2' => 0,
          'grade_quater3' => 0,
          'semester' => $g['semester'],
          'academic_year' => $g['academic_year'],
          'grade_status' => $g['grade_status'],
          'firstname' => $g['firstname'],
          'lastname' => $g['lastname'],
          'curriculum_year' => $g['curriculum_year'],
          'course_id' => $g['course_id'],
          'course_name' => $g['course_name']

        );
        array_push($check , $g['course_id']);

        $result[$g['course_id']] = $a;

        $result[$g['course_id']]['grade_quater'.$g['quater']] = $g['grade'];
      }


    }

    return $result;


  }

  public function exportTranscriptPDF(){
    $pdf = PDF::loadView('transcript.formGrade1-3');
    $pdf->setPaper('a4', 'potrait');
    return $pdf->stream();

  }


}
