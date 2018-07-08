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
        // $this->call(UsersTableSeeder::class);
        Eloquent::unguard();
<<<<<<< HEAD
        $this->call(TeacherStatusTableSeeder::class);
=======
    //     $this->call(UsersTablesSeeder::class);
    //     $this->call(StudentsTableSeeder::class);
    //     $this->call(TeachersTableSeeder::class);
    //     $this->call(GPAsTableSeeder::class);
    //     $this->call(SubjectsTableSeeder::class);
    //     $this->call(TeachingsTableSeeder::class);
    //     $this->call(AssingnGradesTableSeeder::class);
    //     $this->call(RoomsTableSeeder::class);
    //     $this->call(CurriculumsTableSeeder::class);
    $this->call(Student_StatusTableSeeder::class);
    $this->call(StudentsTableSeeder::class);
>>>>>>> cd511a565faf90c450711d7184fea20bbf270216
    }


}
