<?php

use Illuminate\Database\Seeder;

class ActivityRecordsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $admin = new App\Activity_Record;
      $admin->student_id ='1111111111';
      $admin->open_course_id = '441202';
      $admin->semester = '1';
      $admin->academic_year = '2561';
      $admin->grade_status = '0';
      $admin->data_status = '0';
      $admin->datetime = '2017-01-01';
      $admin->save();

      $admin = new App\Activity_Record;
      $admin->student_id ='1111111112';
      $admin->open_course_id = '441212';
      $admin->semester = '2';
      $admin->academic_year = '2558';
      $admin->grade_status = '1';
      $admin->data_status = '0';
      $admin->datetime = '2017-01-01';
      $admin->save();


      $admin = new App\Activity_Record;
      $admin->student_id ='1111111113';
      $admin->open_course_id = '441210';
      $admin->semester = '3';
      $admin->academic_year = '2559';
      $admin->grade_status = '2';
      $admin->data_status = '1';
      $admin->datetime = '2017-01-02';
      $admin->save();


      $admin = new App\Activity_Record;
      $admin->student_id ='1111111114';
      $admin->open_course_id = '441288';
      $admin->semester = '4';
      $admin->academic_year = '2560';
      $admin->grade_status = '3';
      $admin->data_status = '2';
      $admin->datetime = '2017-01-03';
      $admin->save();
    }
}
