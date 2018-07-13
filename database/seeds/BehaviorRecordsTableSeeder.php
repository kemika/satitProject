<?php

use Illuminate\Database\Seeder;

class BehaviorRecordsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      factory(App\Behavior_Record::class, 5)->create();
        //
    }
}
