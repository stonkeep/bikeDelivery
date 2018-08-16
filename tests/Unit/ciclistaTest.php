<?php

namespace Tests\Unit;

use App\ciclista;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Schema;
use Tests\CreatesApplication;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory as Faker;


class ciclistaTest extends TestCase
{
    use CreatesApplication, DatabaseMigrations, DatabaseTransactions;
    protected $faker;

    protected function setUp()
    {
        parent::setUp();
        factory(User::class)->create();
        $this->faker = Faker::create('pt_BR');
    }

    /**
     * A basic test example.
     *
     * @return void
     * @test
     */
    public function cria_ciclista_e_vincula_a_usuario()
    {
        //busca usuário
        $user = User::first();

        //Cria ciclista
        $ciclista = Ciclista::create([
            'cpf' => $this->faker->cpf(false)
        ]);

        //Vincula o ciclista ao usuário
        $user->ciclista()->save($ciclista);
        $user = User::first();

        //verifica se o nome do ciclista é o mesmo do usuário vinculado
        $this->assertEquals($ciclista->id, $user->ciclista->id);
        $this->assertEquals($ciclista->cpf, $user->ciclista->cpf);
    }

    /** @test */
    public function vincula_ciclista_a_outro_usuario()
    {
        // Cria um segundo usuário
        factory(User::class)->create();

        //Cria ciclista
        $ciclista = Ciclista::create([
            'cpf' => $this->faker->cpf(false)
        ]);

        //Vincula o ciclista ao usuário
        $user = User::first();
        $user->ciclista()->save($ciclista);

        //Vincula o ciclista ao usuário2
        User::find(2)->ciclista()->save($ciclista);

        //Verifcia se o usuário 1 esta mesmo desassociado
        $this->assertEmpty(User::find(1)->ciclista);

        //VErifica se o usuário dois esta mesmo com o ciclista criado
        $this->assertEquals(User::find(2)->ciclista->cpf, $ciclista->cpf);

        //busca o ciclista e verifica se o relacionamento foi corretamente criado
        $this->assertEquals(Ciclista::first()->user->id, 2);

    }

    /**
     * Reset the migrations
     */
    public function tearDown()
    {
        Schema::disableForeignKeyConstraints();
        $this->artisan('migrate:reset');
        parent::tearDown();
    }
}
