<?php

use Faker\Generator as Faker;

$factory->define(App\GPA::class, function (Faker $faker) {
    return [
      'subj_number' => $faker->ean8,
      'std_number' => $faker->ean8,
      'score' => $faker->randomElement(['A','B+','B', 'C+', 'C', 'D+', 'D', 'F']),
      'year' => $faker->randomElement(['2559','2560','2561', '2562']),
      'semester' => $faker->randomElement(['1','2','3'])

    ];
});
