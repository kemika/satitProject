<?php

use Faker\Generator as Faker;

$factory->define(App\GPA::class, function (Faker $faker) {
    return [
      'subj_id' => $faker->ean8,
      'std_id' => $faker->ean8,
      'gpa' => $faker->randomElement(['A','B+','B', 'C+', 'C', 'D+', 'D', 'F'])

    ];
});
