<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Curriculum;
use App\SystemConstant;

class ManageCurriculumController extends Controller
{


    public function index()
    {
        //  $curricula  = Curriculum::all();
        //  $curricula  = DB::table('Curricula')->select('year',DB::raw('count(*) as total'))->groupBy('year')->get();
        $curricula = Curriculum::orderBy('curriculum_year', 'asc')->groupBy('curriculum_year')->get();
        return view('manageCurriculum.index', ['curricula' => $curricula]);
    }


    public function editSubject(Request $request) // edit subject
    {

        $curriculum = Curriculum::where('curriculum_year', $request->input('year'))
            ->where('course_id', $request->input('old_course_id'))
            ->update(array(
                'course_id' => $request->input('course_id'),
                'course_name' => $request->input('name'),
                'min_grade_level' => $request->input('min'),
                'max_grade_level' => $request->input('max'),
                'is_activity' => $request->input('activity')
            ));

        $redi = "manageCurriculum/" . $request->input('year');

        return redirect($redi);
    }

    public function createNewYear(Request $request)
    {
        // Check if the year exists or not.  If it is does nothing.
        $year = $request->input('year');

        if(!Curriculum::where('curriculum_year', $year)->first()) {
            // Create a fake course with a place holder value to be removed later.
            $curriculum = new Curriculum;
            $curriculum->curriculum_year = $year;
            $curriculum->course_id = "Create " . $request->input('year');
            $curriculum->course_name = SystemConstant::CLASS_NAME_PLACE_HOLDER;
            $curriculum->min_grade_level = "0";
            $curriculum->max_grade_level = "0";
            $curriculum->is_activity = "0";
            $curriculum->save();
        }

        $redi = "manageCurriculum/" . $request->input('year');
        return redirect($redi);
    }

    public function importFromPrevious(Request $request)
    {
        $current_year = $request->input('cur_year');
        $year_pre = ($request->input('from_year'));
        $redi = "manageCurriculum/" . $current_year;

        $subs = Curriculum::where('curriculum_year', $year_pre)->get();

        // Do something if content on the selected year is more than one
        if (count($subs) > 1) {
            // Remove all current information if there is content on the selected year
            try {
            Curriculum::where('curriculum_year', $current_year)->delete();

            }catch(\Illuminate\Database\QueryException $e){
                $redi = $redi . "?conflict=true";
                return redirect($redi);
            }
            foreach ($subs as $sub) {
                $re = $sub->replicate();
                $re->curriculum_year = $current_year;
                /* There should not be any place holder left */
                $re->save();
            }


        }

        return redirect($redi);
        //return response()->json(['Status' => 'success'], 200);
    }

    public function createNewSubject(Request $request)
    {
        $year = $request->input('year');
//        Log::info($year);

        $check = Curriculum::where('curriculum_year', $year)
            ->where('course_id', $request->input('course_id'))
            ->first();
        if ($check !== null) {
            Log::info("Exists");
            return response()->json(['Status' => SystemConstant::AJAX_EXISTS_RESPONSE], 200);
        }
        // Try removing place holder class
        try{
            Curriculum::where('curriculum_year', $year)
                ->where('course_name', SystemConstant::CLASS_NAME_PLACE_HOLDER)
                ->delete();
        }catch (\Exception $e){
            return response()->json(['Status' => SystemConstant::AJAX_PLACE_HOLDER_USED_RESPONSE], 200);
        }
        try {
            $curriculum = new Curriculum;
            $curriculum->curriculum_year = $year;
            $curriculum->course_id = $request->input('course_id');
            $curriculum->course_name = $request->input('name');
            $curriculum->min_grade_level = $request->input('min');
            $curriculum->max_grade_level = $request->input('max');
            $curriculum->is_activity = $request->input('activity');
            $curriculum->save();
        } catch (\Exception $e) {
            return response()->json(['Status' => $e->getMessage()], 200);
        }
        return response()->json(['Status' => SystemConstant::AJAX_OK_RESPONSE], 200);
    }

    public function editWithYear($year, Request $request)
    {
        $available_curriculum_years = Curriculum::select('curriculum_year')->orderBy('curriculum_year', 'dsc')->groupBy('curriculum_year')->get();

        $curriculum = Curriculum::where('curriculum_year', $year)->first();
        if ($curriculum === null) {
            return redirect('manageCurriculum');
        }
        // Select all except place holder class which is not real class
        $curricula = Curriculum::where('curriculums.curriculum_year', $year)
            ->where('course_name', '!=', SystemConstant::CLASS_NAME_PLACE_HOLDER)
            ->get();

        // Check if we cannot overwrite old information due to curriculum is being used.
        $query_fail = "";
        if($request->query->get('conflict') == 'true'){
            $query_fail = "Cannot perform import for this curriculum because it is 
            being used by at least one academic year.  You can add more class manually 
            or remove all usage for this curriculum from all Academic Year.";
        }

        return view('manageCurriculum.curriculumTable',
            ['curricula' => $curricula, 'cur_year' => $year,
                'available_curriculum_years' => $available_curriculum_years,
                'query_fail' => $query_fail]);
    }
}
