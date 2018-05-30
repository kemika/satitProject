<?php

use Illuminate\Database\Seeder;

class TeachingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Teaching::class, 5)->create();
    }
}
