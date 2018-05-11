<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManageAcademicController extends Controller
{
  public function index(){
    return view('manageAcademic.index');
  }
}
