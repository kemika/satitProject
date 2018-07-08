<?php

use Faker\Generator as Faker;

$factory->define(App\Student::class, function (Faker $faker) {
    return [
      'student_id' => $faker->regexify('[0-9]{10}'),
      'firstname' => $faker->firstName,
      'lastname' => $faker->lastName,
      'student_status' => $faker->randomElement(['2','0','1'])
    ];
});
