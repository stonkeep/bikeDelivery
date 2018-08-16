<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\CreatesApplication;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class userTest extends TestCase
{
    use CreatesApplication, DatabaseMigrations, DatabaseTransactions;
    /**
     * @return void
     * TODO criação dos testes CRUD
     * @test
     */
    public function cria_usuario()
    {
        factory(User::class)->create();
        $user = User::first();
        $this->assertNotNull($user);
    }

    /** @test */
    public function edita_usuario()
    {
        factory(User::class)->create();
        $user = User::first();
        $user->name = 'Mateus';
        $user->save(); // Não esquecer do passo de salvar no banco de dados se não fica somente na memória
        $user = User::first();
        $this->assertEquals('Mateus', $user->name);
    }

    /** @test */
    public function deleta_usuario()
    {
        factory(User::class)->create();
        $user = User::first();
        $user->delete();
    }
}
