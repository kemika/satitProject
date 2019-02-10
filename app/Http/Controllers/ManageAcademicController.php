<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Academic_Year;
use App\Curriculum;
use App\Offered_Courses;
use App\Student_Grade_level;
use App\Homeroom;
use App\Student;

class ManageAcademicController extends Controller
{
  public function index(){
    $academic  = Academic_Year::orderBy('academic_year', 'desc')->groupBy('academic_year')->first();
    return view('manageAcademic.index' , ['cur_year' => $academic->academic_year]);
  }

  public function editAcademicYear(){
    $academicYear  = Academic_Year::orderBy('academic_year', 'desc')->groupBy('academic_year')->first();
    $year = $academicYear->academic_year;
    $academicDetail = Academic_Year::where('academic_year', $year)->orderBy('grade_level', 'asc')->get();
    $academicAbove  = Academic_Year::orderBy('academic_year', 'desc')->groupBy('academic_year')->where('academic_year',">=",$year)->get();
    $curriculum  = Curriculum::orderBy('curriculum_year', 'asc')->groupBy('curriculum_year')->get();
    return view('manageAcademic.academicTable' , ['cur_year' => $year,'sel_year' => $academicAbove,'academicDetail'=>$academicDetail]);
  }



  public function assignSubject($grade,$room,Request $request){
    if(!($room >= 1 && $room <= 12) || !($grade >= 1 && $grade <= 12)){
      $redi  = "editCurrentAcademic";
      return redirect($redi);
    }
    $curricula_year  = Curriculum::orderBy('curriculum_year', 'asc')->groupBy('curriculum_year')->get();
    $academic  = Academic_Year::orderBy('academic_year', 'desc')->groupBy('academic_year')->first();
    $year = $academic->academic_year;
    if($room === 0){
      $academic  = Academic_Year::where('academic_year', $year)
                                ->where('grade_level', $grade)
                                ->get();
    }
    else if($room >= 1 and $room <= 12){
      $academic  = Academic_Year::where('academic_year', $year)
                                ->where('room', $room)
                                ->where('grade_level', $grade)
                                ->get();
    }
    $selCur = 0;
    if(!isset($academic[0])){ // No classroom id
      $courses = [];
      $allSub =[];
    }
    else{
      $courses  = Academic_Year::where('academic_year.academic_year', $year)
              ->where('academic_year.classroom_id', $academic[0]->classroom_id)
              ->Join('offered_courses','offered_courses.classroom_id','=','academic_year.classroom_id')
              ->Join('curriculums', function($join)
                  {
                      $join->on('curriculums.course_id', '=', 'offered_courses.course_id');
                      $join->on('curriculums.curriculum_year','=', 'offered_courses.curriculum_year');
                  })
              ->select('offered_courses.open_course_id','academic_year.academic_year','curriculums.course_id','academic_year.grade_level','academic_year.room'
                      ,'curriculums.course_name','offered_courses.credits','offered_courses.semester','offered_courses.is_elective')
              ->get();
      $selCur = $academic[0]->curriculum_year;
      $allSub = Curriculum::where('min_grade_level','<=',$grade)
                          ->where('max_grade_level','>=',$grade)
                          ->where('curriculum_year',$academic[0]->curriculum_year)
                          ->get();
    }
    return view('manageAcademic.assignSubject' , ['cur_year' => $year,'grade'=>$grade,'room'=>$room,'subs'=>$courses,'curricula'=>$curricula_year,
                'selCur'=>$selCur,'allSub'=>$allSub]);
  }

  public function assignStudent($grade,$room,Request $request){
    if(!($room >= 1 && $room <= 12) || !($grade >= 1 && $grade <= 12)){
      $redi  = "editCurrentAcademic";
      return redirect($redi);
    }
    $curricula_year  = Curriculum::orderBy('curriculum_year', 'asc')->groupBy('curriculum_year')->get();
    $academic  = Academic_Year::orderBy('academic_year', 'desc')->groupBy('academic_year')->first();
    $year = $academic->academic_year;
    if($room === 0){
      $academic  = Academic_Year::where('academic_year', $year)
                                ->where('grade_level', $grade)
                                ->get();
    }
    else if($room >= 1 and $room <= 12){
      $academic  = Academic_Year::where('academic_year', $year)
                                ->where('room', $room)
                                ->where('grade_level', $grade)
                                ->get();
    }
    $selCur = 0;
    if(!isset($academic[0])){ // No classroom id
      $courses = [];
      $std_in = [];
    }
    else{
      $std_in  = Student_Grade_Level::where('classroom_id', $academic[0]->classroom_id)
              ->Join('students','student_grade_levels.student_id','students.student_id')
              ->select('students.firstname','students.lastname','students.student_id')
              ->get();


    }
    $temp = Academic_Year::where('academic_year', $year)
                          ->pluck('classroom_id');

    $allStd = Student::where('student_status',0)
                      ->leftJoin('student_grade_levels','student_grade_levels.student_id','students.student_id')

                      ->select('students.student_id','students.firstname','students.lastname')
                      ->get();
    return view('manageAcademic.assignStudent' , ['cur_year' => $year,'grade'=>$grade,'room'=>$room,'stds'=>$std_in,'curricula'=>$curricula_year
                ,'allStd'=>$allStd]);
  }

  public function addRoom(Request $request){
    $grade = $request->input('grade');
    $year = $request->input('year');
    $academic  = Academic_Year::where('academic_year',$year)->where('grade_level',$grade)->orderBy('room', 'desc')->first();
    $room = $academic->room;


    try{
    $createNewRoom = new Academic_Year;
    $createNewRoom->academic_year = $year;
    $createNewRoom->grade_level = $grade;
    $createNewRoom->room = $room+1;
    $createNewRoom->curriculum_year = $academic->curriculum_year;
    $createNewRoom->save();
    }
    catch(\Exception $e){
       // do task when error
       return response()->json(['Status' => $e->getMessage()], 200);

    }

    return response()->json(['Status' => 'success'], 200);
  }

  public function removeRoom(Request $request){
    $grade = $request->input('grade');
    $year = $request->input('year');



    try{
      $academic  = Academic_Year::where('academic_year',$year)->where('grade_level',$grade)->orderBy('room', 'desc')->first();
      $room = $academic->room;
      Academic_Year::where('academic_year',$year)->where('grade_level',$grade)->where('room', $room)->delete();
    }
    catch(\Exception $e){
       // do task when error
       return response()->json(['Status' => $e->getMessage()], 200);

    }

    return response()->json(['Status' => 'success'], 200);
  }

  public function addStudent(Request $request){
    $room = $request->input('room');
    $grade = $request->input('grade');
    $std_id = $request->input('std_id');
    $curYear = Curriculum::orderBy('curriculum_year', 'desc')->groupBy('curriculum_year')->get();
    $academic  = Academic_Year::orderBy('academic_year', 'desc')->groupBy('academic_year')->first();
    $year = $academic->academic_year;

    $checkAca =  Academic_Year::where('academic_year',$year)
                              ->where('grade_level',$grade)
                              ->where('room',$room)
                              ->get();

    if(!isset($checkAca[0])){ // No classroom
      $createAca = new Academic_Year;
      $createAca->academic_year = $year;
      $createAca->grade_level = $grade;
      $createAca->room = $room;
      $createAca->curriculum_year = $curYear[0]->curriculum_year;
      $createAca->save();


    }
    $checkAca =  Academic_Year::where('academic_year',$year)
                              ->where('grade_level',$grade)
                              ->where('room',$room)
                              ->get();
    try{
    $createStuClass = new Student_Grade_Level;
    $createStuClass->classroom_id = $checkAca[0]->classroom_id;
    $createStuClass->student_id = $std_id;
    $createStuClass->save();
    }
    catch(\Exception $e){
       // do task when error
       return response()->json(['Status' => $e->getMessage()], 200);

    }

    return response()->json(['Status' => 'success'], 200);
  }


  public function createNewAcademic(Request $request){
    $academic  = Academic_Year::orderBy('academic_year', 'desc')->groupBy('academic_year')->first();
    $year = $academic->academic_year;

    try{
      $temp = $academic->replicate();
      $temp->academic_year = $year+1;
      $temp->classroom_id = null;
      $temp->save();

    }
    catch(\Exception $e){
       // do task when error
       return response()->json(['Status' => $e->getMessage()], 200);

    }
    return response()->json(['Status' => 'success'], 200);
  }


  public function addSubject(Request $request){
    $room = $request->input('room');
    $grade = $request->input('grade');
    $inCurYear = $request->input('year');

    $curYear = Curriculum::orderBy('curriculum_year', 'desc')->groupBy('curriculum_year')->get();
    $academic  = Academic_Year::orderBy('academic_year', 'desc')->groupBy('academic_year')->first();
    $year = $academic->academic_year;
    $checkAca =  Academic_Year::where('academic_year',$year)
                              ->where('grade_level',$grade)
                              ->where('room',$room)
                              ->get();

    if(!isset($checkAca[0])){ // No classroom
      $createAca = new Academic_Year;
      $createAca->academic_year = $year;
      $createAca->grade_level = $grade;
      $createAca->room = $room;
      $createAca->curriculum_year = $inCurYear;
      $createAca->save();
    }

    $checkAca =  Academic_Year::where('academic_year',$year)
                              ->where('grade_level',$grade)
                              ->where('room',$room)
                              ->get();

    try{
      $createStuClass = new Offered_Courses;
      $createStuClass->classroom_id = $checkAca[0]->classroom_id;
      $createStuClass->semester = $request->input('semester');
      $createStuClass->credits = $request->input('credit');
      $createStuClass->is_elective = $request->input('elective');
      $createStuClass->course_id = $request->input('course_id');
      $createStuClass->curriculum_year = $inCurYear;
      $createStuClass->save();
    }catch(\Exception $e){
       // do task when error
       return response()->json(['Status' => $e->getMessage()], 200);

    }


    return response()->json(['Status' => 'success'], 200);
  }


  public function changeCurYear(Request $request){
    $room = $request->input('room');
    $grade = $request->input('grade');
    $curYear = $request->input('selCur');
    if($curYear === "---"){
      $redi  = "assignSubject/".$grade."/".$room;
      return redirect($redi);
    }
    $academic  = Academic_Year::orderBy('academic_year', 'desc')->groupBy('academic_year')->first();
    $year = $academic->academic_year;

    $checkAca =  Academic_Year::where('academic_year',$year)
                              ->where('grade_level',$grade)
                              ->where('room',$room)
                              ->get();
    if(!isset($checkAca[0])){ // No classroom
      $createAca = new Academic_Year;
      $createAca->academic_year = $year;
      $createAca->grade_level = $grade;
      $createAca->room = $room;
      $createAca->curriculum_year = $curYear;
      $createAca->save();
    }
    else{
      $class_id = $checkAca[0]->classroom_id;
      $temp = Offered_Courses::where('classroom_id',$class_id)->delete();
      $temp = Homeroom::where('classroom_id',$class_id)->delete();
      $temp = Student_Grade_level::where('classroom_id',$class_id)->delete();

      $checkAca =  Academic_Year::where('academic_year',$year)
                                ->where('grade_level',$grade)
                                ->where('room',$room)
                                ->update(['curriculum_year' => $curYear]);
    }
    $redi  = "assignSubject/".$grade."/".$room;
    return redirect($redi);
  }
}
