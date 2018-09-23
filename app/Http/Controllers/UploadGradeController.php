<?php

namespace App\Http\Controllers;

use function App\clean_blank_space;
use App\SystemConstant;
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
use App\Teacher_Comment;
use App\Physical_Record;
use App\Behavior_Record;
use App\Attendance_Record;
use App\Activity_Record;
use App\Room;
use App\Academic_Year;
use App\Behavior_Type;
use App\Grade_Status;
use App\WaitApprove;
use Illuminate\Support\Facades\Input;
use App\Grade;
use App\Offered_Courses;
use Illuminate\Support\Facades\Log;

class UploadGradeController extends Controller
{
    public function index()
    {

        return view('uploadGrade.index');
    }


    public function upload()
    {
        return view('uploadGrade.upload');
    }


    // public function import(Request $request)
    // {
    //   if($request->hasfFile('file')){getUpload
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


    public function getUploadComments(Request $request)
    {

        // dd($datetime);
        // dd($studentsID);
        // var_dump($studentsID);
        // print_r($studentsID);


        //$stdArray = $tempsss->unwrap($studentsID);

        // print_r($arr);
        //
        // if (in_array("1111111111", $arr)) {
        //     echo "Got My";
        // }

        if ($request->hasFile('file')) {
            $errorArray = array();

            foreach ($request->file as $file) {

                $finalResult = array();
                $errorDetail = array();
                $fact = true;
                $factGrade = true;
                $factValidate = true;
                $factEmpty = true;
                //$file = Input::file('file');
                $file_name = $file->getClientOriginalName();
                $file_type = \File::extension('files/' . $file_name);
                $file->move('files/', $file_name);
                $checkFileName = substr("$file_name", 0, 8);


                $getAcademicYear = Excel::load('files/' . $file_name, function ($reader) {
                    $reader->setHeaderRow(1);
                })->get();


                $getGradeLevel = Excel::load('files/' . $file_name, function ($reader) {
                    $reader->setHeaderRow(2);
                })->get();

                $getRoom = Excel::load('files/' . $file_name, function ($reader) {
                    $reader->setHeaderRow(3);
                })->get();

                $results = Excel::load('files/' . $file_name, function ($reader) {
                    $reader->setHeaderRow(4);
                    $reader->all();
                })->get();

                $year = $getAcademicYear->getHeading()[1];
                $gradeLevel = $getGradeLevel->getHeading()[1];
                $room = $getRoom->getHeading()[1];

                /*
                                $students = Student::all();
                                $studentsID = Student::Join('student_grade_levels', 'student_grade_levels.student_id', '=', 'students.student_id')
                                    ->Join('academic_year', 'academic_year.classroom_id', '=', 'student_grade_levels.classroom_id')
                                    ->where('academic_year.academic_year', $year)
                                    ->where('academic_year.room', $room)
                                    ->where('academic_year.grade_level', $gradeLevel)
                                    ->select('students.student_id', 'students.firstname', 'students.lastname')
                                    ->get();

                                $checkYear = Academic_Year::where('academic_year.academic_year',$year)->first();
                                $checkRoom = Academic_Year::where('academic_year.room',$room)->first();
                                $checkGradeLevel = Academic_Year::where('academic_year.grade_level',$gradeLevel)->first();



                                if(!is_int($year)){
                                  $errorDetail["year"] = "Invalid type of Academic Year";
                                }
                                else{

                                }*/

                $students = Student::all();
                $studentsID = Student::Join('student_grade_levels', 'student_grade_levels.student_id', '=', 'students.student_id')
                    ->Join('academic_year', 'academic_year.classroom_id', '=', 'student_grade_levels.classroom_id')
                    ->where('academic_year.academic_year', $year)
                    ->where('academic_year.room', $room)
                    ->where('academic_year.grade_level', $gradeLevel)
                    ->select('students.student_id', 'students.firstname', 'students.lastname')
                    ->get();

                $stdArray = array();
                $stdName = array();

                date_default_timezone_set('Asia/Bangkok');
                $datetime = date("Y-m-d H:i:s");

                foreach ($studentsID as $studentID) {
                    $stdArray[] = $studentID->student_id;
                    $stdName[(String)($studentID->student_id)] = $studentID->firstname . " " . $studentID->lastname;
                }

                for ($i = 0; $i < count($results); $i++) {
                    if (in_array($results[$i]->students_id, $stdArray)) {

                        if ($stdName[(String)($results[$i]->students_id)] === $results[$i]->students_name) {
                            for ($j = 1; $j <= 4; $j++) {
                                $qComment = "quater_" . $j;
                                if ($j == 1 || $j == 2) {
                                    $semester = 1;
                                } else if ($j == 3 || $j == 4) {
                                    $semester = 2;
                                }
                                if ($results[$i]->$qComment != "") {
                                    $comment = new Teacher_Comment;
                                    $comment->student_id = $results[$i]->students_id;
                                    $comment->quater = $j;
                                    $comment->comment = $results[$i]->$qComment;
                                    $comment->semester = $semester;
                                    $comment->academic_year = $year;
                                    $comment->datetime = $datetime;
                                    $finalResult[] = $comment;
                                }
                            }
                        } else if ($stdName[(String)($results[$i]->students_id)] !== $results[$i]->students_name) {
                            $errorDetail[(String)($results[$i]->students_id)] = $results[$i]->students_id . " This student ID doesn't match with student name";
                        }
                    } else if (!in_array($results[$i]->students_id, $stdArray)) {
                        $errorDetail[(String)($results[$i]->students_id)] = $results[$i]->students_id . " This Student ID doesn't exist in this room";
                    }

                }

                if (count($errorDetail) <= 0) {
                    foreach ($finalResult as $result) {
                        $result->save();
                    }
                    $errorDetail["Status"] = "upload file Academic_Year : " . $year . " Grade Level : " . $gradeLevel . " Room : " . $room . " success";

                } else {
                    $errorDetail["Status"] = "upload file Academic_Year : " . $year . " Grade Level : " . $gradeLevel . " Room : " . $room . " error";
                    /*
                    foreach($errorDetail as $key => $value){
                      print_r("Student ID : ".$key." got error => ".$value."</br>");
                    }*/

                }
                $errorArray[] = $errorDetail;
            }

        }


        return view('uploadGrade.errorDetail', ['errorDetail' => $errorArray]);

    } // END upload Comment

    public function getUploadHeightAndWeight(Request $request)
    {

        // dd($datetime);
        // dd($studentsID);
        // var_dump($studentsID);
        // print_r($studentsID);


        //$stdArray = $tempsss->unwrap($studentsID);

        // print_r($arr);
        //
        // if (in_array("1111111111", $arr)) {
        //     echo "Got My";
        // }

        if ($request->hasFile('file')) {
            $errorArray = array();

            foreach ($request->file as $file) {

                $finalResult = array();
                $errorDetail = array();
                $fact = true;
                $factGrade = true;
                $factValidate = true;
                $factEmpty = true;
                //$file = Input::file('file');
                $file_name = $file->getClientOriginalName();
                $file_type = \File::extension('files/' . $file_name);
                $file->move('files/', $file_name);
                $checkFileName = substr("$file_name", 0, 8);

                $getAcademicYear = Excel::load('files/' . $file_name, function ($reader) {
                    $reader->setHeaderRow(1);
                })->get();


                $getGradeLevel = Excel::load('files/' . $file_name, function ($reader) {
                    $reader->setHeaderRow(2);
                })->get();

                $getRoom = Excel::load('files/' . $file_name, function ($reader) {
                    $reader->setHeaderRow(3);
                })->get();

                $results = Excel::load('files/' . $file_name, function ($reader) {
                    $reader->setHeaderRow(4);
                    $reader->all();
                })->get();

                $year = $getAcademicYear->getHeading()[1];
                $gradeLevel = $getGradeLevel->getHeading()[1];
                $room = $getRoom->getHeading()[1];

                $students = Student::all();
                $studentsID = Student::Join('student_grade_levels', 'student_grade_levels.student_id', '=', 'students.student_id')
                    ->Join('academic_year', 'academic_year.classroom_id', '=', 'student_grade_levels.classroom_id')
                    ->where('academic_year.academic_year', $year)
                    ->where('academic_year.room', $room)
                    ->where('academic_year.grade_level', $gradeLevel)
                    ->select('students.student_id', 'students.firstname', 'students.lastname')
                    ->get();

                $stdArray = array();
                $stdName = array();

                date_default_timezone_set('Asia/Bangkok');
                $datetime = date("Y-m-d H:i:s");

                foreach ($studentsID as $studentID) {
                    $stdArray[] = $studentID->student_id;
                    $stdName[(String)($studentID->student_id)] = $studentID->firstname . " " . $studentID->lastname;
                }

                for ($i = 0; $i < count($results); $i++) {
                    if (in_array($results[$i]->students_id, $stdArray)) {

                        if ($stdName[(String)($results[$i]->students_id)] === $results[$i]->students_name) {
                            if ($results[$i]->s1_weight != "" && $results[$i]->s1_height != "") {
                                $physical = new Physical_Record;
                                $physical->student_id = $results[$i]->students_id;
                                $physical->weight = $results[$i]->s1_weight;
                                $physical->height = $results[$i]->s1_height;
                                $physical->semester = 1;
                                $physical->academic_year = $year;
                                $physical->datetime = $datetime;
                                $physical->data_status = 1;
                                //$physical->save();
                                $finalResult[] = $physical;
                            }
                            if ($results[$i]->s2_weight != "" && $results[$i]->s2_height != "") {
                                $physical = new Physical_Record;
                                $physical->student_id = $results[$i]->students_id;
                                $physical->weight = $results[$i]->s2_weight;
                                $physical->height = $results[$i]->s2_height;
                                $physical->semester = 2;
                                $physical->academic_year = $year;
                                $physical->datetime = $datetime;
                                $physical->data_status = 1;
                                //$physical->save();
                                $finalResult[] = $physical;
                            }


                        } else if ($stdName[(String)($results[$i]->students_id)] !== $results[$i]->students_name) {
                            $errorDetail[(String)($results[$i]->students_id)] = $results[$i]->students_id . " This student ID doesn't match with student name";
                        }


                    } else if (!in_array($results[$i]->students_id, $stdArray)) {
                        $errorDetail[(String)($results[$i]->students_id)] = $results[$i]->students_id . " This Student ID doesn't exist in this room";
                    }

                }
                if (count($errorDetail) <= 0) {
                    foreach ($finalResult as $result) {
                        $result->save();
                    }
                    $errorDetail["Status"] = "upload file Academic_Year : " . $year . " Grade Level : " . $gradeLevel . " Room : " . $room . " success";

                } else {
                    $errorDetail["Status"] = "upload file Academic_Year : " . $year . " Grade Level : " . $gradeLevel . " Room : " . $room . " error";
                    /*
                    foreach($errorDetail as $key => $value){
                      print_r("Student ID : ".$key." got error => ".$value."</br>");
                    }*/

                }
                $errorArray[] = $errorDetail;
            }
        }


        return view('uploadGrade.errorDetail', ['errorDetail' => $errorArray]);

    } // END upload HeightAndWeight

    public function getUploadBehavior(Request $request)
    {

        // dd($datetime);
        // dd($studentsID);
        // var_dump($studentsID);
        // print_r($studentsID);


        //$stdArray = $tempsss->unwrap($studentsID);

        // print_r($arr);
        //
        // if (in_array("1111111111", $arr)) {
        //     echo "Got My";
        // }

        if ($request->hasFile('file')) {
            $errorArray = array();

            foreach ($request->file as $file) {

                $finalResult = array();
                $errorDetail = array();
                $fact = true;
                $factGrade = true;
                $factValidate = true;
                $factEmpty = true;
                //$file = Input::file('file');
                $file_name = $file->getClientOriginalName();
                $file_type = \File::extension('files/' . $file_name);
                $file->move('files/', $file_name);
                $checkFileName = substr("$file_name", 0, 8);


                $getAcademicYear = Excel::load('files/' . $file_name, function ($reader) {
                    $reader->setHeaderRow(1);
                })->get();


                $getGradeLevel = Excel::load('files/' . $file_name, function ($reader) {
                    $reader->setHeaderRow(2);
                })->get();

                $getRoom = Excel::load('files/' . $file_name, function ($reader) {
                    $reader->setHeaderRow(3);
                })->get();

                $results = Excel::load('files/' . $file_name, function ($reader) {
                    $reader->skipColumns(2);
                    $reader->setHeaderRow(4);
                    $reader->limitrows(4);
                })->get();

                // Create lookup table for excel column of each behavior type
                // behavior type => Start column number
                $behavior_col = array_flip($results->getHeading());
                // These two are bug from Excel that has to be removed
                unset($behavior_col["behavior_id"]);
                unset($behavior_col[""]);

                // TODO Check if behavior types and behavior ID are correct according to table
//                $behaviorTypeCol = Behavior_Type::all();
//                $behaviorType = array();
//                foreach ($behaviorTypeCol as $type) {
//                    $behaviorType[$type->behavior_type_text] = $type->behavior_type;
//                }

//                $results = Excel::load('files/' . $file_name, function ($reader) {
//                    $reader->setHeaderRow(5);
//                    $reader->all();
//                })->get();

                $resultsStudent = Excel::load('files/' . $file_name, function ($reader) {
                    $reader->setHeaderRow(6);
                    $reader->all();
                })->get();
//

                $year = $getAcademicYear->getHeading()[1];
                $gradeLevel = $getGradeLevel->getHeading()[1];
                $room = $getRoom->getHeading()[1];

                // Get student id and name from database
                $students = Student::all();
                $studentsID = Student::Join('student_grade_levels', 'student_grade_levels.student_id', '=', 'students.student_id')
                    ->Join('academic_year', 'academic_year.classroom_id', '=', 'student_grade_levels.classroom_id')
                    ->where('academic_year.academic_year', $year)
                    ->where('academic_year.room', $room)
                    ->where('academic_year.grade_level', $gradeLevel)
                    ->select('students.student_id', 'students.firstname', 'students.lastname')
                    ->get();

                $validStudents = array();
                date_default_timezone_set('Asia/Bangkok');
                $datetime = date("Y-m-d H:i:s");

                // Extract id and construct full name from query and create
                // Lookup table for student name
                foreach ($studentsID as $studentID) {
                    $validStudents[(String)($studentID->student_id)] =
                        $studentID->firstname . " " . $studentID->lastname;
                }
                $total_students = count($validStudents);

                $student_list_from_file = array();
                // Remove empty lines (artifact from Excel that say there exists line with empty content)

                // Copy info from position that does not have empty data
                // Also check if the name and id are valid
                for ($i = 0; $i < $total_students && $resultsStudent[$i]->students_id != null; $i++) {
                    $id = trim((String)$resultsStudent[$i]->students_id);
                    $name = trim((String)$resultsStudent[$i]->students_name);
                    if ($validStudents[$id] === $name) {
                        $student_list_from_file[] = ["id" => $id, "name" => "$name"];
                    } else {
                        $errorDetail[$id] = $id . " " . $name . " doesn't exist in this room" . $gradeLevel;
                    }
                }
                Log::info($behavior_col);
                // Loop for each behavior type
                if (count($errorDetail) > 0) {

                } else {
                    foreach ($behavior_col as $behavior_type => $col_index) {
                        Log::info($col_index);
                        // Read grade of all students for that behavior type
                        $results = Excel::load('files/' . $file_name, function ($reader) use ($col_index) {
                            $reader->skipColumns($col_index);
                            $reader->limitColumns($col_index + 4);
                            $reader->setHeaderRow(6);
                            $reader->all();
                        })->get();
                        // For each students we insert grade for this behavior
                        foreach ($results as $data) {
                            if ($data->students_id != "") {
                                for ($semester = 1; $semester <= SystemConstant::TOTAL_SEMESTERS; $semester++) {
                                    $i = 1;
                                    for (; $i <= SystemConstant::TOTAL_QUARTERS; $i++) {
                                        if ($data['q' . $i] !== null) {
                                            $behavior = new Behavior_Record;
                                            $behavior->student_id = $data['students_id'];
                                            $behavior->quater = $i;
                                            $behavior->behavior_type = $behavior_type;
                                            $behavior->grade = $data['q' . $i];
                                            $behavior->semester = $semester;
                                            $behavior->academic_year = $year;
                                            $behavior->datetime = $datetime;
                                            $behavior->data_status = 1; //TODO change when implemented approve behavior
                                            //dd($behavior);
                                            $behavior->save();
                                        }
                                    }
                                }
                                //$behavior->save();
                                //$finalResult[] = $behavior;
                            }

                        }
                    }
                    //dd("");
                }
                // Loop for each person
//                for ($i = 0; $i < $total_students; $i++) {
//                        if ($stdName[(String)($resultsStudent[$i]->students_id)] === $resultsStudent[$i]->students_name) {
//                            foreach ($headerBehavior as $head => $index) {
//
//                                $strCheckType = ucfirst($head);
//                                $strCheckType = str_replace("_", " ", $strCheckType);
//                                if (array_key_exists($strCheckType, $behaviorType)) {
//                                    $results = Excel::load('files/' . $file_name, function ($reader) use ($index) {
//                                        $reader->setHeaderRow(6);
//
//                                        $reader->limitColumns($index + 4);
//                                        $reader->skipColumns($index);
//                                        $reader->all();
//                                    })->get();
//
//                                    dd($results);
//
//                                    for ($j = 1; $j <= 4; $j++) {
//                                        $qBehavior = "q" . $j;
//                                        if ($j == 1 || $j == 2) {
//                                            $semester = 1;
//                                        } else if ($j == 3 || $j == 4) {
//                                            $semester = 2;
//                                        }
//                                        if ($results[$i]->$qBehavior != "") {
//                                            $behavior = new Behavior_Record;
//                                            $behavior->student_id = $resultsStudent[$i]->students_id;
//                                            $behavior->quater = $j;
//                                            $behavior->behavior_type = $behaviorType[$strCheckType];
//                                            $behavior->grade = $results[$i]->$qBehavior;
//                                            $behavior->semester = $semester;
//                                            $behavior->academic_year = $year;
//                                            $behavior->datetime = $datetime;
//                                            $behavior->data_status = 1;
//                                            //$behavior->save();
//                                            $finalResult[] = $behavior;
//                                        }
//                                    }
//                                }
//                            }
//                        } else if ($stdName[(String)($resultsStudent[$i]->students_id)] !== $resultsStudent[$i]->students_name) {
//                            $errorDetail[(String)($resultsStudent[$i]->students_id)] = $resultsStudent[$i]->students_id . " This student ID doesn't match with student name";
//                        }
//
//
//                    } else if (!in_array($resultsStudent[$i]->students_id, $stdArray)) {
//                        $errorDetail[(String)($resultsStudent[$i]->students_id)] = $resultsStudent[$i]->students_id . " This Student ID doesn't exist in this room";
//                    }
//
//                }
                if (count($errorDetail) <= 0) {
//                    foreach ($finalResult as $result) {
//                        $result->save();
//                    }
                    $errorDetail["Status"] = "upload file Academic_Year : " . $year . " Grade Level : " . $gradeLevel . " Room : " . $room . " success";

                } else {
                    $errorDetail["Status"] = "upload file Academic_Year : " . $year . " Grade Level : " . $gradeLevel . " Room : " . $room . " error";
                    /*
                    foreach($errorDetail as $key => $value){
                      print_r("Student ID : ".$key." got error => ".$value."</br>");
                    }*/

                }
                $errorArray[] = $errorDetail;


            }

        }

        return view('uploadGrade.errorDetail', ['errorDetail' => $errorArray]);

    } // END upload Behavior


    public function getUploadAttendance(Request $request)
    {

        // dd($datetime);
        // dd($studentsID);
        // var_dump($studentsID);
        // print_r($studentsID);


        //$stdArray = $tempsss->unwrap($studentsID);

        // print_r($arr);
        //
        // if (in_array("1111111111", $arr)) {
        //     echo "Got My";
        // }

        if ($request->hasFile('file')) {
            $errorArray = array();

            foreach ($request->file as $file) {

                $finalResult = array();
                $errorDetail = array();
                $fact = true;
                $factGrade = true;
                $factValidate = true;
                $factEmpty = true;
                //$file = Input::file('file');
                $file_name = $file->getClientOriginalName();
                $file_type = \File::extension('files/' . $file_name);
                $file->move('files/', $file_name);
                $checkFileName = substr("$file_name", 0, 8);

                $getAcademicYear = Excel::load('files/' . $file_name, function ($reader) {
                    $reader->setHeaderRow(1);
                })->get();


                $getGradeLevel = Excel::load('files/' . $file_name, function ($reader) {
                    $reader->setHeaderRow(2);
                })->get();

                $getRoom = Excel::load('files/' . $file_name, function ($reader) {
                    $reader->setHeaderRow(3);
                })->get();

                $results = Excel::load('files/' . $file_name, function ($reader) {
                    $reader->setHeaderRow(4);
                    $reader->all();
                })->get();

                $resultsStudent = Excel::load('files/' . $file_name, function ($reader) {
                    $reader->setHeaderRow(5);
                    $reader->all();
                })->get();


                $year = $getAcademicYear->getHeading()[1];
                $gradeLevel = $getGradeLevel->getHeading()[1];
                $room = $getRoom->getHeading()[1];

                $students = Student::all();
                $studentsID = Student::Join('student_grade_levels', 'student_grade_levels.student_id', '=', 'students.student_id')
                    ->Join('academic_year', 'academic_year.classroom_id', '=', 'student_grade_levels.classroom_id')
                    ->where('academic_year.academic_year', $year)
                    ->where('academic_year.room', $room)
                    ->where('academic_year.grade_level', $gradeLevel)
                    ->select('students.student_id', 'students.firstname', 'students.lastname')
                    ->get();

                $stdArray = array();
                $stdName = array();

                date_default_timezone_set('Asia/Bangkok');
                $datetime = date("Y-m-d H:i:s");

                foreach ($studentsID as $studentID) {
                    $stdArray[] = $studentID->student_id;
                    $stdName[(String)($studentID->student_id)] = $studentID->firstname . " " . $studentID->lastname;
                }

                for ($i = 0; $i < count($resultsStudent); $i++) {
                    if (in_array($resultsStudent[$i]->students_id, $stdArray)) {

                        if ($stdName[(String)($resultsStudent[$i]->students_id)] === $resultsStudent[$i]->students_name) {
                            $emptyField = false;
                            $attendance = new Attendance_Record;
                            $attendance->student_id = $resultsStudent[$i]->students_id;
                            if (is_float($results[$i + 1]->late)) $attendance->late = $results[$i + 1]->late;
                            else $emptyField = true;

                            if (is_float($results[$i + 1]->absent)) $attendance->absent = $results[$i + 1]->absent;
                            else $emptyField = true;

                            if (is_float($results[$i + 1]->leave)) $attendance->leave = $results[$i + 1]->leave;
                            else $emptyField = true;

                            if (is_float($results[$i + 1]->sick)) $attendance->sick = $results[$i + 1]->sick;
                            else $emptyField = true;
                            //  $attendance->absent = $results[$i + 1]->absent;
                            //$attendance->leave = $results[$i + 1]->leave;
                            //  $attendance->sick = $results[$i + 1]->sick;
                            $attendance->semester = 1;
                            $attendance->academic_year = $year;
                            $attendance->datetime = $datetime;
                            $attendance->data_status = 1;
                            //  if(!$emptyField) $attendance->save();
                            if (!$emptyField) $finalResult[] = $attendance;
                            $emptyField = false;
                            $attendance = new Attendance_Record;
                            $attendance->student_id = $resultsStudent[$i]->students_id;
                            // $attendance->late = $results[$i + 1]->late_s2;
                            // $attendance->absent = $results[$i + 1]->absent_s2;
                            // $attendance->leave = $results[$i + 1]->leave_s2;
                            // $attendance->sick = $results[$i + 1]->sick_s2;
                            if (is_float($results[$i + 1]->late_s2)) $attendance->late = $results[$i + 1]->late_s2;
                            else $emptyField = true;

                            if (is_float($results[$i + 1]->absent_s2)) $attendance->absent = $results[$i + 1]->absent_s2;
                            else $emptyField = true;

                            if (is_float($results[$i + 1]->leave_s2)) $attendance->leave = $results[$i + 1]->leave_s2;
                            else $emptyField = true;

                            if (is_float($results[$i + 1]->sick_s2)) $attendance->sick = $results[$i + 1]->sick_s2;
                            else $emptyField = true;

                            $attendance->semester = 2;
                            $attendance->academic_year = $year;
                            $attendance->datetime = $datetime;
                            $attendance->data_status = 1;
                            //  if(!$emptyField) $attendance->save();
                            if (!$emptyField) $finalResult[] = $attendance;
                        } else if ($stdName[(String)($resultsStudent[$i]->students_id)] !== $resultsStudent[$i]->students_name) {
                            $errorDetail[(String)($resultsStudent[$i]->students_id)] = $resultsStudent[$i]->students_id . " This student ID doesn't match with student name";
                        }


                    } else if (!in_array($resultsStudent[$i]->students_id, $stdArray)) {
                        $errorDetail[(String)($resultsStudent[$i]->students_id)] = $resultsStudent[$i]->students_id . " This Student ID doesn't exist in this room";
                    }

                }
                if (count($errorDetail) <= 0) {
                    foreach ($finalResult as $result) {
                        $result->save();
                    }
                    $errorDetail["Status"] = "upload file Academic_Year : " . $year . " Grade Level : " . $gradeLevel . " Room : " . $room . " success";

                } else {
                    $errorDetail["Status"] = "upload file Academic_Year : " . $year . " Grade Level : " . $gradeLevel . " Room : " . $room . " error";
                    /*
                    foreach($errorDetail as $key => $value){
                      print_r("Student ID : ".$key." got error => ".$value."</br>");
                    }*/

                }
                $errorArray[] = $errorDetail;


            }
        }


        return view('uploadGrade.errorDetail', ['errorDetail' => $errorArray]);

    } // END upload Attendance


    public function getUploadActivities(Request $request)
    {

        // dd($datetime);
        // dd($studentsID);
        // var_dump($studentsID);
        // print_r($studentsID);


        //$stdArray = $tempsss->unwrap($studentsID);

        // print_r($arr);
        //
        // if (in_array("1111111111", $arr)) {
        //     echo "Got My";
        // }

        if ($request->hasFile('file')) {
            $errorArray = array();

            foreach ($request->file as $file) {
                $finalResult = array();
                $errorDetail = array();
                $fact = true;
                $factGrade = true;
                $factValidate = true;
                $factEmpty = true;
                //$file = Input::file('file');
                $file_name = $file->getClientOriginalName();
                $file_type = \File::extension('files/' . $file_name);
                $file->move('files/', $file_name);
                $checkFileName = substr("$file_name", 0, 8);

                $getAcademicYear = Excel::load('files/' . $file_name, function ($reader) {
                    $reader->setHeaderRow(1);
                })->get();


                $getGradeLevel = Excel::load('files/' . $file_name, function ($reader) {
                    $reader->setHeaderRow(2);
                })->get();

                $getRoom = Excel::load('files/' . $file_name, function ($reader) {
                    $reader->setHeaderRow(3);
                })->get();

                $resultsStudent = Excel::load('files/' . $file_name, function ($reader) {
                    $reader->setHeaderRow(7);
                    $reader->all();
                })->get();


                $flipped = array_flip($resultsStudent->getHeading());
                $indexSecSem = $flipped["2st_semester"];

                //  for($i = 0; $i < count($resultsSemester); $i++)

                $resultsFirst = Excel::load('files/' . $file_name, function ($reader) use ($indexSecSem) {
                    $reader->setHeaderRow(5);
                    //$reader->limitColumns(7);
                    $reader->limitColumns($indexSecSem);
                    $reader->skipColumns(2);
                    $reader->all();
                })->get();

                $resultsSecond = Excel::load('files/' . $file_name, function ($reader) use ($indexSecSem) {
                    $reader->setHeaderRow(5);
                    //$reader->limitColumns(7);
                    $reader->skipColumns($indexSecSem);
                    $reader->all();
                })->get();

                //  dd($resultsFirst);

                $courseIDFirst = array();
                $courseIDSec = array();
                foreach ($resultsFirst[0] as $key => $val) {
                    if ($key !== 0) {
                        /*
                        //$id = strtoupper($key);
                        $id = $key;
                        $id = explode("_", $id);
                        $slice = array_slice($id, 0,-2);
                        foreach($slice as $key2 => $str){
                          $slice[$key2] = ucfirst($str);;
                        }
                        $res = join(' ', $slice);
                        //print_r(array_slice($id, -2));
              */
                        $courseIDFirst[$key] = $val;

                    }
                }

                foreach ($resultsSecond[0] as $key => $val) {
                    if ($key !== 0) {
                        $courseIDSec[$key] = $val;
                    }
                }


                //dd($courseIDSec);
                $year = $getAcademicYear->getHeading()[1];
                $gradeLevel = $getGradeLevel->getHeading()[1];
                $room = $getRoom->getHeading()[1];

                $courses = Academic_Year::Join('offered_courses', 'offered_courses.classroom_id', '=', 'academic_year.classroom_id')
                    ->Join('curriculums', function ($join) {
                        $join->on('curriculums.course_id', '=', 'offered_courses.course_id');
                        $join->on('curriculums.curriculum_year', '=', 'offered_courses.curriculum_year');
                    })
                    ->where('academic_year.academic_year', $year)
                    ->where('academic_year.room', $room)
                    ->where('academic_year.grade_level', $gradeLevel)
                    ->where('curriculums.is_activity', true)
                    ->select('offered_courses.open_course_id', 'offered_courses.semester', 'offered_courses.course_id', 'curriculums.course_name')
                    ->get();

                $courseArr = array();

                foreach ($courses as $course) {
                    $courseArr[$course->course_id . " " . $course->semester] = $course->open_course_id;
                }
                //dd($courseArr);

                $students = Student::all();
                $studentsID = Student::Join('student_grade_levels', 'student_grade_levels.student_id', '=', 'students.student_id')
                    ->Join('academic_year', 'academic_year.classroom_id', '=', 'student_grade_levels.classroom_id')
                    ->where('academic_year.academic_year', $year)
                    ->where('academic_year.room', $room)
                    ->where('academic_year.grade_level', $gradeLevel)
                    ->select('students.student_id', 'students.firstname', 'students.lastname')
                    ->get();

                $gradeStatus = Grade_Status::all();
                $GStatusArr = array();
                foreach ($gradeStatus as $status) {
                    $statusArr[$status->grade_status_text] = $status->grade_status;
                }

                $stdArray = array();
                $stdName = array();
                //$typeStatus = array("U","S","I");


                date_default_timezone_set('Asia/Bangkok');
                $datetime = date("Y-m-d H:i:s");

                foreach ($studentsID as $studentID) {
                    $stdArray[] = $studentID->student_id;
                    $stdName[(String)($studentID->student_id)] = $studentID->firstname . " " . $studentID->lastname;
                }

                for ($i = 0; $i < count($resultsStudent); $i++) {
                    if (in_array($resultsStudent[$i]->students_id, $stdArray)) {

                        if ($stdName[(String)($resultsStudent[$i]->students_id)] === $resultsStudent[$i]->students_name) {

                            foreach ($courseIDFirst as $key => $id) {
                                $resUp = strtoupper($resultsFirst[$i + 2]->$key);

                                if (array_key_exists($resUp, $statusArr)) {
                                    $activity = new Activity_Record;
                                    $activity->student_id = $resultsStudent[$i]->students_id;
                                    $activity->open_course_id = $courseArr[$id . " 1"];
                                    $activity->grade_status = $statusArr[$resUp];
                                    $activity->semester = 1;
                                    $activity->academic_year = $year;
                                    $activity->datetime = $datetime;
                                    $activity->data_status = 1;
                                    //  $activity->save();
                                    $finalResult[] = $activity;
                                } else if ($resUp !== "") {
                                    $errorDetail[(String)($resultsStudent[$i]->students_id)] = " Please check at this student ID " . $resultsStudent[$i]->students_id . " Grade must be S/U/I";
                                }

                            }

                            foreach ($courseIDSec as $key => $id) {
                                $resUp = strtoupper($resultsSecond[$i + 2]->$key);
                                if (array_key_exists($resUp, $statusArr)) {
                                    $activity = new Activity_Record;
                                    $activity->student_id = $resultsStudent[$i]->students_id;
                                    $activity->open_course_id = $courseArr[$id . " 2"];
                                    $activity->grade_status = $statusArr[$resUp];
                                    $activity->semester = 2;
                                    $activity->academic_year = $year;
                                    $activity->datetime = $datetime;
                                    $activity->data_status = 1;
                                    //  $activity->save();
                                    $finalResult[] = $activity;
                                } else if ($resUp !== "") {
                                    $errorDetail[(String)($resultsStudent[$i]->students_id)] = " Please check at this student ID " . $resultsStudent[$i]->students_id . " Grade must be S/U/I";
                                }
                            }
                        } else if ($stdName[(String)($resultsStudent[$i]->students_id)] !== $resultsStudent[$i]->students_name) {
                            $errorDetail[(String)($resultsStudent[$i]->students_id)] = $resultsStudent[$i]->students_id . " This student ID doesn't match with student name";
                        }

                    } else if (!in_array($resultsStudent[$i]->students_id, $stdArray)) {
                        $errorDetail[(String)($resultsStudent[$i]->students_id)] = $resultsStudent[$i]->students_id . " This Student ID doesn't exist in this room";
                    }

                }
                if (count($errorDetail) <= 0) {
                    foreach ($finalResult as $result) {
                        $result->save();
                    }
                    $errorDetail["Status"] = "upload file Academic_Year : " . $year . " Grade Level : " . $gradeLevel . " Room : " . $room . " success";

                } else {
                    $errorDetail["Status"] = "upload file Academic_Year : " . $year . " Grade Level : " . $gradeLevel . " Room : " . $room . " error";
                    /*
                    foreach($errorDetail as $key => $value){
                      print_r("Student ID : ".$key." got error => ".$value."</br>");
                    }*/

                }
                $errorArray[] = $errorDetail;
            }

        }


        return view('uploadGrade.errorDetail', ['errorDetail' => $errorArray]);

    } // END upload Activity


    public function getUpload(Request $request)
    {
        $studentsID = Student::select('student_id')->get();
        $arr = array();

        date_default_timezone_set('Asia/Bangkok');
        $datetime = date("Y-m-d H:i:s");
        // dd($datetime);
        // dd($studentsID);
        // var_dump($studentsID);
        // print_r($studentsID);
        foreach ($studentsID as $studentID) {
            $arr[] = $studentID->student_id;
        }

        $errorArray = array();

        if ($request->hasFile('file')) {
            // Process each upload file
            foreach ($request->file as $file) {

                // Get real file name not temp file name
                $file_name = $file->getClientOriginalName();
                Log::info("Processing file ".$file_name);
                $file_type = \File::extension('files/' . $file_name);
                $file->move('files/', $file_name);

                /*
                Create function for validating grade. It returns error text if there is
                any error.  Otherwise return null;
                */
                function validateGrade($data, $field, $column, $row)
                {
                    if ( $data == "" || $data == null
                        || ((is_int($data) || is_float($data)) && $data < 4.001 && $data > -0.001)
                        || (preg_match("/^([Ii]\/)?(([0-3][\.][0-9]*)|([0-4])|(4\.0*))$/", $data))                      || $data == "I" || $data == "i"
                        || strcasecmp($data ,"N/A") == 0
                        || $data == "S" || $data == "s"
                        || $data == "U" || $data == "u"
                        || $data == "I" || $data == "i"
                        || $data == "0/1"
                        || strcasecmp($data, "DROP") == 0){
                        // Ok return null;
                        return null;
                    } else {
                        return "Field '$field' is incorrect format at row '$column" . ($row + 7) . "'";
                    }
                }


                $importRow = count(\Excel::load('files/' . $file_name, function ($reader) {
                })->get());
                // dd($importRow);
                if ($importRow < 5) {
                    $isOK = false;
                    $text = "File ".$file_name." is not in correct format.";
                    $errorArray[] = $text;
                    $isOK = false;
                    //return view('uploadGrade.validate', compact('errorArray'));
                }
                else {
                    // Get grades of each student in class
                    $results = Excel::load('files/' . $file_name, function ($reader) {
                        $reader->setHeaderRow(6);
                        $reader->all();

                    })->get();

                    // Get course ID and formatting it properly
                    $resultsCourse = Excel::load('files/' . $file_name, function ($reader) {
                        $reader->setHeaderRow(2);
                    })->get();
                    $course_id = $resultsCourse->getHeading()[1];
                    $course_id = strtoupper($course_id);
                    $course_id = str_replace("_", " ", "$course_id");
                    $course_id = SystemConstant::clean_blank_spaces($course_id);

                    // Get grade level
                    $resultsGradeLevel = Excel::load('files/' . $file_name, function ($reader) {
                        $reader->setHeaderRow(3);
                    })->get();
                    $gradeLevel = $resultsGradeLevel->getHeading()[1];

                    // Get academic year
                    $resultsYear = Excel::load('files/' . $file_name, function ($reader) {
                        $reader->setHeaderRow(4);
                    })->get();
                    $year = $resultsYear->getHeading()[1];


                    if ($file_type == "xlsx" || $file_type == "xls") {
                        if (count($results) == 0) {
                            $isOK = false;
                            $errorArray[] = "File".$file_name." is empty";
                            return view('uploadGrade.validate', compact('errorArray'));
                        } else {
                            if ($isOK) {
                                for ($i = 0; $i < count($results); $i++) {
                                    //----- Validate Student ID -------//
                                    if ($results[$i]->student_id == "") {
                                        $text = "Field 'Student ID' is empty at row 'A" . ($i + 7) . "'";
                                        $factValidate = false;
                                        $factEmpty = false;
                                        $errorArray[] = $text;
                                    } elseif ($results[$i]->student_id != "") {
                                        if (in_array($results[$i]->student_id, $arr)) {

                                        } else {
                                            $text = "'Student ID' at row 'A" . ($i + 7) . "' is not in database.";
                                            $factValidate = false;
                                            $factEmpty = false;
                                            $errorArray[] = $text;
                                        }
                                    }

                                    //----- Validate Student Name -------//
                                    if (!preg_match("/^[a-zA-Z ]*$/", $results[$i]->student_name)) {
                                        // echo "Field 'Student name' is incorrect format at row 'B".($i+6)."'<br>";
                                        $text = "Field 'Student name' is incorrect format at row 'B" . ($i + 7) . "'";
                                        $factValidate = false;
                                        $errorArray[] = $text;
                                    }

                                    if ($course_id == "") {
                                        $text = "Field 'Course' is empty at row 'B2'";
                                        $factValidate = false;
                                        $factEmpty = false;
                                        $errorArray[] = $text;
                                    }
                                    if ($year == "") {
                                        $text = "Field 'Academic Year' is empty at row 'B4'";
                                        $factValidate = false;
                                        $factEmpty = false;
                                        $errorArray[] = $text;
                                    }

                                    //----- Validate Q1 -------//
                                    $errorArray[] = validateGrade($results[$i]->q1, "Q1", "C", $factGrade, $i);

                                    //----- Validate Q2 -------//
                                    $errorArray[] = validateGrade($results[$i]->q2, "Q2", "D", $factGrade, $i);

                                    //----- Validate SUM1 -------//
                                    $errorArray[] = validateGrade($results[$i]->q2, "SUM 1", "E", $factGrade, $i);


                                    //----- Validate Q3 -------//
                                    $errorArray[] = validateGrade($results[$i]->q3, "Q3", "G", $factGrade, $i);


                                    //----- Validate Q4 -------//
                                    $errorArray[] = validateGrade($results[$i]->q4, "Q4", "H", $factGrade, $i);

                                    //----- Validate SUM2 -------//
                                    $errorArray[] = validateGrade($results[$i]->q2, "SUM 2", "I", $factGrade, $i);
                                }

                            }

                            if ($factValidate == TRUE) {
                                // dd($results);
                                for ($i = 0; $i < count($results); $i++) {

                                    // Getting course ID
                                    $open_course_id_template = Academic_Year::where('academic_year', $year)
                                        ->join('student_grade_levels',
                                            'student_grade_levels.classroom_id',
                                            'academic_year.classroom_id')
                                        ->where('student_grade_levels.student_id',$results[$i]->student_id)
                                        ->join('offered_courses','offered_courses.classroom_id',
                                            'academic_year.classroom_id')
                                        ->where('offered_courses.course_id',$course_id);


                                    //Log::info($year ." ".$results[$i]->student_id." ".$getCourseID);
                                    //Log::info((clone $open_course_id_template)
                                    //    ->where('offered_courses.semester',1)->toSql());
                                    $openCourseIDSem1 = (clone $open_course_id_template)
                                        ->where('offered_courses.semester',1)
                                        ->value('open_course_id');
                                    //Log::info($openCourseIDSem1);
                                    $openCourseIDSem2 = (clone $open_course_id_template)
                                        ->where('offered_courses.semester',2)
                                        ->value('open_course_id');
                                    //Log::info($openCourseIDSem2);
                                    //-------------------- add Q1 -----------------
                                    $this->set_grade(
                                        $results[$i]->q1,
                                        $results[$i]->student_id,
                                        $openCourseIDSem1,
                                        '1', '1', $year, $datetime
                                    );

                                    //-------------------- add Q2 -----------------
                                    $this->set_grade(
                                        $results[$i]->q2,
                                        $results[$i]->student_id,
                                        $openCourseIDSem1,
                                        '2', '1', $year, $datetime
                                    );

                                    //-------------------- add SUM 1 -----------------
                                    $this->set_grade(
                                        $results[$i]->sum_1,
                                        $results[$i]->student_id,
                                        $openCourseIDSem1,
                                        '3', '1', $year, $datetime
                                    );

                                    //-------------------- add Q3 -----------------
                                    $this->set_grade(
                                        $results[$i]->q3,
                                        $results[$i]->student_id,
                                        $openCourseIDSem2,
                                        '1', '2', $year, $datetime
                                    );


                                    //-------------------- add Q4 -----------------
                                    $this->set_grade(
                                        $results[$i]->q4,
                                        $results[$i]->student_id,
                                        $openCourseIDSem2,
                                        '2', '2', $year, $datetime
                                    );

                                    //-------------------- add SUM 2 -----------------
                                    $this->set_grade(
                                        $results[$i]->sum_2,
                                        $results[$i]->student_id,
                                        $openCourseIDSem2,
                                        '3', '2', $year, $datetime
                                    );
                                }

                                return view('uploadGrade.getUpload', compact('results'));
                            } elseif ($factValidate == FALSE) {
                                //var_dump($arrayValidates);
                                return view('uploadGrade.validate', compact('errorArray'));
                            }

                        }

                    } else {
                        $isOK = false;
                        $errorArray[] =  $file_name." file's type is not xlsx or xls.";
                    }
                }


            }
        } elseif (!($request->hasFile('file'))) {
            $isOK = false;
            $errorArray[] = "Please Select File";
        }


        return view('uploadGrade.errorDetail', ['errorDetail' => $errorArray]);
    }

    /**
     * @param $grade_value Grade read from excel
     */
    private function set_grade($grade_value, $student_id, $open_course_id,
                               $quater, $semester, $academic_year, $datetime)
    {
        if ($open_course_id === null) {
            // Don't do anything if the course does not open
            return;
        }
        $grade = new Grade;
        $grade->student_id = $student_id;
        $grade->open_course_id = $open_course_id;
        $grade->quater = $quater;
        $grade->semester = $semester;
        $grade->academic_year = $academic_year;
        $grade->datetime = $datetime;
        if ($grade_value !== null) {
            if ($grade_value == "No grade" ||
                $grade_value == "no grade" || $grade_value == "no grade") {
                $grade->grade_status = '0';
                $grade->grade = '0';
            } elseif ($grade_value == "I" || $grade_value == "i") {
                $grade->grade_status = '1';
                $grade->grade = '0';
            } elseif ($grade_value == "S" || $grade_value == "s") {
                $grade->grade_status = 2;
                $grade->grade = '0';
            } elseif ($grade_value == "U" || $grade_value == "u") {
                $grade->grade_status = '3';
                $grade->grade = '0';
            } elseif ($grade_value == "0/1") {
                $grade->grade_status = '4';
                $grade->grade = '1';
            } else {
                $grade->grade_status = '5';
                $grade->grade = $grade_value;
            }
            $grade->data_status = '0';
            $grade->save();
        }
    }

}
