<?php


namespace App\Http\Controllers;

use App\School_Days;
use App\SystemConstant;
use DB;
use App\Offered_Courses;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;
use App\Student;
use App\Student_Grade_Level;
use App\Academic_Year;
use App\Teacher;
use App\Homeroom;
use App\Grade;
use App\Activity_Record;
use App\Grade_Status;
use App\Physical_Record;
use App\Behavior_Type;
use App\Behavior_Record;
use App\Attendance_Record;
use App\Teacher_Comment;
use auth;

ini_set('max_execution_time', 180);

class ReportCardController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index2()
    {
        $academic_years = Academic_Year::groupBy('academic_year')->distinct('academic_year')->orderBy('academic_year')->get();
        $rooms = Academic_Year::orderBy('grade_level')->get();


        return view('reportCard.index2', ['academic_years' => $academic_years, 'rooms' => $rooms]);

    }

    public function exportPDF($student_id, $academic_year)
    {
        // Sanity check for security
        // TODO  Need to implement student_id check and $academic_year check to prevent SQL injection


        //SELECT * FROM grades
        //JOIN (
        //    select MAX(datetime) as datetime, `open_course_id`, `quater`
        //    from `grades` where `student_id` = '2540080850' and `academic_year` = 2018
        //    group by `open_course_id`, `quater`
        //) as latest_grade
        // ON grades.open_course_id = latest_grade.open_course_id
        //    and grades.quater = latest_grade.quater
        //    and grades.datetime = latest_grade.datetime
        //WHERE `student_id` = '2540080850' and `academic_year` = 2018
        //

        // Get the latest grade value by getting the time of the latest valid grades then select
        // the actual grade later by sub join
        $student_latest_grade_keys = Grade::where('student_id', $student_id)
            ->where('academic_year', $academic_year)
            ->where('data_status', 1)
            ->groupBy('open_course_id', 'quater')
            ->select(DB::raw('MAX(datetime) as datetime'), 'open_course_id', 'quater');


        $student_latest_grades = Grade::where('student_id', $student_id)
            ->where('academic_year', $academic_year)
            ->joinSub($student_latest_grade_keys, 'latest_grade', function ($join) {
                $join->on('grades.open_course_id', 'latest_grade.open_course_id');
                $join->on('grades.quater', 'latest_grade.quater');
                $join->on('grades.datetime', 'latest_grade.datetime');
            })
            ->leftJoin('grade_status', 'grades.grade_status', 'grade_status.grade_status')
            ->select('grades.*', 'grade_status.grade_status_text');

        // Get student grade level and classroom_id

        //SELECT academic_year.grade_level , student_grade_levels.classroom_id
        //FROM student_grade_levels
        //JOIN academic_year ON student_grade_levels.classroom_id = academic_year.classroom_id
        //WHERE academic_year.academic_year = 2018 AND student_grade_levels.student_id="2540080850"
        $grade_level = Student_Grade_Level::where('student_id', $student_id)
            ->where('academic_year', $academic_year)
            ->join('academic_year', 'academic_year.classroom_id', 'student_grade_levels.classroom_id')
            ->select('academic_year.grade_level', 'student_grade_levels.classroom_id')
            ->first();

        //SELECT * FROM offered_courses
        //JOIN curriculums ON curriculums.course_id = offered_courses.course_id
        //LEFT JOIN (SELECT * FROM grades
        //    JOIN (
        //           select MAX(datetime) as datetime, `open_course_id`, `quater`
        //            from `grades` where `student_id` = '2540080850' and `academic_year` = 2018
        //           group by `open_course_id`, `quater`
        //        ) as latest_grade
        //        ON grades.open_course_id = latest_grade.open_course_id
        //            and grades.quater = latest_grade.quater
        //            and grades.datetime = latest_grade.datetime
        //        WHERE `student_id` = '2540080850' and `academic_year` = 2018)
        //        as student_latest_grades ON offered_courses.classroom_id = student_latest_grades.open_course_id
        //WHERE offered_courses.classroom_id = 7
        //AND curriculums.is_activity = 0
        //AND offered_courses.is_elective = 0

        // Create template for all grade query
        $grade = Offered_Courses::where('classroom_id', $grade_level->classroom_id)
            ->join('curriculums', 'curriculums.course_id', 'offered_courses.course_id')
            ->leftJoinSub($student_latest_grades, 'student_latest_grades', function ($join) {
                $join->on('offered_courses.open_course_id', 'student_latest_grades.open_course_id');
            })
            ->where('curriculums.is_activity', '0')
            ->select('student_latest_grades.quater', 'student_latest_grades.grade',
                'student_latest_grades.grade_status', 'student_latest_grades.grade_status_text',
                'offered_courses.*', 'curriculums.*')
            ->orderby('curriculums.course_name');


        // Get grade for semester 1
        $grade_semester1_raw = (clone $grade)->where('offered_courses.semester', '1')
            ->where('offered_courses.is_elective', '0')
            ->get();

        $grade_semester1 = self::getGradeToFrom($grade_semester1_raw,$grade_level->grade_level);
        //dd($grade_semester1);

//        $grade_avg_sem1 = self::getAvg($grade_semester1);

        // Get grades for semester 2
        $grade_semester2_raw = (clone $grade)->where('offered_courses.semester', '2')
            ->where('offered_courses.is_elective', '0')
            ->get();
        $grade_semester2 = self::getGradeToFrom($grade_semester2_raw,$grade_level->grade_level);
//        $grade_avg_sem2 = self::getAvg($grade_semester2);

        $grade_semester1_6 = self::getGradeToFrom1_6($grade_semester1_raw, $grade_semester2_raw);

        // Get elective grades
        $elective_grades = (clone $grade)->where('offered_courses.is_elective', '1')->get();
        $elective_grades = self::getGradeToFrom($elective_grades,$grade_level->grade_level);
        // Get elective with most scores
        $selected_elective = null;
        $top_number_of_grade = 0;
        foreach ($elective_grades as $g) {
            if ($g['grade_count'] > $top_number_of_grade) {
                $selected_elective = $g;
                $top_number_of_grade = $g['grade_count'];
            }
        }

//        $elective_grade_avg = self::getAvg($elective_grades);

        // Get latest activities Use the same technique as grades
        $student_latest_act_keys = Activity_Record::where('student_id', $student_id)
            ->where('academic_year', $academic_year)
            ->where('data_status', 1)
            ->groupBy('open_course_id')
            ->select(DB::raw('MAX(datetime) as datetime'), 'open_course_id');

        $student_latest_acts = Activity_Record::where('student_id', $student_id)
            ->where('academic_year', $academic_year)
            ->joinSub($student_latest_act_keys, 'latest_act', function ($join) {
                $join->on('activity_records.open_course_id', 'latest_act.open_course_id');
                $join->on('activity_records.datetime', 'latest_act.datetime');
            })
            ->leftJoin('grade_status', 'activity_records.grade_status', 'grade_status.grade_status')
            ->select('activity_records.*', 'grade_status.grade_status_text');

        // Create template for all activity query
        $acts = Offered_Courses::where('classroom_id', $grade_level->classroom_id)
            ->join('curriculums', 'curriculums.course_id', 'offered_courses.course_id')
            ->leftJoinSub($student_latest_acts, 'student_latest_acts', function ($join) {
                $join->on('offered_courses.open_course_id', 'student_latest_acts.open_course_id');
            })
            ->where('curriculums.is_activity', '1')
            ->select('student_latest_acts.grade_status', 'student_latest_acts.grade_status_text', 'offered_courses.*', 'curriculums.*')
            ->orderby('curriculums.course_name');

        $activity_semester1 = (clone $acts)->where('offered_courses.semester', '1')->get();
        $activity_semester2 = (clone $acts)->where('offered_courses.semester', '2')->get();

        // Get latest comments
        $latest_comment_keys = Teacher_Comment::where('student_id', $student_id)
            ->where('academic_year', $academic_year)
            //TODO           ->where('data_status', 1)  // There is no approval system for teacher comment yet
            ->groupBy('semester', 'quater')
            ->select(DB::raw('MAX(datetime) as datetime'), 'semester', 'quater');

        $teacher_comments = Teacher_Comment::where('student_id', $student_id)
            ->where('academic_year', $academic_year)
            ->joinSub($latest_comment_keys, 'latest_comments', function ($join) {
                $join->on('teacher_comments.semester', 'latest_comments.semester');
                $join->on('teacher_comments.quater', 'latest_comments.quater');
                $join->on('teacher_comments.datetime', 'latest_comments.datetime');
            })
            ->select('teacher_comments.*')
            ->orderBy('semester')
            ->orderBy('quater')
            ->get();

        // Get latest comments
        // TODO no approval for this yet
        $physical_record_semester1 = Physical_Record::where('student_id', $student_id)
            ->where('physical_records.semester', '1')
            ->where('physical_records.academic_year', $academic_year)
            ->select('physical_records.*')
            ->orderBy('datetime', 'desc')
            ->first();

        $physical_record_semester2 = Physical_Record::where('student_id', $student_id)
            ->where('physical_records.semester', '2')
            ->where('physical_records.academic_year', $academic_year)
            ->select('physical_records.*')
            ->orderBy('datetime', 'desc')
            ->first();

        // Get latest Behavior using the same technique as grades
        $latest_behavior_keys = Behavior_Record::where('student_id', $student_id)
            ->where('academic_year', $academic_year)
            //TODO           ->where('data_status', 1)  // There is no approval system for behavior  yet
            ->groupBy('semester', 'quater')
            ->select(DB::raw('MAX(datetime) as datetime'), 'semester', 'quater');

        $behavior_records = Behavior_Record::where('student_id', $student_id)
            ->where('academic_year', $academic_year)
            ->joinSub($latest_behavior_keys, 'latest_behavior_keys', function ($join) {
                $join->on('behavior_records.semester', 'latest_behavior_keys.semester');
                $join->on('behavior_records.quater', 'latest_behavior_keys.quater');
                $join->on('behavior_records.datetime', 'latest_behavior_keys.datetime');
            })
            ->join('behavior_types', 'behavior_records.behavior_type', 'behavior_types.behavior_type')
            ->select('behavior_records.*', 'behavior_types.behavior_type_text')
            ->orderBy('behavior_types.behavior_type_text')
            ->get();

        $behavior_types = Behavior_Type::all();
        $behavior_records = self::getBehaviorToFrom($behavior_records, $behavior_types);


        // Get latest attendance using the same technique as grades
        $latest_att_keys = Attendance_Record::where('student_id', $student_id)
            ->where('academic_year', $academic_year)
            //TODO           ->where('data_status', 1)  // There is no approval system for behavior  yet
            ->groupBy('semester')
            ->select(DB::raw('MAX(datetime) as datetime'), 'semester');

        $attendances = Attendance_Record::where('student_id', $student_id)
            ->where('attendace_records.academic_year', $academic_year)
            ->joinSub($latest_att_keys, 'latest_att_keys', function ($join) {
                $join->on('attendace_records.semester', 'latest_att_keys.semester');
                $join->on('attendace_records.datetime', 'latest_att_keys.datetime');
            })
            ->join('school_days', function ($join) {
                $join->on('school_days.semester', 'attendace_records.semester');
                $join->on('school_days.academic_year', 'attendace_records.academic_year');
            })
            ->select('attendace_records.*', 'school_days.total_days')
            ->orderBy('semester', 'asc')
            ->get();

        // Subtrace school day if there are attendances record
        foreach ($attendances as $att) {
            $att->total_days -= $att->late;
            $att->total_days -= $att->sick;
            $att->total_days -= $att->leave;
            $att->total_days -= $att->absent;
        }

        $student = Student::where('students.student_id', $student_id)
            ->join('student_grade_levels', 'student_grade_levels.student_id', 'students.student_id')
            ->select('students.*', 'student_grade_levels.*')
            ->join('academic_year', 'academic_year.classroom_id', 'student_grade_levels.classroom_id')
            ->select('students.*', 'student_grade_levels.*', 'academic_year.*')
            ->first();

// Pack data for view
        $view_data = ['academic_year' => $academic_year,
            'grade_semester1' => $grade_semester1,
            'grade_semester2' => $grade_semester2,
            'student' => $student,
//            'avg1' => $grade_avg_sem1,
//            'avg2' => $grade_avg_sem2,
            'activity_semester1' => $activity_semester1,
            'activity_semester2' => $activity_semester2,
            'elective_grades' => $elective_grades,
            'selected_elective' => $selected_elective,
//            'elective_grade_avg' => $elective_grade_avg,
            'physical_record_semester1' => $physical_record_semester1,
            'physical_record_semester2' => $physical_record_semester2,
            'attendances' => $attendances,
            'teacher_comments' => $teacher_comments,
            'behavior_types' => $behavior_types,
            'behavior_records' => $behavior_records];

        if ($grade_level->grade_level <= 6) {
            //ยังต้องเปลี่ยนเป็นฟอร์ม 1-6 ถ้าอาจารจะทดสอบให้ทดสอบที่อันนี้ก่อนครับ ผมมีตารางใน seeder แล้วนะครับ ลองseedได้ครับ
            $view_data['grade_semester1'] = $grade_semester1_6;
            $view_data = self::computeCumulative($view_data, $grade_level->grade_level);
            $pdf = PDF::loadView('reportCard.formGrade1-6', $view_data);
        } elseif ($grade_level->grade_level <= 8) {
            $view_data = self::computeCumulative($view_data, $grade_level->grade_level);
            $pdf = PDF::loadView('reportCard.formGrade7-8', $view_data);
        } else { //If not it can only be grade 9-12
            $view_data = self::computeCumulative($view_data, $grade_level->grade_level);
            $pdf = PDF::loadView('reportCard.formGrade9-12', $view_data);
        }

        $pdf->setPaper('a4', 'potrait');
        return $pdf->stream();

// return $pdf->download('reportCard.pdf');

    }


    public
    static function getDistinct($arr, $field)
    {
        $result = array();
        $check = array();


        foreach ($arr as $x) {

            if (!in_array($x->classroom_id . "", $check)) {
                array_push($check, $x->classroom_id);
                array_push($result, $x);

            }
        }

        return $result;

    }


    public
    function Room($classroom_id)
    {

        $room = Academic_Year::where('classroom_id', $classroom_id)
            ->select('academic_year.*')
            ->first();

        $students = Student_Grade_Level::where('classroom_id', $classroom_id)
            ->select('student_grade_levels.*')
            ->join('students', 'students.student_id', 'student_grade_levels.student_id')
            ->select('student_grade_levels.*', 'students.*')
            ->get();
        return view('reportCard.room', ['students' => $students, 'room' => $room]);

    }

    public
    function getBehaviorToFrom($behavior_records, $behavior_types)
    {
        foreach ($behavior_types as $behavior_type) {
            $behavior_type->sem1_q1 = '';
            $behavior_type->sem1_q2 = '';
            $behavior_type->sem2_q1 = '';
            $behavior_type->sem2_q2 = '';
        }
        foreach ($behavior_types as $behavior_type) {
            foreach ($behavior_records as $behavior_record) {
                if ($behavior_type->behavior_type == $behavior_record->behavior_type) {
                    if ($behavior_record->semester == 1 && $behavior_record->quater == 1) {
                        $behavior_type->sem1_q1 = $behavior_record->grade;
                    }
                    if ($behavior_record->semester == 1 && $behavior_record->quater == 2) {
                        $behavior_type->sem1_q2 = $behavior_record->grade;
                    }
                    if ($behavior_record->semester == 2 && $behavior_record->quater == 1) {
                        $behavior_type->sem2_q1 = $behavior_record->grade;
                    }
                    if ($behavior_record->semester == 2 && $behavior_record->quater == 2) {
                        $behavior_type->sem2_q2 = $behavior_record->grade;
                    }
                }

            }
        }
        return $behavior_types;
    }


    // This method set up grade into a form ready for report card
    // Also compute semester grade of each subject
    public
    static function getGradeToFrom($arr, $grade_level)
    {
        $result = array();
        foreach ($arr as $x) {

            if (!array_key_exists($x->course_id, $result)) {

                $element = array('course_name' => $x->course_name,
                    'course_id' => $x->course_id,
                    'credits' => $x->credits,
                    'quater1' => "",
                    'quater2' => "",
                    'quater3' => "",
                    'semester_grade' => 0,
                    'grade_count' => 0);

                $result[$x->course_id] = $element;
            } else {
                $element = $result[$x->course_id];
            }

            switch ($x->grade_status) {
                case SystemConstant::NO_GRADE:
                    $element['quater' . $x->quater] = "";
                    break;
                case SystemConstant::HAS_GRADE:
                    $element['quater' . $x->quater] = $x->grade;
                    $element['semester_grade'] += $x->grade;
                    // Only count when this is not final quarter
                    if($x->quater < SystemConstant::FINAL_Q){
                        $element['grade_count']++;
                    }
                    break;
                default:
                    $element['quater' . $x->quater] = $x->grade_status_text;
            }
            $result[$x->course_id] = $element;
        }

        // Change semester to "-" when one of the quarters is missing
        // otherwise compute semester grade normally
        foreach ($result as $key => $x) {
            if ($x['grade_count'] != SystemConstant::TOTAL_QUARTERS) {
                $x['semester_grade'] = "-";

            }else{
                if($x['quater'.SystemConstant::FINAL_Q] == ""){
                    // There is no final just average grade without
                    $grade = $x['semester_grade'] / SystemConstant::TOTAL_QUARTERS;
                }else{
                    $grade = $x['semester_grade'] / (SystemConstant::TOTAL_QUARTERS + 1);
                }
                $x['semester_grade'] = round($grade,1);
            }
            $result[$key] = $x;
        }

        return $result;

    }


    public
    static function getGradeToFrom1_6($grade_sem1, $grade_sem2)
    {
        $check = array();
        $result = array();

        $boom = array();

        foreach ($grade_sem1 as $x) {
            $b = $x->course_id . " : " . $x->course_name . "    :" . ('quater' . $x->quater . '_sem' . $x->semester . '  :' . $x->grade . "  open course id : " . $x->open_course_id);
            array_push($boom, $b);

            if (!in_array($x->course_id . "", $check)) {


                $element = array('course_name' => $x->course_name,
                    'course_id' => $x->course_id,
                    'credits' => $x->credits,
                    'in_class' => $x->in_class,
                    'practice' => $x->practice,
                    'quater1_sem1' => -1,
                    'quater2_sem1' => -1,
                    'quater3_sem1' => -1,
                    'quater1_sem2' => -1,
                    'quater2_sem2' => -1,
                    'quater3_sem2' => -1,
                    'total_point' => 0,
                    'enable_sem1' => true,
                    'enable_sem2' => true,
                    'total_point_sem1' => 0,
                    'total_point_sem2' => 0);

                $element['quater' . $x->quater . '_sem1'] = $x->grade;
                $element['total_point'] += +$x->grade;
                $element['total_point_sem1'] += +$x->grade;
                $result[$x->course_id] = $element;
                array_push($check, $x->course_id);


            } else {

                $result[$x->course_id]['quater' . $x->quater . '_sem1'] = $x->grade;
                $result[$x->course_id]['total_point'] += $x->grade;
                $result[$x->course_id]['total_point_sem1'] += $x->grade;

            }

        }

        array_push($boom, '================================');

        foreach ($grade_sem2 as $x) {
            $b = $x->course_id . " : " . $x->course_name . "    :" . ('quater' . $x->quater . '_sem' . $x->semester . '  :' . $x->grade . "  open course id : " . $x->open_course_id);
            array_push($boom, $b);

            if (!in_array($x->course_id . "", $check)) {


                $element = array('course_name' => $x->course_name,
                    'course_id' => $x->course_id,
                    'credits' => $x->credits,
                    'in_class' => $x->in_class,
                    'practice' => $x->practice,
                    'quater1_sem1' => -1,
                    'quater2_sem1' => -1,
                    'quater3_sem1' => -1,
                    'quater1_sem2' => -1,
                    'quater2_sem2' => 0,
                    'quater3_sem2' => 0,
                    'enable_sem1' => true,
                    'enable_sem2' => true,
                    'total_point' => 0,
                    'total_point_sem1' => 0,
                    'total_point_sem2' => 0);

                $element['quater' . $x->quater . '_sem2'] = $x->grade;
                $element['total_point'] += $x->grade;
                $element['total_point_sem2'] += $x->grade;
                $result[$x->course_id] = $element;
                array_push($check, $x->course_id);


            } else {

                $result[$x->course_id]['quater' . $x->quater . '_sem2'] = $x->grade;
                $result[$x->course_id]['total_point'] += $x->grade;
                $result[$x->course_id]['total_point_sem2'] += $x->grade;

            }

        }


        foreach ($result as $x) {
            for ($j = 1; $j <= 2; $j++) {
                for ($i = 1; $i <= 3; $i++) {
                    if ($x['quater' . $i . '_sem' . $j] == -1) {
                        $result[$x['course_id']]['total_point_sem' . $j] = '';
                        $result[$x['course_id']]['enable_sem' . $j] = false;
                        $result[$x['course_id']]['quater' . $i . '_sem' . $j] = '';
                    }
                }
            }
            // if($x['quater1_sem1'] == -1 || $x['quater2_sem1'] == -1  || $x['quater3_sem1'] == -1 || $x['quater1_sem2'] == -1 || $x['quater2_sem2'] == -1  || $x['quater3_sem2'] == -1   ){
            //
            //   $result[$x['course_id']]['total_point'] -= ($x['total_point_sem1']  +$x['total_point_sem2']);
            //
            // }

        }
        $b = count($result);

        for ($i = count($result); $i < 14; $i++) {

            $element = array('course_name' => '',
                'course_id' => '',
                'credits' => 0,
                'in_class' => '',
                'practice' => '',
                'quater1_sem1' => '',
                'quater2_sem1' => '',
                'quater3_sem1' => '',
                'quater1_sem2' => '',
                'quater2_sem2' => '',
                'quater3_sem2' => '',
                'enable_sem1' => false,
                'enable_sem2' => false,
                'total_point' => '',
                'total_point_sem1' => '',
                'total_point_sem2' => '');

            $result['course' . $i] = $element;

        }
        // dd($b,count($result),$result);


        return $result;

    }


//    public static function getAvg($arr)
//    {
//
//        $total_score = 0;
//        $total_credit = 0;
//        foreach ($arr as $key => $x) {
//            $score = (($x['total_point']) * $x['credits']);
//            $score = substr($score, 0, strpos($score, '.') + 3);
//            // $total_score += number_format((($x['total_point']/3)*$x['credits']),2);
//            $total_score += $score;
//            $total_credit += $x['credits'];
//        }
//        if ($total_credit == 0) {
//            return 0;
//        }
//        $avg = $total_score / $total_credit;
//
//        return substr($avg, 0, strpos($avg, '.') + 3);
//    }


    public
    function index()
    {
        return view('reportCard.master2');
    }

    public
    function exportForm()
    {
        $pdf = PDF::loadView('reportCard.form2');
        $pdf->setPaper('a4', 'potrait');
        return $pdf->stream();
    }

    public
    function exportGrade1()
    {
        $pdf = PDF::loadView('reportCard.formGrade1-6');
        $pdf->setPaper('a4', 'potrait');
        return $pdf->stream();
    }

    public
    function exportGrade2()
    {
        $pdf = PDF::loadView('reportCard.formGrade7-8');
        $pdf->setPaper('a4', 'potrait');
        return $pdf->stream();
    }

    public
    function exportGrade3()
    {
        $pdf = PDF::loadView('reportCard.formGrade9-12');
        $pdf->setPaper('a4', 'potrait');
        return $pdf->stream();
    }

    /* $view_data = ['academic_year' => $academic_year,
            'grade_semester1' => $grade_semester1,
            'grade_semester2' => $grade_semester2,
            'student' => $student,
            'avg1' => $grade_avg_sem1,
            'avg2' => $grade_avg_sem2,
            'activity_semester1' => $activity_semester1,
            'activity_semester2' => $activity_semester2,
            'elective_grades' => $elective_grades,
            'elective_grade_avg' => $elective_grade_avg,
            'physical_record_semester1' => $physical_record_semester1,
            'physical_record_semester2' => $physical_record_semester2,
            'attendances' => $attendances,
            'teacher_comments' => $teacher_comments,
            'behavior_types' => $behavior_types,
            'behavior_records' => $behavior_records];
    */
    /* Compute cumulative stats such as GPA, Semester GPA, CR, CE*/
    public
    function computeCumulative($view_data, $grade_level)
    {
        $total_credit = 0; // CR CE
        $total_sem1_credit = 0;
        $total_sem2_credit = 0;
        $semester_1_gpa = 0;
        $semester_2_gpa = 0;
        $total_sem1_subject = 0;
        $total_sem2_subject = 0;

        // Semester 1 computation
        foreach ($view_data['grade_semester1'] as $key => $g) {
            $credit = $g['credits'];
            $total_sem1_credit += $credit;
            $grade = $g['semester_grade'];
            if ($grade != "-") {
                if($grade_level <= 6){
                    $semester_1_gpa += $grade;
                    $total_sem1_subject++;
                }else {
                    $semester_1_gpa += $grade * $credit;
                }
            }
        }

        $total_credit += $total_sem1_credit;
        $gpa = $semester_1_gpa;
        if($grade_level <=6){
            $semester_1_gpa = round($semester_1_gpa / $total_sem1_subject, 2);
        }else {
            $semester_1_gpa = round($semester_1_gpa / $total_sem1_credit, 2);
        }

        // Semester 2 computation
        foreach ($view_data['grade_semester2'] as $key => $g) {

            $credit = $g['credits'];
            $total_sem2_credit += $credit;
            $grade = $g['semester_grade'];
            if ($grade != "-") {
                if($grade_level <= 6){
                    $semester_2_gpa += $grade;
                    $total_sem2_subject++;
                }else {
                    $semester_2_gpa += $grade * $credit;
                }
            }
        }

        // Grade 1-6 already double count credits so there is
        // no need to add more to total credit
        if($grade_level > 6) {
            if ($grade_level < 9) {  // Grade 7-8
                $total_credit += $total_sem2_credit;
            } else {  // Grade 9-12

                // Add selected elective
                $credit = $view_data['selected_elective']['credit'];
                $total_sem2_credit += $credit;
                $grade = $view_data['selected_elective']['semester_grade'];
                $semester_2_gpa += $grade * $credit;
                $total_credit += $total_sem2_credit;
            }
        }

        $gpa += $semester_2_gpa;
        if($grade_level <=6){
            // total subject in semester 1 and 2 should be equal
            $semester_2_gpa = round($semester_2_gpa / $total_sem2_subject, 2);
        }else {
            $semester_2_gpa = round($semester_2_gpa / $total_sem2_credit, 2);
        }
        $gpa /= $total_credit;

        // Pack data back to view_data
        $view_data['gpa'] = $gpa;
        $view_data['semester_1_gpa'] = $semester_1_gpa;
        $view_data['semester_2_gpa'] = $semester_2_gpa;
        $view_data['total_sem1_credit'] = $total_sem1_credit;
        $view_data['total_sem2_credit'] = $total_sem2_credit;
        $view_data['total_credit'] = $total_credit;

        return $view_data;
    }

    // This method take floating grade number from 0.0 to 4.0 and output
    // Grade according to academic evaluation chart 2
    // The input is expected to be a float.
    public function academic_evaluation_cart_2($grade){
        // Round number to two decimal points
        $grade = round($grade,2);
        if($grade < 1.00){
            return 0;
        }elseif ($grade < 1.25){
            return 1;
        }elseif ($grade < 1.75){
            return 1.5;
        }elseif ($grade < 2.25){
            return 2;
        }elseif ($grade < 2.75){
            return 2.5;
        }elseif ($grade < 3.25){
            return 3;
        }elseif ($grade < 3.75){
            return 3.5;
        }

        return 4;
    }
}
