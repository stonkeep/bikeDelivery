<?php

namespace Tests\Unit;

use App\Clientes;
use App\Enderecos;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory as Faker;


class enderecoTest extends TestCase
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
     * @test
     */
    public function crud_simples()
    {
        // cria cliente
        $cliente = factory(Clientes::class)->create();

        // cria endereço
        factory(Enderecos::class)->create();
        $endereco = Enderecos::firstOrFail();
        $this->assertNotEmpty($endereco);

        // vincula endenreço ao cliente
        $cliente->enderecos()->save($endereco);

        //Veriica se foi vinculado
        $this->assertNotEmpty(Clientes::firstOrFail()->enderecos()->get());
        // Verifica se os dados são iguais
        $this->assertEquals(Clientes::firstOrFail()->enderecos()->firstOrFail(), Enderecos::firstOrFail());

        // altera endereço
        $endereco = Clientes::firstOrFail()->enderecos()->firstOrFail();
        $endereco->cep = '71920-000';
        $endereco->save();

        //Verifica se o cep foi mesmo alterado
        $this->assertEquals(Clientes::firstOrFail()->enderecos()->firstOrFail()->cep, '71920-000');

        // deleta endereço
        $endereco->delete();
        //Verificca se o endereço foi mesmo deletado
        $this->assertEmpty(Enderecos::first());
        // Verifica se o sofdelete esta realmente funcionando
        $this->assertNotEmpty(Enderecos::onlyTrashed()->first());
    }

    /** @test */
    public function teste_crud_com_rota()
    {
        // Cria cliente
        factory(Clientes::class)->create();
        // Tenta gravar um endereço sem dados para verificar se a validação esta funcionado
        $this->post(route('enderecos.store'), [])
            ->assertStatus(302);
        // Data para endereço
        $data = [
            'cliente' => 1,
            'endereco' => [
                'logradouro' => $this->faker->address,
                'cidade' => $this->faker->city,
                'uf' => $this->faker->regionAbbr,
                'cep' => $this->faker->postcode
                ]
        ];
        // Grava endereço no banco de dados vinculado a um cliente
//        $this->disableExceptionHandling();
        $this->post(route('enderecos.store'), $data)
        ->assertStatus(200);
        //Verifica se o endereco foi mesmo cadastrado
        $this->assertNotEmpty(Clientes::firstOrFail()->enderecos()->firstOrFail());
        // Cria mais de um endereço pra vincular para ver se o a relação 1 - N esta funcionando
        $data = [
            'cliente' => 1,
            'endereco' => [
                'logradouro' => $this->faker->address,
                'cidade' => $this->faker->city,
                'uf' => $this->faker->regionAbbr,
                'cep' => $this->faker->postcode
            ]
        ];
        $this->post(route('enderecos.store'), $data)
            ->assertStatus(200);
        //Verifica se o endereco foi mesmo cadastrado
        $this->assertNotEmpty(Clientes::firstOrFail()->enderecos()->find(2));
        // Le endereço de um cliente
        //TODO verificar direito se os dados retornaram
        $this->get(route('enderecos.show', Clientes::firstOrFail()->enderecos()->find(2)))
        ->assertStatus(200);

        // altera endereço do cliente
        $data = [
            'cliente' => 1,
            'endereco' => [
                'id' => 1,
                'logradouro' => 'Casa do cliente',
                'cidade' => $this->faker->city,
                'uf' => $this->faker->regionAbbr,
                'cep' => $this->faker->postcode
            ]
        ];
        $this->put(route('enderecos.update', Enderecos::find(1)), $data);

        // verifica se os dados dos cliente foi alterado
        $this->assertEquals('Casa do cliente', Clientes::firstOrFail()->enderecos()->find(1)->logradouro);
        // deleta endereco do cliente
        $this->delete(route('enderecos.destroy', Enderecos::firstOrFail()));

        //Verifica se endereço foi mesmo deletado
        $this->assertEmpty(Enderecos::find(1));

        //Verifica se sofdelete deu certo
        $this->assertNotEmpty(Enderecos::onlyTrashed(1));
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
