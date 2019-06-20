<?php

use Illuminate\Database\Seeder;

class Teacher_CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      $admin = new App\Teacher_Comment;
      $admin->student_id ='2600081170';
      $admin->datetime = '2017-01-01';
      $admin->academic_year = '18';
      $admin->quarter = '1';
      $admin->semester = '1';
      $admin->comment = 'Good man cooment sem1 q1';
      $admin->data_status = '1';
      $admin->save();




      $admin = new App\Teacher_Comment;
      $admin->student_id ='2600081170';
      $admin->datetime = '2017-01-01';
      $admin->academic_year = '18';
      $admin->quarter = '2';
      $admin->semester = '2';
      $admin->comment = 'comment 3 sem 2 q2';
      $admin->data_status = '1';
      $admin->save();





      $admin = new App\Teacher_Comment;
      $admin->student_id ='2600081170';
      $admin->datetime = '2017-01-01';
      $admin->academic_year = '18';
      $admin->quarter = '1';
      $admin->semester = '2';
      $admin->comment = 'comment 4 sem2 q1';
      $admin->data_status = '1';
      $admin->save();


      $admin = new App\Teacher_Comment;
      $admin->student_id ='2600081170';
      $admin->datetime = '2017-01-01';
      $admin->academic_year = '18';
      $admin->quarter = '2';
      $admin->semester = '1';
      $admin->comment = 'comment2 sem1 q2';
      $admin->data_status = '1';
      $admin->save();



    }
}
