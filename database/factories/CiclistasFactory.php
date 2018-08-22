<?php

use App\User;
//use Faker\Generator as Faker;
use Faker\Factory as Faker;


$factory->define(App\ciclista::class, function () {
    $faker = Faker::create('pt_BR');
    return [
        'cpf' => $faker->cpf(false),
    ];
});
