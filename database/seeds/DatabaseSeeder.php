<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();
        // $this->call(Student_StatusTableSeeder::class);
        // $this->call(StudentsTableSeeder::class);
        // $this->call(Data_StatusesTableSeeder::class);
        // $this->call(TeacherStatusTableSeeder::class);
        $this->call(Teacher_CommentsTableSeeder::class);


    }


}
