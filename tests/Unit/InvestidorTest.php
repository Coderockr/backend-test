<?php

namespace Tests\Unit;
use Tests\TestCase;


class InvestidorTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @test
     * @return void
     */
    public function verificar_se_o_usuario_foi_criado_corretamente()
    {
       $response = $this->json('POST','api/investidor/', ['email' => 'teste@hotmail.com']);

       $response->assertStatus(201)->assertJson(['email' => 'teste@hotmail.com']);
    }
}
