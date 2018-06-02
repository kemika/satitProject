<?php

use Illuminate\Database\Seeder;
use App\Curriculum;
class CurriculaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sub = new Curriculum;
        $sub->year = 1234;
        $sub->code = "T1234";
        $sub->name = "Thai language";
        $sub->min = 3;
        $sub->max = 6;
        $sub->status = true;
        $sub->save();

        $sub = new Curriculum;
        $sub->year = 1236;
        $sub->code = "E3451";
        $sub->name = "ENG language";
        $sub->min = 3;
        $sub->max = 6;
        $sub->status = true;
        $sub->save();

        $sub = new Curriculum;
        $sub->year = 1236;
        $sub->code = "E5231";
        $sub->name = "ENG2 language";
        $sub->min = 3;
        $sub->max = 4;
        $sub->status = false;
        $sub->save();
    }
}
