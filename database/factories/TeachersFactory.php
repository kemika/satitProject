<?php

use Faker\Generator as Faker;

$factory->define(App\Teachers::class, function (Faker $faker) {
    return [
      'teacher_id' => $faker->regexify('[0-9]{10}'),
      'firstname' => $faker->firstName,
      'lastname' => $faker->lastName,
      'teacher_status' => $faker->randomElement(['0','1'])
    ];
});
