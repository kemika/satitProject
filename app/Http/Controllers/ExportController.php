<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Excel;
use Auth;
use App\Homeroom;
use App\Teacher;
use App\Student;
use App\Academic_Year;
use App\Offered_Courses;


use App\Curriculum;
use App\Student_Grade_Level;


class ExportController extends Controller
{
  public function index(){
    $id =  auth::user()->teacher_number;
    $teacher = Teacher::where('teacher_id',$id)->select('teachers.*')->get()[0];



    if($teacher->teacher_status == 0){
      $academicYear = Homeroom::where('teacher_id',$teacher->teacher_id)
      ->select('homeroom.*')
      ->join('academic_year','academic_year.classroom_id','=','homeroom.classroom_id')
      ->select('homeroom.*','academic_year.*')
      ->get()[0];


      // Assian Artibute
      $classroom_id = $academicYear->classroom_id;
      $students = Student_Grade_Level::where('student_grade_levels.classroom_id',$classroom_id)
      ->select('student_grade_levels.*')
      ->join('students','students.student_id','student_grade_levels.student_id')
      ->select('student_grade_levels.*','students.*')
      ->get();


      // $teachings = Teaching::where('teacher_number','=',$teacher_number)
      // ->join('subjects','subjects.subj_number','=','teachings.subj_number')
      // ->select('teachings.*','subjects.name')
      // ->join('gpas', function($j) {
      // $j->on('gpas.subj_number', '=', 'teachings.subj_number');
      // $j->on('gpas.semester','=','teachings.semester');
      // $j->on('gpas.year','=','teachings.year');
      // })
      // ->select('teachings.*','subjects.name','gpas.std_number','gpas.score')
      // ->get();



      $subjects = Offered_Courses::where('classroom_id', $classroom_id)
      ->where('Offered_Courses.is_elective',  '1')
      ->select('Offered_Courses.*')
      ->join('Curriculums', function($j) {
      $j->on('Curriculums.course_id', '=', 'Offered_Courses.course_id');
      $j->on('Curriculums.curriculum_year','=','Offered_Courses.curriculum_year');
      })
      ->select('Offered_Courses.*','Curriculums.*')
      ->get();


      dd($subjects[0]->course_name,$subjects[1]->course_name);
      // dd("CLASS ROOM ID : ".$academicYear->classroom_id,"GRADE LEVEL : ".$academicYear->grade_level,"ROOM : ".$academicYear->room);

      return "OK";
    }

    return 'Permission Denine';


    return auth::user()->teacher_number;
  }


  public function exportExcel($type,$semester,$year,$grade,$room)
{






  Excel::create('Laravel Excel', function($excel)  {

    $excel->sheet('Excel sheet', function($sheet)  {

    $sheet->setOrientation('landscape');
    $sheet->cells('A1:A5', function($cells) {
    $cells->setValignment('center'); });


    });

  })->export($type);
}

    //
}
