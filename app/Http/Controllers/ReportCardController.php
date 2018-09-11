<?php


namespace App\Http\Controllers;

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
            ->select('grades.*');

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
            ->select('student_latest_grades.quater', 'student_latest_grades.grade', 'student_latest_grades.grade_status', 'offered_courses.*', 'curriculums.*')
            ->orderby('curriculums.course_name');


        // Get grade for semester 1
        $grade_semester1_raw = (clone $grade)->where('offered_courses.semester', '1')
            ->where('offered_courses.is_elective', '0')
            ->get();
        $grade_semester1 = self::getGradeToFrom($grade_semester1_raw);
        $grade_avg_sem1 = self::getAvg($grade_semester1);

        // Get grades for semester 2
        $grade_semester2_raw = (clone $grade)->where('offered_courses.semester', '2')
            ->where('offered_courses.is_elective', '0')
            ->get();
        $grade_semester2 = self::getGradeToFrom($grade_semester2_raw);
        $grade_avg_sem2 = self::getAvg($grade_semester2);

        $grade_semester1_6 = self::getGradeToFrom1_6($grade_semester1_raw, $grade_semester2_raw);

        // Get elective grades
        $elective_grades = (clone $grade)->where('offered_courses.is_elective', '1')->get();
        $elective_grades = self::getGradeToFrom($elective_grades);
        $elective_grade_avg = self::getAvg($elective_grades);

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
            ->select('activity_records.*');

        // Create template for all activity query
        $acts = Offered_Courses::where('classroom_id', $grade_level->classroom_id)
            ->join('curriculums', 'curriculums.course_id', 'offered_courses.course_id')
            ->leftJoinSub($student_latest_acts, 'student_latest_acts', function ($join) {
                $join->on('offered_courses.open_course_id', 'student_latest_acts.open_course_id');
            })
            ->where('curriculums.is_activity', '1')
            ->select('student_latest_acts.grade_status', 'offered_courses.*', 'curriculums.*')
            ->orderby('curriculums.course_name');

        $activity_semester1 = (clone $acts)->where('offered_courses.semester', '1')->get();
        $activity_semester2 = (clone $acts)->where('offered_courses.semester', '2')->get();

        // Get latest comments Use the same technique as grades
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

        $physical_record_semester1 = Physical_Record::where('student_id', $student_id)
            ->where('physical_records.semester', '1')
            ->where('physical_records.academic_year', $academic_year)
            ->select('physical_records.*')
            ->first();

        $physical_record_semester2 = Physical_Record::where('student_id', $student_id)
            ->where('physical_records.semester', '2')
            ->where('physical_records.academic_year', $academic_year)
            ->select('physical_records.*')
            ->first();

        $behavior_records = Behavior_Record::where('student_id', $student_id)
            ->where('behavior_records.academic_year', $academic_year)
            ->where('behavior_records.data_status', 1)
            ->select('behavior_records.*')
            ->get();


        $behavior_types = Behavior_Type::all();
        $behavior_records = self::getBehaviorToFrom($behavior_records, $behavior_types);


        $attendances = Attendance_Record::where('data_status', 1)
            ->where('attendace_records.student_id', $student_id)
            ->where('attendace_records.academic_year', $academic_year)
            ->select('attendace_records.*')
            ->get();


        $student = Student::where('students.student_id', $student_id)
            ->join('student_grade_levels', 'student_grade_levels.student_id', 'students.student_id')
            ->select('students.*', 'student_grade_levels.*')
            ->join('academic_year', 'academic_year.classroom_id', 'student_grade_levels.classroom_id')
            ->select('students.*', 'student_grade_levels.*', 'academic_year.*')
            ->first();


        if ($grade_level->grade_level <= 6) {
            //ยังต้องเปลี่ยนเป็นฟอร์ม 1-6 ถ้าอาจารจะทดสอบให้ทดสอบที่อันนี้ก่อนครับ ผมมีตารางใน seeder แล้วนะครับ ลองseedได้ครับ
            $pdf = PDF::loadView('reportCard.formGrade1-6', ['academic_year' => $academic_year,
                'grade_semester1' => $grade_semester1_6,
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
                'behavior_records' => $behavior_records]);

            $pdf->setPaper('a4', 'potrait');
            return $pdf->stream();

        } elseif ($grade_level->grade_level <= 8) {

            $pdf = PDF::loadView('reportCard.formGrade7-8', ['academic_year' => $academic_year,
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
                'behavior_records' => $behavior_records]);

            $pdf->setPaper('a4', 'potrait');
            return $pdf->stream();

        } elseif ($grade_level->grade_level <= 12) {

            $pdf = PDF::loadView('reportCard.formGrade9-12', ['academic_year' => $academic_year,
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
                'behavior_records' => $behavior_records]);

            $pdf->setPaper('a4', 'potrait');
            return $pdf->stream();

        }


        // return $pdf->download('reportCard.pdf');

    }


    public static function getDistinct($arr, $field)
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


    public function Room($classroom_id)
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

    public function getBehaviorToFrom($behavior_records, $behavior_types)
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
                // code...
            }
        }
        return $behavior_types;
    }


    public static function getGradeToFrom($arr)
    {
        $check = array();
        $result = array();

        foreach ($arr as $x) {
            if (!in_array($x->course_id . "", $check)) {


                $element = array('course_name' => $x->course_name,
                    'course_id' => $x->course_id,
                    'credits' => $x->credits,
                    'quater1' => 0,
                    'quater2' => 0,
                    'quater3' => 0,
                    'total_point' => 0);

        $element = array('course_name'=> $x->course_name,
                        'course_id'=> $x->course_id,
                        'credits'=>$x->credits,
                        'quater1' => -1,
                        'quater2' => -1,
                        'quater3' => -1,
                        'total_point' => 0);


            } else {

                $result[$x->course_id]['quater' . $x->quater] = $x->grade;
                $result[$x->course_id]['total_point'] += $x->grade;

            }

        }


        return $result;

    }


    public static function getGradeToFrom1_6($grade_sem1, $grade_sem2)
    {
        $check = array();
        $result = array();

        $boom = array();

        foreach ($grade_sem1 as $x) {
            $b = $x->course_id . " : " . $x->course_name . "    :" . ('quater' . $x->quater . '_sem' . $x->semester . '  :' . $x->grade . "  " . $x->open_course_id);
            array_push($boom, $b);

            if (!in_array($x->course_id . "", $check)) {


                $element = array('course_name' => $x->course_name,
                    'course_id' => $x->course_id,
                    'credits' => $x->credits,
                    'in_class' => $x->in_class,
                    'practice' => $x->practice,
                    'quater1_sem1' => 0,
                    'quater2_sem1' => 0,
                    'quater3_sem1' => 0,
                    'quater1_sem2' => 0,
                    'quater2_sem2' => 0,
                    'quater3_sem2' => 0,
                    'total_point' => 0,
                    'total_point_sem1' => 0,
                    'total_point_sem2' => 0);

                $element['quater' . $x->quater . '_sem1'] = $x->grade;
                $element['total_point'] += +$x->grade;
                $element['total_point_sem1'] += +$x->grade;
                $result[$x->course_id] = $element;
                array_push($check, $x->course_id);


            } else {

        $element = array('course_name'=> $x->course_name,
                        'course_id'=> $x->course_id,
                        'credits'=>$x->credits,
                        'in_class'=>$x->in_class,
                        'practice'=>$x->practice,
                        'quater1_sem1' => -1,
                        'quater2_sem1' => -1,
                        'quater3_sem1' => -1,
                        'quater1_sem2' => -1,
                        'quater2_sem2' => -1,
                        'quater3_sem2' => -1,
                        'total_point' => 0,
                        'total_point_sem1' => 0 ,
                        'total_point_sem2' => 0);

            }

        }

        array_push($boom, '================================');

        foreach ($grade_sem2 as $x) {
            $b = $x->course_id . " : " . $x->course_name . "    :" . ('quater' . $x->quater . '_sem' . $x->semester . '  :' . $x->grade . "  " . $x->open_course_id);
            array_push($boom, $b);

            if (!in_array($x->course_id . "", $check)) {


                $element = array('course_name' => $x->course_name,
                    'course_id' => $x->course_id,
                    'credits' => $x->credits,
                    'quater1_sem1' => 0,
                    'quater2_sem1' => 0,
                    'quater3_sem1' => 0,
                    'quater1_sem2' => 0,
                    'quater2_sem2' => 0,
                    'quater3_sem2' => 0,
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

        // dd($boom);


        return $result;

    }


    public static function getAvg($arr)
    {

        $total_score = 0;
        $total_credit = 0;
        foreach ($arr as $key => $x) {
            $score = (($x['total_point'] / 3) * $x['credits']);
            $score = substr($score, 0, strpos($score, '.') + 3);
            // $total_score += number_format((($x['total_point']/3)*$x['credits']),2);
            $total_score += $score;
            $total_credit += $x['credits'];
        }
        if ($total_credit == 0) {
            return 0;
        }
        $avg = $total_score / $total_credit;

        return substr($avg, 0, strpos($avg, '.') + 3);
    }


    public function index()
    {
        return view('reportCard.master2');
    }

    public function exportForm()
    {
        $pdf = PDF::loadView('reportCard.form2');
        $pdf->setPaper('a4', 'potrait');
        return $pdf->stream();
    }

    public function exportGrade1()
    {
        $pdf = PDF::loadView('reportCard.formGrade1-6');
        $pdf->setPaper('a4', 'potrait');
        return $pdf->stream();
    }

    public function exportGrade2()
    {
        $pdf = PDF::loadView('reportCard.formGrade7-8');
        $pdf->setPaper('a4', 'potrait');
        return $pdf->stream();
    }

    public function exportGrade3()
    {
        $pdf = PDF::loadView('reportCard.formGrade9-12');
        $pdf->setPaper('a4', 'potrait');
        return $pdf->stream();
    }


}
