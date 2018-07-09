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
        // $this->call(UsersTablesSeeder::class);

        $this->call(TeacherStatusTableSeeder::class);
        $this->call(TeachersTableSeeder::class);
        $this->call(TeachersTableSeeder::class);
        $this->call(CurriculumTableSeeder::class);
        $this->call(AcademicYearTableSeeder::class);
        $this->call(Student_StatusTableSeeder::class);
        $this->call(StudentsTableSeeder::class);
        $this->call(Data_StatusesTableSeeder::class);
        $this->call(Teacher_CommentsTableSeeder::class);


    }


}
