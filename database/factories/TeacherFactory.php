<?php

use Faker\Generator as Faker;

$factory->define(App\Teacher::class, function (Faker $faker) {
    return [
      'number' => $faker->ean8,
      'firstname' => $faker->firstName,
      'lastname' => $faker->lastName,
      'nid' => $faker->isbn13,
      'passport' => $faker->regexify('[A-Z][0-9]{8}'),
      'nationality' => $faker->randomElement(['US','Thai']),
      'status' => $faker->randomElement(['Active','Inactive'])

    ];
});
