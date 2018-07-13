<?php

use Illuminate\Database\Seeder;

class AcademicYearTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $admin = new App\Academic_Year;
      $admin->academic_year ='2558';
      $admin->grade_level = '12';
      $admin->room = '1';
      $admin->curriculum_year = '2558';
      $admin->total_days = '0';
      $admin->save();


      $admin = new App\Academic_Year;
      $admin->academic_year ='2559';
      $admin->grade_level = '6';
      $admin->room = '2';
      $admin->curriculum_year = '2559';
      $admin->total_days = '0';
      $admin->save();


      $admin = new App\Academic_Year;
      $admin->academic_year ='2560';
      $admin->grade_level = '5';
      $admin->room = '3';
      $admin->curriculum_year = '2560';
      $admin->total_days = '0';
      $admin->save();

      $admin = new App\Academic_Year;
      $admin->academic_year ='2561';
      $admin->grade_level = '10';
      $admin->room = '4';
      $admin->curriculum_year = '2561';
      $admin->total_days = '0';
      $admin->save();


      $admin = new App\Academic_Year;
      $admin->academic_year ='2562';
      $admin->grade_level = '11';
      $admin->room = '5';
      $admin->curriculum_year = '2562';
      $admin->total_days = '0';
      $admin->save();
    }
}
