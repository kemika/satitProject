<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Teacher;

class ManageTeachersController extends Controller
{
  public function index(){
    $teachers  = Teacher::all();

    return view('manageTeachers.index' , ['teachers' => $teachers]);
  }

  public function update(Request $request)
  {
      //

      $teacher  = Teacher::all()->where('id', $request->input('id'))->first();
      $teacher->firstname=$request->input('firstname');
      $teacher->lastname=$request->input('lastname');
      $teacher->nationality=$request->input('nationality');
      $teacher->status=$request->input('status');



      $teacher->save();

      $teachers  = Teacher::all();
      return view('manageTeachers.index' , ['teachers' => $teachers]);
  }

}
