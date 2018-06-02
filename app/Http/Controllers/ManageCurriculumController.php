<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Curriculum;
class ManageCurriculumController extends Controller
{
  public function index(){
  //  $curricula  = Curriculum::all();
    $curricula  = DB::table('Curricula')->select('year',DB::raw('count(*) as total'))->groupBy('year')->get();
    return view('manageCurriculum.index' , ['curricula' => $curricula]);
  }


  public function edit(Request $request)
  {
      //

      $curricula  = Curriculum::all()->where('year', $request->input('year'));
      return view('manageCurriculum.curriculumTable' , ['curricula' => $curricula]);
  }

  public function editSubject(Request $request) // edit subject 
  {
      //

      $curriculum  = Curriculum::all()->where('id', $request->input('id'))->first();
      $redi  = "manageCurriculum/".$request->input('year');

      if($curriculum === null){
        return redirect($redi);
      }
      $curriculum->year = $request->input('year');
      $curriculum->code = $request->input('code');
      $curriculum->name = $request->input('name');
      $curriculum->min = $request->input('min');
      $curriculum->max = $request->input('max');
      $curriculum->status = $request->input('status');
      $curriculum->save();
      return redirect($redi);
  }

  public function createNewYear(Request $request)
  {
      //

      $curriculum  = new Curriculum;
      $curriculum->year = $request->input('year');
      $curriculum->save();


      $redi  = "manageCurriculum/".$request->input('year');
      return redirect($redi);
  }

  public function createNewSubject(Request $request)
  {
      //
      $curriculum  = Curriculum::all()->where('year', $request->input('year'))->first();
      if($curriculum->code !== "Z000") {
          $curriculum  = new Curriculum;
      }

      $curriculum->year = $request->input('year');
      $curriculum->code = $request->input('code');
      $curriculum->name = $request->input('name');
      $curriculum->min = $request->input('min');
      $curriculum->max = $request->input('max');
      $curriculum->status = $request->input('status');
      $curriculum->save();


      $redi  = "manageCurriculum/".$request->input('year');
      return redirect($redi);
  }

  public function editWithYear($year,Request $request)
  {
      //

      $exists  = Curriculum::all()->where('year', $year)->first();
      if($exists === null) {
        return redirect('manageCurriculum');
      }
      $curricula  = Curriculum::all()->where('year', $year);

      return view('manageCurriculum.curriculumTable' , ['curricula' => $curricula]);
  }
}
