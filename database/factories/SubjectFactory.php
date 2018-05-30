<?php

use Faker\Generator as Faker;

$factory->define(App\Subject::class, function (Faker $faker) {
    return [
      'subj_number' => $faker->randomElement(['01418111','01418112','01418113', '01418114', '01418115']),
      'name' => $faker->randomElement(['Automata','Drawing','Alogorithm', 'Network', 'Unix'])

    ];
});
