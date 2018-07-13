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
      $admin->teacher_id ='1934552870';
      $admin->classroom_id = '1';
      $admin->date = '2017-01-01';
      $admin->valid = '0';
      $admin->save();


      $admin = new App\Homeroom;
      $admin->teacher_id ='4429675012';
      $admin->classroom_id = '2';
      $admin->date = '2017-01-02';
      $admin->valid = '0';
      $admin->save();

      $admin = new App\Homeroom;
      $admin->teacher_id ='5319404831';
      $admin->classroom_id = '3';
      $admin->date = '2017-01-02';
      $admin->valid = '1';
      $admin->save();

      $admin = new App\Homeroom;
      $admin->teacher_id ='5523170119';
      $admin->classroom_id = '4';
      $admin->date = '2017-01-03';
      $admin->valid = '1';
      $admin->save();

    }
}
