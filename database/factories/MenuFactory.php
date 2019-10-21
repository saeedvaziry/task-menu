<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Menu;
use Faker\Generator as Faker;

$factory->define(Menu::class, function (Faker $faker) {
    return [
        'field' => $faker->name,
        'max_depth' => $faker->numberBetween(1, 10),
        'max_children' => $faker->numberBetween(1, 10),
    ];
});
