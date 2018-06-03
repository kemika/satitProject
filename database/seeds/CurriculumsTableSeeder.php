<?php

use Illuminate\Database\Seeder;

class CurriculumsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Curriculum::class, 15)->create();
    }
}
