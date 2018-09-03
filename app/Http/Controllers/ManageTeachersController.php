<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Teacher;

class ManageTeachersController extends Controller
{
  public function index(){
    $teachers  = Teacher::join('teacher_status','teachers.teacher_status','=','teacher_status.teacher_status')
    ->select('teachers.teacher_id','teachers.firstname','teachers.lastname','teacher_status.teacher_status_text')
    ->orderBy('teachers.teacher_id','asc')
    ->get();
    //dd($teachers);

    return view('manageTeachers.index' , ['teachers' => $teachers]);
  }

  public function update(Request $request)
  {
      //

      $teacher  = Teacher::all()->where('teacher_id', $request->input('teacherID'))->first();
      $teacher->firstname=$request->input('firstname');
      $teacher->lastname=$request->input('lastname');
      $teacher->teacher_status=$request->input('status');

      $teacher->save();

      $teachers  = Teacher::join('teacher_status','teachers.teacher_status','=','teacher_status.teacher_status')
      ->select('teachers.teacher_id','teachers.firstname','teachers.lastname','teacher_status.teacher_status_text')
      ->orderBy('teachers.teacher_id','asc')
      ->get();
      return view('manageTeachers.index' , ['teachers' => $teachers]);
  }

}
