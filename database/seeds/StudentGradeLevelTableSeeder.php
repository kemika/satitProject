<?php

use Illuminate\Database\Seeder;

class StudentGradeLevelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


      $admin = new App\Student_Grade_Level;
      $admin->classroom_id ='1';
      $admin->student_id = '1111111111';
      $admin->save();

      $admin = new App\Student_Grade_Level;
      $admin->classroom_id ='2';
      $admin->student_id = '1111111112';
      $admin->save();

      $admin = new App\Student_Grade_Level;
      $admin->classroom_id ='3';
      $admin->student_id = '1111111113';
      $admin->save();

      $admin = new App\Student_Grade_Level;
      $admin->classroom_id ='4';
      $admin->student_id = '1111111114';
      $admin->save();

      $admin = new App\Student_Grade_Level;
      $admin->classroom_id ='5';
      $admin->student_id = '1111111115';
      $admin->save();
        //
    }
}
