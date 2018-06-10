<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Curriculum;
use App\Room;

class ViewGradeController extends Controller
{
  public function index(){
    $curriculums = Curriculum::all();
    $rooms = Room::all();

    return view('grade.index' , ['curriculums' => $curriculums, 'rooms' => $rooms]);

  }
}
