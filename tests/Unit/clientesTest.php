<?php

namespace Tests\Unit;

use App\Clientes;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpKernel\Client;
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
//        factory(User::class)->create();
        $this->faker = Faker::create('pt_BR'); //faker precisa ser setado para o Brasil para criação d CPF
    }
    /**
     * @return void
     * @test
     */
    public function crud_simples()
    {
        // Cria cliente
        $cliente = factory(Clientes::class)->create();

        // Busca usuário cadastrado
        factory(User::class)->create();
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
    /** @test */
    public function cria_cliente_rota()
    {
//        $this->disableExceptionHandling();
        // Verifica se a validação esta funcionando
        // Deve retorar 302 pois o CNPJ esta faltando
        $this->post(route('clientes.store'), [])
        ->assertStatus(302);

        // Cria dados nescessários para cadastrar cliente
        $data = [
            'cnpj' => $this->faker->cnpj(false)
        ];

        // Cadastra cliente
        $this->post(route('clientes.store'), $data)
            ->assertStatus(200);

        // Verifica se o cliente foi cadastrado
        $this->assertNotEmpty(Clientes::first());


        //Grava usuário para cliente já cadastrado
        $this->post(route('clientes.usuario'), [
            'clienteId' => 1,
            'user' => [
                'name' => $this->faker->name,
                'email' => $this->faker->unique()->safeEmail,
                'password' => '12345', // secret
            ]
        ])
        ->assertStatus(200);
        // Verifica se o usuário foi criado
        $this->assertNotEmpty(User::first());

        //Verifica o vinculo usuário cliente
        $this->assertNotEmpty(User::firstOrFail()->cliente());

        //Busca do detalhes do cliente
        //TODO ver como vai ficar o retorno dos dados
        $response = $this->get(route('clientes.show', Clientes::firstOrFail()->id));

        //Altera os dados do cliente
        //Cria um novo cnpj
        $cnpj = $this->faker->cnpj(false);
        $data = [
            'cnpj' => $cnpj
        ];
        $this->put(route('clientes.update', Clientes::first()), $data)
        ->assertStatus(200);

        // Valida se o cnpj foi atualizado realmente
        $this->assertEquals($cnpj, Clientes::firstOrFail()->cnpj);

        // Deleta cliente
        $this->delete(route('clientes.destroy', Clientes::first()))
        ->assertStatus(200);

        // Valida se o cliente foi mesmo deletado
        $this->assertEmpty(Clientes::first());

        // Valida se o softdelete esta funcionando
        $this->assertNotEmpty(Clientes::onlyTrashed(1)->get());

        // Valida se os usuários também foram deletados
        $this->assertEmpty(Clientes::onlyTrashed(1)->get()[0]->users()->get());
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
