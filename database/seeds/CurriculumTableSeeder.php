<?php

use Illuminate\Database\Seeder;

class CurriculumTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $admin = new App\Curriculum;
      $admin->curriculum_year ='2558';
      $admin->course_id = '01418111';
      $admin->course_name = 'Unix';
      $admin->min_grade_level = '1';
      $admin->max_grade_level = '6';
      $admin->is_activity = '1';
      $admin->save();


      $admin = new App\Curriculum;
      $admin->curriculum_year ='2559';
      $admin->course_id = '01418112';
      $admin->course_name = 'Computer Programming';
      $admin->min_grade_level = '2';
      $admin->max_grade_level = '12';
      $admin->is_activity = '1';
      $admin->save();


      $admin = new App\Curriculum;
      $admin->curriculum_year ='2560';
      $admin->course_id = '01418113';
      $admin->course_name = 'Algorithm';
      $admin->min_grade_level = '6';
      $admin->max_grade_level = '10';
      $admin->is_activity = '1';
      $admin->save();

      $admin = new App\Curriculum;
      $admin->curriculum_year ='2560';
      $admin->course_id = '01418114';
      $admin->course_name = 'Introduction to Computer Science';
      $admin->min_grade_level = '1';
      $admin->max_grade_level = '12';
      $admin->is_activity = '0';
      $admin->save();


      $admin = new App\Curriculum;
      $admin->curriculum_year ='2561';
      $admin->course_id = '01418115';
      $admin->course_name = 'Sceince';
      $admin->min_grade_level = '1';
      $admin->max_grade_level = '12';
      $admin->is_activity = '1';
      $admin->save();


      $admin = new App\Curriculum;
      $admin->curriculum_year ='2560';
      $admin->course_id = '01418116';
      $admin->course_name = 'Automata';
      $admin->min_grade_level = '1';
      $admin->max_grade_level = '12';
      $admin->is_activity = '1';
      $admin->save();


    }
}
