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
use App\Behavior_type;

use App\Curriculum;
use App\Student_Grade_Level;


class ExportController extends Controller
{
  public function __construct() {
    $this->middleware('auth');
  }


  public function index(){
    $academicYear = Academic_Year::groupBy('academic_year')->distinct('academic_year')->orderBy('academic_year')->get();
    $gradeLevel = Academic_Year::groupBy('grade_level')->distinct('grade_level')->orderBy('grade_level')->get();
    // dd(count($academicYear),count($gradeLevel));C
    return view('export.index',['academicYear' => $academicYear,'gradeLevel' => $gradeLevel]);


  }

  public function show($academic_year,$semester,$grade_level,$room){

      $academic_year = Academic_Year::where('academic_year',$academic_year)
      // ->where('semester',$semester)
      ->where('grade_level',$grade_level)
      ->where('room',$room)
      ->first();

      $classroom_id = $academic_year->classroom_id;

      $students = Student_Grade_Level::where('student_grade_levels.classroom_id',$classroom_id)
      ->select('student_grade_levels.*')
      ->join('students','students.student_id','student_grade_levels.student_id')
      ->select('student_grade_levels.*','students.*')
      ->get();

      $subjects = Offered_Courses::where('classroom_id', $classroom_id)
      ->where('offered_courses.is_elective',  '0')
      ->select('offered_courses.*')
      ->where('offered_courses.curriculum_year',$academic_year->curriculum_year)
      ->select('offered_courses.*')
      ->join('curriculums', function($j) {
      $j->on('curriculums.course_id', '=', 'offered_courses.course_id');
      $j->on('curriculums.curriculum_year','=','offered_courses.curriculum_year');
      })
      ->select('offered_courses.*','curriculums.*')
      ->get();


      $subjectElecs = Offered_Courses::where('classroom_id', $classroom_id)
      ->where('offered_courses.is_elective',  '1')
      ->select('offered_courses.*')
      ->where('offered_courses.curriculum_year',$academic_year->curriculum_year)
      ->select('offered_courses.*')
      ->join('curriculums', function($j) {
      $j->on('curriculums.course_id', '=', 'offered_courses.course_id');
      $j->on('curriculums.curriculum_year','=','offered_courses.curriculum_year');
      })
      ->select('offered_courses.*','curriculums.*')
      ->get();


      return view('export.show',['academic_year' => $academic_year,'subjects' => $subjects,'subjectElecs'=> $subjectElecs]);


  }





  public function exportExcel($classroom_id,$course_id,$curriculum_year){


  $subject = Offered_Courses::where('classroom_id', $classroom_id)
  ->where('offered_courses.course_id',  $course_id)
  ->select('offered_courses.*')
  ->where('offered_courses.curriculum_year',$curriculum_year)
  ->select('offered_courses.*')
  ->join('curriculums', function($j) {
  $j->on('curriculums.course_id', '=', 'offered_courses.course_id');
  $j->on('curriculums.curriculum_year','=','offered_courses.curriculum_year');
  })
  ->select('offered_courses.*','curriculums.*')
  ->get()[0];



  $academic_year = Academic_Year::where('classroom_id',$classroom_id)->where('curriculum_year',$curriculum_year)->select('academic_year.*')->get()[0];




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


public function exportElectiveCourseForm($classroom_id,$course_id,$curriculum_year){

  $subject = Offered_Courses::where('classroom_id', $classroom_id)
  ->where('offered_courses.course_id',  $course_id)
  ->select('offered_courses.*')
  ->where('offered_courses.curriculum_year',$curriculum_year)
  ->select('offered_courses.*')
  ->join('curriculums', function($j) {
  $j->on('curriculums.course_id', '=', 'offered_courses.course_id');
  $j->on('curriculums.curriculum_year','=','offered_courses.curriculum_year');
  })
  ->select('offered_courses.*','curriculums.*')
  ->get()[0];



    $academic_year = Academic_Year::where('classroom_id',$classroom_id)->where('curriculum_year',$curriculum_year)->select('academic_year.*')->get()[0];



  $type='xlsx';
  Excel::create('template', function($excel) use($subject,$academic_year) {

    $excel->sheet('Excel sheet', function($sheet) use($subject,$academic_year) {

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

      ///////////////////////Subject////////////////////////


        $sheet->setCellValue('B3', '');
        $sheet->setCellValue('B2', strtoupper(substr($subject->course_name, 0, 3))." ".$subject->course_id);
        $sheet->setCellValue('C2', $subject->course_name);
        $sheet->setCellValue('B4', $academic_year->academic_year);






    });

  })->export($type);

}

  public function exportHeight($classroom_id, $curriculum_year){
    $academic_year = Academic_Year::where('classroom_id',$classroom_id)->where('curriculum_year',$curriculum_year)->select('academic_year.*')->get()[0];


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
    Excel::create('HeightandWeight', function($excel) use($students,$room,$academic_year) {

      $excel->sheet('Excel sheet', function($sheet) use($students,$room,$academic_year) {

        $sheet->setOrientation('landscape');

        $sheet->setCellValue('A1', 'Academic Year');
        $sheet->setCellValue('A2', 'Grade Level');
        $sheet->setCellValue('A3', 'Room');
        $sheet->setCellValue('A4', 'Students ID');
        $sheet->setCellValue('B4', 'Students Name');
        $sheet->setCellValue('C4', 'S1 Height');
        $sheet->setCellValue('D4', 'S1 Weight');
        $sheet->setCellValue('E4', 'S2 Height');
        $sheet->setCellValue('F4', 'S2 Weight');

        $sheet->setCellValue('B1', $academic_year->academic_year);
        $sheet->setCellValue('B2', $academic_year->grade_level);
        $sheet->setCellValue('B3', $academic_year->room);
        ////////////////////////////Student/////////////////////////////

        $i=5;
        foreach ($students as $student) {

          $sheet->cells('A'.$i, function($cells) {
            $cells->setAlignment('center');
            $cells->setValignment('center');
          });
            $sheet->setCellValue('A'.$i, $student->student_id);
            $sheet->setCellValue('B'.$i, $student->firstname." ".$student->lastname);
            $i+=1;

        }


        $sheet->setWidth(array(
            'A' => 12,
            'B' => 25,
            'C' => 19
        ));

        $sheet->setStyle(array(
            'font' => array(
                'name'      =>  'Tw Cen MT',
                'size'      =>  12,
                'bold'      =>  false
            )
        ));

        $sheet->setBorder('A4:G4', 'thin');

        $sheet->cells('D4:G4', function($cells) {
            $cells->setAlignment('center');
            $cells->setValignment('center');
            $cells->setTextRotation(90);
          });

      });

    })->export($type);

  }

  public function exportComments($classroom_id, $curriculum_year){
    $academic_year = Academic_Year::where('classroom_id',$classroom_id)->where('curriculum_year',$curriculum_year)->select('academic_year.*')->get()[0];


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
    Excel::create('Comments', function($excel) use($students,$room,$academic_year) {

      $excel->sheet('Excel sheet', function($sheet) use($students,$room,$academic_year) {

        $sheet->setOrientation('landscape');

        ////////////////////////////Student/////////////////////////////

        $i=5;
        foreach ($students as $student) {

          $sheet->cells('A'.$i, function($cells) {
            $cells->setAlignment('center');
            $cells->setValignment('center');
          });
            $sheet->setCellValue('A'.$i, $student->student_id);
            $sheet->setCellValue('B'.$i, $student->firstname." ".$student->lastname);
            $i+=1;

        }

        $sheet->setCellValue('B1', $academic_year->academic_year);
        $sheet->setCellValue('B2', $academic_year->grade_level);
        $sheet->setCellValue('B3', $academic_year->room);

        $sheet->setCellValue('A1', 'Academic Year');
        $sheet->setCellValue('A2', 'Grade Level');
        $sheet->setCellValue('A3', 'Room');
        $sheet->setCellValue('A4', 'Students ID');
        $sheet->setCellValue('B4', 'Students Name');
        $sheet->setCellValue('C4', 'Quater 1');
        $sheet->setCellValue('D4', 'Quater 2');
        $sheet->setCellValue('E4', 'Quater 3');
        $sheet->setCellValue('F4', 'Quater 4');


        $sheet->setWidth(array(
            'A' => 12,
            'B' => 25,
            'C' => 50,
            'D' => 50,
            'E' => 50,
            'F' => 50
        ));

        $sheet->setStyle(array(
            'font' => array(
                'name'      =>  'Tw Cen MT',
                'size'      =>  12,
                'bold'      =>  false
            ),
            'borders' => [
                'allborders' => [
                    'color' => [
                        'rgb' => 'DBDBDB'
                    ]
                ]
            ]
        ));


      });

    })->export($type);

  }

  public function exportBehavior($classroom_id, $curriculum_year){
    $academic_year = Academic_Year::where('classroom_id',$classroom_id)->where('curriculum_year',$curriculum_year)->select('academic_year.*')->get()[0];


    $students = Student_Grade_Level::where('classroom_id',$classroom_id)
    ->select('student_grade_levels.*')
    ->join('students','students.student_id','student_grade_levels.student_id')
    ->select('student_grade_levels.*','students.*')
    ->get();

    $room = Academic_Year::where('classroom_id',$classroom_id)
    ->where('curriculum_year',$curriculum_year)
    ->select('academic_year.*')
    ->get()[0];

    $behaviors = Behavior_type::all();

    $type='xlsx';
    Excel::create('Behavior', function($excel) use($students,$room,$academic_year, $behaviors) {

      $excel->sheet('Excel sheet', function($sheet) use($students,$room,$academic_year, $behaviors) {

        $sheet->setOrientation('landscape');

        ////////////////////////////Student/////////////////////////////

        $i=5;

        foreach ($students as $student) {

          $sheet->cells('A'.$i, function($cells) {
            $cells->setAlignment('center');
            $cells->setValignment('center');
          });
            $sheet->setCellValue('A'.$i, $student->student_id);
            $sheet->setCellValue('B'.$i, $student->firstname." ".$student->lastname);
            $i+=1;

        }

        ////////////////////////////Behaavior/////////////////////////////

        $j=67;
        $c = 65;

        $BOOM = array();
        foreach ($behaviors as $behavior) {
          $chr1 = '';
          $chr2 = '';
          if($j > 90){
            $count = floor($j/90);

            $jj = 65+($j%90);
            $chr2 = chr(64+$count).''.chr($jj+4).''.'5';
            $jj = chr(64+$count).chr($jj).'5';
            $chr1 = $jj;

          }else{
            $chr1 = chr($j).'5';
            $chr2 = chr($j+4).'5';
          }
          // $sheet->mergeCells($chr1.':'.$chr2);
          // $sheet->cells(chr($j).'5', function($cells) {
          //   $cells->setAlignment('center');
          //   $cells->setValignment('center');
          // });




          $sheet->setCellValue($chr1, $behavior->behavior_type_text);
          $j+=5;

          array_push($BOOM, $chr1,$chr2);
        }
        dd($BOOM);


        $sheet->setCellValue('B1', $academic_year->academic_year);
        $sheet->setCellValue('B2', $academic_year->grade_level);
        $sheet->setCellValue('B3', $academic_year->room);

        $sheet->setCellValue('A1', 'Academic Year');
        $sheet->setCellValue('A2', 'Grade Level');
        $sheet->setCellValue('A3', 'Room');
        $sheet->setCellValue('A4', 'Behavior');
        $sheet->setCellValue('A5', 'Students ID');
        $sheet->setCellValue('B5', 'Students Name');
        //-------- From Behavior table--------------------//
        $sheet->setCellValue('C6', 'Q1');
        $sheet->setCellValue('D6', 'Q2');
        $sheet->setCellValue('E6', 'Q3');
        $sheet->setCellValue('F6', 'Q4');


        $sheet->setWidth(array(
            'A' => 12,
            'B' => 25
        ));

        $sheet->setStyle(array(
            'font' => array(
                'name'      =>  'Tw Cen MT',
                'size'      =>  12,
                'bold'      =>  false
            )
        ));





        $sheet->setBorder('A4:F23', 'thin');


      });

    })->export($type);

  }





    //
}
