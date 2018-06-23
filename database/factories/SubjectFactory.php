<?php

use Faker\Generator as Faker;

$factory->define(App\Subject::class, function (Faker $faker) {
    return [
      'curriculum_id' => $faker->numberBetween($min = 1, $max = 20),
      'code' => $faker->randomElement(['01418111','01418112','01418113', '01418114', '01418115']),
      'name' => $faker->randomElement(['Automata','Drawing','Alogorithm', 'Network', 'Unix']),
      'min' => $faker->numberBetween($min = 1, $max = 5),
      'max' => $faker->numberBetween($min = 6, $max = 15),
      'credit' => $faker->numberBetween($min = 1, $max = 4),
      'semester' => $faker->numberBetween($min = 1, $max = 3),
      'elective' => $faker->numberBetween($min = 0, $max = 1),
      'status' => $faker->numberBetween($min = 0, $max = 1),


    ];
});
