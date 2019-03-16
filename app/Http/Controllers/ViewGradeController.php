<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Curriculum;
use App\Room;
use App\Http\Controllers\DB;
use App\Grade;

class ViewGradeController extends Controller
{
  public function index(){
    $grades = Grade::join('offered_courses','grades.open_course_id','offered_courses.open_course_id')
    ->select('offered_courses.*','grades.*')
    ->get();
    $grades = $grades->toArray();

    $grades_array = array();
    $grades_array = $grades;

    // for ($i = 0 ;$i< 10 ; $i ++){
    //   array_push($grades_array,$grades[$i]);
    // }
    // dd($grades->toArray());
    return view('grade.list',['grades_all'=>$grades_array]);

  }



  public function result(Request $request)
  {
      dd($request);
      if($request->input('year') == "chooseYear"){
        $curriculums = Curriculum::all();
      }

      return view('grade.index' , ['curriculums' => $curriculums]);

  }



  public function api(Request $request){
    // $filter= $_GET['filter'];
    // $datas = self::getData($filter);
    $grades = Grade::join('offered_courses','grades.open_course_id','offered_courses.open_course_id')
    ->select('grades.grade','grades.student_id','offered_courses.course_id','grades.semester','grades.quater')
    ->join('students','students.student_id','grades.student_id')
    ->select('grades.grade','grades.student_id','offered_courses.course_id','grades.semester','grades.quater','students.firstname','students.lastname')
    ->join('curriculums','curriculums.course_id','offered_courses.course_id')
    ->select('grades.grade','grades.student_id','offered_courses.course_id','grades.semester','grades.quater','students.firstname','students.lastname','curriculums.course_name')
    ->join('student_grade_levels','student_grade_levels.student_id','grades.student_id')
    ->join('academic_year','academic_year.classroom_id','student_grade_levels.classroom_id')
    ->select('grades.grade','grades.student_id','offered_courses.course_id','grades.semester','grades.quater','students.firstname','students.lastname','curriculums.course_name','academic_year.room','academic_year.grade_level','academic_year.academic_year')
    ->get();





    $grades_array = $grades->toArray();


    foreach ($grades_array as $key => $value) {
      $grades_array[$key]['student_name'] = $value['firstname'].' '.$value['lastname'];
    }
    $filter= $_GET['filter'];
    // $filter = ['grade' => '0','course_id' => 'ART','student_id' => '','semester'=>'','firstname'=>'','lastname'=>'','course_name'=>'','quater'=>''];
    $check = true;
    foreach ($filter as $key => $value) {
      if($value){
          $check = false;
      }


    }
    if($check){
      return $grades_array;
    }



    $b = array();
      foreach ($grades_array as $data) {

        $check = true;
        foreach ($filter as $key => $value) {
          $strCheck = $filter[$key];


          if($strCheck != ''){

            if(strpos($data[$key], $filter[$key]) !== false || $data[$key] == $filter[$key] ){


              $check = false;
            }
            else{
              $check = true;
              break;
            }



              }
          }
          if(!$check){
            array_push($b,$data);

          }
        }



    return $b;
  }


  public function api2(Request $request){
    $grades = Grade::join('offered_courses','grades.open_course_id','offered_courses.open_course_id')
    ->select('offered_courses.*','grades.*')
    ->get();

    $gradesx = $grades->toArray();
    foreach ($gradesx as $x) {
      $x = '1';
      // code...
    }

    dd($grades[0],$gradesx[0]);


    //
    // $age1 = array("Name"=>"My1", "Age"=>"10");
    // $age2 = array("Name"=>"My2", "Age"=>"12");
    // $age3 = array("Name"=>"My3", "Age"=>"13");
    // $data =array($age1,$age2,$age3);
    // $data2 = array("data"=>$data,'itemsCount'=>3);
    // return $data2;

  }
}
