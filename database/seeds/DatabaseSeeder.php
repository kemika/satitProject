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
        // $this->call(TeachersTableSeeder::class);
        // $this->call(CurriculumTableSeeder::class);
        $this->call(AcademicYearTableSeeder::class);
    }


}
