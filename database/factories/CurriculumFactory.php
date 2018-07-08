<?php

use Faker\Generator as Faker;

$factory->define(App\Curriculum::class, function (Faker $faker) {
    return [
      'year' => $faker->randomElement(['2559','2560','2561', '2562', '2558']),
      'adjust' => $faker->numberBetween($min = 0, $max = 1)

    ];
});
