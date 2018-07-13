<?php

use Faker\Generator as Faker;

$factory->define(App\Physical_Record::class, function (Faker $faker) {
    return [
      'student_id'=> $faker->randomElement(['1111111111','1111111112','1111111113','1111111114','1111111115']),
      'academic_year'=> $faker->randomElement(['2010','2011','2015','2017']),
      'semester' => $faker->randomElement(['1','2']),
      'datetime' => $faker->datetime(),

      'weight' => $faker->numberBetween($min = 40, $max = 200),
      'height' =>  $faker->numberBetween($min = 130, $max = 200),
      'data_status' => $faker->randomElement(['0','1','2']),

        //
    ];
});
