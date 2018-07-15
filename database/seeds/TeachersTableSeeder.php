<?php

use Illuminate\Database\Seeder;

class TeachersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      $admin = new App\Teacher;
      $admin->teacher_id = '2222222221';
      $admin->firstname = 'Sirikorn';
      $admin->lastname = 'Junnual';
      $admin->teacher_status = '0';
      $admin->save();



      $admin = new App\Teacher;
      $admin->teacher_id = '2222222222';
      $admin->firstname = 'Chu';
      $admin->lastname = 'Juju';
      $admin->teacher_status = '0';
      $admin->save();



      $admin = new App\Teacher;
      $admin->teacher_id = '2222222223';
      $admin->firstname = 'Anaphat';
      $admin->lastname = 'Insuwan';
      $admin->teacher_status = '0';
      $admin->save();


      $admin = new App\Teacher;
      $admin->teacher_id = '2222222224';
      $admin->firstname = 'My';
      $admin->lastname = 'Kemika';
      $admin->teacher_status = '0';
      $admin->save();
    }
}
