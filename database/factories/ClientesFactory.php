<?php

use Faker\Factory as Faker;



$factory->define(App\Clientes::class, function () {

    $faker = Faker::create('pt_BR');
    return [
        'cnpj' => $faker->cnpj(false)
    ];
});
