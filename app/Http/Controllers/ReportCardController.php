<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;
use App\Student;
use App\Student_Grade_Level;
use App\Academic_Year;
use App\Teacher;
use App\Homeroom;
use auth;

class ReportCardController extends Controller
{
  public function index(){
    $id =  auth::user()->teacher_number;
    $teacher = Teacher::where('teacher_id',$id)->select('teachers.*')->get()[0];

    $academic_year = Homeroom::where('teacher_id',$teacher->teacher_id)
    ->select('homeroom.*')
    ->join('academic_year','academic_year.classroom_id','=','homeroom.classroom_id')
    ->select('homeroom.*','academic_year.*')
    ->get()[0];

    $rooms = Academic_Year::where('academic_year',$academic_year->academic_year)
    ->select('academic_year.*')
    ->distinct()
    ->join('student_grade_levels','student_grade_levels.classroom_id','academic_year.classroom_id')
    ->select('academic_year.*','student_grade_levels.*')
    ->distinct('student_grade_levels.classroom_id')
    ->get('student_grade_levels.classroom_id');

    $rooms = self::getDistinct($rooms,'classroom_id');
    dd(count($rooms));







    $data = ['name' => 'My'];
    return view('reportCard.form2', compact('data'));

  }

  public function exportPDF(){
    $data = ['name' => 'My'];
    $pdf = PDF::loadView('reportCard.form2', compact('data'));
    $pdf->setPaper('a4', 'potrait');
    return $pdf->stream();
    // return $pdf->download('reportCard.pdf');

  }



  public static function getDistinct($arr,$field){
    $result = array();

    foreach($arr as $x){

      if (!in_array($x->classroom_id."",$result)){
        array_push($result,$x->classroom_id);

      }
    }

    return $result;

  }


}
