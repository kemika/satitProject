<?php

use Illuminate\Database\Seeder;

class PhysicalRecordsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new App\Physical_Record;
        $admin->student_id = '2600081170';
        $admin->academic_year =  '18';
        $admin->semester =  '1';
        $admin->datetime =  '2017-01-01';
        $admin->weight =  '68';
        $admin->height =  '179';
        $admin->data_status =  '1';
        $admin->save();



        $admin = new App\Physical_Record;
        $admin->student_id = '2600081170';
        $admin->academic_year =  '18';
        $admin->semester =  '2';
        $admin->datetime =  '2017-07-01';
        $admin->weight =  '60';
        $admin->height =  '179';
        $admin->data_status =  '1';
        $admin->save();





        $admin = new App\Physical_Record;
        $admin->student_id = '2600081170';
        $admin->academic_year =  '17';
        $admin->semester =  '1';
        $admin->datetime =  '2016-01-01';
        $admin->weight =  '60';
        $admin->height =  '172';
        $admin->data_status =  '1';
        $admin->save();



    }
}
