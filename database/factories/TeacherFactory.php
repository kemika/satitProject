<?php

use Faker\Generator as Faker;

$factory->define(App\Teacher::class, function (Faker $faker) {
    return [
      'number' => $faker->idNumber,
      'firstname' => $faker->firstName,
      'lastname' => $faker->lastName,
      'nid' => $faker->nationalIdNumber,
      'passport' => $faker->regexify('[A-Z]+[0-9]{8}'),
      'nationality' => $faker->randomElement(['US','Thai']),
      'status' => $faker->randomElement(['Active','Inactive'])

    ];
});
