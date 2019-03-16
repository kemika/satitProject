<?php

use Illuminate\Database\Seeder;

class AttendanceRecordTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new App\Attendance_Record;
        $admin->student_id = '2600081170';
        $admin->academic_year = '18';
        $admin->semester = '1';
        $admin->datetime = '2017-01-01';
        $admin->late = '3';
        $admin->sick = '2';
        $admin->leave = '0';
        $admin->absent = '10';
        $admin->data_status = '1';

        $admin->save();



        $admin = new App\Attendance_Record;
        $admin->student_id = '2600081170';
        $admin->academic_year = '18';
        $admin->semester = '2';
        $admin->datetime = '2017-01-01';
        $admin->late = '0';
        $admin->sick = '0';
        $admin->leave = '2';
        $admin->absent = '7';
        $admin->data_status = '1';
        $admin->save();



    }
}
