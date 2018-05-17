<?php

use Faker\Generator as Faker;

$factory->define(App\Student::class, function (Faker $faker) {
    return [
      'number' => $faker->numberBetween($min = 1000000, $max = 900000),
      'firstname' => $faker->name,
      'lastname' => $faker->lastName,
      'birthdate' => $faker->date($format = 'd-m-Y', $max = 'now'),
      'status' => $faker->randomElement(['Active','Inactive','Graduated'])
    ];
});
