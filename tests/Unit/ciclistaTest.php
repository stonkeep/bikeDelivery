<?php

namespace Tests\Unit;

use App\ciclista;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Schema;
use Tests\CreatesApplication;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory as Faker;


class ciclistaTest extends TestCase
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

    /** @test */
    public function cria_ciclista_back_end()
    {
        //Caso de erro 500 descomentar essa linha para ver qual erro está acontecendo
//        $this->disableExceptionHandling();

        //cria variavel vazio para testar a validacao
        $data = [];
        //testa se os campos estao sendo validados
        $this->post(route('ciclistas.store'), $data)
            ->assertStatus(302);

        //Cria dados para gravação do ciclista
        $data = [
            'cpf' => $this->faker->cpf(false),
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm'
        ];

        // Chama o controle para criar o usuário e o ciclista
        $this->post(route('ciclistas.store'), $data)
            ->assertStatus(200);

        //verifica se o usuário foi criado
        $this->assertNotEmpty(User::firstOrFail());

        //verifica se o ciclista foi criado
        $this->assertNotEmpty(Ciclista::firstOrFail());
    }

    /** @test */
    public function altera_dados_ciclista_back_end()
    {
        $this->cria_ciclista_back_end();
        $ciclista = Ciclista::find(1);
        $cpf = $this->faker->cpf(false);
        $ciclista->cpf = $cpf;
        $this->put(route('ciclistas.update', ['ciclista' => $ciclista]), $ciclista->toArray())
        ->assertStatus(200);
        $ciclista = Ciclista::find(1);
        $this->assertEquals($ciclista->cpf, $cpf);
    }

    /** @test */
    public function deleta_ciclista_back_end()
    {
        $this->cria_ciclista_back_end();
        $ciclista = Ciclista::find(1);
        $this->delete(route('ciclistas.destroy', ['ciclista' => $ciclista]))
            //se falhar
//            ->dump
            // Vericia se tudo deu certo
            ->assertStatus(200);
        $this->assertEmpty(Ciclista::find(1));

    }

    /** @test */
    public function lista_ciclistas_route()
    {
        //Cria 5 ciclistas
        $this->cria_ciclista_back_end();
        $this->cria_ciclista_back_end();
        $this->cria_ciclista_back_end();
        $this->cria_ciclista_back_end();
        $this->cria_ciclista_back_end();

        // Chama a lista de ciclistas
        $response = $this->get(route('ciclistas.index'))->json();
        // Verifica se a quantidade é 5
        $this->assertCount(5, $response);

        //Deleta um ciclista
        $ciclista = Ciclista::find(1);
        $this->delete(route('ciclistas.destroy', ['ciclista' => $ciclista]));

        // Chama a lista de ciclistas
        $response = $this->get(route('ciclistas.index'))->json();
        // Verifica se a quantidade é 4
        $this->assertCount(4, $response);

        //Verifica se o softdelete esta funcionando
        $this->assertNotEmpty(Ciclista::onlyTrashed());
        // Verifica se tem somente um softdelete
        $this->assertCount(1, Ciclista::onlyTrashed()->get());

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
