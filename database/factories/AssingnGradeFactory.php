<?php

use Faker\Generator as Faker;

$factory->define(App\AssingnGrade::class, function (Faker $faker) {
    return [
      'subject_id' => $faker->regexify('[0-9]{8}'),
      'grade' => $faker->numberBetween($min = 1, $max = 6),
      'fileName' => $faker->regexify('[a-z]{8}.xlsx'),
      'status' => $faker->randomElement(['Pending','Approve'])

    ];
});
