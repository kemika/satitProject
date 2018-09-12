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
use App\Behavior_Type;

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
    // dd(count($academicYear),count($gradeLevel));
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
  ->first();



  $academic_year = Academic_Year::where('classroom_id',$classroom_id)->where('curriculum_year',$curriculum_year)->select('academic_year.*')->first();




  $students = Student_Grade_Level::where('classroom_id',$classroom_id)
  ->select('student_grade_levels.*')
  ->join('students','students.student_id','student_grade_levels.student_id')
  ->select('student_grade_levels.*','students.*')
  ->get();


  $room = Academic_Year::where('classroom_id',$classroom_id)
  ->where('curriculum_year',$curriculum_year)
  ->select('academic_year.*')
  ->first();








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
        $sheet->setCellValue('B2', $subject->course_id);
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
  ->first();



    $academic_year = Academic_Year::where('classroom_id',$classroom_id)->where('curriculum_year',$curriculum_year)->select('academic_year.*')->first();



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
        $sheet->setCellValue('B2', $subject->course_id);
        $sheet->setCellValue('C2', $subject->course_name);
        $sheet->setCellValue('B4', $academic_year->academic_year);






    });

  })->export($type);

}

  public function exportHeight($classroom_id, $curriculum_year){
    $academic_year = Academic_Year::where('classroom_id',$classroom_id)->where('curriculum_year',$curriculum_year)->select('academic_year.*')->first();


    $students = Student_Grade_Level::where('classroom_id',$classroom_id)
    ->select('student_grade_levels.*')
    ->join('students','students.student_id','student_grade_levels.student_id')
    ->select('student_grade_levels.*','students.*')
    ->get();

    $room = Academic_Year::where('classroom_id',$classroom_id)
    ->where('curriculum_year',$curriculum_year)
    ->select('academic_year.*')
    ->first();

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
    $academic_year = Academic_Year::where('classroom_id',$classroom_id)->where('curriculum_year',$curriculum_year)->select('academic_year.*')->first();


    $students = Student_Grade_Level::where('classroom_id',$classroom_id)
    ->select('student_grade_levels.*')
    ->join('students','students.student_id','student_grade_levels.student_id')
    ->select('student_grade_levels.*','students.*')
    ->get();

    $room = Academic_Year::where('classroom_id',$classroom_id)
    ->where('curriculum_year',$curriculum_year)
    ->select('academic_year.*')
    ->first();

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
    $academic_year = Academic_Year::where('classroom_id',$classroom_id)->where('curriculum_year',$curriculum_year)->select('academic_year.*')->first();



    $students = Student_Grade_Level::where('classroom_id',$classroom_id)
    ->select('student_grade_levels.*')
    ->join('students','students.student_id','student_grade_levels.student_id')
    ->select('student_grade_levels.*','students.*')
    ->get();


    $behaviors = Behavior_Type::all();

    $type='xlsx';
    Excel::create('Behavior', function($excel) use($students,$academic_year, $behaviors) {

      $excel->sheet('Excel sheet', function($sheet) use($students,$academic_year, $behaviors) {

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


        $BOOM = array();
        foreach ($behaviors as $behavior) {

          $b = self::get_index_char_cell($j,3,5);
          $c = self::get_index_char_cell($j,3,4);
          $cells = self::get_index_cell($j,4,6);
          $q=1;
          foreach ($cells as $cell ) {
              $sheet->setCellValue($cell, 'Q'.$q);
              $q+=1;


            // code...
          }
          $sheet->mergeCells($b[0].':'.$b[1]);
          $sheet->cell($b[0].':'.$b[1], function($cell) {
              $cell->setAlignment('center');
          });
          $sheet->setCellValue($b[0], $behavior->behavior_type_text);


          $sheet->mergeCells($c[0].':'.$c[1]);
          $sheet->cell($c[0].':'.$c[1], function($cell) {
              $cell->setAlignment('center');
          });
          $sheet->setCellValue($c[0], $behavior->behavior_type);
          $j+=4;


        }



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


  public function exportAttandance($classroom_id, $curriculum_year)
  {
    $type='xlsx';
    $academic_year = Academic_Year::where('classroom_id',$classroom_id)->where('curriculum_year',$curriculum_year)->select('academic_year.*')->first();


    $students = Student_Grade_Level::where('classroom_id',$classroom_id)
    ->select('student_grade_levels.*')
    ->join('students','students.student_id','student_grade_levels.student_id')
    ->select('student_grade_levels.*','students.*')
    ->get();




    Excel::create('Attandance', function($excel) use($students,$academic_year) {

      $excel->sheet('Excel sheet', function($sheet) use($students,$academic_year) {

          $sheet->setOrientation('landscape');

          $sheet->setCellValue('A1', 'Academic Year');
          $sheet->setCellValue('B1', $academic_year->academic_year);
          $sheet->setCellValue('B2', $academic_year->grade_level);
          $sheet->setCellValue('B3', $academic_year->room);
          $sheet->setCellValue('A2', 'Grade Level');

          $sheet->setCellValue('A3', 'Room');
          $sheet->setCellValue('A5', 'No');
          $sheet->setCellValue('B5', 'Students ID');
          $sheet->setCellValue('C5', 'Students Name');
          $sheet->setCellValue('D5', '1st Semester');
          $sheet->setCellValue('I5', '2nd Semester');
          $sheet->setCellValue('C4', 'Attandance');
          //------------- From Attentance table ----------//

          $sheet->setCellValue('D4', 'Days Present');
          $sheet->setCellValue('E4', 'Late');
          $sheet->setCellValue('F4', 'Sick');
          $sheet->setCellValue('G4', 'Leave');
          $sheet->setCellValue('H4', 'Absent');
          $sheet->setCellValue('I4', 'Days Present S2');
          $sheet->setCellValue('J4', 'Late S2');
          $sheet->setCellValue('K4', 'Sick S2');
          $sheet->setCellValue('L4', 'Leave S2');
          $sheet->setCellValue('M4', 'Absent S2');


          for($i =1 ; $i<=count($students) ;$i++){
            $sheet->setCellValue('A'.(5+$i), $i);
            $sheet->setCellValue('B'.(5+$i), $students[$i-1]->student_id);

            $sheet->setCellValue('C'.(5+$i), $students[$i-1]->firstname.' '.$students[$i-1]->lastname);
          }


          $sheet->setWidth(array(
              'A' => 12,
              'B' => 12,
              'C' => 30
          ));

          $sheet->setStyle(array(
              'font' => array(
                  'name'      =>  'Tw Cen MT',
                  'size'      =>  12,
                  'bold'      =>  false
              )
          ));

          $sheet->setBorder('A4:M5', 'thin');

          $sheet->cells('A4:AN4', function($cells) {
              $cells->setAlignment('center');
              $cells->setValignment('center');
              $cells->setTextRotation(90);
            });

          $sheet->mergeCells('D5:H5');
          $sheet->cell('D5:H5', function($cell) {
              $cell->setAlignment('center');
          });

          $sheet->mergeCells('I5:M5');
          $sheet->cell('I5:M5', function($cell) {
              $cell->setAlignment('center');
          });

          $sheet->cell('C4', function($cell) {
              $cell->setBackground('#FFC300');
          });


        });

    })->export($type);
  }


  public function exportActivities($classroom_id,$curriculum_year)
  {
    $type='xlsx';
    $academic_year = Academic_Year::where('classroom_id',$classroom_id)->where('curriculum_year',$curriculum_year)->select('academic_year.*')->first();


    $subject_sem1 = Offered_Courses::where('classroom_id', $classroom_id)
    ->where('offered_courses.semester', 1)
    ->where('offered_courses.is_elective', '0')
    ->select('offered_courses.*')
    ->where('offered_courses.curriculum_year',$curriculum_year)
    ->select('offered_courses.*')
    ->join('curriculums', function($j) {

    $j->on('curriculums.course_id', '=', 'offered_courses.course_id');
    $j->on('curriculums.curriculum_year','=','offered_courses.curriculum_year');

    })
    ->where('curriculums.is_activity','1')
    ->select('offered_courses.*','curriculums.*')
    ->get();
    // dd($subject_sem1[0]->course_id);


    $subject_sem2 = Offered_Courses::where('classroom_id', $classroom_id)
    ->where('offered_courses.semester', 2)
    ->where('offered_courses.is_elective', '0')
    ->select('offered_courses.*')
    ->where('offered_courses.curriculum_year',$curriculum_year)
    ->select('offered_courses.*')
    ->join('curriculums', function($j) {

    $j->on('curriculums.course_id', '=', 'offered_courses.course_id');
    $j->on('curriculums.curriculum_year','=','offered_courses.curriculum_year');

    })
    ->where('curriculums.is_activity','1')
    ->select('offered_courses.*','curriculums.*')
    ->get();

    // dd($subject_sem1,$subject_sem2);


    $students = Student_Grade_Level::where('classroom_id',$classroom_id)
    ->select('student_grade_levels.*')
    ->join('students','students.student_id','student_grade_levels.student_id')
    ->select('student_grade_levels.*','students.*')
    ->get();
    Excel::create('Activities', function($excel) use($students,$academic_year,$subject_sem1,$subject_sem2) {

      $excel->sheet('Excel sheet', function($sheet) use($students,$academic_year,$subject_sem1,$subject_sem2) {

        $sheet->setOrientation('landscape');
        $sheet->setCellValue('B1', $academic_year->academic_year);
        $sheet->setCellValue('B2', $academic_year->grade_level);
        $sheet->setCellValue('B3', $academic_year->room);

        $sheet->setCellValue('A1', 'Academic Year');
        $sheet->setCellValue('A2', 'Grade Level');
        $sheet->setCellValue('A3', 'Room');
        $sheet->setCellValue('A4', 'Fill these cells in with S, U, or leave blank');
        // $sheet->setCellValue('A6', 'No');
        $sheet->setCellValue('A7', 'Students ID');
        $sheet->setCellValue('B7', 'Students Name');

        $sheet->setCellValue('B5', 'Activities');
        $sheet->setCellValue('B6', 'Course ID');
        //------------- From Attentance table ----------//
        $cells = self::get_index_cell(ord('C'),count($subject_sem1),5);

        $i = 0;
        foreach ($cells as $cell ) {
          $sheet->setCellValue($cell,$subject_sem1[$i]->course_name);
          $i+=1;
        }

        $cells = self::get_index_cell(ord('C'),count($subject_sem1),6);

        $i = 0;
        foreach ($cells as $cell ) {
          $sheet->setCellValue($cell,$subject_sem1[$i]->course_id);
          $i+=1;
        }



        $b = self::get_index_char_cell(ord('C'),count($subject_sem1)-1,7);

        $sheet->mergeCells($b[0].':'.$b[1]);
        $sheet->cell($b[0].':'.$b[1], function($cell) {
            $cell->setAlignment('center');
        });
        $sheet->setCellValue($b[0], '1st Semester');

        $start = ord('C')+count($subject_sem1);




        $cells = self::get_index_cell($start,count($subject_sem2),5);

        $i = 0;
        foreach ($cells as $cell ) {
          $sheet->setCellValue($cell,$subject_sem2[$i]->course_name);
          $i+=1;

        }

        $cells = self::get_index_cell($start,count($subject_sem2),6);

        $i = 0;
        foreach ($cells as $cell ) {
          $sheet->setCellValue($cell,$subject_sem2[$i]->course_id);
          $i+=1;
        }

        $b = self::get_index_char_cell($start,count($subject_sem2)-1,7);
        $sheet->mergeCells($b[0].':'.$b[1]);
        $sheet->cell($b[0].':'.$b[1], function($cell) {
            $cell->setAlignment('center');

        });

        $sheet->setCellValue($b[0], '2st Semester');

        for($i =1 ; $i<=count($students) ;$i++){
          // $sheet->setCellValue('A'.(6+$i), $i);
          $sheet->setCellValue('A'.(7+$i), $students[$i-1]->student_id);
          $sheet->setCellValue('B'.(7+$i), $students[$i-1]->firstname.' '.$students[$i-1]->lastname);
        }



        $sheet->setWidth(array(
            'A' => 12,
            'B' => 35,
            'C' => 12
        ));

        $sheet->setHeight(array(
            'B' => 100
        ));





        $sheet->setStyle(array(
            'font' => array(
                'name'      =>  'Tw Cen MT',
                'size'      =>  12,
                'bold'      =>  false
            )
        ));

        $sheet->getStyle('A5:Z6')->getAlignment()->setWrapText(true);
        //$sheet->getStyle('A6:Z6')->getAlignment()->setWrapText(true);

        $sheet->setBorder('A5:Q7', 'thin');

        $sheet->cells('A5:AN6', function($cells) {
            $cells->setAlignment('center');
            $cells->setValignment('center');
            //$cells->setTextRotation(90);
          });


        $sheet->mergeCells('A4:D4');
        $sheet->cell('A4:D4', function($cell) {
            $cell->setAlignment('center');
        });

        $sheet->cell('B5', function($cell) {
            $cell->setBackground('#FFC300');
        });

        $sheet->cell('B6', function($cell) {
            $cell->setBackground('#FFC300');
        });

        $sheet->cell('A4', function($cell) {
            $cell->setBackground('#95B3D7');
        });


      });

    })->export($type);
  }


  public function get_index_char_cell($i,$amount,$row ){
    $result = array();
    $x = '';
    $y = '';
    $count = 1;

    if($i > 90){
      $count = floor($i/90);
      // dd(chr(64 + $count),chr(64 + $i%90),chr(64 + $count).chr($i%90).'5');
      $x = chr(64 + $count).chr(64 + $i%90).$row;



    }
    else{
      $x=chr($i).$row;

    }

    $j = $i+$amount;
    if($j > 90){
      $count = floor($j/90);
      $y = chr(64 + $count).chr(64 +$j%90).$row;

    }
    else{
      $y=chr($j).$row;

    }
    array_push($result,$x,$y);
    return $result;


  }

  public function get_index_cell($index,$amount,$row){

    $result = array();

    for($i = 0 ; $i < $amount ; $i++ ){

      $k = $i+$index;
      if( $k > 90){
        $count = floor($k/90);
        $x = chr(64 + $count).chr(64 + $k%90).$row;
        array_push($result,$x);



      }
      else{
        $x=chr($k).$row;
        array_push($result,$x);


      }



    }

    return $result;

  }





    //
}
