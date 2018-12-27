<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Curriculum;
use App\Room;
use App\Http\Controllers\DB;
use App\Grade;

class ViewGradeController extends Controller
{
  public function index(){


    return view('grade.list');

  }



  public function result(Request $request)
  {
      dd($request);
      if($request->input('year') == "chooseYear"){
        $curriculums = Curriculum::all();
      }

      return view('grade.index' , ['curriculums' => $curriculums]);
  }



  public function api(Request $request){
    $filter= $_GET['filter'];
    $datas = self::getData($filter);
    return $datas;
  }

  public function getData($filter){
    $age1 = array("name"=>"Boom", "age"=>"10" , 'total' => 'Anaphat');
    $age2 = array("name"=>"Boom2", "age"=>"12" , 'total' => 'Insuwan');
    $age3 = array("name"=>"Boom3", "age"=>"13", 'total' => 'Kemika');






    $datas =array($age1,$age2,$age3);
    $realData = array();
    foreach ($datas as $data) {
      $check = true;
      foreach ($data as $key => $value) {
        $strCheck = $filter[$key];
        array_push($datas,['KEYS' => $strCheck]);

        if($strCheck){
          array_push($datas,['ME KEY' => $strCheck , 'VALUE' => $value,'BOOLEAN' => strpos('Boom2', 'Bo') !== false]);
            if( strpos( $value, $strCheck ) !== false ){
                    array_push($datas,['CHECK_STR' => $strCheck,'DATA',$data]);
            }
            else{
              $check = false;
              break;

            }
        }
      }
      if($check && !in_array($realData,$data)){
        array_push($realData,$data);
      }
    }
    $grades = Grade::join('offered_courses','grades.open_course_id','offered_courses.open_course_id')
    ->select('offered_courses.*','grades.*')
    ->get();


    $array = array();
    for($i = 0 ; $i < 5 ; $i++){
      array_push($array,$grades[$i]);
    }

    $grades = $grades->toArray();
    $actualData = array("data"=>$array,'itemsCount'=>10 ,'pageSize'=>10 , 'pageIndex'=>1,'pageButtonCount' => 5);




    // array_push($actualData,['GRADE' => $grades[0],'REALDATA' => $realData[0]]);

    return $actualData;

  }


  public function api2(Request $request){
    $grades = Grade::join('offered_courses','grades.open_course_id','offered_courses.open_course_id')
    ->select('offered_courses.*','grades.*')
    ->get();

    $gradesx = $grades->toArray();
    foreach ($gradesx as $x) {
      $x = '1';
      // code...
    }

    dd($grades[0],$gradesx[0]);


    //
    // $age1 = array("Name"=>"My1", "Age"=>"10");
    // $age2 = array("Name"=>"My2", "Age"=>"12");
    // $age3 = array("Name"=>"My3", "Age"=>"13");
    // $data =array($age1,$age2,$age3);
    // $data2 = array("data"=>$data,'itemsCount'=>3);
    // return $data2;

  }
}
