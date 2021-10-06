<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ViewAPITest extends TestCase
{

    public function testIfViewApiListsWithdrawal()
    {
        $token = $this->JWTtoken('admin');

        $investment = $this->createInvestment(
            $token,
            [
                'date' => '2021-08-10',
                'amount' => '1000.00'
            ]
        );

        $this->json(
            'POST',
            '/api/v1/investment/withdrawal',
            [
                'investmentId' => $investment->data->investment->id,
                'date'         => '2021-09-20',
                'type'         => 'partial',
                'amount'       => '200.00'
            ],
            [
                'HTTP_Authorization' => $token
            ]
        )->seeStatusCode(200);

        $this->json(
            'POST',
            '/api/v1/investment/withdrawal',
            [
                'investmentId' => $investment->data->investment->id,
                'date'         => '2021-09-20',
                'type'         => 'partial',
                'amount'       => '200.00'
            ],
            [
                'HTTP_Authorization' => $token
            ]
        )->seeStatusCode(200);

        $this->json(
            'GET',
            '/api/v1/investment/view/' . $investment->data->investment->id ,
            [
                'HTTP_Authorization' => $token
            ]
            )->seeStatusCode(200);

        $jsonResponse = json_decode($this->response->getContent(), true);

        

        $this->assertArrayHasKey('status', $jsonResponse);
        $this->assertArrayHasKey('investment', $jsonResponse['data']);
        $this->assertArrayHasKey('balance', $jsonResponse['data']);
        $this->assertArrayHasKey('withdrawals', $jsonResponse['data']);

    }




    public function createInvestment($token, $data=[])
    {
        $this->json(
            'POST',
            '/api/v1/investment/create',
            [
                'owner'  => $data['owner'] ?? '1',
                'date'   => $data['date'] ?? '2021-01-01',
                'amount' => $data['amount'] ?? '1000.00'
            ],
            [
                'HTTP_Authorization' => $token
            ]
        );

        return json_decode($this->response->getContent());

    }

    public function JWTtoken($role)
    {
        $this->post(
            '/auth/login',
            [
                'email' => $role . '@email.com',
                'password'=> 'passwd123'
            ]
        );

        $r = json_decode($this->response->getContent());

        if ($r->access_token) {
            return  $r->access_token;
        }
    }
}
