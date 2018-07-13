<?php

use Illuminate\Database\Seeder;

class GradeStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      $admin = new App\Grade_Status;
      $admin->grade_status = '0';
      $admin->grade_status_text = 'No grade';
      $admin->save();

      $admin = new App\Grade_Status;
      $admin->grade_status = '1';
      $admin->grade_status_text = 'I';
      $admin->save();


      $admin = new App\Grade_Status;
      $admin->grade_status = '2';
      $admin->grade_status_text = 'S';
      $admin->save();


      $admin = new App\Grade_Status;
      $admin->grade_status = '3';
      $admin->grade_status_text = 'U';
      $admin->save();


      $admin = new App\Grade_Status;
      $admin->grade_status = '4';
      $admin->grade_status_text = '0/1';
      $admin->save();
        //
    }
}
