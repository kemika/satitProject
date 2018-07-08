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
        $admin->student_id ='1111111114';
        $admin->firstname = 'Boom';
        $admin->lastname = 'Anaphat';
        $admin->student_status = '0';
        $admin->save();


        $admin = new App\Student;
        $admin->student_id ='1111111112';
        $admin->firstname = 'My';
        $admin->lastname = 'Kemika';
        $admin->student_status = '2';
        $admin->save();


        $admin = new App\Student;
        $admin->student_id ='1111111113';
        $admin->firstname = 'Ball';
        $admin->lastname = 'Step';
        $admin->student_status = '0';
        $admin->save();

        $admin = new App\Student;
        $admin->student_id ='1111111115';
        $admin->firstname = 'Stamp';
        $admin->lastname = 'Papon';
        $admin->student_status = '1';
        $admin->save();


        $admin = new App\Student;
        $admin->student_id ='1111111116';
        $admin->firstname = 'Nut';
        $admin->lastname = 'Sunut';
        $admin->student_status = '0';
        $admin->save();




        factory(App\Student::class, 20)->create();
    }
}
