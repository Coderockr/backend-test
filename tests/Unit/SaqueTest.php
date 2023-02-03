<?php

namespace Tests\Unit;

use Tests\TestCase;

class SaqueTest extends TestCase
{
    /**
     * A basic unit test example.
     * @test
     * @return void
     */
    public function verificar_se_esta_sacando()
    {
        $response = $this->json('POST','/api/investimento/sacar', ['investimento' => 'b87bebf4-d892-4926-afbf-483edfec52e9']);

        $response->assertStatus(200)->assertJson(['message' => 'saque realizado !']);
    }
}
