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
    $courses  = Academic_Year::Join('offered_courses','offered_courses.classroom_id','=','academic_year.classroom_id')
            ->Join('curriculums', function($join)
                         {
                             $join->on('curriculums.course_id', '=', 'offered_courses.course_id');
                             $join->on('curriculums.curriculum_year','=', 'offered_courses.curriculum_year');
                         })
            ->leftJoin('grades','grades.open_course_id','=','offered_courses.open_course_id')
            ->leftJoin('data_status','grades.data_status','=','data_status.data_status')
            ->where('offered_courses.curriculum_year', $year)
            ->where('offered_courses.semester',$semester)
            ->groupBy('grades.datetime','grades.open_course_id','grades.quater')
            ->select('offered_courses.open_course_id','academic_year.academic_year','offered_courses.course_id','academic_year.grade_level','grades.quater'
                    ,'curriculums.course_name','data_status.data_status_text','grades.datetime','offered_courses.semester','offered_courses.is_elective')
            ->orderBy('course_id','asc')
            ->orderBy('datetime','desc')
            ->get();

    /*

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
          ->get();*/

    $check = 0;
    $checkQuater = 0;
    $count = 0;
    $saveUnset = array();
    foreach ($courses as $course){
      if($course->is_elective === 1){
        $course->grade_level = "";
      }
      if(!isset($course->data_status_text)){
        $course->quater = "Not submitted";
      }

      if($check !== $course->open_course_id || $checkQuater !== $course->quater){
        $check = $course->open_course_id;
        $checkQuater = $course->quater;
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
    $temp = array('academic_year'=>$year,'semester'=>$semester);
    $tempCourse[0] = $temp;
    }



    $yearInfo  = Academic_Year::select('academic_year')
    ->groupBy('academic_year')
    ->get();
    return view('approveGrade.index' , ['courses' => $tempCourse,'yearInfo' => $yearInfo]);
  }




  public function testPage(Request $request){



    $courses  = Academic_Year::Join('offered_courses','offered_courses.classroom_id','=','academic_year.classroom_id')
            ->Join('curriculums', function($join)
                         {
                             $join->on('curriculums.course_id', '=', 'offered_courses.course_id');
                             $join->on('curriculums.curriculum_year','=', 'offered_courses.curriculum_year');
                         })
            ->leftJoin('grades','grades.open_course_id','=','offered_courses.open_course_id')
            ->leftJoin('data_status','grades.data_status','=','data_status.data_status')
            ->where('academic_year.academic_year',2559)
            ->groupBy('grades.datetime','grades.open_course_id','grades.quater')
            ->select('offered_courses.open_course_id','academic_year.academic_year','offered_courses.course_id','academic_year.grade_level','grades.quater'
                    ,'curriculums.course_name','data_status.data_status_text','grades.datetime','offered_courses.semester','offered_courses.is_elective')
            ->orderBy('course_id','asc')
            ->orderBy('datetime','desc')
            ->get();

/*
    $courses  = Offered_Courses::leftJoin('grades','grades.open_course_id','=','offered_courses.open_course_id')
          ->Join('curriculums','offered_courses.course_id','=','curriculums.course_id')
          ->Join('academic_year','offered_courses.classroom_id','=','academic_year.classroom_id')
          ->leftJoin('data_status','grades.data_status','=','data_status.data_status')

          ->groupBy('grades.datetime','grades.open_course_id')
          ->select('offered_courses.open_course_id','offered_courses.curriculum_year','offered_courses.course_id','academic_year.grade_level','grades.quater'
                  ,'curriculums.course_name','offered_courses.semester','data_status.data_status_text')
          ->orderBy('course_id','asc')
          ->orderBy('datetime','desc')
          ->get();*/

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
            $temp = array('academic_year'=>$request->input('year'),'semester'=>$request->input('semester'));
            $tempCourse[0] = $temp;
          }



                $yearInfo  = Academic_Year::select('academic_year')
                ->groupBy('academic_year')
                ->get();
          return view('approveGrade.index' , ['courses' => $tempCourse,'yearInfo' => $yearInfo]);


    /*
      $yearInfo  = Offered_Courses::select('curriculum_year')
                ->get();
      $courses = [];
      return view('approveGrade.index' , ['courses' => $courses,'yearInfo' => $yearInfo]);
      */
  }


  public function getApprovePage(Request $request){

    $year  = Academic_Year::select('academic_year')
              ->orderBy('academic_year','desc')
              ->first();

  $redi  = "approveGrade/".$year->academic_year."/3";
  return redirect($redi);
              $courses  = Academic_Year::Join('offered_courses','offered_courses.classroom_id','=','academic_year.classroom_id')
                      ->Join('curriculums', function($join)
                                   {
                                       $join->on('curriculums.course_id', '=', 'offered_courses.course_id');
                                       $join->on('curriculums.curriculum_year','=', 'offered_courses.curriculum_year');
                                   })
                      ->leftJoin('grades','grades.open_course_id','=','offered_courses.open_course_id')
                      ->leftJoin('data_status','grades.data_status','=','data_status.data_status')
                      ->where('academic_year.academic_year',$year->curriculum_year)
                      ->where('offered_courses.Semester',3)
                      ->groupBy('grades.datetime','grades.open_course_id')
                      ->select('offered_courses.open_course_id','academic_year.academic_year','offered_courses.course_id','academic_year.grade_level','grades.quater'
                              ,'curriculums.course_name','data_status.data_status_text','grades.datetime','offered_courses.semester','offered_courses.is_elective')
                      ->orderBy('course_id','asc')
                      ->orderBy('datetime','desc')
                      ->get();

              /*
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
        $temp = array('academic_year'=>$request->input('year'),'semester'=>$request->input('semester'));
        $tempCourse[0] = $temp;
      }



            $yearInfo  = Academic_Year::select('academic_year')
            ->groupBy('academic_year')
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


    $redi  = "approveGrade/".$request->input('year')."/".$request->input('semester');
    return redirect($redi);
    $courses  = Academic_Year::Join('offered_courses','offered_courses.classroom_id','=','academic_year.classroom_id')
            ->Join('curriculums', function($join)
                         {
                             $join->on('curriculums.course_id', '=', 'offered_courses.course_id');
                             $join->on('curriculums.curriculum_year','=', 'offered_courses.curriculum_year');
                         })
            ->leftJoin('grades','grades.open_course_id','=','offered_courses.open_course_id')
            ->leftJoin('data_status','grades.data_status','=','data_status.data_status')
            ->where('offered_courses.curriculum_year', $request->input('year'))
            ->where('offered_courses.semester',$request->input('semester'))
            ->groupBy('grades.datetime','grades.open_course_id')
            ->select('offered_courses.open_course_id','academic_year.academic_year','offered_courses.course_id','academic_year.grade_level','grades.quater'
                    ,'curriculums.course_name','data_status.data_status_text','grades.datetime','offered_courses.semester','offered_courses.is_elective')
            ->orderBy('course_id','asc')
            ->orderBy('datetime','desc')
            ->get();

    /*

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
          ->get();*/

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
  $temp = array('academic_year'=>$request->input('year'),'semester'=>$request->input('semester'));
  $tempCourse[0] = $temp;
}



$yearInfo  = Academic_Year::select('academic_year')
->groupBy('academic_year')
->get();
    return view('approveGrade.index' , ['courses' => $tempCourse,'yearInfo' => $yearInfo]);
  }

  public function acceptAll(Request $request){
    $year = $request->input('year');
    $semseter = $request->input('semester');
/*
    $grade  = Grade::where('academic_year',$year)
                  ->where('semester',$semseter)
                  ->where('data_status',0)
                  ->groupBy('datetime','open_course_id')
                  ->orderBy('datetime','desc')
                  ->update(['data_status' => 1]);
                  */

    $grade  = Grade::join('offered_courses','grades.open_course_id','=','offered_courses.open_course_id')
                  ->where('grades.academic_year',$year)
                  ->where('grades.semester',$semseter)
                  ->where('grades.data_status',0)
                  ->groupBy('grades.datetime','grades.open_course_id','grades.quater')
                  ->orderBy('offered_courses.course_id','asc')
                  ->orderBy('grades.datetime','desc')
                  ->select('grades.datetime','grades.open_course_id','grades.quater')
                  ->get();

    $check = 0;
    $checkQuater = 0;
    $count = 0;
    $saveUnset = array();
    foreach ($grade as $course){

      if($check !== $course->open_course_id || $checkQuater !== $course->quater){
        $check = $course->open_course_id;
        $checkQuater = $course->quater;
      }
      else{
       //unset($courses[$count]);
       array_unshift($saveUnset,$count);
       //$saveUnset[] = $count;
        $count += 1;
        continue;
      }
      $count += 1;
    }

    $tempCourse = $grade->all();
    foreach($saveUnset as $num){
      unset($tempCourse[$num]);
    }
    $tempCourse = array_values($tempCourse);

    foreach($tempCourse as $course){
      $grade  = Grade::where('open_course_id',$course->open_course_id)
                    ->where('quater',$course->quater)
                    ->where('datetime',$course->datetime)
                    ->where('data_status',0)
                    ->update(['data_status' => 1]);
    }



    $yearInfo  = Academic_Year::select('academic_year')
        ->groupBy('academic_year')
        ->get();

    $redi  = "approveGrade/".$request->input('year')."/".$request->input('semester');;
    return redirect($redi)->with('status', 'Approve!');
  }

  public function accept(Request $request){
    $openID = $request->input('open_course_id');
    $quater = $request->input('quater');
    $datetime = $request->input('datetime');


    $grade  = Grade::where('open_course_id',$openID)
                  ->where('quater',$quater)
                  ->where('datetime',$datetime)
                  ->where('data_status',0)
                  ->update(['data_status' => 1]);




    $redi  = "approveGrade/".$request->input('year')."/".$request->input('semester');;
    return redirect($redi)->with('status', 'Approve!');
  }

  public function cancelAll(Request $request){
    $year = $request->input('year');
    $semseter = $request->input('semester');


        $grade  = Grade::join('offered_courses','grades.open_course_id','=','offered_courses.open_course_id')
                      ->where('grades.academic_year',$year)
                      ->where('grades.semester',$semseter)
                      ->where('grades.data_status',0)
                      ->groupBy('grades.datetime','grades.open_course_id','grades.quater')
                      ->orderBy('offered_courses.course_id','asc')
                      ->orderBy('grades.datetime','desc')
                      ->select('grades.datetime','grades.open_course_id','grades.quater')
                      ->get();

       $check = 0;
       $checkQuater = 0;
       $count = 0;
       $saveUnset = array();
        foreach ($grade as $course){

          if($check !== $course->open_course_id || $checkQuater !== $course->quater){
            $check = $course->open_course_id;
            $checkQuater = $course->quater;
          }
          else{
           //unset($courses[$count]);
           array_unshift($saveUnset,$count);
           //$saveUnset[] = $count;
            $count += 1;
            continue;
          }
          $count += 1;
        }

        $tempCourse = $grade->all();
        foreach($saveUnset as $num){
          unset($tempCourse[$num]);
        }
        $tempCourse = array_values($tempCourse);

        foreach($tempCourse as $course){
          $grade  = Grade::where('open_course_id',$course->open_course_id)
                        ->where('quater',$course->quater)
                        ->where('datetime',$course->datetime)
                        ->where('data_status',0)
                        ->update(['data_status' => 2]);
        }



        $yearInfo  = Academic_Year::select('academic_year')
            ->groupBy('academic_year')
            ->get();

        $redi  = "approveGrade/".$request->input('year')."/".$request->input('semester');;
        return redirect($redi)->with('status', 'Cancel!');
  }

  public function cancel(Request $request){
    $openID = $request->input('open_course_id');
    $quater = $request->input('quater');
    $datetime = $request->input('datetime');


    $grade  = Grade::where('open_course_id',$openID)
                  ->where('quater',$quater)
                  ->where('datetime',$datetime)
                  ->where('data_status',0)
                  ->update(['data_status' => 2]);


    $redi  = "approveGrade/".$request->input('year')."/".$request->input('semester');;
    return redirect($redi)->with('status', 'Cancel!');
  }


}
