<?php

use Faker\Generator as Faker;

$factory->define(App\Behavior_Record::class, function (Faker $faker) {
    return [
      'student_id'=> $faker->randomElement(['1111111111','1111111112','1111111113','1111111114','1111111115']),
      'academic_year'=> $faker->randomElement(['2010','2011','2015','2017']),
      'semester' => $faker->randomElement(['1','2']),
      'datetime' => $faker->datetime(),
      'behavior_type' => $faker->unique()->numberBetween($min = 1, $max = 10),
      'quarter' => $faker->randomElement(['1','2']),
      'grade' => $faker->randomElement(['0.5','1.0','1.5','2.0','2.5','3.0','3.5','4.0']),
      'data_status' => $faker->randomElement(['0','1','2']),

        //
    ];
});
