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
use App\Grade;
use App\Activity_Record;
use App\Grade_Status;
use auth;

class ReportCardController extends Controller
{

  public function __construct() {
    $this->middleware('auth');
  }
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
    ->get();

    // ->join('student_grade_levels','student_grade_levels.classroom_id','academic_year.classroom_id')
    // ->select('academic_year.*','student_grade_levels.*')
    // ->distinct('student_grade_levels.classroom_id')
    // ->get('student_grade_levels.classroom_id');

    // $rooms = self::getDistinct($rooms,'classroom_id');







    return view('reportCard.master', ['rooms' => $rooms]);

  }

  public function exportPDF($student_id,$academic_year){
    $grade_semester1 = Grade::where('grades.student_id',$student_id)
    ->where('grades.data_status','1')
    ->distinct()
    ->where('grades.semester','1')
    ->where('grades.academic_year' , $academic_year)
    ->join('offered_courses','offered_courses.open_course_id', 'grades.open_course_id')
    // ->where('offered_courses','offered_courses.semester','grades.semester')
    ->where('offered_courses.is_elective','1')
    ->select('grades.*','offered_courses.*')
    ->join('curriculums','curriculums.course_id','offered_courses.course_id')
    ->select('grades.*','offered_courses.*','curriculums.*')
    // ->where('curriculums.curriculum_year','offered_courses.curriculum_year')
    // ->select('grades.*','offered_courses.*','curriculums.*')
    ->get();
    $grade_semester1 = self::getGradeToFrom($grade_semester1);
    $grade_avg_sem1 = self::getAvg($grade_semester1);


    $grade_semester2 = Grade::where('student_id',$student_id)
    ->where('grades.data_status','1')
    ->distinct()
    ->where('grades.semester','2')
    ->where('grades.academic_year' , $academic_year)
    ->join('offered_courses','offered_courses.open_course_id','grades.open_course_id')
      // ->where('offered_courses','offered_courses.semester','grades.semester')
    ->where('offered_courses.is_elective','1')
    ->select('grades.*','offered_courses.*')
    ->join('curriculums','curriculums.course_id','offered_courses.course_id')
    // ->where('curriculums.curriculum_year','offered_courses.curriculum_year')
    ->select('grades.*','offered_courses.*','curriculums.*')
    ->get();
    $grade_semester2 = self::getGradeToFrom($grade_semester2);
    $grade_avg_sem2 = self::getAvg($grade_semester2);


    $grade_elec_semester1 = Activity_Record::where('student_id',$student_id)
    ->where('activity_records.data_status','1')
    ->where('activity_records.semester','1')
    ->where('activity_records.academic_year' , $academic_year)
    ->join('offered_courses','offered_courses.open_course_id','activity_records.open_course_id')
      // ->where('offered_courses','offered_courses.semester','grades.semester')
    ->where('offered_courses.is_elective','0')
    ->select('activity_records.*','offered_courses.*')
    ->join('curriculums','curriculums.course_id','offered_courses.course_id')
    // ->where('curriculums.curriculum_year','offered_courses.curriculum_year')
    ->select('activity_records.*','offered_courses.*','curriculums.*')
    ->join('grade_status','grade_status.grade_status','activity_records.grade_status')
    ->select('activity_records.*','offered_courses.*','curriculums.*','grade_status.*')
    ->get();



    $grade_elec_semester2 = Activity_Record::where('student_id',$student_id)
    ->where('activity_records.data_status','1')
    ->where('activity_records.semester','2')
    ->where('activity_records.academic_year' , $academic_year)
    ->join('offered_courses','offered_courses.open_course_id','activity_records.open_course_id')
      // ->where('offered_courses','offered_courses.semester','grades.semester')
    ->where('offered_courses.is_elective','0')
    ->select('activity_records.*','offered_courses.*')
    ->join('curriculums','curriculums.course_id','offered_courses.course_id')
    // ->where('curriculums.curriculum_year','offered_courses.curriculum_year')
    ->select('activity_records.*','offered_courses.*','curriculums.*')
    ->join('grade_status','grade_status.grade_status','activity_records.grade_status')
    ->select('activity_records.*','offered_courses.*','curriculums.*','grade_status.*')
    ->get();



    $student = Student::where('students.student_id',$student_id)
    ->join('student_grade_levels','student_grade_levels.student_id','students.student_id')
    ->select('students.*','student_grade_levels.*')
    ->join('academic_year','academic_year.classroom_id','student_grade_levels.classroom_id')
    ->select('students.*','student_grade_levels.*','academic_year.*')
    ->first();


    $pdf = PDF::loadView('reportCard.form',['grade_semester1' => $grade_semester1,'grade_semester2' => $grade_semester2,'student' => $student,'avg1' => $grade_avg_sem1,'avg2' => $grade_avg_sem2,'grade_elec_semester1' => $grade_elec_semester1,'grade_elec_semester2' => $grade_elec_semester2]);
    $pdf->setPaper('a4', 'potrait');
    return $pdf->stream();
    // return $pdf->download('reportCard.pdf');

  }



  public static function getDistinct($arr,$field){
    $result = array();
    $check = array();



    foreach($arr as $x){

      if (!in_array($x->classroom_id."",$check)){
        array_push($check,$x->classroom_id);
        array_push($result,$x);

      }
    }

    return $result;

  }


  public function Room($classroom_id){

    $room = Academic_Year::where('classroom_id',$classroom_id)
    ->select('academic_year.*')
    ->first();

    $students = Student_Grade_Level::where('classroom_id',$classroom_id)
    ->select('student_grade_levels.*')
    ->join('students','students.student_id','student_grade_levels.student_id')
    ->select('student_grade_levels.*','students.*')
    ->get();
    return view('reportCard.room',['students' =>$students,'room' => $room]);

  }


  public static function getGradeToFrom($arr){
    $check = array();
    $result = array();

    foreach ($arr as $x ) {
      if (!in_array($x->course_id."",$check)){


        $element = array('course_name'=> $x->course_name,
                        'course_id'=> $x->course_id,
                        'credits'=>$x->credits,
                        'quater1' => 0,
                        'quater2' => 0,
                        'quater3' => 0,
                        'total_point' => 0);

        $element['quater'.$x->quater] = $x->grade;
        $element['total_point'] +=+$x->grade;
        $result[$x->course_id] = $element;
        array_push($check,$x->course_id);



      }else{

        $result[$x->course_id]['quater'.$x->quater] = $x->grade;
        $result[$x->course_id]['total_point'] += $x->grade;

      }

    }


    return $result;

  }



  public static function getAvg($arr){

    $total_score = 0;
    $total_credit = 0;
    foreach ($arr as $key => $x) {
      $score = (($x['total_point']/3)*$x['credits']);
      $score = substr($score,0,strpos($score,'.')+3);
       // $total_score += number_format((($x['total_point']/3)*$x['credits']),2);
      $total_score += $score;
      $total_credit += $x['credits'];
    }
    if($total_credit == 0 ){
      return 0;
    }
    $avg = $total_score/$total_credit;

    return substr($avg,0,strpos($avg,'.')+3);



  }


}
