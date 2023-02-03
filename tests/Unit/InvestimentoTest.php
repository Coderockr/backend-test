<?php

namespace Tests\Unit;

use Tests\TestCase;

class InvestimentoTest extends TestCase
{
    /**
     * A basic unit test example.
     * @test
     * @return void
     */
    public function verificar_se_o_investimento_foi_criado()
    {
        $response = $this->json('POST','/api/investimento',
            ['investidor_id' => '523db1c7-f761-4ca3-950b-011ebf88f693',
            'data' => '2023-02-02',
            'saldo_inicial' => 1
            ]);

        $response->assertStatus(201);
    }
}
