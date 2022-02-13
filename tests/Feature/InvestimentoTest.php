<?php

namespace Tests\Feature;

use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvestimentoTest extends TestCase
{
    public function test_criar_investimento_data_atual() {
        $investimentoData = [
            'valor' => 1000.00,
            'cpf_investidor' => '032.047.300-77',
            'data' => date('Y-m-d'),
        ];

        $response = $this->post('/api/investimento', $investimentoData);

        $response->assertStatus(201);
        $response->assertJson($response->json());
    }

    public function test_criar_investimento_formato_invalido() {
        $investimentoData = [
            'valor' => '1000.00a',
            'cpf_investidor' => '032.047.300-77',
            'data' => date('Y-m-dd'),
        ];

        $response = $this->post('/api/investimento', $investimentoData);

        $response->assertStatus(400);
        $response->assertJson($response->json());
    }

    public function test_criar_investimento_data_futura() {
        $date = new DateTime();
        $investimentoData = [
            'valor' => '1000.00a',
            'cpf_investidor' => '032.047.300-77',
            'data' => $date->modify('1 day')->format('Y-m-d'),
        ];

        $response = $this->post('/api/investimento', $investimentoData);

        $response->assertStatus(400);
        $response->assertJson($response->json());
    }

    public function test_criar_investimento_valor_negativo() {
        $investimentoData = [
            'valor' => -10.00,
            'cpf_investidor' => '032.047.300-77',
            'data' => date('Y-m-dd'),
        ];

        $response = $this->post('/api/investimento', $investimentoData);

        $response->assertStatus(400);
        $response->assertJson($response->json());
    }
}
