<?php

namespace Tests\Unit;

use App\Ciclista;
use App\Clientes;
use App\Pedidos;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory as Faker;


class PedidosTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;
    use WithoutMiddleware; // retira a necessidade de autenticação
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
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        //cria cliente
        $cliente = factory(Clientes::class)->create();
        //cria ciclista
        $ciclista = factory(Ciclista::class)->create();
        //cria pedido
        $pedido = Pedidos::create();
        //vincula pedido a cliente
        $cliente->pedidos()->save($pedido);
        //vincula pedido ao ciclista
        $ciclista->pedidos()->save($pedido);
        //Verficia se o pedido tem o mesmo cliente
        $this->assertEquals($cliente->id,Pedidos::firstOrFail()->cliente->id);
        //Verficia se o pedido tem o mesmo ciclista
        $this->assertEquals($ciclista->id,Pedidos::firstOrFail()->ciclista->id);



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
