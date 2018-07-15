<?php

use Illuminate\Database\Seeder;

class HomeroomTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $admin = new App\Homeroom;
      $admin->teacher_id ='2222222221';
      $admin->classroom_id = '1';
      $admin->date = '2017-01-01';
      $admin->valid = '0';
      $admin->save();


      $admin = new App\Homeroom;
      $admin->teacher_id ='2222222222';
      $admin->classroom_id = '2';
      $admin->date = '2017-01-02';
      $admin->valid = '0';
      $admin->save();

      $admin = new App\Homeroom;
      $admin->teacher_id ='2222222223';
      $admin->classroom_id = '3';
      $admin->date = '2017-01-02';
      $admin->valid = '1';
      $admin->save();

      $admin = new App\Homeroom;
      $admin->teacher_id ='2222222224';
      $admin->classroom_id = '4';
      $admin->date = '2017-01-03';
      $admin->valid = '1';
      $admin->save();

    }
}
