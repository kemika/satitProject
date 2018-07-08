<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Excel;
use App\Subject;
use App\Teaching;
use App\GPA;
use Auth;
use App\Teacher;
use App\Student;
use App\Room;
use App\WaitApprove;
use Illuminate\Support\Facades\Input;

class UploadGradeController extends Controller
{
  public function index(){
    // $subjects = Subject::all();
    // $gpas  = GPA::all();
    // $teachings = Teaching::where('teacher_number','=',$teacher_number)
    // ->join('subjects','subjects.subj_number','=','teachings.subj_number')
    // ->select('teachings.*','subjects.name')->orderBy('year','semester', 'desc')
    // ->get();
    // $teacher = Teacher::where('number',$teacher_number)->select('teachers.*')->get();



    $teacher_number = Auth::user()->teacher_number;


    $teachings = Teaching::where('teachings.teacher_id',$teacher_number )
    ->join('subjects','subjects.id','=','teachings.subj_id')
    ->select('subjects.*')
    ->join('curriculums','curriculums.id','=','subjects.curriculum_id')
    ->select('subjects.*','curriculums.year','curriculums.adjust')
    // ->join('gpas','gpas.subj_id','=','subjects.id')
    ->get();

    // ->select('subjects.*','curriculums.year','curriculums.adjust')




    return view('uploadGrade.index',['teachings' => $teachings]);
  }

  public function exportExcel($type,$semester,$year,$grade,$room)
  {
    $subjects = Room::where('grade',$grade)
    ->where('room',$room)
    ->select('rooms.*')
    ->join('gpas','gpas.std_id','=','rooms.std_id')
    ->select('rooms.*','gpas.subj_id')
    ->join('subjects','subjects.id','=','gpas.subj_id')
    ->select('rooms.*','gpas.subj_id','subjects.*')
    ->join('curriculums','curriculums.id','=','subjects.curriculum_id')
    ->select('rooms.*','gpas.subj_id','subjects.*','curriculums.year')
    ->distinct('name')
    ->get();

    $students = Room::where('grade',$grade)
    ->where('room',$room)
    ->select('rooms.*')
    ->join('students','students.std_id','=','rooms.std_id')
    ->select('rooms.*','students.*')
    ->get();






    Excel::create('Laravel Excel', function($excel) use($subjects,$students) {

      $excel->sheet('Excel sheet', function($sheet) use($subjects,$students)  {

      $sheet->setOrientation('landscape');
      $sheet->cells('A1:A5', function($cells) {
      $cells->setValignment('center');
    // manipulate the range of cells

  });


      // $teacher_number = Auth::user()->teacher_number;


      // $teachings = Teaching::where('teacher_number','=',$teacher_number)
      // ->join('subjects','subjects.subj_number','=','teachings.subj_number')
      // ->select('teachings.*','subjects.name')
      // ->join('gpas', function($j) {
      // $j->on('gpas.subj_number', '=', 'teachings.subj_number');
      // $j->on('gpas.semester','=','teachings.semester');
      // $j->on('gpas.year','=','teachings.year');
      // })
      // ->select('teachings.*','subjects.name','gpas.std_number','gpas.score')
      // ->get();
      // $teacher = Teacher::where('number',$teacher_number)->select('teachers.*')->get();
      //
       // $sheet->getStyle("A1")->getAlignment()->setTextRotation(90);
       // $sheet->setSize('B2', 50, 50);
      $sheet->cell('A1', function($cell) {$cell->setValue('Quarter1');   });
      $sheet->cell('A2', function($cell) {$cell->setValue('Student ID');   });
      $sheet->cells('B2:Z2', function($cells) {
          $cells->setAlignment('center');
          $cells->setValignment('center');
        });
      $i = 0;
      foreach($students as $student){
        $sheet->cell(chr(66+$i)."2", function($cell) use($student) {$cell->setValue($student->std_id);   });
        $i+=1;
      }
      $sheet->cell(chr(66+$i)."2", function($cell) use($student) {$cell->setValue('     ');   });

      $sheet->cell('A3', function($cell) {$cell->setValue('Name');   });
      $i = 0;
      foreach($students as $student){
        $sheet->getStyle(chr(66+$i)."3")->getAlignment()->setTextRotation(90);
        $sheet->cell(chr(66+$i)."3", function($cell) use($student) {$cell->setValue($student->firstname." ".$student->lastname);   });


        $i+=1;

      }
      $sheet->getStyle(chr(66+$i)."3")->getAlignment()->setTextRotation(90);
      $sheet->cell(chr(66+$i)."3", function($cell) use($student) {$cell->setValue('Credit');   });
      $arrsubj = array();

      $i=4;

      foreach ($subjects as $subject) {
        if (!in_array($subject->name,$arrsubj)){
          array_push($arrsubj,$subject->name);

          $sheet->cell('A'.$i, function($cell) use($subject) {$cell->setValue($subject->name);   });
          $i+=1;

        }
      }

      // $sheet->cell('B3', function($cell) {$cell->setValue('อนพัทย์ อินทร์สุวรรณ');   });
      // $sheet->cell('C3', function($cell) {$cell->setValue('เขมิกา ธิติธันธวัฒ');   });
      // $sheet->cell('D3', function($cell) {$cell->setValue('ธิติ ตันติยานุกูลชัย');   });

      $i = 1;
         // if (!empty($teachings)) {
         //
         //     foreach ($teachings as $teaching) {
         //         $i+=1;
         //         $sheet->cell('A'.$i, "ajsdkljalksd");
                 // $sheet->cell('B'.$i, $teacher[0]->firstname.' '.$teacher[0]->lastname);
                 // $sheet->cell('C'.$i, $teaching->subj_number);
                 // $sheet->cell('D'.$i, $teaching->name);
                 // $sheet->cell('E'.$i, $teaching->semester);
                 // $sheet->cell('F'.$i, $teaching->year);
                 // $sheet->cell('G'.$i, $teaching->score);
         //     }
         // }

      });

    })->export($type);
  }


  public function show(Subject $subject){
    //
    //
    // $gpas = Teaching::where('teachings.id','=',$teaching->id)
    // ->join('subjects','subjects.subj_number','=','teachings.subj_number')
    // ->select('teachings.*','subjects.name')
    // ->join('gpas', function($j) {
    // $j->on('gpas.subj_number', '=', 'teachings.subj_number');
    // $j->on('gpas.semester','=','teachings.semester');
    // $j->on('gpas.year','=','teachings.year');
    // })
    // ->select('teachings.*','subjects.name','gpas.std_number','gpas.score')
    // ->join('students','students.number','=','gpas.std_number')
    // ->select('teachings.*','subjects.name','gpas.std_number','gpas.score','students.firstname','students.lastname')
    // ->get();
    // $students = Student::all();



    $rooms = Subject::where('subjects.id', $subject->id)
    ->select('subjects.*')
    ->join('gpas','gpas.subj_id','=','subjects.id')
    ->select('subjects.*','gpas.std_id','gpas.gpa')
    ->join('rooms','rooms.std_id','=','gpas.std_id')
    ->select('rooms.grade')
    ->distinct()
    ->get();



    $gpas = Subject::where('subjects.id', $subject->id)
    ->select('subjects.*')
    ->join('gpas','gpas.subj_id','=','subjects.id')
    ->select('subjects.*','gpas.std_id','gpas.gpa')
    ->join('rooms','rooms.std_id','=','gpas.std_id')
    ->select('subjects.*','gpas.std_id','gpas.gpa','rooms.grade','rooms.room')
    ->join('students','students.std_id','=','gpas.std_id')
    ->select('subjects.*','gpas.std_id','gpas.gpa','rooms.grade','rooms.room','students.firstname','students.lastname','gpas.std_id')
    ->join('curriculums','curriculums.id','=','subjects.curriculum_id')
    ->select('subjects.*','gpas.std_id','gpas.gpa','rooms.grade','rooms.room','students.firstname','students.lastname','gpas.std_id','curriculums.year','curriculums.adjust')
    ->get();






    return view('uploadGrade.show',['gpas' => $gpas,'rooms' => $rooms]);
  }







    public function showClass(Subject $subject){



      $rooms = Subject::where('subjects.id', $subject->id)
      ->select('subjects.*')
      ->join('gpas','gpas.subj_id','=','subjects.id')
      ->select('subjects.*','gpas.std_id','gpas.gpa')
      ->join('rooms','rooms.std_id','=','gpas.std_id')
      ->select('rooms.grade','rooms.room')
      ->distinct('rooms.grade','rooms.room')
      ->get();
      // dd(count($rooms),$rooms[0]->grade,$rooms[1]->grade,$rooms[2]->grade,$rooms[3]->grade,$rooms[4]->grade);



      $gpas = Subject::where('subjects.id', $subject->id)
      ->select('subjects.*')
      ->join('gpas','gpas.subj_id','=','subjects.id')
      ->select('subjects.*','gpas.std_id','gpas.gpa')
      ->join('rooms','rooms.std_id','=','gpas.std_id')
      ->select('subjects.*','gpas.std_id','gpas.gpa','rooms.grade','rooms.room')
      ->join('students','students.std_id','=','gpas.std_id')
      ->select('subjects.*','gpas.std_id','gpas.gpa','rooms.grade','rooms.room','students.firstname','students.lastname','gpas.std_id')
      ->join('curriculums','curriculums.id','=','subjects.curriculum_id')
      ->select('subjects.*','gpas.std_id','gpas.gpa','rooms.grade','rooms.room','students.firstname','students.lastname','gpas.std_id','curriculums.year','curriculums.adjust')
      ->get();






      return view('uploadGrade.boom',['gpas' => $gpas,'rooms' => $rooms]);

    }

    public function upload()
    {
        return view('uploadGrade.upload');
    }


    // public function import(Request $request)
    // {
    //   if($request->hasfFile('file')){
    //     $path = $request->file('file')->getRealpath();
    //     $data = Excel::load($path, function($reader){})->get();
    //       if (!empty($data) && $data->count()) {
    //         foreach($variable as $key => $value){
    //           $waitApprove = new WaitApprove();
    //           $waitApprove->name = $value->name;
    //           $waitApprove->email = $value->email;
    //           $waitApprove->save();
    //         }
    //       }
    //   }
    //   return back();
    //
    // }

    public function getUpload()
    {
      $file = Input::file('file');
      $file_name = $file->getClientOriginalName();
      $file->move('files/', $file_name);

      // $results = Excel::load('files/'.$file_name,function($reader){
      //   $reader->setHeaderRow(5);
      //   $reader->all();
      // })->get();

      $results = Excel::load('files/'.$file_name,function($reader){
      $reader->get(array('Course'));
    })->get();

      dd($results);
      // dd($results[0]->name);
      // echo count($results);
      // echo "<br>";
      // if(count($results)==0){
      //   echo "This file is empty";
      // }
      // else{
      //   for ($i = 0; $i < count($results); $i++) {
      //     echo "Name: ".$results[$i]->student_name;
      //     echo "<br>";
      //     echo "E-mail: ".$results[$i]->q1;
      //     echo "<br>";
      //   }
      // }



    // $name = sizeof($results[0]);//number of row in the Rooms sheet //example 2
    // $email = sizeof($results[1]);//number of row in the Room Equipment sheet //example 3
    // if($name == 0 && $email == 0){
    //     echo 'There are no data to input';
    //     self::$valid = false;
    // }


      return view('uploadGrade.getUpload', compact('results'));
    }




}
