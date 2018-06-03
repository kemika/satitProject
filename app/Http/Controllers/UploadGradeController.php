<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Excel;

class UploadGradeController extends Controller
{
  public function index(){
    $subjects = Subject::all();
    $teachings = Teaching::all();
    $gpas  = GPA::all();
    $teacher_number = Auth::user()->teacher_number;

    //subject_details = Subject::where('teacher_number')

    return view('uploadGrade.index');
  }

  public function exportExcel($type)
  {
    Excel::create('Laravel Excel', function($excel) {

      $excel->sheet('Excel sheet', function($sheet) {

      $sheet->setOrientation('landscape');

      });

    })->export($type);
  }


}
