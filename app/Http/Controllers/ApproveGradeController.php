<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Curriculum;
use App\Academic_Year;
use App\Grade;
use App\Offered_Courses;

class ApproveGradeController extends Controller
{
  public function index($year,$semester,Request $request){

  //  $curricula  = Curriculum::all();
  //  $curricula  = DB::table('Curricula')->select('year',DB::raw('count(*) as total'))->groupBy('year')->get();
    #$grade  = Grade::where('academic_year', $request->input('year'))->get();

    #$curriculum  = Curriculum::where('curriculum_year', $request->input('year'))->get();
  /*
    $courses  = offered_courses::where('curriculum_year', $year)
                  ->join('grades','offered_courses.open_course_id','=','grades.open_course_id')
                  ->join('data_status','data_status.data_status','=','grades.data_status')
                  ->where('data_status.data_status_text','!=','canceled')
                  ->where('grades.semester',$semester)
                  ->select('offered_courses.open_course_id','offered_courses.course_id','grades.grade','grades.quater'
                          ,'data_status.data_status_text','grades.datetime')
                  ->get();
*/


  $courses  = Offered_Courses::join('academic_year','academic_year.curriculum_year','=','offered_courses.curriculum_year')
            ->join('curriculums','curriculums.curriculum_year','=','offered_courses.curriculum_year')
            ->join('grades','offered_courses.open_course_id','=','grades.open_course_id')
            ->join('data_status','data_status.data_status','=','grades.data_status')
            ->where('offered_courses.curriculum_year', $year)
            ->where('grades.semester', $semester)
            ->where('data_status.data_status_text','!=','canceled')
            ->select('offered_courses.open_course_id','offered_courses.course_id','academic_year.grade_level','grades.grade','grades.quater'
                    ,'curriculums.course_name','data_status.data_status_text','grades.datetime','offered_courses.is_elective')
            ->get();

  foreach ($courses as $course){
    if($course->is_elective === 1){
      $course->grade_level = "";
    }
  }



    return view('approveGrade.index' , ['courses' => $courses]);
  }
  public function test(Request $request){
      $courses = [];
      return view('approveGrade.index' , ['courses' => $courses]);;
  }
  public function testPost(Request $request){
    $courses  = Offered_Courses::join('academic_year','academic_year.curriculum_year','=','offered_courses.curriculum_year')
              ->join('curriculums','curriculums.curriculum_year','=','offered_courses.curriculum_year')
              ->join('grades','offered_courses.open_course_id','=','grades.open_course_id')
              ->join('data_status','data_status.data_status','=','grades.data_status')
              ->where('offered_courses.curriculum_year', $request->input('year'))
              ->where('data_status.data_status_text','!=','canceled')
              ->select('offered_courses.open_course_id','offered_courses.course_id','academic_year.grade_level','grades.grade','grades.quater'
                      ,'curriculums.course_name','data_status.data_status_text','grades.datetime','offered_courses.is_elective')
              ->get();

    foreach ($courses as $course){
      if($course->is_elective === 1){
        $course->grade_level = "";
      }
    }



      return view('approveGrade.index' , ['courses' => $courses]);
  }
}
