<?php

use Illuminate\Database\Seeder;

class GPAsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\GPA::class, 15)->create();
    }
}
