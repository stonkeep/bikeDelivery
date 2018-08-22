<?php

use Faker\Factory as Faker;

$factory->define(App\Enderecos::class, function () {
    $faker = Faker::create('pt_BR');
    return [
        'logradouro' => $faker->address,
        'cidade' => $faker->city,
        'uf' => $faker->regionAbbr,
        'cep' => $faker->postcode
    ];
});

