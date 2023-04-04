<?php

use Modules\Program\Entities\Program;

$factory->define(Program::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word(),
        'slug' => $faker->slug(),
        'on_navigation' => $faker->boolean(),
        'is_active' => $faker->boolean(),
    ];
});
