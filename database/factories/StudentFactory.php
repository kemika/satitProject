<?php

use Faker\Generator as Faker;

$factory->define(App\Student::class, function (Faker $faker) {
    return [
      'number' => $faker->idNumber,
      'firstname' => $faker->firstName,
      'lastname' => $faker->lastName,
      'birthdate' => $faker->date($format = 'd-m-Y', $max = 'now'),
      'status' => $faker->randomElement(['Active','Inactive','Graduated'])
    ];
});
