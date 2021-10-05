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

    public function testIfWithdrawalOfInvestmentAlertsAboutExceedsBalance()
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
                'date'         => '2021-09-11',
                'type'         => 'partial',
                'amount'       => '500.00'
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
                'amount'       => '500.00'
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
                'date'         => '2021-10-05',
                'type'         => 'partial',
                'amount'       => '500.00'
            ],
            [
                'HTTP_Authorization' => $token
            ]
        )->seeStatusCode(401);

    }

    public function testIfAlertsWhenTryToWithdrawalMoreThenBalance()
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
                'date'         => '2021-08-15',
                'type'         => 'partial',
                'amount'       => '2000.00'
            ],
            [
                'HTTP_Authorization' => $token
            ]
        )->seeStatusCode(401);
    }

    public function testIfFailsWhenTheWithdrawalDateIsLowerThanInvestimentDate()
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
                'date'         => '2021-08-07',
                'type'         => 'partial',
                'amount'       => '200.00'
            ],
            [
                'HTTP_Authorization' => $token
            ]
        )->seeStatusCode(401);
    }

    public function testIfFailsWhenTryToRegisteraWithdrawalWithDateLowerThanOtherWithdrawal()
    {
        $token = $this->JWTtoken('admin');

        $investment = $this->createInvestment(
            $token,
            [
                'date' => '2021-01-01',
                'amount' => '1000.00'
            ]
        );

        $this->json(
            'POST',
            '/api/v1/investment/withdrawal',
            [
                'investmentId' => $investment->data->investment->id,
                'date'         => '2021-06-10',
                'type'         => 'partial',
                'amount'       => '500.00'
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
                'date'         => '2021-05-10',
                'type'         => 'partial',
                'amount'       => '500.00'
            ],
            [
                'HTTP_Authorization' => $token
            ]
        )->seeStatusCode(401);

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
