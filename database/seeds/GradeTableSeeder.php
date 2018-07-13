<?php

use Illuminate\Database\Seeder;

class GradeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $admin = new App\Grade;
      $admin->student_id ='1111111111';
      $admin->open_course_id = '441202';
      $admin->quater = '1';
      $admin->semester = '1';
      $admin->academic_year = '2561';
      $admin->grade = '1.11';
      $admin->grade_status = '0';
      $admin->data_status = '0';
      $admin->datetime = '2017-01-01';
      $admin->save();



      $admin = new App\Grade;
      $admin->student_id ='1111111112';
      $admin->open_course_id = '441212';
      $admin->quater = '2';
      $admin->semester = '2';
      $admin->academic_year = '2558';
      $admin->grade = '2.22';
      $admin->grade_status = '1';
      $admin->data_status = '0';
      $admin->datetime = '2017-01-01';
      $admin->save();


      $admin = new App\Grade;
      $admin->student_id ='1111111113';
      $admin->open_course_id = '441210';
      $admin->quater = '3';
      $admin->semester = '3';
      $admin->academic_year = '2559';
      $admin->grade = '3.33';
      $admin->grade_status = '2';
      $admin->data_status = '1';
      $admin->datetime = '2017-01-02';
      $admin->save();


      $admin = new App\Grade;
      $admin->student_id ='1111111114';
      $admin->open_course_id = '441288';
      $admin->quater = '4';
      $admin->semester = '4';
      $admin->academic_year = '2560';
      $admin->grade = '4.00';
      $admin->grade_status = '3';
      $admin->data_status = '2';
      $admin->datetime = '2017-01-03';
      $admin->save();
    }
}
