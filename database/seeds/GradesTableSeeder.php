<?php

use Illuminate\Database\Seeder;


class GradesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $admin = new App\Grade;
      $admin->student_id = '5810451152';
      $admin->open_course_id = '01418144';
      $admin->quater = '1';
      $admin->semester = '2';
      $admin->academic_year = '1996';
      
      $admin->save();

        //
    }
}
