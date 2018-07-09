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
        factory(App\Attendace_Record::class, 20)->create();
        //
    }
}
