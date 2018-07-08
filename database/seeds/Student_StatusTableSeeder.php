<?php

use Illuminate\Database\Seeder;

class Student_StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $admin = new App\Student_Status;
      $admin->student_status = '0';
      $admin->student_status_text = 'Active';
      $admin->save();

      $admin = new App\Student_Status;
      $admin->student_status = '2';
      $admin->student_status_text = 'Graduated';
      $admin->save();


      $admin = new App\Student_Status;
      $admin->student_status = '1';
      $admin->student_status_text = 'Inactive';
      $admin->save();

    }
}
