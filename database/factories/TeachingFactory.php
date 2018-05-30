<?php

use Faker\Generator as Faker;

$factory->define(App\Teaching::class, function (Faker $faker) {
    return [
      'teacher_number' => $faker->ean8,
      'subj_number' => $faker->randomElement(['01418111','01418112','01418113', '01418114', '01418115']),
      'year' => $faker->randomElement(['2559','2560','2561', '2562']),
      'semester' => $faker->randomElement(['1','2','3'])

    ];
});
