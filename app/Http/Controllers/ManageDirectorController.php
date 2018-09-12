<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Information;

class ManageDirectorController extends Controller
{
  public function index(){
    $informations  = Information::first();

    return view('manageDirector.index' , ['informations' => $informations]);
  }

  public function update(Request $request)
  {
      //

      $information  = Information::first();
      //dd(directName1);
      $information->director_full_name = $request->input('inputName');

      $information->save();

      $informations  = Information::first();

      return view('manageDirector.index' , ['informations' => $informations]);
  }

}
