<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Excel;
use App\Subject;
use App\Teaching;
use App\GPA;
use Auth;
use App\Teacher;
use App\Student;


class UploadGradeController extends Controller
{
  public function index(){
    $subjects = Subject::all();

    $gpas  = GPA::all();
    $teacher_number = Auth::user()->teacher_number;

    $teachings = Teaching::where('teacher_number','=',$teacher_number)
    ->join('subjects','subjects.subj_number','=','teachings.subj_number')
    ->select('teachings.*','subjects.name')->orderBy('year','semester', 'desc')

    ->get();
    $teacher = Teacher::where('number',$teacher_number)->select('teachers.*')->get();




    return view('uploadGrade.index',['teachings' => $teachings,'teacher' => $teacher]);
  }

  public function exportExcel($type)
  {
    Excel::create('Laravel Excel', function($excel) {

      $excel->sheet('Excel sheet', function($sheet) {

      $sheet->setOrientation('landscape');
      $teacher_number = Auth::user()->teacher_number;


      $teachings = Teaching::where('teacher_number','=',$teacher_number)
      ->join('subjects','subjects.subj_number','=','teachings.subj_number')
      ->select('teachings.*','subjects.name')
      ->join('gpas', function($j) {
      $j->on('gpas.subj_number', '=', 'teachings.subj_number');
      $j->on('gpas.semester','=','teachings.semester');
      $j->on('gpas.year','=','teachings.year');
      })
      ->select('teachings.*','subjects.name','gpas.std_number','gpas.score')
      ->get();
      $teacher = Teacher::where('number',$teacher_number)->select('teachers.*')->get();


      $sheet->cell('A1', function($cell) {$cell->setValue('Teacher Number');   });
      $sheet->cell('B1', function($cell) {$cell->setValue('Teacher Name');   });
      $sheet->cell('C1', function($cell) {$cell->setValue('Subject Number');   });
      $sheet->cell('D1', function($cell) {$cell->setValue('Subject Name');   });
      $sheet->cell('E1', function($cell) {$cell->setValue('Semester');   });
      $sheet->cell('F1', function($cell) {$cell->setValue('Year');   });
      $sheet->cell('G1', function($cell) {$cell->setValue('Grade');   });

      $i = 1;
         if (!empty($teachings)) {

             foreach ($teachings as $teaching) {
                 $i+=1;
                 $sheet->cell('A'.$i, $teaching->teacher_number);
                 $sheet->cell('B'.$i, $teacher[0]->firstname.' '.$teacher[0]->lastname);
                 $sheet->cell('C'.$i, $teaching->subj_number);
                 $sheet->cell('D'.$i, $teaching->name);
                 $sheet->cell('E'.$i, $teaching->semester);
                 $sheet->cell('F'.$i, $teaching->year);
                 $sheet->cell('G'.$i, $teaching->score);
             }
         }

      });

    })->export($type);
  }


  public function show(Teaching $teaching){


    $gpas = Teaching::where('teachings.id','=',$teaching->id)
    ->join('subjects','subjects.subj_number','=','teachings.subj_number')
    ->select('teachings.*','subjects.name')

    ->join('gpas', function($j) {
    $j->on('gpas.subj_number', '=', 'teachings.subj_number');
    $j->on('gpas.semester','=','teachings.semester');
    $j->on('gpas.year','=','teachings.year');
    })
    ->select('teachings.*','subjects.name','gpas.std_number','gpas.score')
    ->join('students','students.number','=','gpas.std_number')
    ->select('teachings.*','subjects.name','gpas.std_number','gpas.score','students.firstname','students.lastname')

    ->get();

    $students = Student::all();
    return view('uploadGrade.show',['students' => $students,'gpas' => $gpas]);
  }





}
