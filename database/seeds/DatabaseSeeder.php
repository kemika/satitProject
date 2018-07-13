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


        // $this->call(TeacherStatusTableSeeder::class);
        // $this->call(TeachersTableSeeder::class);
        $this->call(CurriculumTableSeeder::class);
        $this->call(AcademicYearTableSeeder::class);
        $this->call(Student_StatusTableSeeder::class);
        $this->call(StudentsTableSeeder::class);
        $this->call(Data_StatusesTableSeeder::class);
        // $this->call(Teacher_CommentsTableSeeder::class);
        $this->call(StudentGradeLevelTableSeeder::class);
        // $this->call(AttendanceRecordTableSeeder::class);
        // $this->call(BehaviorTypesTableSeeder::class);
        // $this->call(BehaviorRecordsTableSeeder::class);
        // $this->call(PhysicalRecordsTableSeeder::class);
        $this->call(GradeStatusTableSeeder::class);
        $this->call(OfferedCoursesTableSeeder::class);
        $this->call(GradeTableSeeder::class);
        // $this->call(ActivityRecordsTableSeeder::class);
        //$this->call(HomeroomTableSeeder::class);



        //
    }


}
