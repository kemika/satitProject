<?php

use Illuminate\Database\Seeder;

class PhysicalRecordsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Physical_Record::class, 10)->create();
    }
}
