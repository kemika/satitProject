<?php

use Faker\Generator as Faker;

$factory->define(App\Room::class, function (Faker $faker) {
    return [
      'std_id' => $faker->regexify('[0-9]{6}'),
      'grade' => $faker->numberBetween($min = 1, $max = 12),
      'room' => $faker->numberBetween($min = 1, $max = 10)

    ];
});
