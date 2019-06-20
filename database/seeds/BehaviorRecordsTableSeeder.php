<?php

use Illuminate\Database\Seeder;

class BehaviorRecordsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $admin = new App\Behavior_Record;
      $admin->student_id = '2600081170';
      $admin->academic_year = '18';
      $admin->semester = '1';
      $admin->datetime = '2017-01-01';
      $admin->behavior_type = '1';
      $admin->quarter = '1';
      $admin->grade = '3';
      $admin->data_status = '1';
      $admin->save();


      $admin = new App\Behavior_Record;
      $admin->student_id = '2600081170';
      $admin->academic_year = '18';
      $admin->semester = '1';
      $admin->datetime = '2017-01-01';
      $admin->behavior_type = '1';
      $admin->quarter = '2';
      $admin->grade = '4';
      $admin->data_status = '1';
      $admin->save();



      $admin = new App\Behavior_Record;
      $admin->student_id = '2600081170';
      $admin->academic_year = '18';
      $admin->semester = '2';
      $admin->datetime = '2017-01-01';
      $admin->behavior_type = '1';
      $admin->quarter = '1';
      $admin->grade = '2';
      $admin->data_status = '1';
      $admin->save();


      $admin = new App\Behavior_Record;
      $admin->student_id = '2600081170';
      $admin->academic_year = '18';
      $admin->semester = '2';
      $admin->datetime = '2017-01-01';
      $admin->behavior_type = '1';
      $admin->quarter = '2';
      $admin->grade = '2';
      $admin->data_status = '1';
      $admin->save();






      $admin = new App\Behavior_Record;
      $admin->student_id = '2600081170';
      $admin->academic_year = '18';
      $admin->semester = '1';
      $admin->datetime = '2017-01-01';
      $admin->behavior_type = '2';
      $admin->quarter = '1';
      $admin->grade = '1';
      $admin->data_status = '1';
      $admin->save();


      $admin = new App\Behavior_Record;
      $admin->student_id = '2600081170';
      $admin->academic_year = '18';
      $admin->semester = '1';
      $admin->datetime = '2017-01-01';
      $admin->behavior_type = '2';
      $admin->quarter = '2';
      $admin->grade = '4';
      $admin->data_status = '1';
      $admin->save();



      $admin = new App\Behavior_Record;
      $admin->student_id = '2600081170';
      $admin->academic_year = '18';
      $admin->semester = '2';
      $admin->datetime = '2017-01-01';
      $admin->behavior_type = '2';
      $admin->quarter = '1';
      $admin->grade = '2';
      $admin->data_status = '1';
      $admin->save();


      $admin = new App\Behavior_Record;
      $admin->student_id = '2600081170';
      $admin->academic_year = '18';
      $admin->semester = '2';
      $admin->datetime = '2017-01-01';
      $admin->behavior_type = '2';
      $admin->quarter = '2';
      $admin->grade = '2';
      $admin->data_status = '1';
      $admin->save();




       //
    }
}
