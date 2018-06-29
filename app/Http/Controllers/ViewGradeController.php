<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Curriculum;
use App\Room;

class ViewGradeController extends Controller
{
  public function index(){
    $curriculums = Curriculum::orderBy('year', 'desc')->get();
    $rooms = Room::all();
    
    return view('grade.index' , ['curriculums' => $curriculums, 'rooms' => $rooms]);

  }

  public function result(Request $request)
  {
      dd($request);
      if($request->input('year') == "chooseYear"){
        $curriculums = Curriculum::all();
      }

      return view('grade.index' , ['curriculums' => $curriculums]);
  }
}
