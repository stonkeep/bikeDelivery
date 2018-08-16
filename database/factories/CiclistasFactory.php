<?php

use App\User;
use Faker\Generator as Faker;

$factory->define(App\ciclista::class, function (Faker $faker) {
    return [
        'nome' => $faker->name,
    ];
});
