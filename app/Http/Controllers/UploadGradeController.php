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


    public function getUploadComments(Request $request)
    {

        if ($request->hasFile('file')) {
            $errorArray = array();

            foreach ($request->file as $file) {

                list($file_name, $file_type) = $this->storeFile($file);

                list($year, $gradeLevel, $room) = $this->get_room_grade_year($file_name);

                // Read all comments
                $results = Excel::load(SystemConstant::FILE_STORE_DIR . '/' . $file_name, function ($reader) {
                    $reader->setHeaderRow(4);
                    $reader->all();
                })->get();

                // Get all students and and ID in the class
                $students = $this->get_students_in_class($year, $gradeLevel, $room);

                date_default_timezone_set('Asia/Bangkok');
                $datetime = date("Y-m-d H:i:s");

                if (count($results) == 0) {
                    $errorArray[] = "File" . $file_name . " is empty";
                } else {
                    // Set up flag so that we stop when there is any error
                    $isOK = true;

                    // TODO Check correctness of error message
                    if ($year == "") {
                        $isOK = false;
                        $errorArray[] = $file_name . " Field 'Academic Year' is empty at row '?'";
                    }

                    if ($gradeLevel == "") {
                        $isOK = false;
                        $errorArray[] = $file_name . " Field 'Grade Level' is empty at row '?'";
                    }

                    if ($room == "") {
                        $isOK = false;
                        $errorArray[] = $file_name . " Field 'Room' is empty at row '?'";
                    }

                    // Loop and check correctness of each result  We need to use index
                    // because we will remove irrelevant data from result.
                    for ($i = 0; $i < count($results); $i++) {
                        //----- Validate Student ID and name-------//
                        // Get ID and Name and clean up unnecessary spaces
                        $student_id = trim($results[$i]->students_id);
                        $results[$i]->students_id = $student_id;
                        $student_name = SystemConstant::clean_blank_spaces($results[$i]->students_name);
                        $results[$i]->students_name = $student_name;

                        // Check if name and id are empty or not
                        if ($student_id == "" && $student_name == "") {
                            // This is not the line we want to read. Drop it from processing
                            unset($results[$i]);
                        } elseif ($student_id == "") {
                            // Only id is empty.  This should be error
                            $isOK = false;
                            $errorArray[] = $file_name . " Field 'Student ID' is empty at row 'A" . ($i + 7) . "'";
                        } elseif (!isset($students[$student_id])) {
                            // We have both name and ID
                            // Check if ID is in database
                            $isOK = false;
                            $errorArray[] = $file_name . " 'Student ID' ".$student_id." at row 'A" . ($i + 7) . "' is not in database.";
                        } elseif (strcasecmp($students[$student_id], $student_name) != 0) {
                            // Check if student name matches with ID
                            $isOK = false;
                            $errorArray[] = $file_name . " Field 'Student name' ".$students[$student_id]." is incorrect at row 'B" . ($i + 7) . "'";
                        }
                    }

                    // Add comment when there is no error
                    if ($isOK) {
                        foreach ($results as $r) {
                            $q_count = 1;
                            for ($semester = 1; $semester <= SystemConstant::TOTAL_SEMESTERS; $semester++) {
                                for ($quarter = 1; $quarter <= SystemConstant::TOTAL_QUARTERS; $quarter++) {
                                    $qComment = "quarter_" . $q_count;
                                    $q_count++;
                                    // Only add when the comment is not empty
                                    if (trim($r[$qComment]) != "") {
                                        $comment = new Teacher_Comment;
                                        $comment->student_id = $r->students_id;
                                        $comment->quarter = $quarter;
                                        $comment->comment = $r->$qComment;
                                        $comment->semester = $semester;
                                        $comment->academic_year = $year;
                                        $comment->datetime = $datetime;
                                        $comment->data_status = SystemConstant::DATA_STATUS_WAIT;
                                        $comment->save();
                                    //    Log::info("Add ".$r);
                                    }
                                }
                            }
                        }
                        $errorArray[] = $file_name . " is uploaded successfully.";
                    }
                }
            }
        }

        return view('uploadGrade.validate', compact('errorArray'));

    } // END upload Comment

    public function getUploadHeightAndWeight(Request $request)
    {

        if ($request->hasFile('file')) {
            $errorArray = array();

            foreach ($request->file as $file) {

                $finalResult = array();
                $errorDetail = array();

                list($file_name, $file_type) = $this->storeFile($file);

                $getAcademicYear = Excel::load(SystemConstant::FILE_STORE_DIR . '/' . $file_name, function ($reader) {
                    $reader->setHeaderRow(1);
                })->get();


                $getGradeLevel = Excel::load(SystemConstant::FILE_STORE_DIR . '/' . $file_name, function ($reader) {
                    $reader->setHeaderRow(2);
                })->get();

                $getRoom = Excel::load(SystemConstant::FILE_STORE_DIR . '/' . $file_name, function ($reader) {
                    $reader->setHeaderRow(3);
                })->get();

                $results = Excel::load(SystemConstant::FILE_STORE_DIR . '/' . $file_name, function ($reader) {
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
        if ($request->hasFile('file')) {
            $errorArray = array();

            foreach ($request->file as $file) {

                list($file_name, $file_type) = $this->storeFile($file);

                list($year, $gradeLevel, $room) = $this->get_room_grade_year($file_name);

                $results = Excel::load(SystemConstant::FILE_STORE_DIR . '/' . $file_name, function ($reader) {
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

                // Get the list of students and their ids
                /*
                 * "students_id" => 2560780994.0
        "students_name" => "Chanok Waraporn"
        "q1" => 3.0
        "q2" => 3.0
        "q3" => null
        "q4" => null
                */
                $resultsStudent = Excel::load(SystemConstant::FILE_STORE_DIR . '/' . $file_name, function ($reader) {
                    $reader->setHeaderRow(6);
                    $reader->all();
                })->get();

                // Get all students and and ID in the class
                $students = $this->get_students_in_class($year, $gradeLevel, $room);

                $STUDENT_ROW_START = 6;

                /*
                Check correctness of ids and name and construct id array
                that will be null when we want to skip that entry
                */
                $total_students = count($resultsStudent);
                $student_ids = array_fill(0,$total_students,null);
                $isOK = true;
                for($i = 0; $i < $total_students; $i++){
                    $student_id = trim($resultsStudent[$i]->students_id);
                    $student_name = SystemConstant::clean_blank_spaces($resultsStudent[$i]->students_name);

                    // Check if name and id are empty or not
                    if ($student_id == "" && $student_name == "") {
                        // This is not the line we want to read. Drop it from processing by doing nothing
                    } elseif ($student_id == "") {
                        // Only id is empty.  This should be error
                        $isOK = false;
                        $errorArray[] = $file_name . " Field 'Student ID' is empty at 'A" . ($i + 7) . "'";
                    } elseif (!isset($students[$student_id])) {
                        // We have both name and ID
                        // Check if ID is in database
                        $isOK = false;
                        $errorArray[] = $file_name . " 'Student ID' at 'A" . ($i + 7) . "' is not in database.";
                    } elseif (strcasecmp($students[$student_id], $student_name) != 0) {
                        // Check if student name matches with ID
                        $isOK = false;
                        $errorArray[] = $file_name . " Field 'Student name' is incorrect at 'B" . ($i + 7) . "'";
                    }
                }

                date_default_timezone_set('Asia/Bangkok');
                $datetime = date("Y-m-d H:i:s");


                // Loop for each behavior type if there is no error in name
                if ($isOK) {
                    $finalResult = array();
                    foreach ($behavior_col as $behavior_type => $col_index) {
                        // Read grade of all students for that behavior type
                        $results = Excel::load(SystemConstant::FILE_STORE_DIR . '/' . $file_name, function ($reader) use ($col_index) {
                            $reader->skipColumns($col_index);
                            $reader->limitColumns($col_index + 4);
                            $reader->setHeaderRow(6);
                            $reader->all();
                        })->get();

                        // For each students we insert grade for this behavior
                        foreach ($results as $data) {
                            if ($data->students_id != "") {
                                $i = 1;
                                for ($semester = 1; $semester <= SystemConstant::TOTAL_SEMESTERS; $semester++) {
                                    for ($quarter = 1; $quarter <= SystemConstant::TOTAL_QUARTERS; $quarter++) {
                                        $v = $data['q' . $i];
                                        if(is_numeric($v) &&
                                            $v > 0-SystemConstant::MIN_TO_ZERO &&
                                            $v < 4+SystemConstant::MIN_TO_ZERO){
                                            $behavior = new Behavior_Record;
                                            $behavior->student_id = $data['students_id'];
                                            $behavior->quarter = $quarter;
                                            $behavior->behavior_type = $behavior_type;
                                            $behavior->grade = $data['q' . $i];
                                            $behavior->semester = $semester;
                                            $behavior->academic_year = $year;
                                            $behavior->datetime = $datetime;
                                            $behavior->data_status = 1; //TODO change when implemented approve behavior
                                            $finalResult[] = $behavior;
                                        }elseif(trim($v) != ""){
                                            // If this is not empty then something is wrong
                                            $isOK = false;
                                            $errorArray[] = $file_name . " score ".$v." is not correct for student " . ($i + $STUDENT_ROW_START+1) . " " . $data->students_id. "'";
                                        }
                                        $i++;
                                    }
                                }
                            }

                        }
                    }
                }

                if ($isOK) {
                    foreach ($finalResult as $result) {
                        $result->save();
                    }
                    $errorArray[] = "Upload file " . $file_name . " Academic_Year : " . $year . " Grade Level : " . $gradeLevel . " Room : " . $room . " success";

                }
            }
        }

        return view('uploadGrade.validate', compact('errorArray'));

    } // END upload Behavior


    public function getUploadAttendance(Request $request)
    {
        if ($request->hasFile('file')) {
            $errorArray = array();

            foreach ($request->file as $file) {

                $finalResult = array();

                list($file_name, $file_type) = $this->storeFile($file);

                list($year, $gradeLevel, $room) = $this->get_room_grade_year($file_name);

                $results = Excel::load(SystemConstant::FILE_STORE_DIR . '/' . $file_name, function ($reader) {
                    $reader->setHeaderRow(4);
                    $reader->all();
                })->get();

                $resultsStudent = Excel::load(SystemConstant::FILE_STORE_DIR . '/' . $file_name, function ($reader) {
                    $reader->setHeaderRow(5);
                    $reader->all();
                })->get();

                // Get all students and and ID in the class
                $students = $this->get_students_in_class($year, $gradeLevel, $room);

                date_default_timezone_set('Asia/Bangkok');
                $datetime = date("Y-m-d H:i:s");

                $isOK = true;
                $STUDENT_ROW_START = 5;
                $DATA_START = 1;

                for ($i = 0; $i < count($resultsStudent); $i++) {

                    // Clean up name and id
                    $student_id = trim($resultsStudent[$i]->students_id);
                    $student_name = SystemConstant::clean_blank_spaces($resultsStudent[$i]->students_name);
                    // Check if name and id are empty or not
                    if ($student_id == "" && $student_name == "") {
                        // This is not the line we want to read. Skip it
                    } elseif ($student_id == "") {
                        // Only id is empty.  This should be error
                        $isOK = false;
                        $errorArray[] = $file_name . " Field 'Student ID' is empty at row 'A" . ($i + $STUDENT_ROW_START) . "'";
                    } elseif (!isset($students[$student_id])) {
                        // We have both name and ID
                        // Check if ID is in database
                        $isOK = false;
                        $errorArray[] = $file_name . " 'Student ID' at row 'A" . ($i + $STUDENT_ROW_START) . "' is not in database.";
                    } elseif (strcasecmp($students[$student_id], $student_name) != 0) {
                        // Check if student name matches with ID
                        $isOK = false;
                        $errorArray[] = $file_name . " Field 'Student name' is incorrect at row 'B" . ($i + $STUDENT_ROW_START) . "'";
                    } else {

                        $attendance = new Attendance_Record;
                        $attendance->student_id = $student_id;
                        $data = $results[$i + $DATA_START];
                        $isNotEmprty = false;
                        // We are assuming that Excel Parser convert text to number of us
                        if (is_numeric($data->late)) {
                            $attendance->late = $data->late;
                            $isNotEmprty = true;
                        } elseif
                        (trim($data->late) == "") {
                            $attendance->late = 0;
                        } else {
                            // This is not OK.  Tell user
                            $isOK = false;
                            $errorArray[] = $file_name . " late value is not a number in Row " . ($i + $STUDENT_ROW_START) . "'";
                        }

                        if (is_numeric($data->absent)) {
                            $attendance->absent = $data->absent;
                            $isNotEmprty = true;
                        } elseif
                        (trim($data->absent) == "") {
                            $attendance->absent = 0;
                        } else {
                            // This is not OK.  Tell user
                            $isOK = false;
                            $errorArray[] = $file_name . " absent value is not a number in Row " . ($i + $STUDENT_ROW_START) . "'";
                        }

                        if (is_numeric($data->leave)) {
                            $attendance->leave = $data->leave;
                            $isNotEmprty = true;
                        } elseif(trim($data->leave) == "") {
                            $attendance->leave = 0;
                        } else {
                            // This is not OK.  Tell user
                            $isOK = false;
                            $errorArray[] = $file_name . " leave value is not a number in Row " . ($i + $STUDENT_ROW_START) . "'";
                        }

                        if (is_numeric($data->sick)) {
                            $attendance->sick = $data->sick;
                            $isNotEmprty = true;
                        } elseif(trim($data->sick) == "") {
                            $attendance->sick = 0;
                        } else {
                            // This is not OK.  Tell user
                            $isOK = false;
                            $errorArray[] = $file_name . " sick value is not a number in Row " . ($i + $STUDENT_ROW_START) . "'";
                        }

                        $attendance->semester = 1;
                        $attendance->academic_year = $year;
                        $attendance->datetime = $datetime;
                        $attendance->data_status = 1;  //TODO Attendance approval is not implemented yet

//                        if ($isNotEmprty) {
                            $finalResult[] = $attendance;
//                        }

                        $isNotEmprty = false;
                        $attendance = new Attendance_Record;
                        $attendance->student_id = $student_id;
                        $data = $results[$i + $DATA_START];

                        // We are assuming that Excel Parser convert text to number of us
                        if (is_numeric($data->late_s2)) {
                            $attendance->late = $data->late_s2;
                            $isNotEmprty = true;
                        } elseif(trim($data->late_s2) == "") {
                            $attendance->late = 0;
                        } else {
                            // This is not OK.  Tell user
                            $isOK = false;
                            $errorArray[] = $file_name . " late value is not a number in Row " . ($i + $STUDENT_ROW_START) . "'";
                        }

                        if (is_numeric($data->absent_s2)) {
                            $attendance->absent = $data->absent_s2;
                            $isNotEmprty = true;
                        } elseif(trim($data->absent_s2) == "") {
                            $attendance->absent = 0;
                        } else {
                            // This is not OK.  Tell user
                            $isOK = false;
                            $errorArray[] = $file_name . " absent value is not a number in Row " . ($i + $STUDENT_ROW_START) . "'";
                        }

                        if (is_numeric($data->leave_s2)) {
                            $attendance->leave = $data->leave_s2;
                            $isNotEmprty = true;
                        } elseif(trim($data->leave_s2) == "") {
                            $attendance->leave = 0;
                        } else {
                            // This is not OK.  Tell user
                            $isOK = false;
                            $errorArray[] = $file_name . " leave value is not a number in Row " . ($i + $STUDENT_ROW_START) . "'";
                        }

                        if (is_numeric($data->sick_s2)) {
                            $attendance->sick = $data->sick_s2;
                            $isNotEmprty = true;
                        } elseif(trim($data->sick_s2) == "") {
                            $attendance->sick = 0;
                        } else {
                            // This is not OK.  Tell user
                            $isOK = false;
                            $errorArray[] = $file_name . " sick value is not a number in Row " . ($i + $STUDENT_ROW_START) . "'";
                        }

                        $attendance->semester = 2;
                        $attendance->academic_year = $year;
                        $attendance->datetime = $datetime;
                        $attendance->data_status = 1;  //TODO Attendance approval is not implemented yet

//                        if ($isNotEmprty) {
                            $finalResult[] = $attendance;
//                        }
                    }

                }
                if ($isOK) {
                    foreach ($finalResult as $result) {
                        $result->save();
                    }
                    $errorArray[] = "Upload file " . $file_name . " Academic_Year : " . $year . " Grade Level : " . $gradeLevel . " Room : " . $room . " success";

                }
            }
        }

        return view('uploadGrade.validate', compact('errorArray'));

    } // END upload Attendance


    public function getUploadActivities(Request $request)
    {
        if ($request->hasFile('file')) {
            $errorArray = array();

            foreach ($request->file as $file) {
                list($file_name, $file_type) = $this->storeFile($file);

                list($year, $gradeLevel, $room) = $this->get_room_grade_year($file_name);

                /* Get array of Student ID and name
                "students_id" => 2560780994.0
                "students_name" => "Chanok Waraporn"
                "1st_semester" => null
                0 => null
                "2st_semester" => null
                */
                $resultsStudent = Excel::load(SystemConstant::FILE_STORE_DIR . '/' . $file_name, function ($reader) {
                    $reader->setHeaderRow(7);
                    $reader->all();
                })->get();

                $flipped = array_flip($resultsStudent->getHeading());
                $indexSecSem = $flipped["2st_semester"];

                /* Get array of following. It should be noted that the real data
                At index 0 of the array gives Course name -> Course ID mapping
                "newspaper" => "NSP 1"
        "shadowing" => "SHW"
        "yearbook" => "YB 1"
        "homeroom_5" => "ก 33913"
        "extra_curricular_activities_5" => "ก 33923"
        "guidance_and_developmental_skills_5" => "ก 33953"
        "social_spirit_5" => "ก 33973"

                From index 2 of the array data give grade for each student
                "newspaper" => null
        "shadowing" => null
        "yearbook" => "S"
        "homeroom_5" => "S"
        "extra_curricular_activities_5" => "S"
        "guidance_and_developmental_skills_5" => "S"
        "social_spirit_5" => null
                */
                $resultsFirst = Excel::load(SystemConstant::FILE_STORE_DIR . '/' . $file_name, function ($reader) use ($indexSecSem) {
                    $reader->setHeaderRow(5);
                    //$reader->limitColumns(7);
                    $reader->limitColumns($indexSecSem);
                    $reader->skipColumns(2);
                    $reader->all();
                })->get();

                $resultsSecond = Excel::load(SystemConstant::FILE_STORE_DIR . '/' . $file_name, function ($reader) use ($indexSecSem) {
                    $reader->setHeaderRow(5);
                    //$reader->limitColumns(7);
                    $reader->skipColumns($indexSecSem);
                    $reader->all();
                })->get();

                // Extract course name to course id mapping for each semester
                $courseIDFirst = array();
                $courseIDSec = array();
                foreach ($resultsFirst[0] as $key => $val) {
                    if ($key !== 0) {
                        $courseIDFirst[$key] = $val;
                    }
                }

                foreach ($resultsSecond[0] as $key => $val) {
                    if ($key !== 0) {
                        $courseIDSec[$key] = $val;
                    }
                }

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

                // Get all students and and ID in the class
                $students = $this->get_students_in_class($year, $gradeLevel, $room);

                date_default_timezone_set('Asia/Bangkok');
                $datetime = date("Y-m-d H:i:s");

                $isOK = true;
                $finalResult = array();

                $STUDENT_ROW_START = 7;
                $STUDENT_GRADE_START = 2;

                for ($i = 0; $i < count($resultsStudent); $i++) {

                    // Clean up name and id
                    $student_id = trim($resultsStudent[$i]->students_id);
                    $student_name = SystemConstant::clean_blank_spaces($resultsStudent[$i]->students_name);

                    // Check if name and id are empty or not
                    if ($student_id == "" && $student_name == "") {
                        // This is not the line we want to read. Skip it
                    } elseif ($student_id == "") {
                        // Only id is empty.  This should be error
                        $isOK = false;
                        $errorArray[] = $file_name . " Field 'Student ID' is empty at row 'A" . ($i + $STUDENT_ROW_START) . "'";
                    } elseif (!isset($students[$student_id])) {
                        // We have both name and ID
                        // Check if ID is in database
                        $isOK = false;
                        $errorArray[] = $file_name . " 'Student ID' at row 'A" . ($i + $STUDENT_ROW_START) . "' is not in database.";
                    } elseif (strcasecmp($students[$student_id], $student_name) != 0) {
                        // Check if student name matches with ID
                        $isOK = false;
                        $errorArray[] = $file_name . " Field 'Student name' is incorrect at row 'B" . ($i + $STUDENT_ROW_START) . "'";
                    } else {
                        foreach ($courseIDFirst as $key => $id) {
                            $gradeRawValue = preg_replace('/\s+/', '', $resultsFirst[$i + $STUDENT_GRADE_START]->$key);
                            $error = $this->validateGrade($gradeRawValue, $id, "", $i + $STUDENT_ROW_START);
                            if ($error !== null) {
                                $isOK = false;
                                $errorArray[] = $error;
                            } elseif ($gradeRawValue != "") {
                                // Get grade status
                                list($dummy, $grade_status) = $this->parseGrade($gradeRawValue);

                                $activity = new Activity_Record;
                                $activity->student_id = $student_id;
                                $activity->open_course_id = $courseArr[$id . " 1"];
                                $activity->grade_status = $grade_status;
                                $activity->semester = 1;
                                $activity->academic_year = $year;
                                $activity->datetime = $datetime;
                                $activity->data_status = SystemConstant::DATA_STATUS_WAIT;
                                $finalResult[] = $activity;
                            }
                        }

                        foreach ($courseIDSec as $key => $id) {
                            $gradeRawValue = preg_replace('/\s+/', '', $resultsSecond[$i + $STUDENT_GRADE_START]->$key);
                            $error = $this->validateGrade($gradeRawValue, $id, "", $i + $STUDENT_ROW_START);
                            if ($error !== null) {
                                $isOK = false;
                                $errorArray[] = $error;
                            } elseif ($gradeRawValue != "") {
                                // Get grade status
                                list($dummy, $grade_status) = $this->parseGrade($gradeRawValue);

                                $activity = new Activity_Record;
                                $activity->student_id = $resultsStudent[$i]->students_id;
                                $activity->open_course_id = $courseArr[$id . " 2"];
                                $activity->grade_status = $grade_status;
                                $activity->semester = 2;
                                $activity->academic_year = $year;
                                $activity->datetime = $datetime;
                                $activity->data_status = SystemConstant::DATA_STATUS_WAIT;
                                $finalResult[] = $activity;
                            }
                        }
                    }
                }
                if ($isOK) {
                    foreach ($finalResult as $result) {
                        $result->save();
                    }
                    $errorArray[] = "Upload file " . $file_name . " Academic_Year : " . $year . " Grade Level : " . $gradeLevel . " Room : " . $room . " success";

                }
            }
        }

        return view('uploadGrade.validate', compact('errorArray'));

    } // END upload Activity


    public function getUpload(Request $request)
    {
        date_default_timezone_set('Asia/Bangkok');
        $datetime = date("Y-m-d H:i:s");

        $errorArray = array();

        if ($request->hasFile('file')) {
            // Process each upload file
            foreach ($request->file as $file) {

                // Get real file name not temp file name
                list($file_name, $file_type) = $this->storeFile($file);

                $importRow = count(\Excel::load(SystemConstant::FILE_STORE_DIR . '/' . $file_name, function ($reader) {
                })->get());
                // Check if there are enough header
                if ($importRow < 5) {
                    $errorArray[] = "File " . $file_name . " is not in correct format. Try copy data to a new grade file downloaded from Export Form.";
                } else {
                    // Get grades of each student in class
                    $results = Excel::load(SystemConstant::FILE_STORE_DIR . '/' . $file_name, function ($reader) {
                        $reader->setHeaderRow(6);
                        $reader->all();

                    })->get();

                    // Get course ID and formatting it properly
                    $resultsCourse = Excel::load(SystemConstant::FILE_STORE_DIR . '/' . $file_name, function ($reader) {
                        $reader->setHeaderRow(2);
                    })->get();
                    $course_id = $resultsCourse->getHeading()[1];
                    $course_id = strtoupper($course_id);
                    $course_id = str_replace("_", " ", "$course_id");
                    $course_id = SystemConstant::clean_blank_spaces($course_id);

                    // Get grade level
                    $resultsGradeLevel = Excel::load(SystemConstant::FILE_STORE_DIR . '/' . $file_name, function ($reader) {
                        $reader->setHeaderRow(3);
                    })->get();
                    $gradeLevel = $resultsGradeLevel->getHeading()[1];

                    // Get academic year
                    $resultsYear = Excel::load(SystemConstant::FILE_STORE_DIR . '/' . $file_name, function ($reader) {
                        $reader->setHeaderRow(4);
                    })->get();
                    $year = trim($resultsYear->getHeading()[1]);

                    // Get all students that can take this course
                    $students_query = Academic_Year::where('academic_year', $year)
                        ->join('student_grade_levels',
                            'student_grade_levels.classroom_id',
                            'academic_year.classroom_id')
                        ->join('offered_courses', 'offered_courses.classroom_id',
                            'academic_year.classroom_id')
                        ->where('offered_courses.course_id', $course_id)
                        ->join('students', 'students.student_id',
                            'student_grade_levels.student_id')
                        ->select('students.student_id', 'students.firstname', 'students.lastname')
                        ->get();
                    $students = array();
                    foreach ($students_query as $r) {
                        $students[$r->student_id] = $r->firstname . " " . $r->lastname;
                    }

                    // Check file extension.
                    if ($file_type == "xlsx" || $file_type == "xls") {
                        // Check number of data in the file
                        if (count($results) == 0) {
                            $errorArray[] = "File" . $file_name . " is empty";
                        } else {
                            // Set up flag so that we stop when there is any error
                            $isOK = true;

                            if ($course_id == "") {
                                $isOK = false;
                                $errorArray[] = $file_name . " Field 'Course' is empty at row 'B2'";
                            }

                            if ($year == "") {
                                $isOK = false;
                                $errorArray[] = $file_name . " Field 'Academic Year' is empty at row 'B4'";
                            }

                            // Loop and check correctness of each result
                            for ($i = 0; $i < count($results); $i++) {

                                //----- Validate Student ID and name-------//
                                // Get ID and Name and clean up unnecessary spaces
                                $student_id = trim($results[$i]->student_id);
                                $results[$i]->student_id = $student_id;
                                $student_name = SystemConstant::clean_blank_spaces($results[$i]->student_name);
                                $results[$i]->student_name = $student_name;

                                // Check if name and id are empty or not
                                if ($student_id == "" && $student_name == "") {
                                    // This is not the line we want to read. Drop it from processing
                                    unset($results[$i]);
                                } elseif ($student_id == "") {
                                    // Only id is empty.  This should be error
                                    $isOK = false;
                                    $errorArray[] = $file_name . " Field 'Student ID' is empty at row 'A" . ($i + 7) . "'";
                                } elseif (!isset($students[$student_id])) {
                                    // We have both name and ID
                                    // Check if ID is in database
                                    $isOK = false;
                                    $errorArray[] = $file_name . " 'Student ID' ".$student_id." at row 'A" . ($i + 7) . "' is not in database.";
                                } elseif (strcasecmp($students[$student_id], $student_name) != 0) {
                                    // Check if student name matches with ID
                                    $isOK = false;
                                    $errorArray[] = $file_name . " Field 'Student name' ".$students[$student_id]." is incorrect at row 'B" . ($i + 7) . "'";
                                } else {
                                    //----- Validate Q1 -------//
                                    // Clean white space
                                    $results[$i]->q1 = preg_replace('/\s+/', '', $results[$i]->q1);
                                    $error = $this->validateGrade($results[$i]->q1, "Q1", "C", $i);
                                    if ($error !== null) {
                                        $isOK = false;
                                        $errorArray[] = $file_name .":". $error;
                                    }

                                    //----- Validate Q2 -------//
                                    // Clean white space
                                    $results[$i]->q2 = preg_replace('/\s+/', '', $results[$i]->q2);
                                    $error = $this->validateGrade($results[$i]->q2, "Q2", "D", $i);
                                    if ($error !== null) {
                                        $isOK = false;
                                        $errorArray[] = $file_name .":". $error;
                                    }


                                    //----- Validate SUM1 -------//
                                    // Clean white space
                                    $results[$i]->sum_1 = preg_replace('/\s+/', '', $results[$i]->sum_1);

                                    $error = $this->validateGrade($results[$i]->sum_1, "SUM 1", "E", $i);
                                    if ($error !== null) {
                                        $isOK = false;
                                        $errorArray[] = $file_name .":". $error;
                                    }


                                    //----- Validate Q3 -------//
                                    // Clean white space
                                    $results[$i]->q3 = preg_replace('/\s+/', '', $results[$i]->q3);
                                    $error = $this->validateGrade($results[$i]->q3, "Q3", "G", $i);
                                    if ($error !== null) {
                                        $isOK = false;
                                        $errorArray[] = $file_name .":". $error;
                                    }


                                    //----- Validate Q4 -------//
                                    // Clean white space
                                    $results[$i]->q4 = preg_replace('/\s+/', '', $results[$i]->q4);

                                    $error = $this->validateGrade($results[$i]->q4, "Q4", "H", $i);
                                    if ($error !== null) {
                                        $isOK = false;
                                        $errorArray[] = $file_name .":". $error;
                                    }

                                    //----- Validate SUM2 -------//
                                    // Clean white space
                                    $results[$i]->sum_2 = preg_replace('/\s+/', '', $results[$i]->sum_2);

                                    $error = $this->validateGrade($results[$i]->sum_2, "SUM 2", "I", $i);
                                    if ($error !== null) {
                                        $isOK = false;
                                        $errorArray[] = $file_name .":". $error;
                                    }
                                }
                            }

                            // Add grade when there is no error
                            if ($isOK) {
                                foreach ($results as $r) {

                                    // Getting open course ID (Not the same as course ID) for the student
                                    $open_course_id_template = Academic_Year::where('academic_year', $year)
                                        ->join('student_grade_levels',
                                            'student_grade_levels.classroom_id',
                                            'academic_year.classroom_id')
                                        ->where('student_grade_levels.student_id', $r->student_id)
                                        ->join('offered_courses', 'offered_courses.classroom_id',
                                            'academic_year.classroom_id')
                                        ->where('offered_courses.course_id', $course_id);


                                    //Log::info($year ." ".$r->student_id." ".$getCourseID);
                                    //Log::info((clone $open_course_id_template)
                                    //    ->where('offered_courses.semester',1)->toSql());
                                    $openCourseIDSem1 = (clone $open_course_id_template)
                                        ->where('offered_courses.semester', 1)
                                        ->value('open_course_id');
                                    //Log::info($openCourseIDSem1);
                                    $openCourseIDSem2 = (clone $open_course_id_template)
                                        ->where('offered_courses.semester', 2)
                                        ->value('open_course_id');

                                    //-------------------- add Q1 -----------------
                                    $this->set_grade(
                                        $r->q1,
                                        $r->student_id,
                                        $openCourseIDSem1,
                                        '1', '1', $year, $datetime
                                    );

                                    //-------------------- add Q2 -----------------
                                    $this->set_grade(
                                        $r->q2,
                                        $r->student_id,
                                        $openCourseIDSem1,
                                        '2', '1', $year, $datetime
                                    );

                                    //-------------------- add SUM 1 -----------------
                                    $this->set_grade(
                                        $r->sum_1,
                                        $r->student_id,
                                        $openCourseIDSem1,
                                        '3', '1', $year, $datetime
                                    );

                                    //-------------------- add Q3 -----------------
                                    $this->set_grade(
                                        $r->q3,
                                        $r->student_id,
                                        $openCourseIDSem2,
                                        '1', '2', $year, $datetime
                                    );


                                    //-------------------- add Q4 -----------------
                                    $this->set_grade(
                                        $r->q4,
                                        $r->student_id,
                                        $openCourseIDSem2,
                                        '2', '2', $year, $datetime
                                    );

                                    //-------------------- add SUM 2 -----------------
                                    $this->set_grade(
                                        $r->sum_2,
                                        $r->student_id,
                                        $openCourseIDSem2,
                                        '3', '2', $year, $datetime
                                    );
                                }
                                $errorArray[] = $file_name . " is uploaded successfully.";
                                //return view('uploadGrade.getUpload', compact('results'));
                            }
                        }

                    } else {
                        $errorArray[] = $file_name . " file's type is not xlsx or xls.";
                    }
                }


            }
        } elseif (!($request->hasFile('file'))) {
            $errorArray[] = "Please Select File";
        }

        return view('uploadGrade.validate', compact('errorArray'));
    }

    /**
     * @param $grade_value Grade read from excel
     */
    private function set_grade($grade_value, $student_id, $open_course_id,
                               $quarter, $semester, $academic_year, $datetime)
    {
        if ($open_course_id === null) {
            // Don't do anything if the course does not open
            return;
        }
        $grade = new Grade;
        $grade->student_id = $student_id;
        $grade->open_course_id = $open_course_id;
        $grade->quarter = $quarter;
        $grade->semester = $semester;
        $grade->academic_year = $academic_year;
        $grade->datetime = $datetime;
        /*
         * Text : Char 20
         * 0: No grade
         * 1: I
         * 2: S
         * 3: U
         * 4: 0/1
         * 5: Value
         * 6: I/X This type is to be displayed as I/<grade value>
         * 7: Drop
        */
        if ($grade_value !== null) {
            if ($grade_value == "") {
                // Does nothing if there is no grade
                return;
            } else {
                list($grade->grade, $grade->grade_status) = $this->parseGrade($grade_value);
            }
            $grade->data_status = SystemConstant::DATA_STATUS_WAIT;
            $grade->save();
        }
    }

    /*
     * Return array of [grade value , grade status]
     * */
    private function parseGrade($grade_value): array
    {
        if ($grade_value == "") {
            return [0, 0];
        } elseif ($grade_value == "I" || $grade_value == "i") {
            return [0, SystemConstant::I_GRADE];
        } elseif ($grade_value == "S" || $grade_value == "s") {
            return [0, SystemConstant::S_GRADE];
        } elseif ($grade_value == "U" || $grade_value == "u") {
            return [0, SystemConstant::U_GRADE];
        } elseif ($grade_value == "0/1") {
            return [1, SystemConstant::REMEDIAL_GRADE];
        } elseif (strcasecmp($grade_value, SystemConstant::DROP_GRADE_TEXT) == 0) {
            return [0, SystemConstant::DROP_GRADE];
        } elseif ($grade_value[0] == 'I' || $grade_value[0] == 'i') {
            // Case of I/2.3 etc
            return [substr($grade_value, 2), SystemConstant::PASS_I_GRADE];
        } elseif (strcasecmp($grade_value, SystemConstant::ERASE_GRADE_TEXT) == 0) {
            return [0, SystemConstant::NO_GRADE];
        } else{
            return [$grade_value, SystemConstant::HAS_GRADE];
        }
    }

    /*

    Create function for validating grade. It returns error text if there is
    any error.  Otherwise return null;

    */
    private function validateGrade($data, $field, $column, $row)
    {
        if ($data == "" || $data == null
            || ((is_int($data) || is_float($data)) && $data < 4.001 && $data > -0.001)
            || $data == "S" || $data == "s"
            || $data == "U" || $data == "u"
            || $data == "I" || $data == "i"
            || $data == "0/1"
            || strcasecmp($data, SystemConstant::DROP_GRADE_TEXT) == 0
            || strcasecmp($data, SystemConstant::ERASE_GRADE_TEXT) == 0
            // Match normal grade value or I/Grade
            || (preg_match("/^([Ii]\/)?(([0-3][\.][0-9]*)|([0-4])|(4\.0*))$/", $data))
        ) {
            // Ok return null;
            return null;
        } else {
            return "Field '$field' value ".$data." is incorrect format at row '$column" . ($row + 7) . "'";
        }
    }

    /**
     * @param $file
     * @return array of [filename , filetype] filename include path as well
     */
    private function storeFile($file): array
    {
        $file_name = $file->getClientOriginalName();
        $file_type = \File::extension(SystemConstant::FILE_STORE_DIR . '/' . $file_name);
        $new_file_name = $file_name;
        while (\File::exists(SystemConstant::FILE_STORE_DIR . '/' . $new_file_name)) {
            // Keep changing file until we get the unique name
            $new_file_name = uniqid() . "_" . $file_name;
            //Log::info($new_file_name);
        }
        $file_name = $new_file_name;
        $file->move(SystemConstant::FILE_STORE_DIR, $file_name);
        return [$file_name, $file_type];
    }

    /**
     * @param $year
     * @param $gradeLevel
     * @param $room
     */
    private function get_students_in_class($year, $gradeLevel, $room): array
    {
        $students_query = Academic_Year::where('academic_year', $year)
            ->where('grade_level', $gradeLevel)
            ->where('room', $room)
            ->join('student_grade_levels',
                'student_grade_levels.classroom_id',
                'academic_year.classroom_id')
            ->join('students', 'students.student_id',
                'student_grade_levels.student_id')
            ->select('students.student_id', 'students.firstname', 'students.lastname')
            ->get();
        $students = array();
        foreach ($students_query as $r) {
            $students[$r->student_id] = $r->firstname . " " . $r->lastname;
        }

        return $students;
    }

    /**
     * This function get room, Grade level, Acedemic year from the second column
     * of the first three rows in excel file.
     * Don't use this if the format is not the same
     * @param $file_name
     * @return array
     */
    private function get_room_grade_year($file_name): array
    {
        $getAcademicYear = Excel::load(SystemConstant::FILE_STORE_DIR . '/' . $file_name, function ($reader) {
            $reader->setHeaderRow(1);
        })->get();

        $getGradeLevel = Excel::load(SystemConstant::FILE_STORE_DIR . '/' . $file_name, function ($reader) {
            $reader->setHeaderRow(2);
        })->get();

        $getRoom = Excel::load(SystemConstant::FILE_STORE_DIR . '/' . $file_name, function ($reader) {
            $reader->setHeaderRow(3);
        })->get();

        $year = trim($getAcademicYear->getHeading()[1]);
        $gradeLevel = trim($getGradeLevel->getHeading()[1]);
        $room = trim($getRoom->getHeading()[1]);
        return array($year, $gradeLevel, $room);
    }
}
