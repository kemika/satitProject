<?php

use Faker\Generator as Faker;

$factory->define(App\Teacher_Status::class, function (Faker $faker) {
    return [
      'teacher_status' => $faker->randomElement(['0','1']),
      'teacher_status_text' => $faker->randomElement(['Active','Inactive'])
    ];
});
