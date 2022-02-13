<?php

namespace Tests\Feature;

use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class InvestimentoTest extends TestCase {
    public function test_criar_investimento_data_atual() {
        $investimentoData = [
            'valor' => 1000.00,
            'cpf_investidor' => '892.517.440-50',
            'data' => date('Y-m-d'),
        ];

        $response = $this->post('/api/investimento', $investimentoData);

        $response->assertStatus(201);
        $response->assertJson($response->json());
    }

    public function test_criar_investimento_formato_invalido() {
        $investimentoData = [
            'valor' => '1000.00a',
            'cpf_investidor' => '892.517.440-50',
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
            'cpf_investidor' => '892.517.440-50',
            'data' => $date->modify('1 day')->format('Y-m-d'),
        ];

        $response = $this->post('/api/investimento', $investimentoData);

        $response->assertStatus(400);
        $response->assertJson($response->json());
    }

    public function test_criar_investimento_valor_negativo() {
        $investimentoData = [
            'valor' => -10.00,
            'cpf_investidor' => '892.517.440-50',
            'data' => date('Y-m-dd'),
        ];

        $response = $this->post('/api/investimento', $investimentoData);

        $response->assertStatus(400);
        $response->assertJson($response->json());
    }

    public function test_visualizar_investimento() {
        $investimento = DB::table('investimento')->first();
        
        $response = $this->get("/api/investimento/{$investimento->id}");

        $response->assertStatus(200);
        $response->assertJson($response->json());
    }

    public function test_visualizar_investimento_que_nao_existe() {
        $investimento = DB::table('investimento')->first();
        
        $id = $investimento->id * -1;
        $response = $this->get("/api/investimento/{$id}");

        $response->assertStatus(404);
        $response->assertJson($response->json());
    }
}
