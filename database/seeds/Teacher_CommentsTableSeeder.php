<?php

use Illuminate\Database\Seeder;

class Teacher_CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      

      factory(App\Teacher_Comment::class, 10)->create();
        //
    }
}
