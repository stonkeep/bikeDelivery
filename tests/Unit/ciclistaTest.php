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

    /*
     * prepara os dados para cada teste
     */
    protected function setUp()
    {
        parent::setUp();
        factory(User::class)->create();
        $this->faker = Faker::create('pt_BR'); //faker precisa ser setado para o Brasil para criação d CPF
    }

    public function criaCiclista() {
        //busca usuário
        $user = User::first();

        //Cria ciclista
        $ciclista = Ciclista::create([
            'cpf' => $this->faker->cpf(false)
        ]);

        //Vincula o ciclista ao usuário
        $user->ciclista()->save($ciclista);
    }
    /**
     * A basic test example.
     *
     * @return void
     * @test
     */
    public function cria_ciclista_e_vincula_a_usuario()
    {
        $this->criaCiclista();
        $user = User::first();
        $ciclista = Ciclista::first();

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
        $this->criaCiclista();
        $ciclista = Ciclista::firstOrFail();

        //Vincula o ciclista ao usuário2
        User::find(2)->ciclista()->save($ciclista);

        //Verifcia se o usuário 1 esta mesmo desassociado
        $this->assertEmpty(User::find(1)->ciclista);

        //VErifica se o usuário dois esta mesmo com o ciclista criado
        $this->assertEquals(User::find(2)->ciclista->cpf, $ciclista->cpf);

        //busca o ciclista e verifica se o relacionamento foi corretamente criado
        $this->assertEquals(Ciclista::first()->user->id, 2);
    }

    /** @test */
    public function deleta_ciclista()
    {
        //Cria ciclista
        $this->criaCiclista();

        //Deleta ciclista
        Ciclista::firstOrFail()->delete();

//        Verifica se o ciclista foi mesmo deletado
        $this->assertEmpty(Ciclista::first());
    }

    //TODO criar os teste com as rotas do CRUD

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
