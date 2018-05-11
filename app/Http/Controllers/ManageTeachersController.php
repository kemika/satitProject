<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManageTeachersController extends Controller
{
  public function index(){
    return view('manageTeachers.index');
  }
}
