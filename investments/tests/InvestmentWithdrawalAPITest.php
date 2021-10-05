<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\User;

class InvestmentWithdrawalAPITest extends TestCase
{

    public function testWithdrawalInvestment()
    {
        $token = $this->JWTtoken('admin');

        $investment = $this->createInvestment($token);

        $this->json(
            'POST',
            '/api/v1/investment/withdrawal',
            [
                'investmentId' => $investment->data->investment->id,
                'date'         => '2021-09-10',
                'type'         => 'partial',
                'amount'       => '100.00'
            ],
            [
                'HTTP_Authorization' => $token
            ]
            )
         ->seeStatusCode(200)
        ;

    }

    public function createInvestment($token)
    {
        $this->json(
            'POST',
            '/api/v1/investment/create',
            [
                'owner'  => '1',
                'date'   => '2021-01-01',
                'amount' => '1000.00'
            ],
            [
                'HTTP_Authorization' => $token
            ]
        );

        return json_decode($this->response->getContent());

    }


    private function JWTtoken($role)
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
