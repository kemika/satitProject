<?php

use Illuminate\Database\Seeder;

class TeacherStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $admin = new App\Teacher_Status;
      $admin->teacher_status = '0';
      $admin->teacher_status_text = 'Active';
      $admin->save();

      $admin = new App\Teacher_Status;
      $admin->teacher_status = '1';
      $admin->teacher_status_text = 'Inactive';
      $admin->save();
    }
}
