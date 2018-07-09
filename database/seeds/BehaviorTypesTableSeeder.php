<?php

use Illuminate\Database\Seeder;

class BehaviorTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $admin = new App\Behavior_Type;
      $admin->behavior_type ='1';
      $admin->behavior_type_text = 'Attentive in class';
      $admin->save();

      $admin = new App\Behavior_Type;
      $admin->behavior_type ='2';
      $admin->behavior_type_text = 'Responsible for assigned work';
      $admin->save();

      $admin = new App\Behavior_Type;
      $admin->behavior_type ='3';
      $admin->behavior_type_text = 'Respectful';
      $admin->save();

      $admin = new App\Behavior_Type;
      $admin->behavior_type ='4';
      $admin->behavior_type_text = 'Helpful';
      $admin->save();

      $admin = new App\Behavior_Type;
      $admin->behavior_type ='5';
      $admin->behavior_type_text = 'Honest';
      $admin->save();

      $admin = new App\Behavior_Type;
      $admin->behavior_type ='6';
      $admin->behavior_type_text = 'Able to work well with others';
      $admin->save();

      $admin = new App\Behavior_Type;
      $admin->behavior_type ='7';
      $admin->behavior_type_text = 'Polite and proper in words';
      $admin->save();

      $admin = new App\Behavior_Type;
      $admin->behavior_type ='8';
      $admin->behavior_type_text = 'Punctual';
      $admin->save();

      $admin = new App\Behavior_Type;
      $admin->behavior_type ='9';
      $admin->behavior_type_text = 'Dressed properly';
      $admin->save();

      $admin = new App\Behavior_Type;
      $admin->behavior_type ='10';
      $admin->behavior_type_text = 'Not problematic to himself or others';
      $admin->save();
        //
    }
}
