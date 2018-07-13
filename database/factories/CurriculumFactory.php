<?php

use Faker\Generator as Faker;

$factory->define(App\Curriculum::class, function (Faker $faker) {
    return [
      'curriculum_year' => $faker->unique()->regexify('[2][5][5-6][0-9]'),
      'course_id' => $faker->unique()->regexify('[0-9]{8}'),
      'course_name' => $faker->unique()->cityPrefix,
      'min_grade_level' => '1',
      'max_grade_level' => $faker->randomElement(['10','12','6']),
      'is_activity' => $faker->randomElement(['0','1'])
    ];
});
