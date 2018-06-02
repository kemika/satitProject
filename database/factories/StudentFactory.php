<?php

use Faker\Generator as Faker;

$factory->define(App\Student::class, function (Faker $faker) {
    return [
      'std_id' => $faker->regexify('[0-9]{6}'),
      'firstname' => $faker->firstName,
      'lastname' => $faker->lastName,
      'birthdate' => $faker->date($format = 'd-m-Y', $max = 'now'),
      'status' => $faker->randomElement(['Active','Inactive','Graduated'])
    ];
});
