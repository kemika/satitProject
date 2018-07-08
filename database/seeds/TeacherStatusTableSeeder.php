<?php

use Illuminate\Database\Seeder;

class TeacherStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Teacher_Status::class, 10)->create();
    }
}
