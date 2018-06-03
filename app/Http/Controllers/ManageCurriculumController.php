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
    $curricula  = Curriculum::orderBy('year', 'asc')->get();
    return view('manageCurriculum.index' , ['curricula' => $curricula]);
  }




  public function editSubject(Request $request) // edit subject
  {
      //
      $subject  = Subject::where('id', $request->input('id'))->first();
      $redi  = "manageCurriculum/".$request->input('year');

      if($subject === null){
        return redirect($redi);
      }
      $subject->code = $request->input('code');
      $subject->name = $request->input('name');
      $subject->min = $request->input('min');
      $subject->max = $request->input('max');
      $subject->status = $request->input('status');
      $subject->save();
      return redirect($redi);
  }

  public function createNewYear(Request $request)
  {
      //

      $curriculum  = new Curriculum;
      $curriculum->year = $request->input('year');
      $curriculum->adjust = 0;
      $curriculum->save();


      $redi  = "manageCurriculum/".$request->input('year');
      return redirect($redi);
  }

  public function importFromPrevious(Request $request)
  {
      //
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
      }

      $redi  = "manageCurriculum/".$request->input('year');
      return redirect($redi);

  }

  public function createNewSubject(Request $request)
  {


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


      $redi  = "manageCurriculum/".$request->input('year');
      return redirect($redi);
  }

  public function editWithYear($year,Request $request)
  {
      //


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
        /*
        $curricula  = Curriculum::where('year', $year)->where('adjust',1)
                      ->join('subjects','curriculums.id','=','subjects.curriculum_id')
                      ->select('curriculums.year','subjects.id','curriculums.adjust','subjects.curriculum_id','subjects.code','subjects.name','subjects.min'
                      ,'subjects.max','subjects.status')
                      ->get();*/
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
      }/*
      $curricula  = Curriculum::where('year', $year)->where('adjust',0)
                    ->join('subjects','curriculums.id','=','subjects.curriculum_id')
                    ->select('curriculums.year','subjects.id','curriculums.adjust','subjects.curriculum_id','subjects.code','subjects.name','subjects.min'
                    ,'subjects.max','subjects.status')
                    ->get();*/
      return view('manageCurriculum.curriculumTable' , ['curricula' => $curricula]);
  }
}
