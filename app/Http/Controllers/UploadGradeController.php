<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Excel;

class UploadGradeController extends Controller
{
  public function index(){
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
