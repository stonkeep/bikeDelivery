<?php

namespace Tests\Unit;

use App\Ciclista;
use App\Clientes;
use App\Pedidos;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;
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
        factory(Clientes::class, 3)->create();
        $cliente = Clientes::firstOrFail();
        //cria ciclista
        factory(Ciclista::class, 3)->create();
        $ciclista = Ciclista::firstOrFail();
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

        //Altera o ciclista do pedido
        Pedidos::firstOrFail()->ciclista()->associate(Ciclista::find(2))->save();
        //Verifica se o ciclista do pedido foi alterado
        $this->assertEquals(Pedidos::firstOrFail()->ciclista->id, Ciclista::find(2)->id);

        //deleta pedido
        Clientes::firstOrFail()->pedidos()->find(1)->delete();
        //verifica se o pedido foi deletado
        $this->assertEmpty(Clientes::firstOrFail()->pedidos()->find(1));
    }

    //TODO crud com rotas

    /** @test */
    public function crud_de_rotas()
    {
        //cria cliente
        factory(Clientes::class, 2)->create();
        //Cria usuário
        factory(User::class)->create();
        $user = User::firstOrFail();
        //Vincula cliente e usuárip
        Clientes::firstOrFail()->users()->save($user);
        //Verifica se usuário foi vinculado a lciente
        $this->assertNotEmpty(User::firstOrFail()->cliente);
        //cria ciclista
        factory(Ciclista::class, 3)->create();
        //testa validacao do pedido status 302 esperado
//        $this->post(route('pedidos.store'), [])
//        ->assertStatus(302);
        //cadastra pedido status 200 esperado
        $this->actingAs($user)->post(route('pedidos.store'), [])
            ->assertStatus(200);
        //Verifica se foi mesmo gravado
        $this->assertNotEmpty(Pedidos::firstOrFail());
        //Verifica se o pedido esta vinculado ao cliente
        $this->assertEquals(Clientes::firstOrFail(), Pedidos::firstOrFail()->cliente);
        //le pedido
        $response = $this->get(route('pedidos.show', Pedidos::firstOrFail()))
            ->assertStatus(200)
        ->assertJsonCount(1)->assertOk()->assertSuccessful();
        $response->assertOk();
        //Verifica se não voltou vazio
        $this->assertNotEmpty($response->decodeResponseJson());

        //altera pedido
        $pedido2 = $pedido = Pedidos::firstOrFail();
        $pedido2->cliente()->associate(Clientes::find(2));
        $this->put(route('pedidos.update', $pedido), $pedido2->get()->toArray())
            ->assertSuccessful()
            ->assertOk();
        //deleta pedido
        $this->delete(route('pedidos.destroy', Pedidos::firstOrFail()));
        //Verifica se foi mesmo deletado
        $this->assertEmpty(Pedidos::first());
        //verifica se o softdelete esta funcionando
        $this->assertNotEmpty(Pedidos::onlyTrashed(1));
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
