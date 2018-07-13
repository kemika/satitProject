<?php

use Illuminate\Database\Seeder;

class Data_StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $admin = new App\Data_Status;
      $admin->data_status = '0';
      $admin->data_status_text = 'Waiting Approval';
      $admin->save();


      $admin = new App\Data_Status;
      $admin->data_status = '1';
      $admin->data_status_text = 'Approved';
      $admin->save();



      $admin = new App\Data_Status;
      $admin->data_status = '2';
      $admin->data_status_text = 'Canceled';
      $admin->save();
        //
    }
}
