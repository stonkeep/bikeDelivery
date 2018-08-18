<?php

namespace Tests\Unit;

use App\Clientes;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory as Faker;


class clientesTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;
    use WithoutMiddleware; // use this trait
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
    /**
     * A basic test example.
     *
     * @return void
     * @test
     */
    public function crud_simples()
    {
        // Cria cliente
        $cliente = factory(Clientes::class)->create();

        // Busca usuário cadastrado
        $user = User::firstOrFail();

        // Vincula usuário a cliente em questão
        $cliente->users()->save($user);

        // verifica se o usuário foi mesmo vinculado ao cliente
        $cliente = Clientes::firstOrFail();
        $this->assertEquals($cliente->users[0]->name, $user->name);

        // Altera dados do cliente
        // Cria um novo CNPJ
        $cnpj = $this->faker->cnpj(false);
        // Altera
        $cliente->cnpj = $cnpj;
        // Salva no banco de dados
        $cliente->save();
        //Busca novamente o cliente np BD
        $cliente = Clientes::firstOrFail();
        //Verifica se o CNPJ foi mesmo atualizado
        $this->assertEquals($cliente->cnpj, $cnpj);

        // Deleta cliente
        $cliente->delete();
        // Verifica se foi deletado mesmo
        $this->assertEmpty(Clientes::first());
        // verifica se o sofdelete esta funcionando deve retornar um cliente que esta no trash
        $this->assertNotEmpty(Clientes::onlyTrashed(1));
    }

    //TODO testes utilizando as rotas

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
