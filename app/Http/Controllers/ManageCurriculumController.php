<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Curriculum;
use App\Subject;
class ManageCurriculumController extends Controller
{
  public function index(){
  //  $curricula  = Curriculum::all();
  //  $curricula  = DB::table('Curricula')->select('year',DB::raw('count(*) as total'))->groupBy('year')->get();
    $curricula  = Curriculum::orderBy('curriculum_year', 'asc')->groupBy('curriculum_year')->get();
    return view('manageCurriculum.index' , ['curricula' => $curricula]);
  }




  public function editSubject(Request $request) // edit subject
  {
      //
      $curriculum  = Curriculum::where('curriculum_year', $request->input('year'))
                          ->where('course_id', $request->input('old_course_id'))
                          ->update(array(
                              'course_id' => $request->input('course_id'),
                              'course_name' => $request->input('name'),
                              'min_grade_level' => $request->input('min'),
                              'max_grade_level' => $request->input('max'),
                              'is_activity' => $request->input('activity')
                              ));

      $redi  = "manageCurriculum/".$request->input('year');
/*
      if($curriculum === null){
        return redirect($redi);
      }
      $curriculum->course_id = $request->input('course_id');
      $curriculum->course_name = $request->input('name');
      $curriculum->min_grade_level = $request->input('min');
      $curriculum->max_grade_level = $request->input('max');
      $curriculum->is_activity = $request->input('activity');
      $curriculum->save();*/
      return redirect($redi);
  }

/*
  public function editSubject(Request $request) // edit subject
  {
      //
      $subject  = Subject::where('id', $request->input('id'))->first();
      $redi  = "manageCurriculum/".$request->input('year');
      $cur  = Curriculum::where('id', $request->input('cur_id'))->first();
      if($cur->adjust === 0){ // if is not adjust page, it will create adjust year and import subject from not adjust
        $checkExistAdjsut = Curriculum::where('year', $cur->year)->where('adjust',1)->first();
        if($checkExistAdjsut === null){ // create adjust year and import data
          $newAdjustYear = new Curriculum;
          $newAdjustYear->year = $request->input('year');
          $newAdjustYear->adjust = 1;
          $newAdjustYear->save();

          $subs = Subject::where('curriculum_id',$cur->id)->get();
          foreach ($subs as $sub){
            $addSub = new Subject;
            if($sub->id === $subject->id){
              $addSub->code = $request->input('code');
              $addSub->name = $request->input('name');
              $addSub->min = $request->input('min');
              $addSub->max = $request->input('max');
              $addSub->status = $request->input('status');

              $addSub->credit = $sub->credit;
              $addSub->elective = $sub->elective;
              $addSub->semester = $sub->semester;
              $addSub->curriculum_id = $newAdjustYear->id;
            }
            else{
              $addSub->code = $sub->code;
              $addSub->name = $sub->name;
              $addSub->min = $sub->min;
              $addSub->max = $sub->max;
              $addSub->credit = $sub->credit;
              $addSub->status = $sub->status;
              $addSub->elective = $sub->elective;
              $addSub->semester = $sub->semester;
              $addSub->curriculum_id = $newAdjustYear->id;
            }
            $addSub->save();
          }
          $redi  = "manageCurriculum/ปรับปรุง".$request->input('year');
          return redirect($redi);
        }
      }

      if($subject === null){
        return redirect($redi);
      }
      $subject->code = $request->input('code');
      $subject->name = $request->input('name');
      $subject->min = $request->input('min');
      $subject->max = $request->input('max');
      $subject->status = $request->input('status');
      $subject->save();
      if($cur->adjust === 1){
        $redi  = "manageCurriculum/ปรับปรุง".$request->input('year');
      }
      return redirect($redi);
  }
  */

  public function createNewYear(Request $request)
  {
      //

      $curriculum  = new Curriculum;
      $curriculum->curriculum_year = $request->input('year');
      $curriculum->course_id = "Create ".$request->input('year');
      $curriculum->course_name = "Create First Course";
      $curriculum->min_grade_level = "0";
      $curriculum->max_grade_level = "0";
      $curriculum->is_activity = "0";
      $curriculum->save();

      $redi  = "manageCurriculum/".$request->input('year');
      return redirect($redi);
  }

  public function importTest(Request $request)
  {


      $year_pre = ($request->input('year'))-1;
      $previous  = Curriculum::where('curriculum_year',$year_pre)
                      ->first();

      if($previous === null){
          return response()->json(['Status' => 'fail'], 200);
      }

      $subs = Curriculum::where('curriculum_year',$year_pre)->get();


      foreach ($subs as $sub){
        $re = $sub->replicate();
        $re->curriculum_year = $request->input('year');
        $temp = Curriculum::where('curriculum_year',$request->input('year'))
                ->where('course_id',$re->course_id)
                ->first();
        if($temp === null && $re->course_name !== 'Create First Course'){
          $re->save();
        }
      }


      return response()->json(['Status' => 'success'], 200);

  }

  public function importFromPrevious(Request $request)
  {
    dd($request->input('year'));
    return response()->json(['year' => $request->input('year')], 200);
      //
      /*
      $year_pre = ($request->input('year'))-1;
      $previous  = Curriculum::where('year',$year_pre)
                      ->where('adjust',1)
                      ->first();
      if($previous === null){
        $previous  = Curriculum::where('year',$year_pre)
                        ->where('adjust',0)
                        ->first();
        if($previous === null){
          $redi  = "manageCurriculum/";
          return redirect($redi);
        }
      }
      */

      $year_pre = ($request->input('year'))-1;
      $previous  = Curriculum::where('curriculum_year',$year_pre)
                      ->first();

      if($previous === null){
          $redi  = "manageCurriculum/";
          return redirect($redi);
      }


      $subs = Curriculum::where('curriculum_year',$year_pre)->get();



      foreach ($subs as $sub){
        $re = $sub->replicate();
        $re->curriculum_year = $request->input('year');
        $re->save();
      }


      /*
      $subs = Subject::where('curriculum_id',$previous->id)->get();
      $cur_id  = Curriculum::where('year',$request->input('year'))
                      ->first();
      foreach ($subs as $sub){
        $addSub = new Subject;
        $addSub->code = $sub->code;
        $addSub->name = $sub->name;
        $addSub->min = $sub->min;
        $addSub->max = $sub->max;
        $addSub->credit = $sub->credit;
        $addSub->status = $sub->status;
        $addSub->elective = $sub->elective;
        $addSub->semester = $sub->semester;
        $addSub->curriculum_id = $cur_id->id;
        $addSub->save();

      }*/

      $redi  = "manageCurriculum/".$request->input('year');
      return redirect($redi);

  }

  public function createNewSubject(Request $request)
  {

/*
      $subject  = new Subject;

      $subject->curriculum_id = $request->input('cur_id');
      $subject->code = $request->input('code');
      $subject->name = $request->input('name');
      $subject->min = $request->input('min');
      $subject->max = $request->input('max');
      $subject->status = $request->input('status');

      $subject->semester = 0;
      $subject->elective = 0;
      $subject->credit = 0;
      $subject->save();
*/

      $curriculum  = new Curriculum;
      $curriculum->curriculum_year = $request->input('year');
      $curriculum->course_id = $request->input('course_id');
      $curriculum->course_name = $request->input('name');
      $curriculum->min_grade_level = $request->input('min');
      $curriculum->max_grade_level = $request->input('max');
      $curriculum->is_activity = $request->input('activity');
      $curriculum->save();


      $redi  = "manageCurriculum/".$request->input('year');
      return redirect($redi);
  }

  public function editWithYear($year,Request $request)
  {
      //

/*
      if(strrpos($year,'ปรับปรุง') !== false ){
        $year = substr($year,-4);
        $curriculum  = Curriculum::where('year', $year)->where('adjust',1)->first();
        if($curriculum === null) {
          return redirect('manageCurriculum');
        }

        $curricula  = Curriculum::where('year', $year)->where('adjust',1)
                      ->join('subjects','curriculums.id','=','subjects.curriculum_id')
                      ->select('curriculums.year','subjects.id','curriculums.adjust','subjects.curriculum_id','subjects.code','subjects.name','subjects.min'
                      ,'subjects.max','subjects.status')
                      ->get();
        if(isset($curricula[0]) === false) {
          $curricula  = Curriculum::where('year', $year)->where('adjust',1)->get();
          $curricula[0]->curriculum_id = $curricula[0]->id;
          return view('manageCurriculum.curriculumTable' , ['curricula' => $curricula]);
        }

        return view('manageCurriculum.curriculumTable' , ['curricula' => $curricula]);
      }

      $curriculum  = Curriculum::where('year', $year)->where('adjust',0)->first();
      if($curriculum === null) {
        return redirect('manageCurriculum');
      }

      $curricula  = Curriculum::where('year', $year)->where('adjust',0)
                    ->join('subjects','curriculums.id','=','subjects.curriculum_id')
                    ->select('curriculums.year','subjects.id','curriculums.adjust','subjects.curriculum_id','subjects.code','subjects.name','subjects.min'
                    ,'subjects.max','subjects.status')
                    ->get();
      if(isset($curricula[0]) === false) {
        $curricula  = Curriculum::where('year', $year)->where('adjust',0)->get();
        $curricula[0]->curriculum_id = $curricula[0]->id;
        return view('manageCurriculum.curriculumTable' , ['curricula' => $curricula]);
      }
      return view('manageCurriculum.curriculumTable' , ['curricula' => $curricula]);
  }
  */
  $curriculum  = Curriculum::where('curriculum_year', $year)->first();
  if($curriculum === null) {
    return redirect('manageCurriculum');
  }
  $curricula  = Curriculum::where('curriculums.curriculum_year',$year)
              ->where('course_name','!=','Create First Course')
              ->get();
  if(isset($curricula[0]) === false) {
    /*
    $curricula  = Curriculum::where('curriculum_year', $year)->get();
    $curricula[0]->curriculum_year = $year;*/
    return view('manageCurriculum.curriculumTable' , ['curricula' => $curricula,'cur_year'=>$year]);
  }
  return view('manageCurriculum.curriculumTable' , ['curricula' => $curricula,'cur_year'=>$year]);
}
}
