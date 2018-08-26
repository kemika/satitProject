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
      $admin->student_id ='2600081170';
      $admin->open_course_id = '6';
      $admin->semester = '1';
      $admin->academic_year = '2561';
      $admin->grade_status = '0';
      $admin->data_status = '1';
      $admin->datetime = '2017-01-01';
      $admin->save();

      $admin = new App\Activity_Record;
      $admin->student_id ='2600081170';
      $admin->open_course_id = '7';
      $admin->semester = '1';
      $admin->academic_year = '18';
      $admin->grade_status = '1';
      $admin->data_status = '1';
      $admin->datetime = '2017-01-01';
      $admin->save();


      $admin = new App\Activity_Record;
      $admin->student_id ='2600081170';
      $admin->open_course_id = '8';
      $admin->semester = '1';
      $admin->academic_year = '18';
      $admin->grade_status = '2';
      $admin->data_status = '1';
      $admin->datetime = '2017-01-02';
      $admin->save();


      $admin = new App\Activity_Record;
      $admin->student_id ='2600081170';
      $admin->open_course_id = '9';
      $admin->semester = '1';
      $admin->academic_year = '18';
      $admin->grade_status = '3';
      $admin->data_status = '1';
      $admin->datetime = '2017-01-03';
      $admin->save();




      $admin = new App\Activity_Record;
      $admin->student_id ='2600081170';
      $admin->open_course_id = '10';
      $admin->semester = '1';
      $admin->academic_year = '18';
      $admin->grade_status = '3';
      $admin->data_status = '1';
      $admin->datetime = '2017-01-03';
      $admin->save();




      $admin = new App\Activity_Record;
      $admin->student_id ='2600081170';
      $admin->open_course_id = '11';
      $admin->semester = '2';
      $admin->academic_year = '18';
      $admin->grade_status = '4';
      $admin->data_status = '1';
      $admin->datetime = '2017-01-03';
      $admin->save();




    }
}
