<?php

use Faker\Generator as Faker;

$factory->define(App\Teaching::class, function (Faker $faker) {
    return [
      'teacher_id' => $faker->ean8,
      'subj_id' => $faker->randomElement(['01418111','01418112','01418113', '01418114', '01418115'])

    ];
});
