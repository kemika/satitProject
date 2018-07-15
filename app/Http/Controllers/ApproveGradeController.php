<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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


$courses  = Offered_Courses::leftJoin('grades','grades.open_course_id','=','offered_courses.open_course_id')
      ->Join('curriculums','offered_courses.course_id','=','curriculums.course_id')
      ->Join('academic_year','offered_courses.classroom_id','=','academic_year.classroom_id')
      ->leftJoin('data_status','grades.data_status','=','data_status.data_status')
      ->where('offered_courses.curriculum_year', $year)
      ->where('offered_courses.semester', $semester)
      ->groupBy('grades.datetime','grades.open_course_id')
      ->select('offered_courses.open_course_id','offered_courses.curriculum_year','offered_courses.course_id','academic_year.grade_level','grades.grade','grades.quater'
              ,'curriculums.course_name','data_status.data_status_text','grades.datetime','grades.semester','offered_courses.is_elective')
      ->orderBy('course_id','asc')
      ->orderBy('datetime','desc')
      ->get();


/*
->select('offered_courses.open_course_id','offered_courses.course_id','academic_year.grade_level','grades.grade'
  ,'grades.datetime','grades.semester','curriculums.course_name')

$courses  = Offered_Courses::leftJoin('grades','grades.open_course_id','=','offered_courses.open_course_id')
           ->Join('curriculums','offered_courses.curriculum_year','=','curriculums.curriculum_year')
           ->Join('academic_year','offered_courses.curriculum_year','=','academic_year.curriculum_year')
           ->leftJoin('data_status','grades.data_status','=','data_status.data_status')
           ->where('offered_courses.curriculum_year', $year)
           ->where('grades.semester', $semester)
           ->orderBy('datetime','desc')
           ->groupBy('grades.datetime','grades.open_course_id')
           ->select('offered_courses.open_course_id','offered_courses.course_id','academic_year.grade_level','grades.grade','grades.quater'
                   ,'curriculums.course_name','data_status.data_status_text','grades.datetime','grades.semester','offered_courses.is_elective')
           ->get();
*/

$check = 0;
$count = 0;
$saveUnset = array();
foreach ($courses as $course){
  if($course->is_elective === 1){
    $course->grade_level = "";
  }
  if(!isset($course->data_status_text)){
    $course->quater = "Not submitted";
  }

  if($check !== $course->open_course_id){
    $check = $course->open_course_id;
  }
  else{
   //unset($courses[$count]);
   array_unshift($saveUnset,$count);
   //$saveUnset[] = $count;
    $count += 1;
    continue;
  }

  if($course->data_status_text === "Canceled"){
    //unset($courses[$count]);
    array_unshift($saveUnset,$count);
    //$saveUnset[] = $count;
    $count += 1;
    continue;
  }
  $count += 1;
}

$tempCourse = $courses->all();
foreach($saveUnset as $num){
  unset($tempCourse[$num]);
}
$tempCourse = array_values($tempCourse);
/*
$saveUnset = array_unique($saveUnset);
$temp = $courses->all();


unset($temp[0]);
$temp = array_values($temp);

*/

if(!isset($tempCourse[0])){
  $tempCourse = array();
  $temp = array('curriculum_year'=>$request->input('year'),'semester'=>$request->input('semester'));
  $tempCourse[0] = $temp;
}



      $yearInfo  = Offered_Courses::select('curriculum_year')
      ->groupBy('curriculum_year')
    ->get();
return view('approveGrade.index' , ['courses' => $temp,'yearInfo' => $yearInfo]);
  }




  public function testPage(Request $request){

    $year  = Offered_Courses::select('curriculum_year')
              ->orderBy('curriculum_year','desc')
              ->first();
    $courses  = Offered_Courses::leftJoin('grades','grades.open_course_id','=','offered_courses.open_course_id')
          ->Join('curriculums','offered_courses.course_id','=','curriculums.course_id')
          ->Join('academic_year','offered_courses.classroom_id','=','academic_year.classroom_id')
          ->leftJoin('data_status','grades.data_status','=','data_status.data_status')

          ->groupBy('grades.datetime','grades.open_course_id')
          ->select('offered_courses.open_course_id','offered_courses.curriculum_year','offered_courses.course_id','academic_year.grade_level','grades.quater'
                  ,'curriculums.course_name','offered_courses.semester','data_status.data_status_text')
          ->orderBy('course_id','asc')
          ->orderBy('datetime','desc')
          ->get();

          $check = 0;
          $count = 0;
          $saveUnset = array();
          foreach ($courses as $course){
            if($course->is_elective === 1){
              $course->grade_level = "";
            }
            if(!isset($course->data_status_text)){
              $course->quater = "Not submitted";
            }

            if($check !== $course->open_course_id){
              $check = $course->open_course_id;
            }
            else{
             //unset($courses[$count]);
             array_unshift($saveUnset,$count);
             //$saveUnset[] = $count;
              $count += 1;
              continue;
            }

            if($course->data_status_text === "Canceled"){
              //unset($courses[$count]);
              array_unshift($saveUnset,$count);
              //$saveUnset[] = $count;
              $count += 1;
              continue;
            }
            $count += 1;
          }

          $tempCourse = $courses->all();
          foreach($saveUnset as $num){
            unset($tempCourse[$num]);
          }
          $tempCourse = array_values($tempCourse);
          /*
          $saveUnset = array_unique($saveUnset);
          $temp = $courses->all();


          unset($temp[0]);
          $temp = array_values($temp);

      */

          if(!isset($tempCourse[0])){
            $tempCourse = array();
            $temp = array('curriculum_year'=>$request->input('year'),'semester'=>$request->input('semester'));
            $tempCourse[0] = $temp;
          }



                $yearInfo  = Offered_Courses::select('curriculum_year')
                ->groupBy('curriculum_year')
              ->get();
          return view('approveGrade.index' , ['courses' => $temp,'yearInfo' => $yearInfo]);


    /*
      $yearInfo  = Offered_Courses::select('curriculum_year')
                ->get();
      $courses = [];
      return view('approveGrade.index' , ['courses' => $courses,'yearInfo' => $yearInfo]);
      */
  }


  public function getApprovePage(Request $request){

    $year  = Offered_Courses::select('curriculum_year')
              ->orderBy('curriculum_year','desc')
              ->first();
    $courses  = Offered_Courses::leftJoin('grades','grades.open_course_id','=','offered_courses.open_course_id')
          ->Join('curriculums','offered_courses.course_id','=','curriculums.course_id')
          ->Join('academic_year','offered_courses.classroom_id','=','academic_year.classroom_id')
          ->leftJoin('data_status','grades.data_status','=','data_status.data_status')
          ->where('offered_courses.curriculum_year', $year->curriculum_year)
          ->where('offered_courses.semester', 3)
          ->groupBy('grades.datetime','grades.open_course_id')
          ->select('offered_courses.open_course_id','offered_courses.curriculum_year','offered_courses.course_id','academic_year.grade_level','grades.quater'
                  ,'curriculums.course_name','data_status.data_status_text','grades.datetime','offered_courses.semester','offered_courses.is_elective')
          ->orderBy('course_id','asc')
          ->orderBy('datetime','desc')
          ->get();

          $check = 0;
          $count = 0;
          $saveUnset = array();
          foreach ($courses as $course){
            if($course->is_elective === 1){
              $course->grade_level = "";
            }
            if(!isset($course->data_status_text)){
              $course->quater = "Not submitted";
            }

            if($check !== $course->open_course_id){
              $check = $course->open_course_id;
            }
            else{
             //unset($courses[$count]);
             array_unshift($saveUnset,$count);
             //$saveUnset[] = $count;
              $count += 1;
              continue;
            }

            if($course->data_status_text === "Canceled"){
              //unset($courses[$count]);
              array_unshift($saveUnset,$count);
              //$saveUnset[] = $count;
              $count += 1;
              continue;
            }
            $count += 1;
          }

          $tempCourse = $courses->all();
          foreach($saveUnset as $num){
            unset($tempCourse[$num]);
          }
          $tempCourse = array_values($tempCourse);
          /*
          $saveUnset = array_unique($saveUnset);
          $temp = $courses->all();


          unset($temp[0]);
          $temp = array_values($temp);

      */

          if(!isset($tempCourse[0])){
            $tempCourse = array();
            $temp = array('curriculum_year'=>$request->input('year'),'semester'=>$request->input('semester'));
            $tempCourse[0] = $temp;
          }



                $yearInfo  = Offered_Courses::select('curriculum_year')
                ->groupBy('curriculum_year')
              ->get();
          return view('approveGrade.index' , ['courses' => $tempCourse,'yearInfo' => $yearInfo]);


    /*
      $yearInfo  = Offered_Courses::select('curriculum_year')
                ->get();
      $courses = [];
      return view('approveGrade.index' , ['courses' => $courses,'yearInfo' => $yearInfo]);
      */
  }

  public function postApprovePage(Request $request){

    $courses  = Offered_Courses::leftJoin('grades','grades.open_course_id','=','offered_courses.open_course_id')
          ->Join('curriculums','offered_courses.course_id','=','curriculums.course_id')
          ->Join('academic_year','offered_courses.classroom_id','=','academic_year.classroom_id')
          ->leftJoin('data_status','grades.data_status','=','data_status.data_status')
          ->where('offered_courses.curriculum_year', $request->input('year'))
          ->where('offered_courses.semester',$request->input('semester'))
          ->groupBy('grades.datetime','grades.open_course_id')
          ->select('offered_courses.open_course_id','offered_courses.curriculum_year','offered_courses.course_id','academic_year.grade_level','grades.grade','grades.quater'
                  ,'curriculums.course_name','data_status.data_status_text','grades.datetime','grades.semester','offered_courses.is_elective')
          ->orderBy('course_id','asc')
          ->orderBy('datetime','desc')
          ->get();

    $check = 0;
    $count = 0;
    $saveUnset = array();
    foreach ($courses as $course){
      if($course->is_elective === 1){
        $course->grade_level = "";
      }
      if(!isset($course->data_status_text)){
        $course->quater = "Not submitted";
      }

      if($check !== $course->open_course_id){
        $check = $course->open_course_id;
      }
      else{
       //unset($courses[$count]);
       array_unshift($saveUnset,$count);
       //$saveUnset[] = $count;
        $count += 1;
        continue;
      }

      if($course->data_status_text === "Canceled"){
        //unset($courses[$count]);
        array_unshift($saveUnset,$count);
        //$saveUnset[] = $count;
        $count += 1;
        continue;
      }
      $count += 1;
    }

    $tempCourse = $courses->all();
    foreach($saveUnset as $num){
      unset($tempCourse[$num]);
    }
    $tempCourse = array_values($tempCourse);
    /*
    $saveUnset = array_unique($saveUnset);
    $temp = $courses->all();


    unset($temp[0]);
    $temp = array_values($temp);

*/

    if(!isset($tempCourse[0])){
      $tempCourse = array();
      $temp = array('curriculum_year'=>$request->input('year'),'semester'=>$request->input('semester'));
      $tempCourse[0] = $temp;
    }



          $yearInfo  = Offered_Courses::select('curriculum_year')
          ->groupBy('curriculum_year')
        ->get();
    return view('approveGrade.index' , ['courses' => $tempCourse,'yearInfo' => $yearInfo]);
  }

  public function acceptAll(Request $request){
    $year = $request->input('year');
    $semseter = $request->input('semester');

    $grade  = Grade::where('academic_year',$year)
                  ->where('semester',$semseter)
                  ->update(['data_status' => 1]);


    $yearInfo  = Offered_Courses::select('curriculum_year')
      ->groupBy('curriculum_year')
      ->get();

    $redi  = "approveGrade/2559/3";
    return redirect($redi);
  }

  public function accept(Request $request){
    $openID = $request->input('open_course_id');
    $quater = $request->input('quater');
    $datetime = $request->input('datetime');


    $grade  = Grade::where('open_course_id',$openID)
                  ->where('quater',$quater)
                  ->where('datetime',$datetime)
                  ->update(['data_status' => 1]);


    $yearInfo  = Offered_Courses::select('curriculum_year')
      ->groupBy('curriculum_year')
    ->get();

    $redi  = "approveGrade/2559/3";
    return redirect($redi);
  }

  public function cancelAll(Request $request){
    $year = $request->input('year');
    $semseter = $request->input('semester');

    $grade  = Grade::where('academic_year',$year)
                  ->where('semester',$semseter)
                  ->update(['data_status' => 2]);


    $yearInfo  = Offered_Courses::select('curriculum_year')
      ->groupBy('curriculum_year')
      ->get();

    $redi  = "approveGrade/2559/3";
    return redirect($redi);
  }

  public function cancel(Request $request){
    $openID = $request->input('open_course_id');
    $quater = $request->input('quater');
    $datetime = $request->input('datetime');


    $grade  = Grade::where('open_course_id',$openID)
                  ->where('quater',$quater)
                  ->where('datetime',$datetime)
                  ->update(['data_status' => 2]);


    $yearInfo  = Offered_Courses::select('curriculum_year')
      ->groupBy('curriculum_year')
    ->get();

    $redi  = "approveGrade/2559/3";
    return redirect($redi);
  }


}
