<?php

use Faker\Generator as Faker;

$factory->define(App\Curriculum::class, function (Faker $faker) {
    return [
      'code' => $faker->regexify('[0-9]{4}'),
      'code' => $faker->regexify('[A-Z]+@[0-9]{4}'),
      'name' => $faker->firstName,
      'min' => $faker->randomDigitNotNull,
      'max' => $faker->randomDigitNotNull,
      'status' => $faker->boolean 
    ];
});
