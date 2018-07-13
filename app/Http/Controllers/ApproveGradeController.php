<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Curriculum;
use App\Academic_Year;
use App\Grade;

class ApproveGradeController extends Controller
{
  public function index(){
  //  $curricula  = Curriculum::all();
  //  $curricula  = DB::table('Curricula')->select('year',DB::raw('count(*) as total'))->groupBy('year')->get();
    $grade  = Grade::where('academic_year', $request->input('year'))->get();

    $curriculum  = Curriculum::where('curriculum_year', $request->input('year'))->get();
    $courses  = offered_courses::where('curriculum_year', $request->input('year'))->get();
    return view('manageCurriculum.index' , ['curricula' => $curricula]);
  }
}
