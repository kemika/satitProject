<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Excel;
use Auth;
use App\Homeroom;
use App\Teacher;
use App\Student;
use App\Academic_Year;
use App\Offered_Courses;


use App\Curriculum;
use App\Student_Grade_Level;


class ExportController extends Controller
{
  public function index(){
    $id =  auth::user()->teacher_number;
    $teacher = Teacher::where('teacher_id',$id)->select('teachers.*')->get()[0];



    if($teacher->teacher_status == 0){
      $academicYear = Homeroom::where('teacher_id',$teacher->teacher_id)
      ->select('homeroom.*')
      ->join('academic_year','academic_year.classroom_id','=','homeroom.classroom_id')
      ->select('homeroom.*','academic_year.*')
      ->get()[0];





      // Assian Artibute

      $classroom_id = $academicYear->classroom_id;

      $students = Student_Grade_Level::where('student_grade_levels.classroom_id',$classroom_id)
      ->select('student_grade_levels.*')
      ->join('students','students.student_id','student_grade_levels.student_id')
      ->select('student_grade_levels.*','students.*')
      ->get();





      $subjects = Offered_Courses::where('classroom_id', $classroom_id)
      ->where('Offered_Courses.is_elective',  '1')
      ->select('Offered_Courses.*')
      ->where('Offered_Courses.curriculum_year',$academicYear->curriculum_year)
      ->select('Offered_Courses.*')
      ->join('Curriculums', function($j) {
      $j->on('Curriculums.course_id', '=', 'Offered_Courses.course_id');
      $j->on('Curriculums.curriculum_year','=','Offered_Courses.curriculum_year');
      })
      ->select('Offered_Courses.*','Curriculums.*')
      ->get();



      $subjectElecs = Offered_Courses::where('classroom_id', $classroom_id)
      ->where('Offered_Courses.is_elective',  '0')
      ->select('Offered_Courses.*')
      ->where('Offered_Courses.curriculum_year',$academicYear->curriculum_year)
      ->select('Offered_Courses.*')
      ->join('Curriculums', function($j) {
      $j->on('Curriculums.course_id', '=', 'Offered_Courses.course_id');
      $j->on('Curriculums.curriculum_year','=','Offered_Courses.curriculum_year');
      })
      ->select('Offered_Courses.*','Curriculums.*')
      ->get();













      return view('export.index',['teacher' => $teacher,'subjects' => $subjects,'subjectElecs'=> $subjectElecs]);











      // dd("CLASS ROOM ID : ".$academicYear->classroom_id,"GRADE LEVEL : ".$academicYear->grade_level,"ROOM : ".$academicYear->room);

    }

    return 'Permission Denine';


  }


  public function exportExcel($classroom_id,$course_id,$curriculum_year)
{


  $subject = Offered_Courses::where('classroom_id', $classroom_id)
  ->where('Offered_Courses.course_id',  $course_id)
  ->select('Offered_Courses.*')
  ->where('Offered_Courses.curriculum_year',$curriculum_year)
  ->select('Offered_Courses.*')
  ->join('Curriculums', function($j) {
  $j->on('Curriculums.course_id', '=', 'Offered_Courses.course_id');
  $j->on('Curriculums.curriculum_year','=','Offered_Courses.curriculum_year');
  })
  ->select('Offered_Courses.*','Curriculums.*')
  ->get()[0];



  $academic_year = Academic_Year::where('classroom_id',$classroom_id)->where('curriculum_year',$curriculum_year)->select('Academic_Year.*')->get()[0];




  $students = Student_Grade_Level::where('classroom_id',$classroom_id)
  ->select('student_grade_levels.*')
  ->join('students','students.student_id','student_grade_levels.student_id')
  ->select('student_grade_levels.*','students.*')
  ->get();




  $room = Academic_Year::where('classroom_id',$classroom_id)
  ->where('curriculum_year',$curriculum_year)
  ->select('academic_year.*')
  ->get()[0];








  $type='xlsx';
  Excel::create($subject->course_name."-".$academic_year->academic_year, function($excel) use($subject,$students,$room,$academic_year) {

    $excel->sheet('Excel sheet', function($sheet) use($subject,$students,$room,$academic_year) {

      $sheet->setOrientation('landscape');

      $sheet->setCellValue('A1', 'Teacher');
      $sheet->setCellValue('A2', 'Course ID');
      $sheet->setCellValue('A3', 'Grade level');
      $sheet->setCellValue('A4', 'Academic Year');
      $sheet->setCellValue('A6', 'Student_ID');
      $sheet->setCellValue('B5', 'If you split a class…');
      $sheet->setCellValue('B6', 'Student Name');
      $sheet->setCellValue('C5', '1st Semester');
      $sheet->setCellValue('C6', 'Q1');
      $sheet->setCellValue('D1', 'Do not worry about any calculations. The report cards will do them');
      $sheet->setCellValue('D2', 'automatically. You are only required to fill in the highlighted sections.');
      $sheet->setCellValue('D3', 'High school teachers, hover here for a special note');
      $sheet->setCellValue('D6', 'Q2');
      $sheet->setCellValue('E6', 'Sum 1');
      $sheet->setCellValue('F6', 'Sem 1');
      $sheet->setCellValue('G5', '2nd Semester');
      $sheet->setCellValue('G6', 'Q3');
      $sheet->setCellValue('H6', 'Q4');
      $sheet->setCellValue('I6', 'Sum 2');
      $sheet->setCellValue('J6', 'Sem 2');
      $sheet->setCellValue('K5', 'Grade');
      $sheet->setCellValue('K6', 'Average');
      $sheet->setCellValue('L5', 'Year');
      $sheet->setCellValue('L6', 'Grade');

      $sheet->setWidth(array(
          'A' => 12,
          'B' => 19,
          'M' => 9,
          'C' => 12,
          'D' => 12,
          'E' => 12,
          'G' => 12,
          'H' => 12,
          'I' => 12


      ));

      $sheet->setStyle(array(
          'font' => array(
              'name'      =>  'Tw Cen MT',
              'size'      =>  12,
              'bold'      =>  false
          )
      ));

      $sheet->cell('B1', function($cell) {
          $cell->setBackground('#FFC300');
      });

      $sheet->cell('B5', function($cell) {
          $cell->setBackground('#FF9F68');
      });

      $sheet->cell('D3:H3', function($cell) {
          $cell->setBackground('#FF9F68');
      });


      $sheet->setBorder('C5:L6', 'thin');

      $sheet->mergeCells('C5:E5');
      $sheet->cell('C5:E5', function($cell) {
          $cell->setAlignment('center');
      });

      $sheet->mergeCells('G5:I5');
      $sheet->cell('G5:I5', function($cell) {
          $cell->setAlignment('center');
      });
      ////////////////////////////Student/////////////////////////////

      $i=7;
      foreach ($students as $student) {

        $sheet->cells('A'.$i, function($cells) {
          $cells->setAlignment('center');
          $cells->setValignment('center');
        });
          $sheet->setCellValue('A'.$i, $student->student_id);
          $sheet->setCellValue('B'.$i, $student->firstname." ".$student->lastname);
          $i+=1;

      }

      ///////////////////////Subject////////////////////////


        $sheet->setCellValue('B3', $room->grade_level.'/'.$room->room);
        $sheet->setCellValue('B2', strtoupper(substr($subject->course_name, 0, 3))." ".$subject->course_id);
        $sheet->setCellValue('C2', $subject->course_name);
        $sheet->setCellValue('B4', $academic_year->academic_year);






    });

  })->export($type);

}





    //
}
