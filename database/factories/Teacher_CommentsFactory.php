<?php

use Faker\Generator as Faker;

$factory->define(App\Teacher_Comment::class, function (Faker $faker) {
    return [
      'student_id' => $faker->randomElement(['1111111111','1111111112','1111111113','1111111114','1111111115']),
      'datetime' => $faker->datetime(),
      'academic_year' => $faker->year(),
      'quater' => $faker->randomElement(['2','3','1']),
      'semester' => $faker->randomElement(['2','1']),
      'comment' => $faker->text,
      'data_status' => $faker->randomElement(['0','1','2'])

        //
    ];
});
