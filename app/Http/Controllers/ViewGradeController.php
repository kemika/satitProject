<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Curriculum;
use App\Room;
use App\Http\Controllers\DB;

class ViewGradeController extends Controller
{
  public function index(){



    $curriculums = Curriculum::orderBy('year')->get();


    $a = '!=';
    $b = '!=';
    $c = '!=';
    $d = '!=' ;


    // if($year != 0 ){
    //   $a = '=';
    // }
    // if($semester != 0 ){
    //   $b = '=';
    // }
    // if($grade != 0 ){
    //   $c = '=';
    // }
    // if($room != 0 ){
    //   $d = '=';
    // }

    $rooms = Room::all();




    return view('grade.index' , ['curriculums' => $curriculums, 'rooms' => $rooms ]);

  }

  public function view(Request $request){
    $year = Curriculum::orderBy('year')->get();
    $year = $request->input('year');
    // dd($year);
    // if ($year != 'chooseRoom') {
    //   $year2  = Curriculum::all()->where('year', $request->input('year'));
    // }

    return view('grade.index');
  }
}
