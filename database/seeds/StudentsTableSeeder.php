<?php

use Illuminate\Database\Seeder;

class StudentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new App\Student;
        $admin->student_id ='1111111111';
        $admin->firstname = 'Anaphat';
        $admin->lastname = 'Insuwan';
        $admin->student_status = '1';
        $admin->save();
    }
}
