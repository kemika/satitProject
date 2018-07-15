<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;
use App\Student;

class ReportCardController extends Controller
{
  public function index(){
    $data = ['name' => 'My'];
    return view('reportCard.master', compact('data'));

  }

  public function exportPDF(){
    $data = ['name' => 'My'];
    $pdf = PDF::loadView('reportCard.form', compact('data'));
    $pdf->setPaper('a4', 'potrait');
    return $pdf->stream();
    // return $pdf->download('reportCard.pdf');

  }


}
