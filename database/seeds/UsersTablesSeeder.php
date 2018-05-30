<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        User::create([
          'email'      =>  'admin@gmail.com',
          'firstname'     =>  'Admin',
          'lastname'      =>  'Adminn',
          'role'          =>  'superadmin',
          'teacher_number'      =>  '112211',
          'password'      =>  Hash::make('1234'),
          'remember_token' =>  str_random(30),


        ]);
    }
}
